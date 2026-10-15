<?php
/**
 * TEDx Regensburg Theme Functions and Definitions
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'TEDX_VERSION', '1.0.0' );
define( 'TEDX_DIR', get_template_directory() );
define( 'TEDX_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function tedx_theme_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'tedx-regensburg', TEDX_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'tedx-speaker', 400, 400, true );
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
	// Uncropped, so it joins the srcset of 'medium' gallery thumbnails (180–231px CSS at 1.5–2x DPR).
	add_image_size( 'tedx-gallery-thumb', 480, 0, false );

	// Register Navigation Menus
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Navigation', 'tedx-regensburg' ),
		'footer'  => esc_html__( 'Footer Legal Menu', 'tedx-regensburg' ),
	) );

	// Switch default core markup for search form, comment form, etc. to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Set up the WordPress core custom logo feature.
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 300,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'tedx_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function tedx_enqueue_scripts() {
	// Main Compiled Tailwind Styles (includes the self-hosted Inter @font-face rules).
	$compiled_style_path = TEDX_DIR . '/assets/css/style.css';
	if ( file_exists( $compiled_style_path ) ) {
		wp_enqueue_style( 'tedx-tailwind-style', TEDX_URI . '/assets/css/style.css', array(), filemtime( $compiled_style_path ) );
	}

	// The parent root style.css only holds theme metadata; a child theme's stylesheet may contain styles.
	if ( is_child_theme() ) {
		$theme_stylesheet_path = get_stylesheet_directory() . '/style.css';
		wp_enqueue_style( 'tedx-main-style', get_stylesheet_uri(), array( 'tedx-tailwind-style' ), file_exists( $theme_stylesheet_path ) ? filemtime( $theme_stylesheet_path ) : TEDX_VERSION );
	}

	// Main JS
	wp_enqueue_script( 'tedx-embed-consent', TEDX_URI . '/assets/js/embed-consent.js', array(), filemtime( TEDX_DIR . '/assets/js/embed-consent.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$main_script_path = TEDX_DIR . '/assets/js/main.js';
	if ( file_exists( $main_script_path ) ) {
		wp_enqueue_script( 'tedx-main-js', TEDX_URI . '/assets/js/main.js', array(), filemtime( $main_script_path ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}

	if ( wp_script_is( 'tedx-main-js', 'registered' ) || wp_script_is( 'tedx-main-js', 'enqueued' ) ) {
		wp_localize_script( 'tedx-main-js', 'tedx_vars', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'tedx_nonce' ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'tedx_enqueue_scripts' );

/**
 * Include Theme Modules
 */
require_once TEDX_DIR . '/inc/template-tags.php';
require_once TEDX_DIR . '/inc/hero-media.php';
require_once TEDX_DIR . '/inc/performance.php';
require_once TEDX_DIR . '/inc/embed-consent.php';
require_once TEDX_DIR . '/inc/custom-post-types.php';
require_once TEDX_DIR . '/inc/customizer-helpers.php';
require_once TEDX_DIR . '/inc/customizer.php';
require_once TEDX_DIR . '/inc/customizer-additions.php';
require_once TEDX_DIR . '/inc/nav-walker.php';
require_once TEDX_DIR . '/inc/blocks.php';
require_once TEDX_DIR . '/inc/press-release-pdf.php';
require_once TEDX_DIR . '/inc/block-patterns.php';

/**
 * Enable SVG and Video Uploads (WebM, MP4)
 */
function tedx_mime_types( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['webm'] = 'video/webm';
	$mimes['mp4']  = 'video/mp4';
	return $mimes;
}
add_filter( 'upload_mimes', 'tedx_mime_types' );


function tedx_check_filetype_and_ext( $data, $file, $filename, $mimes ) {
	$ext = pathinfo( $filename, PATHINFO_EXTENSION );
	if ( strtolower( $ext ) === 'svg' ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	} elseif ( strtolower( $ext ) === 'webm' ) {
		$data['ext']  = 'webm';
		$data['type'] = 'video/webm';
	} elseif ( strtolower( $ext ) === 'mp4' ) {
		$data['ext']  = 'mp4';
		$data['type'] = 'video/mp4';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'tedx_check_filetype_and_ext', 10, 4 );
