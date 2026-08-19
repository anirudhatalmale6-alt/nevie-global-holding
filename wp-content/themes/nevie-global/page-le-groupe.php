<?php
/**
 * Template Name: Page 2 — Le Groupe
 * Textes V3 repris mot pour mot. Interdits Page 1 respectés.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="hero" style="padding-block:clamp(3.4rem,7.5vw,5.6rem) clamp(2.8rem,5.5vw,4.4rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Le Groupe</p>
		<h1 class="monte" style="font-size:clamp(2rem,4.4vw,3.2rem)">Une holding entrepreneuriale construisant progressivement un portefeuille diversifié d'entreprises.</h1>
	</div>
</section>

<section class="section">
	<div class="enveloppe duo">
		<p class="duo__aparte">Notre histoire</p>
		<div class="duo__corps">
			<p>NEVIE-GLOBAL SAS a été fondée à Nantes par Elisa et Emmanuel Varinot, avec la volonté de bâtir un groupe industriel diversifié capable de reprendre et développer des entreprises françaises dans la durée.</p>
		</div>
	</div>
</section>

<section class="section section--creux">
	<div class="enveloppe duo">
		<p class="duo__aparte">Notre vision</p>
		<div class="duo__corps">
			<p>Nous considérons chaque entreprise comme une réalité propre, avec son histoire, ses équipes, ses savoir-faire et ses enjeux. Notre ambition est de construire progressivement un groupe diversifié capable d'accompagner durablement les entreprises qui rejoignent son portefeuille.</p>
		</div>
	</div>
</section>

<section class="section">
	<div class="enveloppe">
		<p class="oeil">Notre philosophie</p>
		<h2 class="titre-section">Quatre principes qui guident nos décisions.</h2>
		<div class="dirigeants">
			<article class="dirigeant"><h3 class="dirigeant__nom">Entrepreneurial</h3><p class="dirigeant__mot">Des décisions pragmatiques et orientées vers l'action.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Long terme</h3><p class="dirigeant__mot">Une approche privilégiant la construction progressive plutôt que la logique de court terme.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Diversification</h3><p class="dirigeant__mot">Des entreprises et secteurs différents au sein d'un portefeuille volontairement diversifié.</p></article>
			<article class="dirigeant"><h3 class="dirigeant__nom">Continuité</h3><p class="dirigeant__mot">Préserver les savoir-faire, les équipes et les identités lorsque cela est pertinent.</p></article>
		</div>
	</div>
</section>

<section class="section section--sombre">
	<div class="enveloppe">
		<p class="oeil">Gouvernance</p>
		<h2 class="titre-section">Elisa Varinot, Présidente — Emmanuel Varinot, Directeur Général, co-fondateur.</h2>
		<p style="margin-top:2rem"><a class="lien-fleche" style="color:var(--or-clair)" href="<?php echo esc_url( home_url( '/gouvernance/' ) ); ?>">La gouvernance du groupe <?php echo ng_fleche( 'lien-fleche__icone' ); ?></a></p>
	</div>
</section>
<?php get_footer();
