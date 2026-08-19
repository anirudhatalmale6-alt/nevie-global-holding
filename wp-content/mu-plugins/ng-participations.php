<?php
/**
 * Plugin Name: NEVIE-GLOBAL — Participations (CMS dynamique)
 * Description: Type de contenu « Participation », taxonomie « Pôle », les 13 champs
 *              de la Partie 5 du CDC, et le journal des changements de statut (WF-04 / WF-15).
 * Version:     1.0.0
 * Author:      Anirudha Talmale
 *
 * Référence CDC : Partie 5 — Système de filiales / participations (CMS dynamique).
 * Règle non négociable : aucun passage automatique au statut « Détenue ».
 * Le changement de statut est une décision éditoriale de NEVIE-GLOBAL SAS, jamais du
 * développeur, et il est journalisé.
 */

defined( 'ABSPATH' ) || exit;

final class NG_Participations {

	const CPT      = 'ng_participation';
	const TAX_POLE = 'ng_pole';
	const NONCE    = 'ng_participation_champs';

	/** Statuts autorisés — libellés repris mot pour mot de la Partie 5. */
	public static function statuts() {
		return array(
			'detenue'     => 'Détenue',
			'acquisition' => 'En cours d\'acquisition',
			'projet'      => 'Projet d\'acquisition',
			'venir'       => 'À venir',
		);
	}

	/**
	 * Les 13 champs du CDC. « Nom » est le titre WordPress ; les 12 autres sont des
	 * métadonnées déclarées ici une seule fois (formulaire, sauvegarde et REST en dérivent).
	 */
	public static function champs() {
		return array(
			'logo'           => array( 'Logo', 'media', 'Logo de l\'entreprise (SVG ou PNG détouré).' ),
			'statut'         => array( 'Statut', 'select', 'Aucun passage automatique à « Détenue » (Partie 5).' ),
			'description'    => array( 'Description', 'textarea', 'Résumé court affiché dans la liste par pôle.' ),
			'secteur'        => array( 'Secteur', 'text', 'Ex. : Ennoblissement textile.' ),
			'localisation'   => array( 'Localisation', 'text', 'Ex. : Issoudun, France.' ),
			'date_integration' => array( 'Date d\'intégration', 'date', 'Laisser vide tant que l\'opération n\'est pas finalisée.' ),
			'site_internet'  => array( 'Site internet', 'url', 'Lien externe de la fiche (ex. nevie-global.com).' ),
			'image'          => array( 'Image', 'media', 'Visuel d\'illustration de la fiche.' ),
			'presentation'   => array( 'Présentation longue', 'textarea', 'Corps de la fiche détaillée.' ),
			'complements'    => array( 'Informations complémentaires', 'textarea', '' ),
			'ordre'          => array( 'Ordre d\'affichage', 'number', 'Croissant. À défaut, tri alphabétique.' ),
			'actif'          => array( 'Statut actif/inactif', 'bool', 'Décoché : la fiche n\'apparaît plus sur le site public.' ),
		);
	}

