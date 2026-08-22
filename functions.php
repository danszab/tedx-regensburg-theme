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
	set_post_thumbnail_size( 800, 600, true );
	add_image_size( 'tedx-speaker', 400, 400, true );
	add_image_size( 'tedx-hero', 1200, 800, true );

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
	// Google Fonts (Roboto)
	wp_enqueue_style( 'tedx-google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap', array(), null );

	// Main Compiled Tailwind Styles
	if ( file_exists( TEDX_DIR . '/assets/css/style.css' ) ) {
		wp_enqueue_style( 'tedx-tailwind-style', TEDX_URI . '/assets/css/style.css', array(), filemtime( TEDX_DIR . '/assets/css/style.css' ) );
	} else {
		wp_enqueue_style( 'tedx-main-style', get_stylesheet_uri(), array(), TEDX_VERSION );
	}

	// Main JavaScript
	wp_enqueue_script( 'tedx-main-js', TEDX_URI . '/assets/js/main.js', array(), TEDX_VERSION, true );

	wp_localize_script( 'tedx-main-js', 'tedx_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'tedx_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'tedx_enqueue_scripts' );

/**
 * Include Theme Modules
 */
require_once TEDX_DIR . '/inc/template-tags.php';
require_once TEDX_DIR . '/inc/custom-post-types.php';
require_once TEDX_DIR . '/inc/customizer-helpers.php';
require_once TEDX_DIR . '/inc/customizer.php';
require_once TEDX_DIR . '/inc/nav-walker.php';
