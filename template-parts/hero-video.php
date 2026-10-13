<?php
/**
 * Video Hero Section Template Part (Figma: Homepage - Hero alternative 2, node 166:2617)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Desktop / Horizontal Video
$desktop_webm   = tedx_mod( 'tedx_hero_video_webm', '' );
$desktop_mp4    = tedx_mod( 'tedx_hero_video_mp4', '' );
$desktop_poster = tedx_mod( 'tedx_hero_video_poster', '' );

// Mobile / Vertical Video
$mobile_webm   = tedx_mod( 'tedx_hero_video_mobile_webm', '' );
$mobile_mp4    = tedx_mod( 'tedx_hero_video_mobile_mp4', '' );
$mobile_poster = tedx_mod( 'tedx_hero_video_mobile_poster', '' );

// Fallbacks for posters
$effective_mobile_poster  = ! empty( $mobile_poster ) ? $mobile_poster : $desktop_poster;
$effective_desktop_poster = ! empty( $desktop_poster ) ? $desktop_poster : $mobile_poster;

$has_desktop_video = ! empty( $desktop_webm ) || ! empty( $desktop_mp4 );
$has_mobile_video  = ! empty( $mobile_webm ) || ! empty( $mobile_mp4 );
$has_any_video     = $has_desktop_video || $has_mobile_video;
$has_both_videos   = $has_desktop_video && $has_mobile_video;

$button_text = tedx_mod( 'tedx_hero_video_btn_text', __( 'This Years Event', 'tedx-regensburg' ) );
$button_url  = tedx_mod( 'tedx_hero_video_btn_url', '#event-pitch' );
$show_button = (bool) tedx_mod( 'tedx_hero_video_btn_show', true );
?>

<style>
	.tedx-hero-video-section {
		height: 100vh;
		height: 100dvh;
		min-height: 100vh;
		min-height: 100dvh;
	}
	.tedx-hero-video-btn-wrap {
		padding-bottom: calc(32px + env(safe-area-inset-bottom, 0px));
	}
	@media (min-width: 768px) {
		.tedx-hero-video-btn-wrap {
			padding-bottom: 3rem !important;
		}
	}
</style>

<section id="hero" class="tedx-hero-video-section relative w-full h-screen min-h-screen overflow-hidden flex flex-col justify-end items-center bg-[#0a0a0a] text-white">

	<!-- Media Background: Vertical (Mobile) & Horizontal (Desktop) Videos or Preview Posters -->
	<div class="tedx-hero-video-media absolute inset-0 w-full h-full z-0 overflow-hidden">
		<?php if ( $has_both_videos ) : ?>
			<!-- Mobile Video (Vertical / Portrait - Visible on screens < 768px) -->
			<video
				autoplay
				loop
				muted
				playsinline
				<?php if ( ! empty( $effective_mobile_poster ) ) : ?>poster="<?php echo esc_url( $effective_mobile_poster ); ?>"<?php endif; ?>
				class="block md:hidden absolute inset-0 w-full h-full object-cover pointer-events-none"
			>
				<?php if ( ! empty( $mobile_webm ) ) : ?>
					<source src="<?php echo esc_url( $mobile_webm ); ?>" type="video/webm">
				<?php endif; ?>
				<?php if ( ! empty( $mobile_mp4 ) ) : ?>
					<source src="<?php echo esc_url( $mobile_mp4 ); ?>" type="video/mp4">
				<?php endif; ?>
			</video>

			<!-- Desktop Video (Horizontal / Landscape - Visible on screens >= 768px) -->
			<video
				autoplay
				loop
				muted
				playsinline
				<?php if ( ! empty( $effective_desktop_poster ) ) : ?>poster="<?php echo esc_url( $effective_desktop_poster ); ?>"<?php endif; ?>
				class="hidden md:block absolute inset-0 w-full h-full object-cover pointer-events-none"
			>
				<?php if ( ! empty( $desktop_webm ) ) : ?>
					<source src="<?php echo esc_url( $desktop_webm ); ?>" type="video/webm">
				<?php endif; ?>
				<?php if ( ! empty( $desktop_mp4 ) ) : ?>
					<source src="<?php echo esc_url( $desktop_mp4 ); ?>" type="video/mp4">
				<?php endif; ?>
			</video>
		<?php elseif ( $has_desktop_video ) : ?>
			<!-- Desktop Video (Used across all devices when no mobile-specific video is uploaded) -->
			<video
				autoplay
				loop
				muted
				playsinline
				<?php if ( ! empty( $effective_desktop_poster ) ) : ?>poster="<?php echo esc_url( $effective_desktop_poster ); ?>"<?php endif; ?>
				class="absolute inset-0 w-full h-full object-cover pointer-events-none"
			>
				<?php if ( ! empty( $desktop_webm ) ) : ?>
					<source src="<?php echo esc_url( $desktop_webm ); ?>" type="video/webm">
				<?php endif; ?>
				<?php if ( ! empty( $desktop_mp4 ) ) : ?>
					<source src="<?php echo esc_url( $desktop_mp4 ); ?>" type="video/mp4">
				<?php endif; ?>
			</video>
		<?php elseif ( $has_mobile_video ) : ?>
			<!-- Mobile Video (Used when only vertical video is uploaded) -->
			<video
				autoplay
				loop
				muted
				playsinline
				<?php if ( ! empty( $effective_mobile_poster ) ) : ?>poster="<?php echo esc_url( $effective_mobile_poster ); ?>"<?php endif; ?>
				class="absolute inset-0 w-full h-full object-cover pointer-events-none"
			>
				<?php if ( ! empty( $mobile_webm ) ) : ?>
					<source src="<?php echo esc_url( $mobile_webm ); ?>" type="video/webm">
				<?php endif; ?>
				<?php if ( ! empty( $mobile_mp4 ) ) : ?>
					<source src="<?php echo esc_url( $mobile_mp4 ); ?>" type="video/mp4">
				<?php endif; ?>
			</video>
		<?php elseif ( ! empty( $mobile_poster ) && ! empty( $desktop_poster ) ) : ?>
			<!-- Responsive Preview Poster Frame (<picture> switching between vertical and horizontal frames) -->
			<picture class="absolute inset-0 w-full h-full pointer-events-none">
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile_poster ); ?>">
				<img
					src="<?php echo esc_url( $desktop_poster ); ?>"
					alt="<?php esc_attr_e( 'Hero preview frame', 'tedx-regensburg' ); ?>"
					class="w-full h-full object-cover"
				/>
			</picture>
		<?php elseif ( ! empty( $effective_desktop_poster ) ) : ?>
			<img
				src="<?php echo esc_url( $effective_desktop_poster ); ?>"
				alt="<?php esc_attr_e( 'Hero preview frame', 'tedx-regensburg' ); ?>"
				class="absolute inset-0 w-full h-full object-cover pointer-events-none"
			/>
		<?php else : ?>
			<!-- Placeholder when admin hasn't uploaded video or preview poster yet -->
			<div class="absolute inset-0 w-full h-full bg-[#111] flex flex-col items-center justify-center pointer-events-none">
				<div class="text-white/40 text-center px-4 max-w-md">
					<svg class="w-12 h-12 mx-auto mb-3 opacity-30 text-tedx-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<polygon points="5 3 19 12 5 21 5 3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					<p class="text-base font-medium text-white/60"><?php esc_html_e( 'Video Hero Placeholder', 'tedx-regensburg' ); ?></p>
					<p class="text-xs text-white/40 mt-1"><?php esc_html_e( 'Upload horizontal (desktop) and/or vertical (mobile) video files in the Customizer.', 'tedx-regensburg' ); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<!-- Top Vignette Overlay (Guarantees header and navigation readability) -->
	<div class="absolute inset-x-0 top-0 h-32 md:h-44 bg-gradient-to-b from-black/80 via-black/30 to-transparent pointer-events-none z-10" aria-hidden="true"></div>

	<!-- Bottom Gradient Overlay (Figma node 2629:1755) -->
	<div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a]/90 via-[#0a0a0a]/30 to-transparent pointer-events-none z-10" aria-hidden="true"></div>

	<!-- Bottom Action Button (Figma node 2629:1764) -->
	<?php if ( $show_button && ! empty( $button_text ) ) : ?>
		<div class="relative z-20 tedx-hero-video-btn-wrap flex justify-center items-center w-full px-4" style="padding-bottom: calc(32px + env(safe-area-inset-bottom, 0px));">
			<a
				href="<?php echo esc_url( $button_url ); ?>"
				class="inline-flex items-center justify-center gap-2 border border-tedx-green text-tedx-green hover:text-tedx-green text-sm font-medium px-4 py-2.5 rounded-[12px] transition-all duration-200 btn-shadcn-anim backdrop-blur-sm bg-black/30 hover:bg-tedx-green/10 hover:shadow-glow-green"
			>
				<svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M11.4102 14.4231L10.8204 13.8238L14.2275 10.4167H4.16667V9.58333H14.2275L10.8204 6.17625L11.4102 5.57687L15.8333 10L11.4102 14.4231Z" fill="currentColor"/>
				</svg>
				<span><?php echo esc_html( $button_text ); ?></span>
			</a>
		</div>
	<?php endif; ?>

</section>
