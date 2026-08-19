<?php
/**
 * Template Name: Page 9 — Mentions légales
 *
 * Correction V3 n°9 : le SIRET et l'hébergeur ne sont pas définitifs. Ils ne doivent être
 * publiés ni avec la mention « à compléter », ni laissés visiblement vides. Tant qu'ils ne
 * sont pas fournis, la page reste bloquée en pré-production : ce gabarit affiche donc un
 * avertissement en clair côté administration et n'expose aucune ligne incomplète au public.
 */
defined( 'ABSPATH' ) || exit;
get_header();

$siret     = get_option( 'ng_siret', '' );
$hebergeur = get_option( 'ng_hebergeur', '' );
$complet   = $siret && $hebergeur;
?>
<section class="hero" style="padding-block:clamp(3.2rem,6.5vw,4.8rem) clamp(2.4rem,4.5vw,3.6rem)">
	<div class="enveloppe hero__grille"><h1 style="font-size:clamp(1.8rem,3.8vw,2.7rem)">Mentions légales</h1></div>
</section>

<section class="section">
	<div class="enveloppe"><div class="prose legal">

		<?php if ( ! $complet && current_user_can( 'manage_options' ) ) : ?>
			<p class="encadre" style="margin-bottom:2rem">
				<strong>Page en pré-production.</strong> Le SIRET et/ou l'hébergeur ne sont pas renseignés.
				Conformément à la correction V3 n°9, ces lignes ne sont pas publiées tant que
				NEVIE-GLOBAL SAS ne les a pas fournies et validées. Ce message n'est visible que par
				les administrateurs.
			</p>
		<?php endif; ?>

		<dl>
			<dt>Dénomination sociale</dt><dd>NEVIE-GLOBAL SAS</dd>
			<dt>Forme juridique</dt><dd>Société par actions simplifiée (SAS)</dd>
			<dt>Capital social</dt><dd>50 €</dd>
			<dt>Siège social</dt><dd>2 Place Jean V, bureau 3, 44000 Nantes</dd>
			<dt>SIREN</dt><dd>993 888 841 (Nantes)</dd>
			<?php if ( $siret ) : ?>
				<dt>SIRET</dt><dd><?php echo esc_html( $siret ); ?></dd>
			<?php endif; ?>
			<dt>Directeur de la publication</dt><dd>Emmanuel Varinot, Directeur Général</dd>
			<?php if ( $hebergeur ) : ?>
				<dt>Hébergeur</dt><dd><?php echo esc_html( $hebergeur ); ?></dd>
			<?php endif; ?>
			<dt>Propriété intellectuelle</dt>
			<dd>L'ensemble des contenus de ce site (textes, images, logo) est la propriété de NEVIE-GLOBAL SAS, sauf mention contraire.</dd>
		</dl>

	</div></div>
</section>
<?php get_footer();
