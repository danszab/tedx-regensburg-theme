<?php
/**
 * Template Name: About Us Page
 * 
 * @package TEDx_Regensburg
 */

get_header();
?>

<main id="primary" class="site-main flex-grow bg-tedx-dark w-full pt-[60px] lg:pt-[70px] pb-16">
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
