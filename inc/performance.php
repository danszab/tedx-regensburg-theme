<?php
/**
 * Front-end performance: font preload and removal of unused core assets.
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Preload the Latin Inter subset; runs after the logo (1) and hero poster (2) preloads.
 */
function tedx_preload_fonts() {
	printf(
		'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
		esc_url( TEDX_URI . '/assets/fonts/inter-latin-var.woff2' )
	);
}
add_action( 'wp_head', 'tedx_preload_fonts', 3 );

/**
 * The theme uses no emoji polyfill; drop its inline script and styles.
 */
function tedx_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'tedx_disable_emojis' );
