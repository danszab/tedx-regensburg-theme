<?php
/**
 * The template for displaying all pages
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$has_full_width = tedx_page_has_full_width_blocks( get_queried_object_id() );

get_header();
?>

<main id="primary" class="<?php echo esc_attr( $has_full_width ? 'site-main flex-grow pt-28 pb-16 bg-tedx-dark tedx-full-width-page' : 'site-main flex-grow pt-28 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark' ); ?>">
	<div class="<?php echo esc_attr( $has_full_width ? 'tedx-full-width-page-inner' : 'max-w-4xl mx-auto' ); ?>">
		
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $has_full_width ? 'tedx-full-width-page-article' : 'bg-tedx-card rounded-3xl p-8 md:p-12 border border-white/5 shadow-2xl' ); ?>>
				
				<header class="entry-header mb-8">
					<h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-4">
						<?php the_title(); ?>
					</h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mb-8 rounded-2xl overflow-hidden aspect-video<?php echo $has_full_width ? ' tedx-full-width-page-featured' : ''; ?>">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $has_full_width ? 'tedx-full-width-page-content text-white/90 leading-relaxed text-base' : 'entry-content text-white/90 leading-relaxed space-y-4 text-base' ); ?>">
					<?php the_content(); ?>
				</div>

			</article>
			<?php
		endwhile;
		?>

	</div>
</main>

<?php
get_footer();
