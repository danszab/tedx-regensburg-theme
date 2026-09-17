<?php
/**
 * Single Speaker Detail Template
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

$speaker_id   = get_the_ID();
$topic        = get_post_meta( $speaker_id, '_speaker_topic', true );
$language     = get_post_meta( $speaker_id, '_speaker_language', true ) ?: 'EN';
$year         = get_post_meta( $speaker_id, '_speaker_year', true ) ?: '2026';
$linkedin_url = get_post_meta( $speaker_id, '_speaker_linkedin', true );
$youtube_url  = get_post_meta( $speaker_id, '_speaker_youtube', true );
?>

<main id="primary" class="site-main flex-grow pt-28 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark">
	<div class="max-w-figma mx-auto">
		
		<!-- Breadcrumb / Back Link -->
		<div class="mb-8">
			<a href="<?php echo esc_url( home_url( '/#speakers' ) ); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-white/60 hover:text-tedx-red transition-colors">
				&larr; <?php esc_html_e( 'Back to All Speakers', 'tedx-regensburg' ); ?>
			</a>
		</div>

		<article id="post-<?php the_ID(); ?>" class="bg-tedx-card rounded-[32px] p-8 md:p-12 border border-white/5 shadow-2xl">
			
			<div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-start">
				
				<!-- Speaker Photo -->
				<div class="md:col-span-5">
					<div class="w-full aspect-square rounded-[24px] overflow-hidden bg-[#333] border border-white/10 relative shadow-lg">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
						<?php else : ?>
							<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-6xl bg-gradient-to-br from-[#333] to-[#222]">
								<?php echo mb_substr( get_the_title(), 0, 1 ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $linkedin_url ) || ! empty( $youtube_url ) ) : ?>
						<div class="mt-6 flex flex-col gap-3">
							<?php if ( ! empty( $youtube_url ) ) : ?>
								<a href="#talk-video" class="inline-flex items-center gap-2 w-full justify-center bg-tedx-red text-white py-3 px-6 rounded-xl font-bold text-[13px] tracking-wider uppercase btn-shadcn-anim transition-all">
									<?php echo tedx_get_icon( 'youtube', 'w-5 h-5' ); ?>
									<span><?php esc_html_e( 'Watch the Talk', 'tedx-regensburg' ); ?></span>
								</a>
							<?php endif; ?>
							<?php if ( ! empty( $linkedin_url ) ) : ?>
								<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 w-full justify-center bg-[#212121] border border-white/20 text-white py-3 px-6 rounded-xl font-bold text-[13px] tracking-wider uppercase btn-shadcn-anim transition-all">
									<?php echo tedx_get_icon( 'linkedin', 'w-5 h-5' ); ?>
									<span><?php esc_html_e( 'View LinkedIn Profile', 'tedx-regensburg' ); ?></span>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Speaker Info & Bio -->
				<div class="md:col-span-7 flex flex-col gap-4">
					
					<div class="flex items-center gap-3">
						<span class="inline-block bg-tedx-red/20 text-tedx-red text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
							TEDxRegensburg <?php echo esc_html( $year ); ?>
						</span>
						<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2.5 py-1 rounded text-xs font-medium text-white">
							<?php echo tedx_get_icon( 'globe', 'w-3.5 h-3.5' ); ?>
							<span><?php echo esc_html( $language ); ?></span>
						</div>
					</div>

					<h1 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
						<?php the_title(); ?>
					</h1>

					<?php if ( ! empty( $topic ) ) : ?>
						<div class="text-xl font-bold text-tedx-red">
							<?php echo esc_html( $topic ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content text-white/90 leading-relaxed text-base space-y-4 pt-4 border-t border-white/10">
						<?php the_content(); ?>
					</div>

					<?php if ( ! empty( $youtube_url ) ) : ?>
						<div id="talk-video" class="mt-10 scroll-mt-32">
							<h3 class="text-2xl font-bold text-white mb-5 flex items-center gap-2">
								<?php echo tedx_get_icon( 'youtube', 'w-6 h-6 text-tedx-red' ); ?>
								<?php esc_html_e( 'The Talk', 'tedx-regensburg' ); ?>
							</h3>
							<div class="aspect-video w-full rounded-[20px] overflow-hidden bg-black shadow-2xl [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0 border border-white/10">
								<?php echo wp_oembed_get( $youtube_url ); ?>
							</div>
						</div>
					<?php endif; ?>

				</div>

			</div>

		</article>

	</div>
</main>

<?php
get_footer();
