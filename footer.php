<?php
/**
 * The Footer for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$linkedin_url  = tedx_mod( 'tedx_social_linkedin', 'https://linkedin.com/company/tedxregensburg' );
$instagram_url = tedx_mod( 'tedx_social_instagram', 'https://instagram.com/tedxregensburg' );
$facebook_url  = tedx_mod( 'tedx_social_facebook', 'https://facebook.com/tedxregensburg' );
$newsletter_action = tedx_mod( 'tedx_newsletter_action', '#' );
?>

<footer id="colophon" class="site-footer bg-black border-t border-white/5 py-16 px-4 md:px-8 lg:px-12 mt-auto">
	<div class="max-w-figma mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-start">
		
		<!-- Left Column: Branding, Legal & License -->
		<div class="flex flex-col gap-4">
			<div class="mb-2">
				<?php tedx_regensburg_logo( 'h-7 w-auto' ); ?>
			</div>

			<!-- Legal Navigation Links -->
			<div class="flex flex-col gap-1.5 text-base font-medium text-white/90">
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'flex flex-col gap-1 list-none p-0 m-0',
						'fallback_cb'    => false,
					) );
				} else {
					?>
					<a href="<?php echo esc_url( home_url( '/imprint/' ) ); ?>" class="hover:text-tedx-red transition-colors"><?php esc_html_e( 'Imprint', 'tedx-regensburg' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>" class="hover:text-tedx-red transition-colors"><?php esc_html_e( 'Privacy', 'tedx-regensburg' ); ?></a>
					<?php
				}
				?>
			</div>

			<!-- TEDx License Notice -->
			<p class="text-sm text-tedx-light/70 font-normal leading-relaxed mt-2 max-w-sm">
				<?php esc_html_e( 'This independent TEDx event is operated under a license from TED.', 'tedx-regensburg' ); ?>
			</p>

			<p class="text-xs text-white/40 mt-1">
				&copy; <?php echo date( 'Y' ); ?> TEDxRegensburg. <?php esc_html_e( 'All rights reserved.', 'tedx-regensburg' ); ?>
			</p>
		</div>

		<!-- Right Column: Socials & Newsletter -->
		<div class="flex flex-col gap-6">
			
			<!-- Stay in Touch -->
			<div>
				<h3 class="text-2xl font-medium text-white tracking-tight mb-4"><?php esc_html_e( 'Let’s stay in touch', 'tedx-regensburg' ); ?></h3>
				<div class="flex items-center gap-4">
					<?php if ( $linkedin_url ) : ?>
						<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-14 h-14 bg-tedx-button hover:bg-tedx-red text-white rounded-2xl transition-all duration-200 hover:scale-105" aria-label="LinkedIn">
							<?php echo tedx_get_icon( 'linkedin', 'w-6 h-6' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $instagram_url ) : ?>
						<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-14 h-14 bg-tedx-button hover:bg-tedx-red text-white rounded-2xl transition-all duration-200 hover:scale-105" aria-label="Instagram">
							<?php echo tedx_get_icon( 'instagram', 'w-6 h-6' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $facebook_url ) : ?>
						<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-14 h-14 bg-tedx-button hover:bg-tedx-red text-white rounded-2xl transition-all duration-200 hover:scale-105" aria-label="Facebook">
							<?php echo tedx_get_icon( 'facebook', 'w-6 h-6' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<!-- Newsletter Subscription -->
			<div class="mt-2">
				<h4 class="text-xl font-medium text-white tracking-tight mb-3"><?php esc_html_e( 'Newsletter', 'tedx-regensburg' ); ?></h4>
				<form id="tedx-newsletter-form" action="<?php echo esc_url( $newsletter_action ); ?>" method="POST" class="flex items-center gap-3 max-w-md">
					<div class="relative flex-1">
						<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/50">
							<?php echo tedx_get_icon( 'mail', 'w-5 h-5' ); ?>
						</div>
						<input type="email" name="email" required placeholder="<?php esc_attr_e( 'Email Address', 'tedx-regensburg' ); ?>" class="w-full pl-11 pr-4 py-3 bg-transparent border border-tedx-outline rounded-lg text-white placeholder:text-white/40 focus:outline-none focus:border-tedx-red focus:ring-1 focus:ring-tedx-red transition-all">
					</div>
					<button type="submit" class="flex items-center justify-center w-12 h-12 bg-tedx-button hover:bg-tedx-red text-white rounded-2xl transition-all duration-200 hover:scale-105 shrink-0" aria-label="<?php esc_attr_e( 'Subscribe', 'tedx-regensburg' ); ?>">
						<?php echo tedx_get_icon( 'send', 'w-5 h-5' ); ?>
					</button>
				</form>
				<p id="tedx-newsletter-feedback" class="text-xs text-tedx-green mt-2 hidden"></p>
			</div>

		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
