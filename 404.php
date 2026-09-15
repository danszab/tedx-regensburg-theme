<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main flex-grow flex items-center justify-center pt-28 pb-24 px-4 bg-tedx-dark text-center">
	<div class="max-w-lg mx-auto bg-tedx-card rounded-[32px] p-8 md:p-12 border border-white/5 shadow-2xl">
		<div class="text-7xl md:text-8xl font-black text-tedx-red mb-4">404</div>
		<h1 class="text-2xl md:text-3xl font-bold text-white mb-3"><?php esc_html_e( 'Page Not Found', 'tedx-regensburg' ); ?></h1>
		<p class="text-white/70 text-base mb-8">
			<?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'tedx-regensburg' ); ?>
		</p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-2 bg-tedx-red hover:bg-tedx-red-hover text-white font-medium px-6 py-3 rounded-xl transition-all duration-200">
			&larr; <?php esc_html_e( 'Return to Homepage', 'tedx-regensburg' ); ?>
		</a>
	</div>
</main>

<?php
get_footer();
