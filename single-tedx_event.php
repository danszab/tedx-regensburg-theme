<?php
/**
 * Single Template for Events
 *
 * @package TEDx_Regensburg
 */

get_header();
?>

<main id="primary" class="site-main flex-grow bg-tedx-dark">
	<?php
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
