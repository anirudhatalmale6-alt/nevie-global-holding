<?php
/**
 * Template Name: Page 5 — Nos Entreprises / Participations
 *
 * Logique d'affichage imposée (Partie 5) : Pôle → Entreprise → Statut → Fiche → Lien externe.
 * Tout provient du CMS : ajouter une entreprise ne demande aucune intervention sur le code.
 *
 * Interdits respectés : aucune cible n'est présentée comme filiale détenue ;
 * la fiche LIMITED ne détaille pas ses activités et renvoie uniquement vers nevie-global.com.
 */

defined( 'ABSPATH' ) || exit;
get_header();

$groupes = NG_Participations::par_pole();
?>

<section class="hero" style="padding-block:clamp(3.6rem,8vw,6rem) clamp(3rem,6vw,4.6rem)">
	<?php echo ng_embleme( 'hero__embleme', 460 ); ?>
	<div class="enveloppe hero__grille">
		<p class="oeil monte">Portefeuille</p>
		<h1 class="monte" style="font-size:clamp(2.1rem,4.6vw,3.4rem)">Nos entreprises</h1>
		<p class="hero__texte monte">
			Découvrez les entreprises détenues, en cours d'acquisition et les pôles à venir du groupe.
		</p>
	</div>
</section>

<section class="section">
	<div class="enveloppe">

		<?php if ( empty( $groupes ) ) : ?>

			<p class="chapeau">Aucune participation n'est publiée pour le moment.</p>

		<?php else : ?>

			<?php foreach ( $groupes as $groupe ) : ?>
				<div class="pole">
					<div class="pole__entete">
						<h2 class="pole__titre">Pôle <?php echo esc_html( $groupe['nom'] ); ?></h2>
						<span class="pole__compte">
							<?php
							$n = count( $groupe['items'] );
							printf( '%d %s', $n, 1 === $n ? 'entreprise' : 'entreprises' );
							?>
						</span>
					</div>
					<div class="pole__filet"></div>

					<div class="portefeuille" style="margin-top:0;border-top:0">
						<?php
						foreach ( $groupe['items'] as $p ) :
							$statut = ng_champ( $p->ID, 'statut' );
							$fiche  = ng_a_une_fiche( $p->ID );
							$balise = $fiche ? 'a' : 'div';
							$href   = $fiche ? ' href="' . esc_url( get_permalink( $p ) ) . '"' : '';
							?>
							<div class="entree">
							<<?php echo $balise . $href; ?> class="ligne">
								<span class="ligne__nom"><?php echo esc_html( get_the_title( $p ) ); ?></span>
								<span class="ligne__secteur">
									<?php
									$bouts = array_filter( array(
										ng_champ( $p->ID, 'secteur' ),
										ng_champ( $p->ID, 'localisation' ),
									) );
									echo esc_html( implode( ' — ', $bouts ) );
									?>
								</span>
								<span class="ligne__fin">
									<span class="pastille <?php echo esc_attr( NG_Participations::classe_statut( $statut ) ); ?>">
										<?php echo esc_html( ng_libelle_statut( $statut ) ); ?>
									</span>
									<?php echo $fiche ? ng_fleche( 'btn__fleche' ) : ''; ?>
								</span>
							</<?php echo $balise; ?>>

							<?php if ( ng_champ( $p->ID, 'description' ) ) : ?>
								<p style="margin:-.5rem 0 1.4rem;max-width:70ch;color:var(--gris);font-weight:300;font-size:.97rem">
									<?php echo esc_html( ng_champ( $p->ID, 'description' ) ); ?>
									<?php if ( ng_champ( $p->ID, 'site_internet' ) && ! $fiche ) : ?>
										<a class="lien-fleche" style="margin-left:.4rem"
										   href="<?php echo esc_url( ng_champ( $p->ID, 'site_internet' ) ); ?>"
										   rel="noopener external" target="_blank">
											Visiter le site <?php echo ng_fleche( 'lien-fleche__icone' ); ?>
										</a>
									<?php endif; ?>
								</p>
							<?php endif; ?>
							</div>

						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>

			<p class="chapeau" style="margin-top:2.6rem">
				D'autres secteurs pourront s'ajouter au fil de nos acquisitions.
			</p>

		<?php endif; ?>

	</div>
</section>

<section class="bandeau">
	<div class="enveloppe bandeau__grille">
		<div>
			<h2>Votre entreprise pourrait rejoindre le groupe.</h2>
			<p>Nous étudions les opportunités de reprise dans différents secteurs et situations.</p>
		</div>
		<div>
			<a class="btn btn--or" href="<?php echo esc_url( home_url( '/ceder-son-entreprise/' ) ); ?>">
				Présenter mon entreprise <?php echo ng_fleche(); ?>
			</a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
