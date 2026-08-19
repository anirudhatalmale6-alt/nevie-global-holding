<?php defined( 'ABSPATH' ) || exit; ?>
</main>

<footer class="pied">
	<div class="enveloppe">

		<div class="pied__grille">

			<div>
				<a class="marque" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo ng_embleme( 'marque__embleme', 24 ); ?>
					<span class="marque__nom">NEVIE<span>-</span>GLOBAL</span>
				</a>
				<p class="pied__adresse">
					NEVIE-GLOBAL SAS<br>
					2 Place Jean V, bureau 3<br>
					44000 Nantes, France
				</p>
			</div>

			<div>
				<p class="pied__titre">Le groupe</p>
				<ul>
					<?php foreach ( ng_menu_secours( 'pied' ) as $url => $libelle ) : ?>
						<li><a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $libelle ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div>
				<p class="pied__titre">Informations légales</p>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>">Mentions légales</a></li>
					<li><a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">Politique de confidentialité</a></li>
					<li><a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/#cookies' ) ); ?>">Gestion des cookies</a></li>
				</ul>
			</div>

		</div>

		<div class="pied__bas">
			<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> NEVIE-GLOBAL SAS — Société par actions simplifiée au capital de 50 € — SIREN 993 888 841 (Nantes).</p>
			<p>Directeur de la publication : Emmanuel Varinot.</p>
		</div>

	</div>
</footer>

<?php
/**
 * Bannière cookies — Partie 6 / Page 10.
 * Aucun script non essentiel n'est chargé avant un consentement explicite ;
 * « Refuser » n'en charge aucun non plus. Le choix est mémorisé localement.
 */
?>
<aside class="cookies" id="cookies" role="dialog" aria-labelledby="cookies-titre" aria-describedby="cookies-texte" hidden>
	<div class="cookies__grille">
		<div>
			<p id="cookies-titre" class="pied__titre" style="color:var(--or-fonce)">Cookies</p>
			<p id="cookies-texte">
				Ce site n'utilise que des cookies strictement nécessaires à son fonctionnement.
				Aucun traceur de mesure d'audience ni de publicité n'est déposé sans votre accord.
				Consultez notre <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">politique de confidentialité</a>.
			</p>
		</div>
		<div class="cookies__actions">
			<button class="btn btn--encre" type="button" data-cookies="accepter">Accepter</button>
			<button class="btn btn--ligne" type="button" data-cookies="refuser">Refuser</button>
			<button class="btn btn--ligne" type="button" data-cookies="personnaliser">Personnaliser</button>
		</div>
	</div>
</aside>

<?php wp_footer(); ?>
</body>
</html>
