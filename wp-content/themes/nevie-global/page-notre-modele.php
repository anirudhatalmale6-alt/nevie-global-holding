<?php
/**
 * Template Name: Page 3 — Notre Modèle
 * Les 7 étapes du CDC, chacune avec sa description courte. Textes V3.
 */
defined( 'ABSPATH' ) || exit;
get_header();

$etapes = array(
	array( '01', 'Sourcing', "Nous identifions des entreprises susceptibles de rejoindre le groupe par sourcing direct, réseaux professionnels et plateformes spécialisées." ),
	array( '02', 'Analyse', "Nous évaluons l'activité, le marché, la situation financière, l'effectif, les actifs, le dirigeant, le contexte de transmission et le potentiel de développement." ),
	array( '03', 'Reprise', "Selon la situation : acquisition classique, reprise progressive, crédit-vendeur, prix symbolique dans certains contextes, ou opérations liées à des procédures judiciaires." ),
	array( '04', 'Intégration', "L'entreprise conserve son nom, son identité, son expertise et son fonctionnement opérationnel. L'intégration est progressive." ),
	array( '05', 'Gouvernance', "Mutualisation possible du pilotage, de la gestion, de la structuration et des fonctions support, selon les besoins de chaque entreprise." ),
	array( '06', 'Développement', "Consolider, structurer, moderniser, développer, accompagner." ),
	array( '07', 'Long terme', "Une logique de portefeuille, pas une logique de court terme. Les secteurs de nos entreprises peuvent être différents ; il n'existe pas nécessairement de synergie commerciale entre elles." ),
);
?>
<section class="hero" style="padding-block:clamp(3.4rem,7.5vw,5.6rem) clamp(2.8rem,5.5vw,4.4rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Notre Modèle</p>
		<h1 class="monte" style="font-size:clamp(2rem,4.4vw,3.2rem)">Acquérir. Structurer. <em>Développer.</em></h1>
		<p class="hero__texte monte">Une approche de reprise adaptée à chaque situation.</p>
	</div>
</section>

<section class="section">
	<div class="enveloppe"><div class="etapes">
		<?php foreach ( $etapes as $e ) : ?>
			<article class="etape">
				<p class="etape__num"><?php echo esc_html( $e[0] ); ?></p>
				<div>
					<h2 class="etape__titre"><?php echo esc_html( $e[1] ); ?></h2>
					<p class="etape__texte"><?php echo esc_html( $e[2] ); ?></p>
				</div>
			</article>
		<?php endforeach; ?>
	</div></div>
</section>

<section class="bandeau">
	<div class="enveloppe bandeau__grille">
		<div><h2>Votre entreprise entre-t-elle dans ce cadre ?</h2><p>Nous étudions les opportunités de reprise dans différents secteurs et situations.</p></div>
		<div><a class="btn btn--or" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">Présenter mon entreprise <?php echo ng_fleche(); ?></a></div>
	</div>
</section>
<?php get_footer();
