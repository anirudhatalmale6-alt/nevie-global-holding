<?php
/**
 * Page 1 — Accueil (CDC Partie 13).
 *
 * Blocs imposés : hero, présentation courte du groupe, gouvernance, portefeuille,
 * stratégie de croissance (par acquisitions), acquisitions, CTA « Céder son entreprise »,
 * contact, footer légal.
 *
 * Interdits respectés : aucun produit, prix, boutique, Founder Card, blockchain,
 * catalogue, ni activité de LIMITED présentée comme celle de la SAS.
 *
 * Les textes sont ceux de la V3, repris mot pour mot.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="hero">
	<?php echo ng_embleme( 'hero__embleme', 520 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Holding entrepreneuriale française</p>
		<h1 class="monte">Un groupe.<br>Des entreprises.<br><em>Une vision de long terme.</em></h1>
		<p class="hero__texte monte">
			NEVIE-GLOBAL construit progressivement un portefeuille diversifié d'entreprises
			indépendantes dans l'industrie, les services, la technologie et l'artisanat.
		</p>
		<div class="hero__actions monte">
			<a class="btn btn--or" href="<?php echo esc_url( home_url( '/le-groupe/' ) ); ?>">
				Découvrir le groupe <?php echo ng_fleche(); ?>
			</a>
			<a class="btn btn--fantome" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">
				Vous cédez votre entreprise ? <?php echo ng_fleche(); ?>
			</a>
		</div>
	</div>
</section>

<section class="section">
	<div class="enveloppe duo">
		<p class="duo__aparte">Le groupe</p>
		<div class="duo__corps">
			<p>
				NEVIE-GLOBAL SAS est une holding entrepreneuriale française qui identifie, reprend
				et accompagne des entreprises dans des secteurs volontairement diversifiés.
				Notre approche privilégie la continuité : chaque entreprise qui rejoint le groupe
				conserve son nom, son identité et son savoir-faire.
			</p>
			<p style="margin-top:1.8rem">
				<a class="lien-fleche" href="<?php echo esc_url( home_url( '/le-groupe/' ) ); ?>">
					En savoir plus sur le groupe <?php echo ng_fleche( 'lien-fleche__icone' ); ?>
				</a>
			</p>
		</div>
	</div>
</section>

<section class="section section--creux">
	<div class="enveloppe">
		<p class="oeil">Gouvernance</p>
		<h2 class="titre-section">Une direction resserrée, une gouvernance claire.</h2>

		<div class="dirigeants">
			<article class="dirigeant">
				<h3 class="dirigeant__nom">Elisa Varinot</h3>
				<p class="dirigeant__role">Présidente</p>
				<p class="dirigeant__mot">Co-fondatrice de NEVIE-GLOBAL SAS.</p>
			</article>
			<article class="dirigeant">
				<h3 class="dirigeant__nom">Emmanuel Varinot</h3>
				<p class="dirigeant__role">Directeur Général</p>
				<p class="dirigeant__mot">Co-fondateur de NEVIE-GLOBAL SAS.</p>
			</article>
		</div>

		<p style="margin-top:2rem">
			<a class="lien-fleche" href="<?php echo esc_url( home_url( '/gouvernance/' ) ); ?>">
				La gouvernance du groupe <?php echo ng_fleche( 'lien-fleche__icone' ); ?>
			</a>
		</p>
	</div>
</section>

<?php
/* Bloc Portefeuille — aperçu alimenté par le CMS (Partie 5), jamais codé en dur. */
$groupes = NG_Participations::par_pole();
?>
<section class="section">
	<div class="enveloppe">
		<p class="oeil">Portefeuille</p>
		<h2 class="titre-section">Nos entreprises</h2>

		<div class="portefeuille">
			<?php
			$apercu = array();
			foreach ( $groupes as $groupe ) {
				foreach ( $groupe['items'] as $p ) {
					$apercu[] = array( 'post' => $p, 'pole' => $groupe['nom'] );
				}
			}
			$apercu = array_slice( $apercu, 0, 4 );

			foreach ( $apercu as $entree ) :
				$p      = $entree['post'];
				$statut = ng_champ( $p->ID, 'statut' );
				$fiche  = ng_a_une_fiche( $p->ID );
				$balise = $fiche ? 'a' : 'div';
				$href   = $fiche ? ' href="' . esc_url( get_permalink( $p ) ) . '"' : '';
				?>
				<<?php echo $balise . $href; ?> class="ligne">
					<span class="ligne__nom"><?php echo esc_html( get_the_title( $p ) ); ?></span>
					<span class="ligne__secteur"><?php echo esc_html( ng_champ( $p->ID, 'secteur', $entree['pole'] ) ); ?></span>
					<span class="ligne__fin">
						<span class="pastille <?php echo esc_attr( NG_Participations::classe_statut( $statut ) ); ?>">
							<?php echo esc_html( ng_libelle_statut( $statut ) ); ?>
						</span>
						<?php echo $fiche ? ng_fleche( 'btn__fleche' ) : ''; ?>
					</span>
				</<?php echo $balise; ?>>
			<?php endforeach; ?>
		</div>

		<?php
		/* Pôles à venir — présentés comme tels, jamais comme des filiales détenues. */
		$a_venir = array();
		foreach ( $groupes as $groupe ) {
			foreach ( $groupe['items'] as $p ) {
				if ( 'venir' === ng_champ( $p->ID, 'statut' ) ) {
					$a_venir[] = get_the_title( $p );
				}
			}
		}
		if ( $a_venir ) : ?>
			<p class="chapeau" style="margin-top:1.8rem">
				Pôles à venir : <?php echo esc_html( implode( ', ', $a_venir ) ); ?>.
			</p>
		<?php endif; ?>

		<p style="margin-top:2rem">
			<a class="lien-fleche" href="<?php echo esc_url( home_url( '/nos-entreprises/' ) ); ?>">
				Voir toutes nos entreprises <?php echo ng_fleche( 'lien-fleche__icone' ); ?>
			</a>
		</p>
	</div>
