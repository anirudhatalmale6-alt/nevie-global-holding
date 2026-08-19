<?php
/**
 * Template Name: Page 4 — Gouvernance
 * Note du CDC : aucune photo n'est fournie ; les cartes sont en version texte seule,
 * sans espace réservé à une photo.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="hero" style="padding-block:clamp(3.4rem,7.5vw,5.6rem) clamp(2.8rem,5.5vw,4.4rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Gouvernance</p>
		<h1 class="monte" style="font-size:clamp(2rem,4.4vw,3.2rem)">Une direction resserrée, <em>une gouvernance claire.</em></h1>
	</div>
</section>

<section class="section">
	<div class="enveloppe">
		<div class="dirigeants" style="margin-top:0">
			<article class="dirigeant">
				<h2 class="dirigeant__nom">Elisa Varinot</h2>
				<p class="dirigeant__role">Présidente</p>
				<p class="dirigeant__mot">Co-fondatrice de NEVIE-GLOBAL SAS.</p>
			</article>
			<article class="dirigeant">
				<h2 class="dirigeant__nom">Emmanuel Varinot</h2>
				<p class="dirigeant__role">Directeur Général</p>
				<p class="dirigeant__mot">Co-fondateur de NEVIE-GLOBAL SAS. 20 ans d'expérience en opérations industrielles (agroalimentaire, ferroviaire, aéronautique, plasturgie).</p>
			</article>
		</div>
	</div>
</section>

<section class="section section--creux">
	<div class="enveloppe">
		<p class="oeil">Notre approche</p>
		<h2 class="titre-section">Trois principes de gouvernance.</h2>
		<div class="dirigeants">
			<article class="dirigeant"><h3 class="dirigeant__nom">Entrepreneuriale</h3><p class="dirigeant__mot">Décisions rapides et pragmatiques.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Décentralisée</h3><p class="dirigeant__mot">Les entreprises conservent leur identité et leur expertise.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Responsable</h3><p class="dirigeant__mot">Une attention particulière portée aux équipes et à la continuité des entreprises.</p></article>
		</div>
	</div>
</section>
<?php get_footer();
