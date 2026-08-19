<?php
/**
 * Template Name: Page 7 — Céder son entreprise
 *
 * Le formulaire est rendu avec tous les champs de la Partie 6. Il n'est PAS actif :
 * le canal n8n n'est pas encore configuré (Jalon 3). Conformément à la règle du CDC
 * — « ne jamais afficher de confirmation d'envoi sans confirmation réelle de réception » —
 * l'envoi est volontairement désactivé plutôt que simulé.
 */
defined( 'ABSPATH' ) || exit;
get_header();

$actif = defined( 'NG_N8N_WEBHOOK_URL' ) && NG_N8N_WEBHOOK_URL;

$etapes = array(
	'Vous nous présentez votre entreprise',
	'Nous analysons votre dossier',
	'Nous échangeons avec vous',
	'Nous approfondissons lorsque le dossier est pertinent',
	'Nous étudions une solution de reprise adaptée',
	'Les modalités sont définies au cas par cas',
);
?>
<section class="hero" style="padding-block:clamp(3.4rem,7.5vw,5.6rem) clamp(2.8rem,5.5vw,4.4rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Céder son entreprise</p>
		<h1 class="monte" style="font-size:clamp(1.9rem,4.2vw,3rem)">Vous envisagez de céder <em>votre entreprise ?</em></h1>
		<p class="hero__texte monte">Nous étudions les opportunités de reprise progressive, les transmissions classiques et certaines situations particulières.</p>
	</div>
</section>

<section class="section">
	<div class="enveloppe">
		<p class="oeil">Ce que nous étudions</p>
		<div class="dirigeants" style="margin-top:1.8rem">
			<article class="dirigeant"><h2 class="dirigeant__nom" style="font-size:1.1rem">Secteurs</h2><p class="dirigeant__mot">Industrie, Technologie, Artisanat, Services, et autres secteurs selon opportunités.</p></article>
			<article class="dirigeant"><h2 class="dirigeant__nom" style="font-size:1.1rem">Zone géographique</h2><p class="dirigeant__mot">France (hors Île-de-France et régions trop au sud en priorité), ainsi que Belgique, Suisse, Canada-Québec, Allemagne.</p></article>
			<article class="dirigeant"><h2 class="dirigeant__nom" style="font-size:1.1rem">Situations étudiées</h2><p class="dirigeant__mot">Départ à la retraite, cession classique, reprise progressive, difficultés, sauvegarde, redressement judiciaire, liquidation judiciaire, autres situations à étudier.</p></article>
		</div>
	</div>
</section>

<section class="section section--creux">
	<div class="enveloppe">
		<p class="oeil">Notre processus</p>
		<div class="etapes etapes--compact" style="margin-top:2rem">
			<?php foreach ( $etapes as $i => $texte ) : ?>
				<article class="etape">
					<p class="etape__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></p>
					<div><p class="etape__texte" style="color:var(--encre);font-size:1.05rem"><?php echo esc_html( $texte ); ?></p></div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="chapeau" style="margin-top:1.8rem">Votre dossier est étudié après réception des informations nécessaires.</p>
	</div>
</section>

<section class="section">
	<div class="enveloppe">
		<p class="oeil">Présenter mon entreprise</p>
		<h2 class="titre-section">Formulaire de cession</h2>

		<?php if ( ! $actif ) : ?>
			<p class="encadre" style="margin-top:1.8rem;max-width:74ch">
				<strong>Formulaire non connecté à ce stade.</strong> Le canal technique — le webhook n8n de
				NEVIE-GLOBAL SAS — sera raccordé au Jalon 3. Tant que la réception n'est pas réellement
				confirmée par n8n, aucun accusé de réception ne doit être affiché au visiteur : c'est la règle
				du CDC (Partie 6), et l'envoi est donc désactivé plutôt que simulé.
			</p>
		<?php endif; ?>

		<form class="formulaire" method="post" novalidate<?php echo $actif ? '' : ' aria-disabled="true"'; ?>>
			<div class="formulaire__grille">
				<p class="champ"><label for="f-nom">Nom et prénom <span aria-hidden="true">*</span></label><input type="text" id="f-nom" name="nom" autocomplete="name" required></p>
				<p class="champ"><label for="f-societe">Société <span aria-hidden="true">*</span></label><input type="text" id="f-societe" name="societe" autocomplete="organization" required></p>
				<p class="champ"><label for="f-fonction">Fonction</label><input type="text" id="f-fonction" name="fonction" autocomplete="organization-title"></p>
				<p class="champ"><label for="f-email">Email <span aria-hidden="true">*</span></label><input type="email" id="f-email" name="email" autocomplete="email" required></p>
				<p class="champ"><label for="f-tel">Téléphone</label><input type="tel" id="f-tel" name="telephone" autocomplete="tel"></p>
				<p class="champ"><label for="f-loc">Localisation</label><input type="text" id="f-loc" name="localisation"></p>
				<p class="champ"><label for="f-activite">Activité</label><input type="text" id="f-activite" name="activite"></p>
				<p class="champ"><label for="f-ca">Chiffre d'affaires</label><input type="text" id="f-ca" name="ca"></p>
				<p class="champ"><label for="f-effectif">Effectif</label><input type="text" id="f-effectif" name="effectif"></p>
				<p class="champ"><label for="f-motif">Motif de cession</label><input type="text" id="f-motif" name="motif"></p>
				<p class="champ champ--large"><label for="f-situation">Situation de l'entreprise</label>
					<select id="f-situation" name="situation">
						<option value="">— Choisir —</option>
						<option>Départ à la retraite</option><option>Cession classique</option>
						<option>Reprise progressive</option><option>Difficultés</option>
						<option>Sauvegarde</option><option>Redressement judiciaire</option>
						<option>Liquidation judiciaire</option><option>Autre situation à étudier</option>
					</select></p>
				<p class="champ champ--large"><label for="f-message">Message</label><textarea id="f-message" name="message" rows="5"></textarea></p>
				<p class="champ champ--large"><label for="f-piece">Pièce jointe (optionnel)</label>
					<input type="file" id="f-piece" name="piece" accept=".pdf,.docx,.xlsx">
					<span class="champ__aide">Formats acceptés : PDF, DOCX, XLSX. Le fichier est stocké de manière privée et n'est jamais accessible par une URL publique.</span></p>
			</div>

			<p class="encadre" style="margin-top:1.6rem;max-width:74ch">
				Les informations et documents transmis dans le cadre d'une démarche de cession sont traités de
				manière confidentielle, sous réserve des obligations légales applicables. Ils sont conservés selon
				les durées indiquées dans notre <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">politique de confidentialité</a>.
			</p>

			<p class="champ champ--case"><label><input type="checkbox" name="consentement_etude" required> J'accepte que les informations transmises soient utilisées pour l'étude de mon projet de cession.</label></p>
			<p class="champ champ--case"><label><input type="checkbox" name="consentement_politique" required> J'ai pris connaissance de la politique de confidentialité.</label></p>

			<p style="margin-top:1.6rem">
				<button class="btn btn--or" type="submit"<?php echo $actif ? '' : ' disabled'; ?>>Transmettre mon dossier <?php echo ng_fleche(); ?></button>
			</p>
		</form>
	</div>
</section>
<?php get_footer();
