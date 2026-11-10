<?php
/**
 * Test suite for the "Bottom Navbar" block: php tests/bottom-navbar-block.php
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
define( 'ABSPATH', TEDX_DIR . '/' );

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return $value; }
function __( $value, $domain = '' ) { return $value; }
function esc_attr_e( $value, $domain = '' ) { echo esc_attr( $value ); }
function get_block_wrapper_attributes( $attrs = array() ) {
	$class = isset( $attrs['class'] ) ? 'class="' . esc_attr( $attrs['class'] ) . '"' : '';
	return trim( $class );
}
function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_bottom_navbar( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-bottom-navbar/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Default render check
$html = render_bottom_navbar();
check( false !== strpos( $html, 'class="tedx-bottom-navbar-root"' ), 'Renders .tedx-bottom-navbar-root' );
check( false !== strpos( $html, 'class="tedx-bottom-navbar-bar"' ), 'Renders .tedx-bottom-navbar-bar' );
check( false !== strpos( $html, 'data-scroll-target="timetable"' ), 'Autoscroll data target attached for anchor' );
check( false !== strpos( $html, 'Timetable' ), 'Default Timetable item rendered' );
check( false !== strpos( $html, 'Speakers' ), 'Default Speakers item rendered' );

// 2. Custom button with custom SVG
$html_custom = render_bottom_navbar( array(
	'buttons' => array(
		array(
			'label'         => 'Buy Tickets',
			'url'           => 'https://example.com/tickets',
			'newTab'        => true,
			'customIconUrl' => 'https://example.com/ticket.svg',
		),
		array(
			'label'         => 'FAQ',
			'url'           => '#qa',
			'newTab'        => false,
			'customIconUrl' => '',
		),
	),
) );
check( false !== strpos( $html_custom, 'Buy Tickets' ), 'Custom button label rendered' );
check( false !== strpos( $html_custom, 'target="_blank"' ), 'External newTab attribute rendered' );
check( false !== strpos( $html_custom, 'data-scroll-target="qa"' ), 'Custom #qa anchor targets correctly' );
check( false !== strpos( $html_custom, 'mask-image: url(\'https://example.com/ticket.svg\')' ), 'Custom SVG mask applied' );

// 3. Block registration and spacing exclusions
require_once TEDX_DIR . '/inc/blocks.php';
check( in_array( 'tedx-bottom-navbar', tedx_get_block_dirs(), true ), 'tedx-bottom-navbar registered in tedx_get_block_dirs()' );

$spacing_attrs = tedx_register_block_spacing_attributes( array(), 'tedx/bottom-navbar' );
check( empty( $spacing_attrs['attributes'] ), 'tedx/bottom-navbar excluded from section spacing attributes' );

// 4. CSS Safe-area support verification
$style_css = file_get_contents( TEDX_DIR . '/blocks/tedx-bottom-navbar/style.css' );
check( false !== strpos( $style_css, 'position: fixed;' ), 'style.css defines fixed positioning' );
check( false !== strpos( $style_css, 'env(safe-area-inset-bottom' ), 'style.css supports iOS Safari safe-area-inset-bottom' );

echo 'All Bottom Navbar block tests passed!' . PHP_EOL;
