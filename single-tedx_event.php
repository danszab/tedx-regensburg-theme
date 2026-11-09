<?php
/**
 * Single Template for Events
 *
 * @package TEDx_Regensburg
 */

$hide_navbar = function_exists( 'tedx_is_navbar_hidden' ) ? tedx_is_navbar_hidden() : false;

get_header();
?>

<main id="primary" class="<?php echo esc_attr( $hide_navbar ? 'site-main flex-grow bg-tedx-dark pt-0 pb-16' : 'site-main flex-grow bg-tedx-dark pt-[60px] lg:pt-[70px] pb-16' ); ?>">
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
