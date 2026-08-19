<?php
/**
 * Plugin Name: NEVIE-GLOBAL — Rôles, validation éditoriale et journal d'audit
 * Description: Matrice des 4 rôles (Partie 14), chaîne de validation Éditeur → Administrateur
 *              (WF-17) et journal d'audit des actions de publication (WF-04 / WF-15).
 * Version:     1.0.0
 * Author:      Anirudha Talmale
 *
 * Principe retenu : les capacités sont portées par les rôles, jamais par des contrôles
 * d'interface. Un Éditeur ne peut pas publier parce qu'il ne détient pas la capacité
 * publish_ng_participations — pas parce qu'un bouton est masqué.
 */

defined( 'ABSPATH' ) || exit;

final class NG_Roles {

	const VERSION_ROLES = '1.0.0';
	const OPTION        = 'ng_roles_version';
	const TABLE_AUDIT   = 'ng_journal_audit';

	public static function init() {
		add_action( 'init', array( __CLASS__, 'installer_roles' ) );
		add_action( 'admin_init', array( __CLASS__, 'restreindre_admin' ) );

		/* WF-17 — un Éditeur ne soumet qu'en attente de relecture. */
		add_filter( 'wp_insert_post_data', array( __CLASS__, 'forcer_en_attente' ), 10, 2 );
		add_action( 'transition_post_status', array( __CLASS__, 'notifier_admin' ), 10, 3 );
		add_action( 'transition_post_status', array( __CLASS__, 'journaliser_publication' ), 10, 3 );

		add_action( 'admin_menu', array( __CLASS__, 'menu_journal' ) );
		add_action( 'admin_notices', array( __CLASS__, 'avis_validation' ) );
	}

	/* ------------------------------------------------------------- Les 4 rôles */

	public static function installer_roles() {

		if ( get_option( self::OPTION ) === self::VERSION_ROLES ) {
			return;
		}

		$part = array(
			'edit_ng_participation', 'read_ng_participation', 'delete_ng_participation',
			'edit_ng_participations', 'edit_others_ng_participations',
			'publish_ng_participations', 'read_private_ng_participations',
			'delete_ng_participations', 'delete_others_ng_participations',
			'edit_published_ng_participations', 'delete_published_ng_participations',
		);

		/* 1. Administrateur NEVIE-GLOBAL SAS — le compte principal reste la propriété du client. */
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			foreach ( array_merge( $part, array( 'ng_consulter_dossiers', 'ng_traiter_dossiers', 'ng_voir_journal' ) ) as $cap ) {
				$admin->add_cap( $cap );
			}
		}

		/* 2. Éditeur de contenu — rédige et soumet, ne publie jamais. */
		remove_role( 'ng_editeur' );
		add_role( 'ng_editeur', 'Éditeur de contenu', array(
			'read'                             => true,
			'upload_files'                     => true,
			'edit_posts'                       => true,
			'edit_pages'                       => true,
			'edit_others_pages'                => true,
			'delete_posts'                     => false,
			'publish_posts'                    => false,
			'publish_pages'                    => false,
			'edit_published_posts'             => true,
			'edit_published_pages'             => true,
			/* Participations : tout sauf publish_* et delete_* */
			'edit_ng_participation'            => true,
			'read_ng_participation'            => true,
			'edit_ng_participations'           => true,
			'edit_others_ng_participations'    => true,
			'edit_published_ng_participations' => true,
			'read_private_ng_participations'   => true,
			'publish_ng_participations'        => false,
			'delete_ng_participations'         => false,
		) );

		/* 3. Responsable acquisitions — dossiers uniquement, zéro droit sur le site. */
		remove_role( 'ng_acquisitions' );
		add_role( 'ng_acquisitions', 'Responsable acquisitions', array(
			'read'                   => true,
			'ng_consulter_dossiers'  => true,
			'ng_traiter_dossiers'    => true,
			'edit_posts'             => false,
			'upload_files'           => false,
		) );

		/* 4. Freelance développeur — accès temporaire, révoqué à la livraison (Partie 14). */
		remove_role( 'ng_developpeur' );
		add_role( 'ng_developpeur', 'Freelance développeur (accès temporaire)', array(
			'read'                             => true,
			'edit_posts'                       => true,
			'edit_pages'                       => true,
			'edit_others_pages'                => true,
			'publish_pages'                    => true,
			'edit_theme_options'               => true,
			'upload_files'                     => true,
			'edit_ng_participations'           => true,
			'edit_others_ng_participations'    => true,
			'publish_ng_participations'        => true,
			/* Aucun accès aux dossiers de cession : capacités volontairement absentes. */
			'ng_consulter_dossiers'            => false,
			'ng_traiter_dossiers'              => false,
			'list_users'                       => false,
			'edit_users'                       => false,
		) );

