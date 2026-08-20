<?php
/**
 * The Header for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$ticket_url  = tedx_mod( 'tedx_ticket_url', '#tickets' );
$ticket_text = tedx_mod( 'tedx_ticket_text', __( 'Tickets', 'tedx-regensburg' ) );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-tedx-dark text-white antialiased selection:bg-tedx-red selection:text-white min-h-screen flex flex-col' ); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-tedx-red focus:text-white focus:rounded-lg" href="#primary">
	<?php esc_html_e( 'Skip to content', 'tedx-regensburg' ); ?>
</a>

<!-- Sticky Navigation Header -->
<header id="site-header" class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300 backdrop-blur-[6.75px] bg-[#151515]/40 border-b border-white/5">
	<div class="max-w-figma-wide mx-auto flex items-center justify-between h-[60px] px-4 md:px-8 lg:px-12">
		
		<!-- Logo -->
		<div class="flex items-center shrink-0">
			<?php tedx_regensburg_logo( 'h-7 md:h-8 w-auto' ); ?>
		</div>

		<!-- Desktop Navigation Menu -->
		<nav class="hidden lg:flex items-center gap-2" aria-label="<?php esc_attr_e( 'Primary Menu', 'tedx-regensburg' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex items-center gap-1 list-none m-0 p-0',
					'walker'         => new TEDx_Tailwind_Nav_Walker(),
					'fallback_cb'    => false,
				) );
			} else {
				// Figma Default Menu Fallback
				?>
				<ul class="flex items-center gap-1 list-none m-0 p-0">
					<li><a href="#hero" class="px-4 py-2 rounded-xl text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-all"><?php esc_html_e( '2026 Event', 'tedx-regensburg' ); ?></a></li>
					<li><a href="#event-pitch" class="px-4 py-2 rounded-xl text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-all"><?php esc_html_e( 'Past Talks', 'tedx-regensburg' ); ?></a></li>
					<li><a href="#about" class="px-4 py-2 rounded-xl text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-all"><?php esc_html_e( 'About Us', 'tedx-regensburg' ); ?></a></li>
					<li><a href="#speakers" class="px-4 py-2 rounded-xl text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-all"><?php esc_html_e( 'Speaker Information', 'tedx-regensburg' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>

		<!-- Right Side: Language & Tickets CTA -->
		<div class="hidden sm:flex items-center gap-6">
			<!-- Language Selector -->
			<div class="hidden md:block">
				<?php tedx_language_switcher(); ?>
			</div>

			<!-- Tickets CTA Button -->
			<a href="<?php echo esc_url( $ticket_url ); ?>" class="inline-flex items-center gap-2 bg-tedx-red hover:bg-tedx-red-hover text-white text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all duration-200 hover:scale-[1.02] active:scale-95">
				<?php echo tedx_get_icon( 'ticket', 'w-4 h-4 text-white' ); ?>
				<span><?php echo esc_html( $ticket_text ); ?></span>
			</a>
		</div>

		<!-- Mobile Menu Button -->
		<div class="flex items-center gap-3 lg:hidden">
			<a href="<?php echo esc_url( $ticket_url ); ?>" class="sm:hidden inline-flex items-center gap-1.5 bg-tedx-red text-white text-xs font-medium px-3 py-1.5 rounded-lg">
				<?php echo tedx_get_icon( 'ticket', 'w-3.5 h-3.5' ); ?>
				<span><?php echo esc_html( $ticket_text ); ?></span>
			</a>
			<button id="mobile-menu-toggle" type="button" class="p-2 text-white/90 hover:text-white rounded-lg focus:outline-none" aria-label="<?php esc_attr_e( 'Toggle navigation', 'tedx-regensburg' ); ?>">
				<span class="open-icon"><?php echo tedx_get_icon( 'menu', 'w-6 h-6' ); ?></span>
				<span class="close-icon hidden"><?php echo tedx_get_icon( 'close', 'w-6 h-6' ); ?></span>
			</button>
		</div>

	</div>

	<!-- Mobile Drawer Menu -->
	<div id="mobile-menu" class="hidden lg:hidden bg-[#181818]/95 backdrop-blur-md border-b border-white/10 px-6 py-6 transition-all duration-300">
		<nav class="flex flex-col gap-3">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-2 list-none p-0 m-0',
					'fallback_cb'    => false,
				) );
			} else {
				?>
				<a href="#hero" class="text-lg font-medium text-white/90 hover:text-white py-1"><?php esc_html_e( '2026 Event', 'tedx-regensburg' ); ?></a>
				<a href="#event-pitch" class="text-lg font-medium text-white/90 hover:text-white py-1"><?php esc_html_e( 'Past Talks', 'tedx-regensburg' ); ?></a>
				<a href="#about" class="text-lg font-medium text-white/90 hover:text-white py-1"><?php esc_html_e( 'About Us', 'tedx-regensburg' ); ?></a>
				<a href="#speakers" class="text-lg font-medium text-white/90 hover:text-white py-1"><?php esc_html_e( 'Speaker Information', 'tedx-regensburg' ); ?></a>
				<?php
			}
			?>
			<div class="pt-4 border-t border-white/10 flex items-center justify-between">
				<?php tedx_language_switcher(); ?>
			</div>
		</nav>
	</div>
</header>
<div class="h-[60px]"></div><!-- Header Spacer -->
