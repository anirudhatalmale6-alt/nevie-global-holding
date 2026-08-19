<?php
/** Repli générique. */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) : the_post(); ?>
<section class="hero" style="padding-block:clamp(3.4rem,7vw,5.4rem) clamp(2.6rem,5vw,4rem)">
	<?php echo ng_embleme( 'hero__embleme', 420 ); ?>
	<div class="enveloppe hero__grille">
		<h1 style="font-size:clamp(2rem,4.4vw,3.2rem)"><?php the_title(); ?></h1>
	</div>
</section>
<section class="section"><div class="enveloppe prose"><?php the_content(); ?></div></section>
<?php endwhile;
get_footer();
