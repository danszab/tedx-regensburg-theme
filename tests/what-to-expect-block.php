<?php
/**
 * Test suite for the "What to expect" Gutenberg block: php tests/what-to-expect-block.php
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
define( 'ABSPATH', TEDX_DIR . '/' );

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return $value; }
function __( $value, $domain = '' ) { return $value; }
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
function get_post_mime_type( $id ) { return 99 === $id ? 'image/svg+xml' : 'image/png'; }
function wp_get_attachment_url( $id ) { return 'https://example.test/uploads/icon-' . $id . '.svg'; }
function wp_get_attachment_image_url( $id, $size = 'thumbnail' ) { return 'https://example.test/uploads/icon-' . $id . '.png'; }
if ( ! function_exists( 'tedx_block_editor_notice' ) ) {
	function tedx_block_editor_notice( $message ) { return '<p class="notice">' . esc_html( $message ) . '</p>'; }
}

require_once TEDX_DIR . '/inc/template-tags.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_what_to_expect( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-what-to-expect/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Default heading and design principles
$html = render_what_to_expect();
check( false !== strpos( $html, 'What to expect' ), 'Renders default title "What to expect"' );
check( false !== strpos( $html, 'class="tedx-block-heading' ), 'Title uses standard .tedx-block-heading styling' );
check( false !== strpos( $html, 'max-w-figma' ), 'Renders inside max-w-figma container' );

// 2. Custom heading and eyebrow
$html = render_what_to_expect( array(
	'title'   => 'Your Experience',
	'eyebrow' => 'Highlights',
) );
check( false !== strpos( $html, 'Your Experience' ), 'Custom admin heading is rendered' );
check( false !== strpos( $html, 'Highlights' ), 'Optional eyebrow is rendered' );
check( false !== strpos( $html, 'text-tedx-green' ), 'Eyebrow adheres to green uppercase design' );

// 3. Card rendering
$html = render_what_to_expect( array(
	'cards' => array(
		array(
			'title'       => 'Pinsa & Drinks',
			'icon'        => 'food',
			'description' => 'Delicious pinsa and refreshing beverages.',
		),
	),
) );
check( false !== strpos( $html, 'Pinsa &amp; Drinks' ), 'Card title is rendered' );
check( false !== strpos( $html, 'Delicious pinsa and refreshing beverages.' ), 'Card description is rendered' );
check( false !== strpos( $html, 'border-tedx-card' ), 'Card has border-tedx-card styling' );
check( false !== strpos( $html, 'rounded-4xl' ), 'Card has rounded-4xl radius' );
check( false !== strpos( $html, 'grid-cols-1' ), 'Mobile grid has 1 column (1 card per row)' );
check( false !== strpos( $html, 'sm:grid-cols-3' ), 'Tablet displays (<1000px) have 3 columns (3 cards per row)' );
check( false !== strpos( $html, 'min-[1000px]:grid-cols-4' ), 'Displays >= 1000px have 4 columns (4 cards per row)' );

// 4. Custom SVG icon via media upload
$html = render_what_to_expect( array(
	'cards' => array(
		array(
			'title'        => 'Custom Feature',
			'icon'         => 'custom',
			'customIconId' => 99,
			'description'  => 'With uploaded SVG.',
		),
	),
) );
check( false !== strpos( $html, 'tedx-what-to-expect-custom-icon' ), 'Custom icon element rendered' );
check( false !== strpos( $html, 'https://example.test/uploads/icon-99.svg' ), 'Custom SVG URL applied in mask' );

// 5. Block registration check
require_once TEDX_DIR . '/inc/blocks.php';
check( in_array( 'tedx-what-to-expect', tedx_get_block_dirs(), true ), 'tedx-what-to-expect is registered in tedx_get_block_dirs()' );
