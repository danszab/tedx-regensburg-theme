<?php
/**
 * Speakers Archive Template
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main flex-grow py-16 px-4 md:px-8 lg:px-12 bg-tedx-dark">
	<div class="max-w-figma mx-auto">
		
		<header class="page-header mb-12">
			<h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-none mb-3">
				<?php esc_html_e( 'TEDx Regensburg Speakers', 'tedx-regensburg' ); ?>
			</h1>
			<p class="text-lg text-white/70">
				<?php esc_html_e( 'Explore all inspiring minds and past speakers from TEDx Regensburg.', 'tedx-regensburg' ); ?>
			</p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">
				<?php
				while ( have_posts() ) :
					the_post();
					$speaker_id    = get_the_ID();
					$topic         = get_post_meta( $speaker_id, '_speaker_topic', true ) ?: __( 'Speaker', 'tedx-regensburg' );
					$language      = get_post_meta( $speaker_id, '_speaker_language', true ) ?: 'EN';
					$linkedin_url  = get_post_meta( $speaker_id, '_speaker_linkedin', true );
					$has_thumbnail = has_post_thumbnail();
					?>
					<article class="bg-tedx-card rounded-[32px] p-6 flex flex-col justify-between border border-white/5 hover:border-white/20 transition-all duration-300 shadow-xl group">
						<div>
							<div class="flex gap-4 items-start mb-4">
								<div class="w-[122px] h-[122px] rounded-2xl overflow-hidden shrink-0 bg-[#333] border border-white/10 relative">
									<?php if ( $has_thumbnail ) : ?>
										<?php the_post_thumbnail( 'tedx-speaker', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300' ) ); ?>
									<?php else : ?>
										<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-3xl bg-gradient-to-br from-[#333] to-[#222]">
											<?php echo mb_substr( get_the_title(), 0, 1 ); ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="flex-1 flex flex-col gap-1 min-w-0">
									<h2 class="text-xl font-bold text-white leading-tight truncate">
										<a href="<?php the_permalink(); ?>" class="hover:text-tedx-red transition-colors">
											<?php the_title(); ?>
										</a>
									</h2>
									<span class="text-sm font-bold text-tedx-red truncate">
										<?php echo esc_html( $topic ); ?>
									</span>
									<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2 py-0.5 rounded text-[11px] font-medium text-white/80 w-fit mt-1">
										<?php echo tedx_get_icon( 'globe', 'w-3 h-3' ); ?>
										<span><?php echo esc_html( $language ); ?></span>
									</div>
								</div>
							</div>

							<div class="text-sm text-white/70 leading-relaxed font-normal mb-6">
								<?php
								if ( has_excerpt() ) {
									the_excerpt();
								} else {
									echo wp_trim_words( get_the_content(), 22, '...' );
								}
								?>
							</div>
						</div>

						<div class="flex items-center justify-between pt-2 border-t border-white/5">
							<a href="<?php the_permalink(); ?>" class="text-xs font-bold text-tedx-red hover:underline uppercase tracking-wider">
								<?php esc_html_e( 'Read Profile', 'tedx-regensburg' ); ?> &rarr;
							</a>
							<?php if ( ! empty( $linkedin_url ) ) : ?>
								<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-white/50 hover:text-white transition-colors" aria-label="LinkedIn">
									<?php echo tedx_get_icon( 'linkedin', 'w-4 h-4' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="pagination mt-12 flex justify-center">
				<?php the_posts_pagination(); ?>
			</div>

		<?php else : ?>
			<p class="text-white/60"><?php esc_html_e( 'No speakers found.', 'tedx-regensburg' ); ?></p>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
