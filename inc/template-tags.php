<?php
/**
 * Custom Template Tags and Helper Functions for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Render standard TEDx Regensburg SVG Logo
 *
 * @param string $classes Optional Tailwind CSS classes.
 * @param bool   $show_link Whether to wrap in home link.
 */
function tedx_regensburg_logo( $classes = 'h-8 w-auto', $show_link = true ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$mobile_logo_id = get_theme_mod( 'tedx_mobile_logo' );
	
	if ( $custom_logo_id ) {
		$desktop_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
		$mobile_url  = $mobile_logo_id ? wp_get_attachment_image_url( $mobile_logo_id, 'full' ) : '';

		if ( $desktop_url ) {
			if ( $show_link ) {
				echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="flex items-center" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			}
			
			if ( $mobile_url ) {
				echo '<img src="' . esc_url( $desktop_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="hidden md:block ' . esc_attr( $classes ) . '">';
				echo '<img src="' . esc_url( $mobile_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . ' (Mobile)" class="block md:hidden ' . esc_attr( $classes ) . '">';
			} else {
				echo '<img src="' . esc_url( $desktop_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="' . esc_attr( $classes ) . '">';
			}

			if ( $show_link ) {
				echo '</a>';
			}
			return;
		}
	}

	// Fallback to crisp TEDxRegensburg vector markup matching Figma
	$output = '<div class="flex items-center gap-1 text-white font-bold select-none tracking-tight leading-none ' . esc_attr( $classes ) . '">';
	$output .= '<span class="text-white text-2xl md:text-3xl font-extrabold tracking-tighter">TED<sup class="text-tedx-red text-lg md:text-xl font-bold ml-[1px]">x</sup></span>';
	$output .= '<span class="text-white text-xl md:text-2xl font-medium tracking-normal ml-2">Regensburg</span>';
	$output .= '</div>';

	if ( $show_link ) {
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="inline-flex items-center transition-opacity hover:opacity-90" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
		echo $output;
		echo '</a>';
	} else {
		echo $output;
	}
}

/**
 * Output SVG Icons
 *
 * @param string $name Name of icon.
 * @param string $classes Classes to apply to SVG.
 * @return string SVG HTML markup.
 */
function tedx_get_icon( $name, $classes = 'w-5 h-5 inline-block' ) {
	$icons = array(
		'ticket' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 4C2.89543 4 2 4.89543 2 6V9.5C3.38071 9.5 4.5 10.6193 4.5 12C4.5 13.3807 3.38071 14.5 2 14.5V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V14.5C20.6193 14.5 19.5 13.3807 19.5 12C19.5 10.6193 20.6193 9.5 22 9.5V6C22 4.89543 21.1046 4 20 4H4ZM9 7H15V9H9V7ZM9 11H15V13H9V11ZM9 15H15V17H9V15Z"/></svg>',
		'globe' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
		'info' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
		'groups' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>',
		'linkedin' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 8.76a1.44 1.44 0 1 0 0-2.88 1.44 1.44 0 0 0 0 2.88M7.86 18.5V10.13H5.07V18.5h2.79z"/></svg>',
		'instagram' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
		'facebook' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		'mail' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
		'send' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>',
		'arrow-right' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
		'menu' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
		'close' => '<svg class="' . esc_attr( $classes ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>'
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Output Language Switcher Component
 */
function tedx_language_switcher() {
	$current_lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : 'en';
	?>
	<div class="flex items-center gap-2 text-sm font-medium text-white/90">
		<span class="text-white/60"><?php echo tedx_get_icon( 'globe', 'w-4 h-4' ); ?></span>
		<div class="flex items-center gap-1 font-sans">
			<a href="?lang=de" class="<?php echo $current_lang === 'de' ? 'underline font-bold text-white' : 'text-white/70 hover:text-white transition-colors'; ?>">DE</a>
			<span class="text-white/40">|</span>
			<a href="?lang=en" class="<?php echo $current_lang === 'en' ? 'font-bold text-white' : 'text-white/70 hover:text-white transition-colors'; ?>">EN</a>
		</div>
	</div>
	<?php
}

/**
 * Helper to fetch Customizer theme modifications with safe fallbacks
 */
function tedx_mod( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}
