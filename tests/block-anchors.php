<?php
/**
 * Test suite for anchor support on all custom TEDx blocks: php tests/block-anchors.php
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
define( 'ABSPATH', TEDX_DIR . '/' );

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return $value; }
function __( $value, $domain = '' ) { return $value; }
function _e( $value, $domain = '' ) { echo esc_html( $value ); }
function esc_attr_e( $value, $domain = '' ) { echo esc_attr( $value ); }
function sanitize_html_class( $value ) { return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $value ); }
function sanitize_title( $value ) { return strtolower( preg_replace( '/[^A-Za-z0-9_-]/', '-', (string) $value ) ); }
function wp_json_encode( $data ) { return json_encode( $data ); }
function get_block_wrapper_attributes( $attrs = array() ) {
	$class = isset( $attrs['class'] ) ? 'class="' . esc_attr( $attrs['class'] ) . '"' : '';
	$id    = isset( $attrs['id'] ) ? ' id="' . esc_attr( $attrs['id'] ) . '"' : '';
	return trim( $class . $id );
}
function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function get_theme_mod( $key, $default = '' ) { return $default; }
function is_admin() { return false; }
function get_the_ID() { return 1; }
function get_the_title( $id = 0 ) { return 'Sample Speaker'; }
function the_title() { echo 'Sample Speaker'; }
function wp_reset_postdata() {}
function wp_parse_args( $args, $defaults = array() ) { return array_merge( $defaults, (array) $args ); }
function attachment_url_to_postid( $url ) { return 0; }
function wp_get_attachment_image( $id, $size = 'thumbnail', $icon = false, $attr = '' ) { return ''; }
function wp_get_attachment_image_url( $id, $size = 'thumbnail' ) { return ''; }
class WP_Query {
	public $posts = array();
	public function __construct( $args = array() ) {}
	public function have_posts() { return false; }
	public function the_post() {}
}
$block = null;

require_once TEDX_DIR . '/inc/template-tags.php';
require_once TEDX_DIR . '/inc/blocks.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

// -------------------------------------------------------------
// Test 1: Every block.json declares "anchor": true in supports
// -------------------------------------------------------------
$block_dirs = tedx_get_block_dirs();
foreach ( $block_dirs as $dir ) {
	$json_path = TEDX_DIR . '/blocks/' . $dir . '/block.json';
	check( file_exists( $json_path ), "block.json exists for {$dir}" );
	$data = json_decode( file_get_contents( $json_path ), true );
	check( isset( $data['supports'] ) && true === ( $data['supports']['anchor'] ?? false ), "{$dir} block.json supports.anchor is true" );
}

// -------------------------------------------------------------
// Test 2: Dynamic filter injects supports.anchor
// -------------------------------------------------------------
foreach ( $block_dirs as $dir ) {
	$block_name = 'tedx/' . str_replace( 'tedx-', '', $dir );
	$filtered_args = tedx_register_block_spacing_attributes( array(), $block_name );
	check( isset( $filtered_args['supports']['anchor'] ) && true === $filtered_args['supports']['anchor'], "Filter injects anchor support for {$block_name}" );
}

// -------------------------------------------------------------
// Test 3: Custom anchor renders in HTML
// -------------------------------------------------------------
// Timetable v2
ob_start();
$attributes = array( 'anchor' => 'my-schedule' );
include TEDX_DIR . '/blocks/tedx-timetable-v2/render.php';
$html_timetable_v2 = ob_get_clean();
check( false !== strpos( $html_timetable_v2, 'id="my-schedule"' ), 'Timetable v2 renders custom anchor id="my-schedule"' );

// Timetable v2 default anchor
ob_start();
$attributes = array();
include TEDX_DIR . '/blocks/tedx-timetable-v2/render.php';
$html_timetable_default = ob_get_clean();
check( false !== strpos( $html_timetable_default, 'id="timetable"' ), 'Timetable v2 defaults to id="timetable"' );

// Speakers default and custom anchor
ob_start();
$attributes = array( 'anchor' => 'custom-speakers', 'yearSource' => 'fixed', 'year' => '2026', 'title' => 'Speakers', 'showYear' => true, 'showEventButton' => false, 'eventButtonText' => '', 'eventButtonUrl' => '', 'showCfs' => true, 'cfsTitle' => 'Call for Speakers', 'cfsDescription' => '', 'cfsUrl' => '', 'cfsButtonText' => 'Apply' );
include TEDX_DIR . '/blocks/tedx-speakers/render.php';
$html_speakers = ob_get_clean();
check( false !== strpos( $html_speakers, 'id="custom-speakers"' ), 'Speakers block renders custom anchor id="custom-speakers"' );

// Gallery custom anchor
ob_start();
$attributes = array( 'anchor' => 'photo-gallery', 'images' => array( array( 'url' => 'test.jpg' ) ) );
include TEDX_DIR . '/blocks/tedx-gallery/render.php';
$html_gallery = ob_get_clean();
check( false !== strpos( $html_gallery, 'id="photo-gallery"' ), 'Gallery renders custom anchor id="photo-gallery"' );

// Bottom navbar custom anchor
ob_start();
$attributes = array( 'anchor' => 'bottom-nav' );
include TEDX_DIR . '/blocks/tedx-bottom-navbar/render.php';
$html_navbar = ob_get_clean();
check( false !== strpos( $html_navbar, 'id="bottom-nav"' ), 'Bottom navbar renders custom anchor id="bottom-nav"' );

// Live ticker custom anchor
ob_start();
$attributes = array( 'anchor' => 'top-ticker' );
include TEDX_DIR . '/blocks/tedx-live-ticker/render.php';
$html_ticker = ob_get_clean();
check( false !== strpos( $html_ticker, 'id="top-ticker"' ), 'Live ticker renders custom anchor id="top-ticker"' );

// Button custom anchor
ob_start();
$attributes = array( 'anchor' => 'cta-button' );
include TEDX_DIR . '/blocks/tedx-button/render.php';
$html_button = ob_get_clean();
check( false !== strpos( $html_button, 'id="cta-button"' ), 'Button block renders custom anchor id="cta-button"' );

// -------------------------------------------------------------
// Test 4: editor-helpers.js includes anchor field
// -------------------------------------------------------------
$helpers_js = file_get_contents( TEDX_DIR . '/blocks/shared/editor-helpers.js' );
check( false !== strpos( $helpers_js, 'HTML Anchor (#section-id)' ), 'editor-helpers.js exposes HTML Anchor input' );

echo PHP_EOL . 'All block anchor tests passed!' . PHP_EOL;
