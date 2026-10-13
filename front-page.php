<?php
/**
 * Front Page Template for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main flex-grow">
	<?php
	// Video Hero Section (exclusive to homepage)
	get_template_part( 'template-parts/hero-video' );

	// Output dynamic Gutenberg blocks
	while ( have_posts() ) :
		the_post();
		?>
		<div class="tedx-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
