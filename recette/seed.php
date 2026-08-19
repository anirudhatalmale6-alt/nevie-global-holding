<?php
/**
 * Amorçage du site de démonstration : pages du CDC + participations réelles (Partie 5).
 * Les textes sont ceux de la V3, repris mot pour mot.
 */

$port = trim( file_get_contents( __DIR__ . '/port.txt' ) );
$_SERVER['HTTP_HOST']   = 'localhost:' . $port;
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_NAME'] = 'localhost';

require __DIR__ . '/site/wp-load.php';

wp_set_current_user( 1 );

/* ------------------------------------------------- Thème et réglages */
switch_theme( 'nevie-global' );
update_option( 'permalink_structure', '/%postname%/' );
update_option( 'blogname', 'NEVIE-GLOBAL SAS' );
update_option( 'blogdescription', 'Holding entrepreneuriale française' );
update_option( 'timezone_string', 'Europe/Paris' );
update_option( 'date_format', 'j F Y' );

/* --------------------------------------------------------- Les pages */
$pages = array(
	'accueil'                     => array( 'Accueil', '' ),
	'le-groupe'                   => array( 'Le Groupe', 'page-le-groupe.php' ),
	'notre-modele'                => array( 'Notre Modèle', 'page-notre-modele.php' ),
	'gouvernance'                 => array( 'Gouvernance', 'page-gouvernance.php' ),
	'nos-entreprises'             => array( 'Nos Entreprises', 'page-nos-entreprises.php' ),
	'croissance-acquisitions'     => array( 'Croissance & Acquisitions', 'page-croissance.php' ),
	'ceder-son-entreprise'        => array( 'Céder son entreprise', 'page-ceder.php' ),
	'contact'                     => array( 'Contact', 'page-contact.php' ),
	'mentions-legales'            => array( 'Mentions légales', 'page-mentions-legales.php' ),
	'politique-de-confidentialite'=> array( 'Politique de confidentialité', 'page-confidentialite.php' ),
);

$ids = array();
foreach ( $pages as $slug => $def ) {
	$existante = get_page_by_path( $slug );
	$id = wp_insert_post( array(
		'ID'           => $existante ? $existante->ID : 0,
		'post_title'   => $def[0],
		'post_name'    => $slug,
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_content' => '',
	) );
	if ( $def[1] ) {
		update_post_meta( $id, '_wp_page_template', $def[1] );
	}
	$ids[ $slug ] = $id;
	echo "page: {$slug} #{$id}\n";
}

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $ids['accueil'] );

/* ------------------------------------------------------------ Pôles */
$poles = array(
	'Technologie'       => 10,
	'Textile / Industrie' => 20,
	'Industrie'         => 30,
	'Services'          => 40,
	'Automobile'        => 50,
);
$term_ids = array();
foreach ( $poles as $nom => $ordre ) {
	$t = term_exists( $nom, NG_Participations::TAX_POLE );
	if ( ! $t ) {
		$t = wp_insert_term( $nom, NG_Participations::TAX_POLE );
	}
	$term_ids[ $nom ] = is_array( $t ) ? (int) $t['term_id'] : (int) $t;
	update_term_meta( $term_ids[ $nom ], 'ng_ordre', $ordre );
	echo "pole: {$nom} #{$term_ids[$nom]}\n";
}

/* --------------------------------------------------- Participations */
$participations = array(
	array(
		'nom'    => 'NEVIE-GLOBAL LIMITED',
		'pole'   => 'Technologie',
		'champs' => array(
			'statut'       => 'detenue',
			'description'  => 'Filiale technologique du groupe, basée au Royaume-Uni — voir le site de NEVIE-GLOBAL LIMITED.',
			'secteur'      => 'Technologie',
			'localisation' => 'Royaume-Uni',
			'site_internet'=> 'https://nevie-global.com',
			'presentation' => '', // volontairement vide : correction V3, renvoi externe uniquement
			'ordre'        => 10,
			'actif'        => '1',
		),
	),
	array(
		'nom'    => 'TECHNIFLOC-DIFFUSION',
		'pole'   => 'Textile / Industrie',
		'champs' => array(
			'statut'       => 'acquisition',
			'description'  => 'Entreprise spécialisée dans le flocage et l\'ennoblissement textile, basée à Issoudun. Acquisition en cours par NEVIE-GLOBAL SAS.',
			'secteur'      => 'Ennoblissement textile',
			'localisation' => 'Issoudun, France',
			'presentation' => "Entreprise spécialisée dans le flocage et l'ennoblissement textile, basée à Issoudun.\n\nL'acquisition est en cours par NEVIE-GLOBAL SAS. Conformément à l'approche du groupe, l'entreprise conservera son nom, son identité et son savoir-faire.",
			'ordre'        => 20,
			'actif'        => '1',
		),
	),
	array(
		'nom'    => 'Fonderie',
		'pole'   => 'Industrie',
		'champs' => array( 'statut' => 'venir', 'secteur' => 'Industrie', 'ordre' => 30, 'actif' => '1' ),
	),
	array(
		'nom'    => 'Pompes funèbres',
		'pole'   => 'Services',
		'champs' => array( 'statut' => 'venir', 'secteur' => 'Services', 'ordre' => 40, 'actif' => '1' ),
	),
	array(
		'nom'    => 'Garage automobile',
		'pole'   => 'Automobile',
		'champs' => array( 'statut' => 'venir', 'secteur' => 'Automobile', 'ordre' => 50, 'actif' => '1' ),
	),
	array(
		'nom'    => 'Mécanique générale',
		'pole'   => 'Industrie',
		'champs' => array( 'statut' => 'venir', 'secteur' => 'Industrie', 'ordre' => 60, 'actif' => '1' ),
	),
);

foreach ( $participations as $p ) {
	$existante = get_page_by_title( $p['nom'], OBJECT, NG_Participations::CPT );
	$id = wp_insert_post( array(
		'ID'          => $existante ? $existante->ID : 0,
		'post_title'  => $p['nom'],
		'post_type'   => NG_Participations::CPT,
		'post_status' => 'publish',
	) );
	wp_set_object_terms( $id, array( $term_ids[ $p['pole'] ] ), NG_Participations::TAX_POLE );
	foreach ( $p['champs'] as $cle => $valeur ) {
		update_post_meta( $id, '_ng_' . $cle, $valeur );
	}
	echo "participation: {$p['nom']} #{$id}\n";
}

/* ------------------------------------------ Comptes de démonstration */
$comptes = array(
	array( 'ng_editeur_demo', 'ng_editeur', 'Éditeur de contenu', 'Nevie!Editeur2026' ),
	array( 'ng_acquisitions_demo', 'ng_acquisitions', 'Responsable acquisitions', 'Nevie!Acquis2026' ),
);
foreach ( $comptes as $c ) {
	$u = get_user_by( 'login', $c[0] );
	if ( ! $u ) {
		$uid = wp_create_user( $c[0], $c[3], $c[0] . '@example.invalid' );
		$u   = get_user_by( 'id', $uid );
	}
	$u->set_role( $c[1] );
	wp_update_user( array( 'ID' => $u->ID, 'display_name' => $c[2] ) );
	echo "compte: {$c[0]} → {$c[1]}\n";
}

flush_rewrite_rules( true );
echo "OK\n";