		update_option( self::OPTION, self::VERSION_ROLES );
		self::creer_table_audit();
	}

	/** Le Responsable acquisitions n'a rien à faire dans l'éditeur de contenu. */
	public static function restreindre_admin() {
		if ( ! is_user_logged_in() || wp_doing_ajax() ) {
			return;
		}
		$u = wp_get_current_user();
		if ( in_array( 'ng_acquisitions', (array) $u->roles, true ) ) {
			global $pagenow;
			$autorises = array( 'index.php', 'admin.php', 'profile.php', 'admin-ajax.php' );
			if ( ! in_array( $pagenow, $autorises, true ) ) {
				wp_safe_redirect( admin_url() );
				exit;
			}
		}
	}

	/* ------------------------------------------------------- WF-17 : validation */

	/**
	 * Un utilisateur sans capacité de publication ne peut pas produire un contenu publié,
	 * quel que soit le chemin emprunté (interface, REST, import).
	 */
	public static function forcer_en_attente( $data, $postarr ) {

		if ( 'publish' !== $data['post_status'] ) {
			return $data;
		}

		$type = $data['post_type'];
		$capacite = NG_Participations::CPT === $type ? 'publish_ng_participations'
			: ( 'page' === $type ? 'publish_pages' : 'publish_posts' );

		if ( ! current_user_can( $capacite ) ) {
			$data['post_status'] = 'pending';
		}

		return $data;
	}

	/** L'Administrateur est prévenu dès qu'un contenu attend sa validation. */
	public static function notifier_admin( $nouveau, $ancien, $post ) {

		if ( 'pending' !== $nouveau || 'pending' === $ancien ) {
			return;
		}
		if ( ! in_array( $post->post_type, array( 'page', 'post', NG_Participations::CPT ), true ) ) {
			return;
		}

		$destinataires = array();
		foreach ( get_users( array( 'role' => 'administrator' ) ) as $u ) {
			$destinataires[] = $u->user_email;
		}
		if ( empty( $destinataires ) ) {
			return;
		}

		$auteur = get_userdata( $post->post_author );
		$sujet  = sprintf( '[NEVIE-GLOBAL] Contenu à valider : %s', get_the_title( $post ) );
		$corps  = sprintf(
			"Un contenu a été soumis pour validation.\n\nTitre : %s\nType : %s\nSoumis par : %s\nDate : %s\n\nRelire et publier : %s\n",
			get_the_title( $post ),
			get_post_type_object( $post->post_type )->labels->singular_name,
			$auteur ? $auteur->display_name : 'inconnu',
			current_time( 'd/m/Y H:i' ),
			admin_url( 'post.php?post=' . $post->ID . '&action=edit' )
		);

		wp_mail( $destinataires, $sujet, $corps );
	}

	public static function avis_validation() {
		if ( ! current_user_can( 'publish_pages' ) ) {
			return;
		}
		$en_attente = get_posts( array(
			'post_type'      => array( 'page', 'post', NG_Participations::CPT ),
			'post_status'    => 'pending',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		) );
		if ( empty( $en_attente ) ) {
			return;
		}
		printf(
			'<div class="notice notice-warning"><p><strong>Validation en attente</strong> — des contenus soumis par un Éditeur attendent votre relecture. <a href="%s">Les consulter</a>.</p></div>',
			esc_url( admin_url( 'edit.php?post_status=pending&post_type=' . NG_Participations::CPT ) )
		);
	}

	/* ------------------------------------------------------- Journal d'audit */

	public static function creer_table_audit() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table   = $wpdb->prefix . self::TABLE_AUDIT;
		$collate = $wpdb->get_charset_collate();
		dbDelta( "CREATE TABLE {$table} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			cree_le DATETIME NOT NULL,
			utilisateur VARCHAR(120) NOT NULL,
			action VARCHAR(60) NOT NULL,
			detail TEXT NULL,
			PRIMARY KEY (id)
		) {$collate};" );
	}

	public static function journaliser_publication( $nouveau, $ancien, $post ) {
		if ( $nouveau === $ancien ) {
			return;
		}
		if ( ! in_array( $post->post_type, array( 'page', 'post', NG_Participations::CPT ), true ) ) {
			return;
		}
		if ( 'publish' !== $nouveau && 'publish' !== $ancien ) {
			return;
		}
		ng_journaliser(
			'publish' === $nouveau ? 'publication' : 'depublication',
			sprintf( '%s « %s » : %s → %s',
				get_post_type_object( $post->post_type )->labels->singular_name,
				get_the_title( $post ), $ancien, $nouveau )
		);
	}

	public static function menu_journal() {
		add_submenu_page(
			'tools.php',
			'Journal d\'audit',
			'Journal d\'audit',
			'ng_voir_journal',
			'ng-journal',
			array( __CLASS__, 'rendu_journal' )
		);
	}

	public static function rendu_journal() {
		global $wpdb;
		$table  = $wpdb->prefix . self::TABLE_AUDIT;
		$lignes = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY id DESC LIMIT 200" );

		echo '<div class="wrap"><h1>Journal d\'audit</h1>';
		echo '<p>Changements de statut des participations (WF-04) et actions de publication / dépublication (WF-15).</p>';
		echo '<table class="widefat striped"><thead><tr>'
		   . '<th>Date</th><th>Utilisateur</th><th>Action</th><th>Détail</th></tr></thead><tbody>';
		if ( empty( $lignes ) ) {
			echo '<tr><td colspan="4">Aucune entrée pour le moment.</td></tr>';
		}
		foreach ( (array) $lignes as $l ) {
			printf( '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
				esc_html( mysql2date( 'd/m/Y H:i', $l->cree_le ) ),
				esc_html( $l->utilisateur ),
				esc_html( $l->action ),
				esc_html( $l->detail )
			);
		}
		echo '</tbody></table></div>';
	}
}

/** Écriture d'une entrée d'audit — utilisée aussi par le module Participations. */
function ng_journaliser( $action, $detail = '' ) {
	global $wpdb;
	$u = wp_get_current_user();
	$wpdb->insert(
		$wpdb->prefix . NG_Roles::TABLE_AUDIT,
		array(
			'cree_le'     => current_time( 'mysql' ),
			'utilisateur' => $u && $u->ID ? $u->display_name : 'système',
			'action'      => $action,
			'detail'      => $detail,
		),
		array( '%s', '%s', '%s', '%s' )
	);
}

NG_Roles::init();
