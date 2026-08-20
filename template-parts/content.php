<?php
/**
 * Generic Content Template Part
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-tedx-card rounded-3xl p-8 md:p-12 border border-white/5 shadow-xl mb-12' ); ?>>
	
	<header class="entry-header mb-8">
		<?php if ( is_singular() ) : ?>
			<h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-4">
				<?php the_title(); ?>
			</h1>
		<?php else : ?>
			<h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight mb-3">
				<a href="<?php the_permalink(); ?>" class="hover:text-tedx-red transition-colors">
					<?php the_title(); ?>
				</a>
			</h2>
		<?php endif; ?>

		<div class="flex items-center gap-4 text-xs font-medium text-white/50">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<span>&bull;</span>
			<span><?php the_author(); ?></span>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="mb-8 rounded-2xl overflow-hidden aspect-video">
			<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content prose prose-invert max-w-none text-white/90 leading-relaxed space-y-4">
		<?php
		the_content( sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'tedx-regensburg' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			wp_kses_post( get_the_title() )
		) );

		wp_link_pages( array(
			'before' => '<div class="page-links mt-6 pt-4 border-t border-white/10 flex gap-2"><span class="text-white/60">' . esc_html__( 'Pages:', 'tedx-regensburg' ) . '</span>',
			'after'  => '</div>',
		) );
		?>
	</div>

</article>
