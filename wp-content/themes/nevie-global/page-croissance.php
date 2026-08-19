<?php
/**
 * Template Name: Page 6 — Croissance & Acquisitions
 * Correction V3 : la mention « croissance organique » est absente du site.
 * Interdits : aucune promesse de prix ni de délai.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="hero" style="padding-block:clamp(3.4rem,7.5vw,5.6rem) clamp(2.8rem,5.5vw,4.4rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Croissance &amp; Acquisitions</p>
		<h1 class="monte" style="font-size:clamp(2rem,4.4vw,3.2rem)">Une stratégie de développement <em>par acquisitions.</em></h1>
		<p class="hero__texte monte">NEVIE-GLOBAL développe progressivement son portefeuille par acquisitions et reprises d'entreprises. Cette dimension est au cœur de notre stratégie.</p>
	</div>
</section>

<section class="section">
	<div class="enveloppe">
		<p class="oeil">Ce que nous recherchons</p>
		<h2 class="titre-section">Des entreprises, des secteurs, des situations.</h2>
		<div class="dirigeants">
			<article class="dirigeant"><h3 class="dirigeant__nom">Entreprises</h3><p class="dirigeant__mot">Avec un potentiel de continuité, de transformation ou de développement.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Secteurs</h3><p class="dirigeant__mot">Industrie, technologie, artisanat, services.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Situations</h3><p class="dirigeant__mot">Transmission classique, départ en retraite, reprise progressive, procédures judiciaires.</p></article>
		</div>
	</div>
</section>

<section class="section section--sombre">
	<div class="enveloppe duo">
		<p class="duo__aparte" style="color:#B3AA9B">Notre approche</p>
		<div class="duo__corps">
			<p>Nous privilégions une entrée progressive dans les entreprises que nous reprenons, en nous adaptant à chaque situation plutôt qu'en imposant un schéma unique.</p>
		</div>
	</div>
</section>

<section class="bandeau">
	<div class="enveloppe bandeau__grille">
		<div><h2>Vous envisagez de céder votre entreprise ?</h2><p>Nous étudions les opportunités de reprise dans différents secteurs et situations.</p></div>
		<div><a class="btn btn--or" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">Présenter mon entreprise <?php echo ng_fleche(); ?></a></div>
	</div>
</section>
<?php get_footer();