	public static function init() {
		add_action( 'init', array( __CLASS__, 'enregistrer' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'metaboxe' ) );
		add_action( 'save_post_' . self::CPT, array( __CLASS__, 'sauvegarder' ), 10, 2 );
		add_filter( 'manage_' . self::CPT . '_posts_columns', array( __CLASS__, 'colonnes' ) );
		add_action( 'manage_' . self::CPT . '_posts_custom_column', array( __CLASS__, 'colonne' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets_admin' ) );
	}

	public static function enregistrer() {

		register_taxonomy( self::TAX_POLE, self::CPT, array(
			'labels'            => array(
				'name'          => 'Pôles',
				'singular_name' => 'Pôle',
				'add_new_item'  => 'Ajouter un pôle',
				'menu_name'     => 'Pôles',
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'pole' ),
		) );

		register_post_type( self::CPT, array(
			'labels'       => array(
				'name'               => 'Participations',
				'singular_name'      => 'Participation',
				'add_new_item'       => 'Ajouter une participation',
				'edit_item'          => 'Modifier la participation',
				'search_items'       => 'Rechercher une participation',
				'not_found'          => 'Aucune participation.',
				'menu_name'          => 'Participations',
			),
			'public'       => true,
			'menu_icon'    => 'dashicons-portfolio',
			'menu_position'=> 21,
			'supports'     => array( 'title', 'revisions', 'author' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'nos-entreprises' ),
			'show_in_rest' => true,
			/* Capacités dédiées : la matrice des rôles (Partie 14) les distribue. */
			'capability_type' => array( 'ng_participation', 'ng_participations' ),
			'map_meta_cap'    => true,
		) );

		foreach ( self::champs() as $cle => $def ) {
			register_post_meta( self::CPT, '_ng_' . $cle, array(
				'type'          => in_array( $def[1], array( 'number' ), true ) ? 'integer' : 'string',
				'single'        => true,
				'show_in_rest'  => false, // pas d'exposition REST publique des champs internes
				'auth_callback' => function () { return current_user_can( 'edit_ng_participations' ); },
			) );
		}
	}

	/* ---------------------------------------------------------------- Admin */

	public static function assets_admin( $hook ) {
		global $post_type;
		if ( self::CPT !== $post_type ) {
			return;
		}
		wp_enqueue_media();
		wp_add_inline_style( 'wp-admin', '
			.ng-champ{margin:0 0 18px}
			.ng-champ label{display:block;font-weight:600;margin-bottom:4px}
			.ng-champ .description{margin-top:4px}
			.ng-champ input[type=text],.ng-champ input[type=url],.ng-champ input[type=date],
			.ng-champ input[type=number],.ng-champ textarea,.ng-champ select{width:100%;max-width:640px}
			.ng-champ textarea{min-height:90px}
			.ng-media__apercu{max-width:180px;margin:8px 0;border:1px solid #dcdcde;padding:6px;background:#fff}
			.ng-note{border-left:4px solid #C8961E;background:#fdf8ec;padding:10px 14px;margin:0 0 18px}
		' );
		wp_add_inline_script( 'media-editor', '
			jQuery(function($){
				$(document).on("click",".ng-media__choisir",function(e){
					e.preventDefault();
					var $c=$(this).closest(".ng-champ"),
					    f=wp.media({title:"Choisir un fichier",multiple:false}).open();
					f.on("select",function(){
						var a=f.state().get("selection").first().toJSON();
						$c.find(".ng-media__id").val(a.id);
						$c.find(".ng-media__apercu").attr("src",a.sizes&&a.sizes.thumbnail?a.sizes.thumbnail.url:a.url).show();
						$c.find(".ng-media__retirer").show();
					});
				});
				$(document).on("click",".ng-media__retirer",function(e){
					e.preventDefault();
					var $c=$(this).closest(".ng-champ");
					$c.find(".ng-media__id").val("");
					$c.find(".ng-media__apercu").hide();
					$(this).hide();
				});
			});
		' );
	}

	public static function metaboxe() {
		add_meta_box(
			'ng_participation_champs',
			'Fiche entreprise — champs du CDC (Partie 5)',
			array( __CLASS__, 'rendu_metaboxe' ),
			self::CPT,
			'normal',
			'high'
		);
		add_meta_box(
			'ng_participation_journal',
			'Journal des changements de statut',
			array( __CLASS__, 'rendu_journal' ),
			self::CPT,
			'side',
			'default'
		);
	}

	public static function rendu_metaboxe( $post ) {
		wp_nonce_field( self::NONCE, self::NONCE );
		echo '<p class="ng-note">Le champ <strong>Nom</strong> est le titre de la fiche, saisi ci-dessus. '
		   . 'Le passage au statut « Détenue » relève d\'une décision de NEVIE-GLOBAL SAS et reste journalisé.</p>';

		foreach ( self::champs() as $cle => $def ) {
			list( $libelle, $type, $aide ) = $def;
			$valeur = get_post_meta( $post->ID, '_ng_' . $cle, true );
			$id     = 'ng_' . $cle;
			echo '<div class="ng-champ">';
			printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $libelle ) );

			switch ( $type ) {
				case 'textarea':
					printf( '<textarea id="%s" name="%s">%s</textarea>',
						esc_attr( $id ), esc_attr( $id ), esc_textarea( $valeur ) );
					break;

				case 'select':
					printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $id ) );
					echo '<option value="">— Choisir —</option>';
					foreach ( self::statuts() as $val => $lib ) {
						printf( '<option value="%s"%s>%s</option>',
							esc_attr( $val ), selected( $valeur, $val, false ), esc_html( $lib ) );
					}
					echo '</select>';
					break;

				case 'bool':
					printf( '<input type="checkbox" id="%s" name="%s" value="1"%s> <span>Fiche visible sur le site public</span>',
						esc_attr( $id ), esc_attr( $id ), checked( $valeur, '1', false ) );
					break;

				case 'media':
					$src = $valeur ? wp_get_attachment_image_url( (int) $valeur, 'thumbnail' ) : '';
					printf( '<input type="hidden" class="ng-media__id" name="%s" value="%s">', esc_attr( $id ), esc_attr( $valeur ) );
					printf( '<img class="ng-media__apercu" src="%s" alt=""%s>',
						esc_url( $src ), $src ? '' : ' style="display:none"' );
					echo '<p><button type="button" class="button ng-media__choisir">Choisir un fichier</button> ';
					printf( '<button type="button" class="button-link ng-media__retirer"%s>Retirer</button></p>',
						$src ? '' : ' style="display:none"' );
					break;

				default:
					printf( '<input type="%s" id="%s" name="%s" value="%s">',
						esc_attr( $type ), esc_attr( $id ), esc_attr( $id ), esc_attr( $valeur ) );
			}

			if ( $aide ) {
				printf( '<p class="description">%s</p>', esc_html( $aide ) );
			}
			echo '</div>';
		}
	}

	public static function rendu_journal( $post ) {
		$journal = get_post_meta( $post->ID, '_ng_journal_statut', true );
		if ( empty( $journal ) || ! is_array( $journal ) ) {
			echo '<p>Aucun changement de statut enregistré.</p>';
			return;
		}
		echo '<ul style="margin:0">';
		foreach ( array_reverse( $journal ) as $e ) {
			printf(
				'<li style="border-bottom:1px solid #eee;padding:6px 0"><strong>%s → %s</strong><br><span style="color:#666">%s — %s</span></li>',
				esc_html( $e['avant'] ? self::statuts()[ $e['avant'] ] ?? $e['avant'] : '—' ),
				esc_html( self::statuts()[ $e['apres'] ] ?? $e['apres'] ),
				esc_html( $e['date'] ),
				esc_html( $e['utilisateur'] )
			);
		}
		echo '</ul>';
	}

	public static function sauvegarder( $post_id, $post ) {

		if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( $_POST[ self::NONCE ], self::NONCE ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_ng_participation', $post_id ) ) {
			return;
		}

		$statut_avant = get_post_meta( $post_id, '_ng_statut', true );

		foreach ( self::champs() as $cle => $def ) {
			$id  = 'ng_' . $cle;
			$brut = isset( $_POST[ $id ] ) ? wp_unslash( $_POST[ $id ] ) : '';

			switch ( $def[1] ) {
				case 'textarea':
					$valeur = sanitize_textarea_field( $brut );
					break;
				case 'url':
					$valeur = esc_url_raw( $brut );
					break;
				case 'number':
					$valeur = '' === $brut ? '' : (string) absint( $brut );
					break;
				case 'bool':
					$valeur = $brut ? '1' : '';
					break;
				case 'select':
					$valeur = array_key_exists( $brut, self::statuts() ) ? $brut : '';
					break;
				case 'media':
					$valeur = $brut ? (string) absint( $brut ) : '';
					break;
				default:
					$valeur = sanitize_text_field( $brut );
			}
			update_post_meta( $post_id, '_ng_' . $cle, $valeur );
		}

		/* WF-04 / WF-15 — journalisation du changement de statut. */
		$statut_apres = get_post_meta( $post_id, '_ng_statut', true );
		if ( $statut_avant !== $statut_apres ) {
			$journal   = get_post_meta( $post_id, '_ng_journal_statut', true );
			$journal   = is_array( $journal ) ? $journal : array();
			$journal[] = array(
				'avant'       => $statut_avant,
				'apres'       => $statut_apres,
				'date'        => current_time( 'd/m/Y H:i' ),
				'utilisateur' => wp_get_current_user()->display_name,
			);
			update_post_meta( $post_id, '_ng_journal_statut', $journal );

			if ( function_exists( 'ng_journaliser' ) ) {
				ng_journaliser( 'statut_participation', sprintf(
					'%s : %s → %s',
					get_the_title( $post_id ),
					$statut_avant ? $statut_avant : '—',
					$statut_apres
				) );
			}
		}
	}

	public static function colonnes( $colonnes ) {
		$nouveau = array();
		foreach ( $colonnes as $cle => $lib ) {
			$nouveau[ $cle ] = $lib;
			if ( 'title' === $cle ) {
				$nouveau['ng_statut']  = 'Statut';
				$nouveau['ng_secteur'] = 'Secteur';
				$nouveau['ng_actif']   = 'Visible';
			}
		}
		return $nouveau;
	}

	public static function colonne( $colonne, $post_id ) {
		switch ( $colonne ) {
			case 'ng_statut':
				$s = get_post_meta( $post_id, '_ng_statut', true );
				echo $s ? esc_html( self::statuts()[ $s ] ?? $s ) : '—';
				break;
			case 'ng_secteur':
				echo esc_html( get_post_meta( $post_id, '_ng_secteur', true ) ?: '—' );
				break;
			case 'ng_actif':
				echo get_post_meta( $post_id, '_ng_actif', true ) ? 'Oui' : 'Non';
				break;
		}
	}

	/* --------------------------------------------------- Lecture pour le thème */

	/**
	 * Retourne les participations actives et publiées, groupées par pôle,
	 * dans l'ordre : ordre d'affichage croissant puis titre.
	 */
	public static function par_pole() {
		$posts = get_posts( array(
			'post_type'      => self::CPT,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_ng_ordre',
			'orderby'        => array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ),
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => '_ng_ordre', 'compare' => 'EXISTS' ),
				array( 'key' => '_ng_ordre', 'compare' => 'NOT EXISTS' ),
			),
		) );

