<?php
/**
 * Template Name: Page 8 — Contact
 * Le formulaire général emprunte le même canal technique que celui de cession
 * (webhook n8n existant) — choix figé par le CDC, Partie 6.
 */
defined( 'ABSPATH' ) || exit;
get_header();
$actif = defined( 'NG_N8N_WEBHOOK_URL' ) && NG_N8N_WEBHOOK_URL;
?>
<section class="hero" style="padding-block:clamp(3.4rem,7.5vw,5.6rem) clamp(2.8rem,5.5vw,4.4rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Contact</p>
		<h1 class="monte" style="font-size:clamp(2rem,4.4vw,3.2rem)">Une question concernant NEVIE-GLOBAL <em>ou son portefeuille ?</em></h1>
	</div>
</section>

<section class="section">
	<div class="enveloppe">
		<div class="coordonnees">
			<article class="dirigeant">
				<h2 class="dirigeant__nom" style="font-size:1.1rem">Siège social</h2>
				<p class="dirigeant__mot">NEVIE-GLOBAL SAS<br>2 Place Jean V, bureau 3<br>44000 Nantes, France</p>
			</article>
			<article class="dirigeant">
				<h2 class="dirigeant__nom" style="font-size:1.1rem">Vous cédez votre entreprise ?</h2>
				<p class="dirigeant__mot">Un formulaire dédié permet de présenter votre dossier.</p>
				<p style="margin-top:1rem"><a class="lien-fleche" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">Accéder au formulaire dédié <?php echo ng_fleche( 'lien-fleche__icone' ); ?></a></p>
			</article>
		</div>
	</div>
</section>

<section class="section section--creux">
	<div class="enveloppe">
		<p class="oeil">Formulaire général</p>
		<h2 class="titre-section">Nous écrire</h2>

		<?php if ( ! $actif ) : ?>
			<p class="encadre" style="margin-top:1.8rem;max-width:74ch">
				<strong>Formulaire non connecté à ce stade.</strong> Comme celui de cession, il passera par le
				webhook n8n existant — canal figé par le CDC. Le raccordement est prévu au Jalon 3.
			</p>
		<?php endif; ?>

		<form class="formulaire" method="post" novalidate<?php echo $actif ? '' : ' aria-disabled="true"'; ?>>
			<div class="formulaire__grille">
				<p class="champ"><label for="c-nom">Nom et prénom <span aria-hidden="true">*</span></label><input type="text" id="c-nom" name="nom" autocomplete="name" required></p>
				<p class="champ"><label for="c-email">Email <span aria-hidden="true">*</span></label><input type="email" id="c-email" name="email" autocomplete="email" required></p>
				<p class="champ"><label for="c-societe">Société</label><input type="text" id="c-societe" name="societe" autocomplete="organization"></p>
				<p class="champ"><label for="c-tel">Téléphone</label><input type="tel" id="c-tel" name="telephone" autocomplete="tel"></p>
				<p class="champ champ--large"><label for="c-message">Message <span aria-hidden="true">*</span></label><textarea id="c-message" name="message" rows="6" required></textarea></p>
			</div>
			<p class="champ champ--case"><label><input type="checkbox" name="consentement" required> J'ai pris connaissance de la <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">politique de confidentialité</a>.</label></p>
			<p style="margin-top:1.6rem"><button class="btn btn--encre" type="submit"<?php echo $actif ? '' : ' disabled'; ?>>Envoyer <?php echo ng_fleche(); ?></button></p>
		</form>
	</div>
</section>
<?php get_footer();
