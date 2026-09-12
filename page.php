<?php
/**
 * The template for displaying all pages
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
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-tedx-card rounded-3xl p-8 md:p-12 border border-white/5 shadow-2xl' ); ?>>
				
				<header class="entry-header mb-8">
					<h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-4">
						<?php the_title(); ?>
					</h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mb-8 rounded-2xl overflow-hidden aspect-video">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="entry-content text-white/90 leading-relaxed space-y-4 text-base">
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
