<?php
/**
 * Template Name: Page 10 — Politique de confidentialité + Cookies
 * Durées de conservation affichées (correction V3). Elles restent à confirmer
 * juridiquement par NEVIE-GLOBAL SAS avant mise en ligne (Partie 6).
 */
defined( 'ABSPATH' ) || exit;
get_header();

$durees = array(
	array( 'Dossier de cession non retenu', '6 mois après clôture de l\'étude' ),
	array( 'Dossier de cession en cours', '12 mois après dernier contact' ),
	array( 'Pièces jointes', 'Alignées sur la durée du dossier associé' ),
	array( 'Logs techniques (formulaire, erreurs)', '90 jours' ),
	array( 'Preuve de consentement RGPD', '3 ans après la dernière interaction' ),
);
?>
<section class="hero" style="padding-block:clamp(3.2rem,6.5vw,4.8rem) clamp(2.4rem,4.5vw,3.6rem)">
	<div class="enveloppe hero__grille"><h1 style="font-size:clamp(1.8rem,3.8vw,2.7rem)">Politique de confidentialité</h1></div>
</section>

<section class="section">
	<div class="enveloppe"><div class="prose legal">

		<dl>
			<dt>Responsable du traitement</dt>
			<dd>NEVIE-GLOBAL SAS, 2 Place Jean V, bureau 3, 44000 Nantes.</dd>
			<dt>Données collectées</dt>
			<dd>Données transmises via le formulaire de contact et le formulaire de cession d'entreprise (identité, coordonnées, informations sur l'entreprise, documents joints le cas échéant).</dd>
			<dt>Finalité</dt>
			<dd>Étude des demandes de contact et des dossiers de cession d'entreprise.</dd>
			<dt>Destinataires</dt>
			<dd>NEVIE-GLOBAL SAS exclusivement. Aucune donnée n'est transférée hors Union Européenne.</dd>
		</dl>

		<h2>Durées de conservation</h2>
		<table class="tableau-duree">
			<thead><tr><th scope="col">Type de donnée</th><th scope="col">Durée de conservation</th></tr></thead>
			<tbody>
			<?php foreach ( $durees as $d ) : ?>
				<tr><td><?php echo esc_html( $d[0] ); ?></td><td><?php echo esc_html( $d[1] ); ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2>Vos droits</h2>
		<p>Conformément au RGPD, vous disposez d'un droit d'accès, de rectification, d'opposition et de suppression de vos données. Pour exercer ces droits, contactez-nous via la page <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>.</p>

		<h2 id="cookies">Cookies</h2>
		<p>Ce site utilise des cookies techniques nécessaires à son fonctionnement. Vous pouvez accepter, refuser ou personnaliser leur utilisation via la bannière affichée lors de votre première visite. Avant votre choix, et si vous choisissez « Refuser », aucun cookie ni script non essentiel n'est chargé.</p>

		<p style="margin-top:1.6rem">
			<button class="btn btn--ligne" type="button" id="rouvrir-cookies">Modifier mon choix de cookies</button>
		</p>

	</div></div>
</section>

<script>
document.getElementById('rouvrir-cookies').addEventListener('click', function () {
	try { localStorage.removeItem('ng_consentement'); } catch (e) {}
	var b = document.getElementById('banniere-cookies');
	if (b) { b.hidden = false; b.scrollIntoView({ block: 'end' }); }
});
</script>
<?php get_footer();
