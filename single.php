<?php
/**
 * The template for displaying all single posts
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main flex-grow pt-28 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark">
	<div class="max-w-4xl mx-auto">
		
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile;
		?>

	</div>
</main>

<?php
get_footer();
