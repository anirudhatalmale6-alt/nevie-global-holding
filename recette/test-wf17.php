<?php
/**
 * Recette WF-17 — un Éditeur de contenu ne peut pas publier.
 * Attendu : toute tentative de publication retombe en « en attente de relecture »,
 * et l'Administrateur, lui, publie normalement.
 */

$port = trim( file_get_contents( __DIR__ . '/port.txt' ) );
$_SERVER['HTTP_HOST']   = 'localhost:' . $port;
$_SERVER['REQUEST_URI'] = '/';
require __DIR__ . '/site/wp-load.php';

function verdict( $libelle, $ok, $detail = '' ) {
	printf( "%s  %s%s\n", $ok ? 'PASS' : 'ECHEC', $libelle, $detail ? "  ({$detail})" : '' );
	return $ok;
}

$resultats = array();

/* ---- 1. L'Éditeur tente de publier une participation -------------------- */
$editeur = get_user_by( 'login', 'ng_editeur_demo' );
wp_set_current_user( $editeur->ID );

$id = wp_insert_post( array(
	'post_title'  => 'Test WF-17 — soumission éditeur',
	'post_type'   => NG_Participations::CPT,
	'post_status' => 'publish',   // tentative explicite de publication
) );
$statut = get_post_status( $id );
$resultats[] = verdict( 'WF-17 — la publication par l\'Éditeur retombe en attente', 'pending' === $statut, "statut obtenu : {$statut}" );

/* ---- 2. Les capacités correspondent bien à la matrice Partie 14 --------- */
$resultats[] = verdict( 'Éditeur — ne détient pas publish_ng_participations',
	! user_can( $editeur->ID, 'publish_ng_participations' ) );
$resultats[] = verdict( 'Éditeur — détient bien edit_ng_participations',
	user_can( $editeur->ID, 'edit_ng_participations' ) );
$resultats[] = verdict( 'Éditeur — ne détient pas delete_ng_participations',
	! user_can( $editeur->ID, 'delete_ng_participations' ) );

$acq = get_user_by( 'login', 'ng_acquisitions_demo' );
$resultats[] = verdict( 'Responsable acquisitions — accède aux dossiers',
	user_can( $acq->ID, 'ng_consulter_dossiers' ) );
$resultats[] = verdict( 'Responsable acquisitions — aucun droit d\'édition du site',
	! user_can( $acq->ID, 'edit_posts' ) );

$dev = get_role( 'ng_developpeur' );
$resultats[] = verdict( 'Freelance développeur — aucun accès aux dossiers de cession',
	empty( $dev->capabilities['ng_consulter_dossiers'] ) );

/* ---- 3. L'Administrateur valide et publie -------------------------------- */
wp_set_current_user( 1 );
wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
$statut = get_post_status( $id );
$resultats[] = verdict( 'WF-17 — l\'Administrateur publie le contenu soumis', 'publish' === $statut, "statut obtenu : {$statut}" );

/* ---- 4. Le journal d'audit a bien enregistré la publication (WF-15) ------ */
global $wpdb;
$table = $wpdb->prefix . NG_Roles::TABLE_AUDIT;
$n = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE action = %s", 'publication' ) );
$resultats[] = verdict( 'WF-15 — la publication est journalisée', $n > 0, "{$n} entrée(s)" );

/* ---- 5. Changement de statut journalisé (WF-04) -------------------------- */
$technifloc = get_page_by_title( 'TECHNIFLOC-DIFFUSION', OBJECT, NG_Participations::CPT );
$avant = get_post_meta( $technifloc->ID, '_ng_journal_statut', true );
$avant = is_array( $avant ) ? count( $avant ) : 0;
/* On repart des valeurs existantes : un vrai formulaire les renvoie toutes. */
$_POST = array( NG_Participations::NONCE => wp_create_nonce( NG_Participations::NONCE ) );
foreach ( array_keys( NG_Participations::champs() ) as $cle ) {
	$_POST[ 'ng_' . $cle ] = get_post_meta( $technifloc->ID, '_ng_' . $cle, true );
}
$_POST['ng_statut'] = 'detenue';
NG_Participations::sauvegarder( $technifloc->ID, get_post( $technifloc->ID ) );
$apres = get_post_meta( $technifloc->ID, '_ng_journal_statut', true );
$apres = is_array( $apres ) ? count( $apres ) : 0;
$resultats[] = verdict( 'WF-04 — le changement de statut est journalisé', $apres > $avant, "{$avant} → {$apres} entrée(s)" );

/* On remet TECHNIFLOC dans son état réel : l'acquisition est en cours. */
$_POST['ng_statut'] = 'acquisition';
NG_Participations::sauvegarder( $technifloc->ID, get_post( $technifloc->ID ) );

/* ---- Nettoyage ----------------------------------------------------------- */
wp_delete_post( $id, true );

printf( "\n%d/%d tests réussis\n", count( array_filter( $resultats ) ), count( $resultats ) );
exit( in_array( false, $resultats, true ) ? 1 : 0 );
