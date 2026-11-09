<?php
/**
 * Test floorplan map block: php tests/floorplan-block.php.
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
define( 'ABSPATH', TEDX_DIR . '/' );

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return (string) $value; }
function esc_attr_e( $text, $domain = '' ) { echo esc_attr( $text ); }
function esc_html_e( $text, $domain = '' ) { echo esc_html( $text ); }
function __( $value, $domain = '' ) { return $value; }
function sanitize_title( $value ) { return preg_replace( '/[^A-Za-z0-9_-]/', '', strtolower( (string) $value ) ); }
function sanitize_html_class( $value ) { return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $value ); }
function get_block_wrapper_attributes( $attrs = array() ) {
	$class = isset( $attrs['class'] ) ? 'class="' . esc_attr( $attrs['class'] ) . '"' : '';
	$id    = isset( $attrs['id'] ) ? ' id="' . esc_attr( $attrs['id'] ) . '"' : '';
	return trim( $class . $id );
}

function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function get_theme_mod( $key, $default = '' ) { return $default; }
function is_admin() { return false; }
function is_singular( $type = '' ) { return false; }

$attached_files = array();
function get_attached_file( $id ) {
	global $attached_files;
	return $attached_files[ $id ] ?? '';
}

require_once dirname( __DIR__ ) . '/inc/template-tags.php';
require_once dirname( __DIR__ ) . '/inc/blocks.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

// -------------------------------------------------------------
// Test 1: Block Registration in inc/blocks.php
// -------------------------------------------------------------
$dirs = tedx_get_block_dirs();
check( in_array( 'tedx-floorplan', $dirs, true ), 'tedx-floorplan is registered in tedx_get_block_dirs()' );

// -------------------------------------------------------------
// Test 2: block.json Validity
// -------------------------------------------------------------
$block_json_file = TEDX_DIR . '/blocks/tedx-floorplan/block.json';
check( file_exists( $block_json_file ), 'block.json exists' );
$block_meta = json_decode( file_get_contents( $block_json_file ), true );
check( 'tedx/floorplan' === $block_meta['name'], 'Block name is tedx/floorplan' );
check( isset( $block_meta['attributes']['svgId'] ), 'svgId attribute defined' );
check( isset( $block_meta['attributes']['svgUrl'] ), 'svgUrl attribute defined' );
check( isset( $block_meta['attributes']['minZoom'] ), 'minZoom attribute defined' );
check( isset( $block_meta['attributes']['maxZoom'] ), 'maxZoom attribute defined' );
check( isset( $block_meta['attributes']['showControls'] ), 'showControls attribute defined' );
check( isset( $block_meta['attributes']['allowFullscreen'] ), 'allowFullscreen attribute defined' );
check( 'file:./script.js' === $block_meta['viewScript'], 'viewScript is set to script.js' );

// -------------------------------------------------------------
// Test 3: Default Rendering
// -------------------------------------------------------------
$attributes = array();
ob_start();
include TEDX_DIR . '/blocks/tedx-floorplan/render.php';
$html = ob_get_clean();

check( false !== strpos( $html, 'max-w-figma' ), 'Renders inside max-w-figma container' );
check( false !== strpos( $html, 'px-4 md:px-8 lg:px-12' ), 'Renders standard block padding px-4 md:px-8 lg:px-12 on outer section' );
check( false === strpos( $html, 'lg:px-0' ), 'Does not strip desktop padding with lg:px-0' );
check( false !== strpos( $html, 'aspect-square' ), 'Renders with aspect-square (1:1 ratio)' );
check( false !== strpos( $html, 'max-h-[85vh]' ), 'Constrained by max-h-[85vh]' );
check( false !== strpos( $html, 'tedx-floorplan-container' ), 'Renders tedx-floorplan-container' );
check( false !== strpos( $html, 'tedx-floorplan-viewport' ), 'Renders tedx-floorplan-viewport' );
check( false !== strpos( $html, 'tedx-floorplan-stage' ), 'Renders tedx-floorplan-stage' );
check( false !== strpos( $html, 'Event Floorplan &amp; Stages' ) || false !== strpos( $html, 'Event Floorplan & Stages' ), 'Renders default heading' );
check( false !== strpos( $html, 'VENUE MAP' ), 'Renders default eyebrow' );
check( false !== strpos( $html, 'tedx-floorplan-zoom-in' ), 'Renders Zoom In button' );
check( false !== strpos( $html, 'tedx-floorplan-zoom-out' ), 'Renders Zoom Out button' );
check( false !== strpos( $html, 'tedx-floorplan-reset' ), 'Renders Reset button' );
check( false !== strpos( $html, 'tedx-floorplan-fullscreen' ), 'Renders Fullscreen button' );
check( false !== strpos( $html, '<svg' ), 'Renders SVG element' );
check( false !== strpos( $html, 'MAIN STAGE' ), 'Default SVG illustration contains MAIN STAGE' );

// -------------------------------------------------------------
// Test 4: Custom Heading & Controls Toggle
// -------------------------------------------------------------
$attributes = array(
	'heading'         => 'Hall 3 Seating Layout',
	'eyebrow'         => 'STAGE BLUE',
	'showControls'    => false,
	'allowFullscreen' => false,
);
ob_start();
include TEDX_DIR . '/blocks/tedx-floorplan/render.php';
$html_custom = ob_get_clean();

check( false !== strpos( $html_custom, 'Hall 3 Seating Layout' ), 'Custom heading is rendered' );
check( false !== strpos( $html_custom, 'STAGE BLUE' ), 'Custom eyebrow is rendered' );
check( false === strpos( $html_custom, 'tedx-floorplan-controls' ), 'Controls bar is omitted when showControls is false' );

// -------------------------------------------------------------
// Test 5: Safe SVG Inlining with Sanitization
// -------------------------------------------------------------
$test_svg_file = __DIR__ . '/test-floorplan.svg';
file_put_contents(
	$test_svg_file,
	'<svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><rect x="10" y="10" width="100" height="100" fill="#f00" onclick="alert(2)" /><text x="50" y="50">CUSTOM SEATING PLAN</text></svg>'
);

$attached_files[999] = $test_svg_file;

$attributes = array(
	'svgId' => 999,
);
ob_start();
include TEDX_DIR . '/blocks/tedx-floorplan/render.php';
$html_svg = ob_get_clean();

check( false !== strpos( $html_svg, 'CUSTOM SEATING PLAN' ), 'Custom SVG content inlined' );
check( false !== strpos( $html_svg, 'preserveAspectRatio="xMidYMid meet"' ), 'preserveAspectRatio injected into SVG' );
check( false === strpos( $html_svg, '<script>' ), 'Dangerous script tags sanitized' );
check( false === strpos( $html_svg, 'onclick=' ), 'Dangerous inline event handlers sanitized' );

// Clean up temporary test file
if ( file_exists( $test_svg_file ) ) {
	unlink( $test_svg_file );
}

echo 'All floorplan block tests passed!' . PHP_EOL;
