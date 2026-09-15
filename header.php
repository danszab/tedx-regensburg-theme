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
if ( empty( trim( $ticket_text ) ) ) { $ticket_text = __( 'Tickets', 'tedx-regensburg' ); }

$show_navbar_ticket = tedx_mod( 'tedx_show_navbar_ticket', true );
if ( is_page() || is_singular( 'tedx_event' ) ) {
	$meta_enable_tickets = get_post_meta( get_the_ID(), '_event_enable_tickets', true );
	if ( '0' === $meta_enable_tickets ) {
		$show_navbar_ticket = false;
	}
}
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
			<div class="flex-shrink-0 flex items-center">
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
						<li class="relative group">
							<a href="#" class="inline-flex items-center py-2 text-base font-medium text-white/90 hover:text-white transition-all duration-200 nav-link nav-link-anim">
								<?php esc_html_e( 'Past Talks', 'tedx-regensburg' ); ?>
								<svg class="w-4 h-4 ml-1.5 opacity-70 nav-dropdown-arrow flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
							</a>
							<div class="absolute left-0 top-full pt-2 z-50 nav-dropdown-wrapper">
								<ul class="sub-menu w-48 bg-tedx-dark border border-white/10 rounded-xl shadow-xl py-2 list-none m-0 p-0">
									<li><a href="<?php echo esc_url( home_url( '/2025' ) ); ?>" class="inline-flex w-full px-4 py-2 text-sm text-gray-300 hover:text-white transition-all duration-200 nav-link-anim"><?php esc_html_e( '2025 Event', 'tedx-regensburg' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/2024' ) ); ?>" class="inline-flex w-full px-4 py-2 text-sm text-gray-300 hover:text-white transition-all duration-200 nav-link-anim"><?php esc_html_e( '2024 Event', 'tedx-regensburg' ); ?></a></li>
								</ul>
							</div>
						</li>
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
			<?php if ( $show_navbar_ticket ) : ?>
			<a href="<?php echo esc_url( $ticket_url ); ?>" class="inline-flex items-center justify-center gap-2 bg-tedx-red text-white text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all duration-200 btn-shadcn-anim leading-none">
				<span class="flex items-center justify-center flex-shrink-0"><?php echo tedx_get_icon( 'ticket', 'w-3.5 h-3.5 text-white block' ); ?></span>
				<span class="leading-none"><?php echo esc_html( $ticket_text ); ?></span>
			</a>
			<?php endif; ?>
		</div>

		<!-- Mobile Menu Button -->
		<div class="flex items-center gap-3 lg:hidden">
			<?php if ( $show_navbar_ticket ) : ?>
			<a href="<?php echo esc_url( $ticket_url ); ?>" class="sm:hidden inline-flex items-center justify-center gap-1.5 bg-tedx-red text-white text-xs font-medium px-3 py-1.5 rounded-lg leading-none">
				<span class="flex items-center justify-center flex-shrink-0"><?php echo tedx_get_icon( 'ticket', 'w-3.5 h-3.5 block' ); ?></span>
				<span class="leading-none"><?php echo esc_html( $ticket_text ); ?></span>
			</a>
			<?php endif; ?>
			<button id="mobile-menu-toggle" type="button" class="p-2 text-white/90 hover:text-white rounded-lg focus:outline-none flex items-center justify-center" aria-label="<?php esc_attr_e( 'Toggle navigation', 'tedx-regensburg' ); ?>">
				<span class="open-icon flex items-center justify-center"><?php echo tedx_get_icon( 'menu', 'w-6 h-6 block' ); ?></span>
				<span class="close-icon hidden flex items-center justify-center"><?php echo tedx_get_icon( 'close', 'w-6 h-6 block' ); ?></span>
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
					<div class="mobile-nav-item">
						<a href="#" class="text-xl font-medium text-white/90 hover:text-white py-3 px-4 rounded-xl inline-flex items-center transition-all nav-link nav-link-anim mobile-nav-parent w-full">
							<?php esc_html_e( 'Past Talks', 'tedx-regensburg' ); ?>
							<svg class="mobile-dropdown-arrow w-5 h-5 ml-1.5 opacity-70 transition-transform duration-300 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
						</a>
						<ul class="sub-menu hidden flex-col gap-3 pl-4 pt-3 mt-1 border-l-2 border-white/10 list-none mb-2">
							<li><a href="<?php echo esc_url( home_url( '/2025' ) ); ?>" class="text-lg font-medium text-white/60 hover:text-white inline-flex items-center transition-all nav-link nav-link-anim px-4 py-1"><?php esc_html_e( '2025 Event', 'tedx-regensburg' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/2024' ) ); ?>" class="text-lg font-medium text-white/60 hover:text-white inline-flex items-center transition-all nav-link nav-link-anim px-4 py-1"><?php esc_html_e( '2024 Event', 'tedx-regensburg' ); ?></a></li>
						</ul>
					</div>
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