		$groupes = array();
		foreach ( $posts as $p ) {
			if ( ! get_post_meta( $p->ID, '_ng_actif', true ) ) {
				continue; // statut actif/inactif — champ 13
			}
			$poles = wp_get_post_terms( $p->ID, self::TAX_POLE );
			$pole  = ! empty( $poles ) ? $poles[0] : null;
			$cle   = $pole ? $pole->term_id : 0;
			if ( ! isset( $groupes[ $cle ] ) ) {
				$groupes[ $cle ] = array(
					'nom'   => $pole ? $pole->name : 'Autres',
					'ordre' => $pole ? (int) get_term_meta( $pole->term_id, 'ng_ordre', true ) : 999,
					'items' => array(),
				);
			}
			$groupes[ $cle ]['items'][] = $p;
		}

		uasort( $groupes, function ( $a, $b ) {
			return $a['ordre'] === $b['ordre'] ? strcmp( $a['nom'], $b['nom'] ) : $a['ordre'] - $b['ordre'];
		} );

		return $groupes;
	}

	/** Classe CSS de la pastille de statut. */
	public static function classe_statut( $statut ) {
		$table = array(
			'detenue'     => 'pastille--detenue',
			'acquisition' => 'pastille--acquisition',
			'projet'      => 'pastille--projet',
			'venir'       => 'pastille--venir',
		);
		return isset( $table[ $statut ] ) ? $table[ $statut ] : 'pastille--venir';
	}
}

NG_Participations::init();
