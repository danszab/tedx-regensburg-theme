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
$ticket_disabled = tedx_mod( 'tedx_navbar_tickets_disabled', false );
if ( empty( trim( $ticket_text ) ) ) { $ticket_text = __( 'Tickets', 'tedx-regensburg' ); }
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
		
		<!-- Left Group: Logo & Navigation -->
		<div class="flex items-center gap-6">
			<!-- Logo -->
			<div class="flex-shrink-0">
				<?php tedx_regensburg_logo( 'h-6 w-auto' ); ?>
			</div>

			<!-- Desktop Navigation Menu -->
			<nav class="hidden lg:flex items-center" aria-label="<?php esc_attr_e( 'Primary Menu', 'tedx-regensburg' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'flex items-center gap-6 list-none m-0 p-0',
						'walker'         => new TEDx_Tailwind_Nav_Walker(),
						'fallback_cb'    => false,
					) );
				} else {
					// Figma Default Menu Fallback
					?>
					<ul class="flex items-center gap-6 list-none m-0 p-0">
						<li><a href="<?php echo esc_url( home_url( '/#hero' ) ); ?>" class="py-2 rounded-xl text-base font-medium text-white/90 hover:text-white inline-block transition-all nav-link nav-link-anim active-nav-link"><?php esc_html_e( 'Home', 'tedx-regensburg' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#event-pitch' ) ); ?>" class="py-2 rounded-xl text-base font-medium text-white/90 hover:text-white inline-block transition-all nav-link nav-link-anim"><?php esc_html_e( '2026 Event', 'tedx-regensburg' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>" class="py-2 rounded-xl text-base font-medium text-white/90 hover:text-white inline-block transition-all nav-link nav-link-anim"><?php esc_html_e( 'About Us', 'tedx-regensburg' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#speakers' ) ); ?>" class="py-2 rounded-xl text-base font-medium text-white/90 hover:text-white inline-block transition-all nav-link nav-link-anim"><?php esc_html_e( 'Speaker Information', 'tedx-regensburg' ); ?></a></li>
					</ul>
					<?php
				}
				?>
			</nav>
		</div>

		<!-- Right Side: Language & Tickets CTA -->
		<div class="hidden sm:flex items-center gap-6">
			<!-- Language Selector -->
			<div class="hidden md:block">
				<?php tedx_language_switcher(); ?>
			</div>

			<!-- Tickets CTA Button -->
			<?php
			if ( $ticket_disabled ) {
				$ticket_classes = 'inline-flex items-center gap-2 bg-gray-600/50 text-white/50 text-sm font-medium px-4 py-2 rounded-xl shadow-sm cursor-not-allowed pointer-events-none';
				$ticket_href = '#';
				$icon_class = 'w-3.5 h-3.5 text-white/50';
			} else {
				$ticket_classes = 'inline-flex items-center gap-2 bg-tedx-red text-white text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all duration-200 btn-shadcn-anim';
				$ticket_href = esc_url( $ticket_url );
				$icon_class = 'w-3.5 h-3.5 text-white';
			}
			?>
			<a href="<?php echo $ticket_href; ?>" class="<?php echo esc_attr( $ticket_classes ); ?>">
				<?php echo tedx_get_icon( 'ticket', $icon_class ); ?>
				<span><?php echo esc_html( $ticket_text ); ?></span>
			</a>
		</div>

		<!-- Mobile Menu Button -->
		<div class="flex items-center gap-3 lg:hidden">
			<?php
			if ( $ticket_disabled ) {
				$mobile_ticket_classes = 'sm:hidden inline-flex items-center gap-1.5 bg-gray-600/50 text-white/50 text-xs font-medium px-3 py-1.5 rounded-lg cursor-not-allowed pointer-events-none';
			} else {
				$mobile_ticket_classes = 'sm:hidden inline-flex items-center gap-1.5 bg-tedx-red text-white text-xs font-medium px-3 py-1.5 rounded-lg';
			}
			?>
			<a href="<?php echo $ticket_href; ?>" class="<?php echo esc_attr( $mobile_ticket_classes ); ?>">
				<?php echo tedx_get_icon( 'ticket', $icon_class ); ?>
				<span><?php echo esc_html( $ticket_text ); ?></span>
			</a>
			<button id="mobile-menu-toggle" type="button" class="p-2 text-white/90 hover:text-white rounded-lg focus:outline-none flex items-center justify-center" aria-label="<?php esc_attr_e( 'Toggle navigation', 'tedx-regensburg' ); ?>">
				<span class="open-icon flex items-center"><?php echo tedx_get_icon( 'menu', 'w-6 h-6' ); ?></span>
				<span class="close-icon hidden flex items-center"><?php echo tedx_get_icon( 'close', 'w-6 h-6' ); ?></span>
			</button>
		</div>

	</div>

	<!-- Mobile Drawer Menu -->
	<div id="mobile-menu" class="hidden lg:hidden bg-[#181818]/95 backdrop-blur-md border-b border-white/10 px-6 py-8 transition-all duration-300 mobile-menu-scrollable">
		<nav class="flex flex-col gap-5">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-4 list-none p-0 m-0 mobile-nav-menu',
					'walker'         => new TEDx_Tailwind_Mobile_Nav_Walker(),
					'fallback_cb'    => false,
				) );
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/#hero' ) ); ?>" class="text-xl font-medium text-white/90 hover:text-white py-3 px-4 rounded-xl inline-block transition-all nav-link nav-link-anim active-nav-link"><?php esc_html_e( 'Home', 'tedx-regensburg' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#event-pitch' ) ); ?>" class="text-xl font-medium text-white/90 hover:text-white py-3 px-4 rounded-xl inline-block transition-all nav-link nav-link-anim"><?php esc_html_e( '2026 Event', 'tedx-regensburg' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#about' ) ); ?>" class="text-xl font-medium text-white/90 hover:text-white py-3 px-4 rounded-xl inline-block transition-all nav-link nav-link-anim"><?php esc_html_e( 'About Us', 'tedx-regensburg' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#speakers' ) ); ?>" class="text-xl font-medium text-white/90 hover:text-white py-3 px-4 rounded-xl inline-block transition-all nav-link nav-link-anim"><?php esc_html_e( 'Speaker Information', 'tedx-regensburg' ); ?></a>
				<?php
			}
			?>
			<div class="pt-6 border-t border-white/10 flex items-center justify-between">
				<?php tedx_language_switcher(); ?>
			</div>
		</nav>
	</div>
</header>
<!-- <div class="h-[60px]"></div> Header Spacer -->
