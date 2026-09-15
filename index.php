<?php
/**
 * The main template file
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main flex-grow pt-28 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark">
	<div class="max-w-figma mx-auto">
		
		<?php if ( have_posts() ) : ?>
			
			<header class="page-header mb-12">
				<h1 class="page-title text-4xl md:text-5xl font-black text-white tracking-tight">
					<?php
					if ( is_home() && ! is_front_page() ) {
						single_post_title();
					} elseif ( is_archive() ) {
						the_archive_title();
					} elseif ( is_search() ) {
						printf( esc_html__( 'Search Results for: %s', 'tedx-regensburg' ), '<span>' . get_search_query() . '</span>' );
					} else {
						esc_html_e( 'Latest News & Updates', 'tedx-regensburg' );
					}
					?>
				</h1>
			</header>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				?>
			</div>

			<div class="pagination mt-12 flex justify-center gap-2">
				<?php
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => '&larr; ' . __( 'Previous', 'tedx-regensburg' ),
					'next_text' => __( 'Next', 'tedx-regensburg' ) . ' &rarr;',
				) );
				?>
			</div>

		<?php else : ?>

			<div class="bg-tedx-card rounded-3xl p-12 text-center border border-white/5">
				<h2 class="text-2xl font-bold text-white mb-4"><?php esc_html_e( 'Nothing Found', 'tedx-regensburg' ); ?></h2>
				<p class="text-white/70 max-w-md mx-auto mb-6"><?php esc_html_e( 'It seems we cannot find what you are looking for.', 'tedx-regensburg' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-tedx-red text-white text-sm font-medium px-6 py-3 rounded-xl"><?php esc_html_e( 'Back to Home', 'tedx-regensburg' ); ?></a>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