</section>

<section class="section section--sombre">
	<div class="enveloppe duo">
		<p class="duo__aparte" style="color:#B3AA9B">Stratégie de croissance</p>
		<div class="duo__corps">
			<p>
				NEVIE-GLOBAL développe progressivement son portefeuille par acquisitions et reprises
				d'entreprises — reprises classiques, reprises progressives, ou opérations à prix
				symbolique selon le contexte de transmission. Cette dimension constitue l'axe central
				de notre stratégie de développement.
			</p>
			<p style="margin-top:1.8rem">
				<a class="lien-fleche" style="color:var(--or-clair)" href="<?php echo esc_url( home_url( '/croissance-acquisitions/' ) ); ?>">
					Croissance &amp; acquisitions <?php echo ng_fleche( 'lien-fleche__icone' ); ?>
				</a>
			</p>
		</div>
	</div>
</section>

<section class="bandeau">
	<div class="enveloppe bandeau__grille">
		<div>
			<h2>Vous envisagez de céder votre entreprise ?</h2>
			<p>
				Nous étudions les opportunités de reprise dans différents secteurs et situations.
			</p>
		</div>
		<div>
			<a class="btn btn--or" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">
				Présenter mon entreprise <?php echo ng_fleche(); ?>
			</a>
		</div>
	</div>
</section>

<section class="section">
	<div class="enveloppe duo">
		<p class="duo__aparte">Contact</p>
		<div class="duo__corps">
			<p>Une question sur NEVIE-GLOBAL ou son portefeuille ?</p>
			<p style="margin-top:1.4rem">
				<a class="btn btn--encre" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					Contactez-nous <?php echo ng_fleche(); ?>
				</a>
			</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
