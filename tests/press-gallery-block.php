<?php
/**
 * Test suite for the "Press Gallery" Gutenberg block: php tests/press-gallery-block.php
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
define( 'ABSPATH', TEDX_DIR . '/' );
define( 'REST_REQUEST', true );

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return $value; }
function __( $value, $domain = '' ) { return $value; }
function sanitize_html_class( $value ) { return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $value ); }
function wp_parse_args( $args, $defaults = array() ) { return array_merge( (array) $defaults, (array) $args ); }
function wpautop( $pee, $br = true ) { return '<p>' . nl2br( $pee ) . '</p>'; }
function wp_kses_post( $string ) { return $string; }
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
function wp_get_attachment_url( $id ) { return 'https://example.test/uploads/high-res-' . $id . '.jpg'; }
function wp_get_attachment_image( $id, $size = 'thumbnail', $icon = false, $attr = '' ) {
	return '<img src="https://example.test/uploads/thumb-' . $id . '.jpg" alt="" class="w-full h-full object-cover" />';
}

require_once TEDX_DIR . '/inc/template-tags.php';
require_once TEDX_DIR . '/inc/blocks.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_press_gallery( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-press-gallery/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Empty items notice
$html = render_press_gallery();
check( false !== strpos( $html, 'Add images in the block settings' ), 'Empty gallery displays placeholder in editor/empty state' );

// 2. Rendering with press items
$sample_desc = "TEDxRegensburg 2026 Stage\nPhoto by Jane Doe.\nFree for editorial use.";
$html = render_press_gallery( array(
	'title'        => 'Official Press Photos',
	'eyebrow'      => 'High-Res Media',
	'downloadText' => 'Download high-resolution image',
	'items'        => array(
		array(
			'image'       => array( 'id' => 42, 'url' => 'https://example.test/img42.jpg', 'alt' => 'Stage Photo' ),
			'description' => $sample_desc,
		),
	),
) );

check( false !== strpos( $html, 'Official Press Photos' ), 'Renders section heading' );
check( false !== strpos( $html, 'class="tedx-block-heading' ), 'Heading uses .tedx-block-heading styling' );
check( false !== strpos( $html, 'High-Res Media' ), 'Eyebrow text rendered' );
check( false !== strpos( $html, 'max-w-figma' ), 'Renders inside max-w-figma container' );
check( false !== strpos( $html, 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' ), 'Grid layout uses responsive 3 columns' );
check( false !== strpos( $html, 'tedx-press-gallery-image' ), 'Image preview wrapper rendered' );
check( false !== strpos( $html, 'Photo by Jane Doe.' ), 'Multi-line description rendered' );
check( false !== strpos( $html, 'Download high-resolution image' ), 'Plain text download button rendered' );
check( false !== strpos( $html, 'https://example.test/uploads/high-res-42.jpg' ), 'Download link targets original high-resolution URL' );
check( false !== strpos( $html, 'download' ), 'Download link includes HTML5 download attribute' );

// 3. Block registration check
check( in_array( 'tedx-press-gallery', tedx_get_block_dirs(), true ), 'tedx-press-gallery is registered in tedx_get_block_dirs()' );
