<?php defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="saut-contenu" href="#contenu">Aller au contenu principal</a>

<header class="entete">
	<div class="enveloppe entete__grille">

		<a class="marque" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="NEVIE-GLOBAL SAS — accueil">
			<?php echo ng_embleme( 'marque__embleme', 26 ); ?>
			<span class="marque__nom">NEVIE<span>-</span>GLOBAL</span>
		</a>

		<button class="bascule" type="button" aria-expanded="false" aria-controls="nav-principale">Menu</button>

		<nav class="nav" id="nav-principale" aria-label="Navigation principale">
			<?php
			if ( has_nav_menu( 'principal' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'principal',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
			} else {
				foreach ( ng_menu_secours( 'principal' ) as $url => $libelle ) {
					$courant = ( untrailingslashit( $_SERVER['REQUEST_URI'] ) === untrailingslashit( $url ) )
						|| ( '/' === $url && is_front_page() );
					printf(
						'<a href="%s"%s>%s</a>',
						esc_url( home_url( $url ) ),
						$courant ? ' aria-current="page"' : '',
						esc_html( $libelle )
					);
				}
			}
			?>
			<a class="nav__cta" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">Céder son entreprise</a>
		</nav>

	</div>
</header>

<main id="contenu">
