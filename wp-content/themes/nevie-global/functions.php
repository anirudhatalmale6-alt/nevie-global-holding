<?php
/**
 * NEVIE-GLOBAL — fonctions du thème.
 *
 * Objectifs de performance (Partie 8) : Lighthouse ≥ 90 sur les 4 axes,
 * LCP < 2,5 s, INP < 200 ms, CLS < 0,1. On y parvient en ne chargeant rien
 * d'inutile : ni jQuery en façade, ni emojis, ni styles de blocs, ni oEmbed.
 */

defined( 'ABSPATH' ) || exit;

const NG_VERSION = '1.0.0';

/* -------------------------------------------------------------- Supports */

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'style', 'script', 'navigation-widgets' ) );
	register_nav_menus( array(
		'principal' => 'Navigation principale',
		'pied'      => 'Pied de page',
		'legal'     => 'Mentions légales',
	) );
} );

/* ------------------------------------------------------ Styles et scripts */

add_action( 'wp_enqueue_scripts', function () {
	$css = get_theme_file_path( 'assets/css/nevie.css' );
	wp_enqueue_style(
		'nevie',
		get_theme_file_uri( 'assets/css/nevie.css' ),
		array(),
		file_exists( $css ) ? filemtime( $css ) : NG_VERSION
	);

	$js = get_theme_file_path( 'assets/js/nevie.js' );
	wp_enqueue_script(
		'nevie',
		get_theme_file_uri( 'assets/js/nevie.js' ),
		array(),
		file_exists( $js ) ? filemtime( $js ) : NG_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}, 20 );

/* Préchargement des deux graisses réellement utilisées au-dessus de la ligne de flottaison. */
add_action( 'wp_head', function () {
	foreach ( array( 'poppins-300', 'poppins-500' ) as $f ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( "assets/fonts/{$f}.woff2" ) )
		);
	}
	echo '<meta name="theme-color" content="#0D0B08">' . "\n";

	/* Favicon SVG en ligne : aucune requête supplémentaire. */
	$embleme = rawurlencode(
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
		. '<rect width="100" height="100" fill="#0D0B08"/>'
		. '<path fill="#C8961E" d="M14 18h16v64H14zM70 18h16v64H70zM30 18h18l38 64H68z"/></svg>'
	);
	printf( '<link rel="icon" href="data:image/svg+xml,%s">' . "\n", $embleme );

	/* Métadonnée de description — reprise de l'accroche institutionnelle. */
	$description = is_front_page()
		? 'NEVIE-GLOBAL SAS, holding entrepreneuriale française : un portefeuille diversifié d\'entreprises indépendantes dans l\'industrie, les services, la technologie et l\'artisanat.'
		: wp_strip_all_tags( get_the_excerpt() );

	if ( ! $description && ! is_front_page() ) {
		$description = 'NEVIE-GLOBAL SAS — holding entrepreneuriale française.';
	}
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
}, 1 );

/* Allègement du front : rien de superflu dans le HTML livré. */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 100 );

/* En-têtes de sécurité (Partie 8). En production ils sont doublés côté serveur. */
add_action( 'send_headers', function () {
	if ( is_admin() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=(), interest-cohort=()' );
} );

/* ------------------------------------------------------------- Utilitaires */

/** Flèche « → » vectorielle, réutilisée dans les boutons et les liens. */
function ng_fleche( $classe = 'btn__fleche' ) {
	return sprintf(
		'<svg class="%s" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">'
		. '<path d="M1 8h12M9 4l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.6" '
		. 'stroke-linecap="square"/></svg>',
		esc_attr( $classe )
	);
}

/** Emblème « N » de la charte. */
function ng_embleme( $classe = '', $taille = 26 ) {
	return sprintf(
		'<svg class="%s" width="%d" height="%d" viewBox="0 0 100 100" aria-hidden="true" focusable="false">'
		. '<path fill="currentColor" d="M14 18h16v64H14zM70 18h16v64H70zM30 18h18l38 64H68z"/></svg>',
		esc_attr( $classe ), (int) $taille, (int) $taille
	);
}

/** Champ d'une participation. */
function ng_champ( $post_id, $cle, $defaut = '' ) {
	$v = get_post_meta( $post_id, '_ng_' . $cle, true );
	return '' === $v ? $defaut : $v;
}

/** Libellé lisible d'un statut. */
function ng_libelle_statut( $statut ) {
	$s = NG_Participations::statuts();
	return isset( $s[ $statut ] ) ? $s[ $statut ] : '';
}

/**
 * Une fiche détaillée n'existe que si l'entreprise est nommée et documentée.
 * Partie 5 : « pas de fiche détaillée tant que non nommée » (pôles à venir).
 */
function ng_a_une_fiche( $post_id ) {
	$statut = ng_champ( $post_id, 'statut' );
	if ( 'venir' === $statut ) {
		return false;
	}
	return (bool) ng_champ( $post_id, 'presentation' );
}

/* ------------------------------------------- Menus de secours (démonstration) */

/**
 * Tant que les menus ne sont pas composés dans l'administration, on affiche
 * la structure des 10 pages du CDC pour que la navigation soit fonctionnelle.
 */
function ng_menu_secours( $emplacement ) {
	$pages = array(
		'/'                     => 'Accueil',
		'/le-groupe/'           => 'Le Groupe',
		'/notre-modele/'        => 'Notre Modèle',
		'/gouvernance/'         => 'Gouvernance',
		'/nos-entreprises/'     => 'Nos Entreprises',
		'/croissance-acquisitions/' => 'Croissance & Acquisitions',
		'/contact/'             => 'Contact',
	);
	if ( 'pied' === $emplacement ) {
		$pages['/ceder-son-entreprise/'] = 'Céder son entreprise';
	}
	return $pages;
}
