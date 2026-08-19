<?php
/**
 * Fiche entreprise (Partie 5) — Pôle → Entreprise → Statut → Fiche → Lien externe.
 *
 * Règle de séparation SAS / LIMITED : si la fiche ne comporte pas de présentation
 * longue, elle se limite au renvoi externe. C'est le cas de NEVIE-GLOBAL LIMITED,
 * dont les activités ne sont pas détaillées sur le site de la holding.
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();

	$id     = get_the_ID();
	$statut = ng_champ( $id, 'statut' );
	$poles  = wp_get_post_terms( $id, NG_Participations::TAX_POLE );
	$pole   = ! empty( $poles ) ? $poles[0]->name : '';
	?>

	<section class="fiche__entete">
		<div class="enveloppe">
			<?php if ( $pole ) : ?>
				<p class="oeil oeil--sombre">Pôle <?php echo esc_html( $pole ); ?></p>
			<?php endif; ?>

			<h1 style="font-size:clamp(1.9rem,4.4vw,3.1rem);color:#fff"><?php the_title(); ?></h1>

			<?php if ( ng_champ( $id, 'description' ) ) : ?>
				<p class="hero__texte" style="margin-top:1.2rem">
					<?php echo esc_html( ng_champ( $id, 'description' ) ); ?>
				</p>
			<?php endif; ?>

			<dl class="fiche__meta">
				<?php if ( $statut ) : ?>
					<div><dt>Statut</dt><dd><?php echo esc_html( ng_libelle_statut( $statut ) ); ?></dd></div>
				<?php endif; ?>
				<?php if ( ng_champ( $id, 'secteur' ) ) : ?>
					<div><dt>Secteur</dt><dd><?php echo esc_html( ng_champ( $id, 'secteur' ) ); ?></dd></div>
				<?php endif; ?>
				<?php if ( ng_champ( $id, 'localisation' ) ) : ?>
					<div><dt>Localisation</dt><dd><?php echo esc_html( ng_champ( $id, 'localisation' ) ); ?></dd></div>
				<?php endif; ?>
				<?php if ( ng_champ( $id, 'date_integration' ) ) : ?>
					<div>
						<dt>Intégration</dt>
						<dd><?php echo esc_html( wp_date( 'F Y', strtotime( ng_champ( $id, 'date_integration' ) ) ) ); ?></dd>
					</div>
				<?php endif; ?>
			</dl>
		</div>
	</section>

	<section class="section">
		<div class="enveloppe"><div class="prose">

			<?php if ( ng_champ( $id, 'presentation' ) ) : ?>
				<?php echo wpautop( esc_html( ng_champ( $id, 'presentation' ) ) ); ?>
			<?php endif; ?>

			<?php if ( ng_champ( $id, 'complements' ) ) : ?>
				<h2>Informations complémentaires</h2>
				<?php echo wpautop( esc_html( ng_champ( $id, 'complements' ) ) ); ?>
			<?php endif; ?>

			<?php if ( ng_champ( $id, 'site_internet' ) ) : ?>
				<p style="margin-top:2.2rem">
					<a class="btn btn--encre" href="<?php echo esc_url( ng_champ( $id, 'site_internet' ) ); ?>"
					   rel="noopener external" target="_blank">
						Visiter le site <?php echo ng_fleche(); ?>
					</a>
				</p>
			<?php endif; ?>

			<?php if ( 'acquisition' === $statut || 'projet' === $statut ) : ?>
				<p class="encadre" style="margin-top:2.2rem">
					Opération en cours. Cette entreprise n'est pas détenue par NEVIE-GLOBAL SAS
					à ce stade ; le statut sera mis à jour à l'issue de l'opération.
				</p>
			<?php endif; ?>

			<p style="margin-top:2.4rem">
				<a class="lien-fleche" href="<?php echo esc_url( home_url( '/nos-entreprises/' ) ); ?>">
					Retour au portefeuille <?php echo ng_fleche( 'lien-fleche__icone' ); ?>
				</a>
			</p>

		</div></div>
	</section>

	<?php
endwhile;

get_footer();
