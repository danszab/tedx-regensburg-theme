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
check( false !== strpos( $html, 'tedx-bottom-navbar-root' ), 'Renders .tedx-bottom-navbar-root' );
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

// 2b. Option to remove all icons (showIcons = false)
$html_no_icons = render_bottom_navbar( array(
	'showIcons' => false,
	'buttons'   => array(
		array(
			'label'         => 'Schedule',
			'url'           => '#timetable',
			'customIconUrl' => 'https://example.com/icon.svg',
		),
		array(
			'label'         => 'Speakers',
			'url'           => '#speakers',
			'customIconUrl' => '',
		),
	),
) );
check( false !== strpos( $html_no_icons, 'tedx-no-icons' ), 'Renders tedx-no-icons class when showIcons is false' );
check( false !== strpos( $html_no_icons, 'is-no-icon' ), 'Renders is-no-icon item class when showIcons is false' );
check( false === strpos( $html_no_icons, 'tedx-bottom-nav-icon-box' ), 'Omits icon box completely when showIcons is false' );
check( false !== strpos( $html_no_icons, 'Schedule' ), 'Retains text labels in no-icons mode' );

// 3. Block registration and spacing exclusions
require_once TEDX_DIR . '/inc/blocks.php';
check( in_array( 'tedx-bottom-navbar', tedx_get_block_dirs(), true ), 'tedx-bottom-navbar registered in tedx_get_block_dirs()' );

$block_json = json_decode( file_get_contents( TEDX_DIR . '/blocks/tedx-bottom-navbar/block.json' ), true );
check( isset( $block_json['attributes']['showIcons'] ), 'block.json defines showIcons attribute' );
check( true === $block_json['attributes']['showIcons']['default'], 'showIcons attribute defaults to true' );

$spacing_attrs = tedx_register_block_spacing_attributes( array(), 'tedx/bottom-navbar' );
check( ! empty( $spacing_attrs['attributes'] ), 'tedx/bottom-navbar receives section spacing attributes' );

// 4. CSS Safe-area support and remove button verification
$style_css = file_get_contents( TEDX_DIR . '/blocks/tedx-bottom-navbar/style.css' );
check( false !== strpos( $style_css, 'position: fixed;' ), 'style.css defines fixed positioning' );
check( false !== strpos( $style_css, 'env(safe-area-inset-bottom' ), 'style.css supports iOS Safari safe-area-inset-bottom' );
check( false !== strpos( $style_css, '.tedx-no-icons' ), 'style.css defines text-only .tedx-no-icons styles' );
check( false !== strpos( $style_css, '.tedx-bottom-nav-item-remove-btn' ), 'style.css defines .tedx-bottom-nav-item-remove-btn' );

// 5. Editor script assets check & item removal / remove all icons support
check( file_exists( TEDX_DIR . '/blocks/tedx-bottom-navbar/index.asset.php' ), 'Bottom navbar has index.asset.php' );
$asset = include TEDX_DIR . '/blocks/tedx-bottom-navbar/index.asset.php';
check( in_array( 'wp-blocks', $asset['dependencies'], true ), 'index.asset.php declares wp-blocks dependency' );
check( in_array( 'wp-block-editor', $asset['dependencies'], true ), 'index.asset.php declares wp-block-editor dependency' );

$index_js = file_get_contents( TEDX_DIR . '/blocks/tedx-bottom-navbar/index.js' );
check( false !== strpos( $index_js, 'removeButton' ), 'index.js implements removeButton' );
check( false !== strpos( $index_js, 'tedx-bottom-nav-item-remove-btn' ), 'index.js renders direct item remove button' );
check( false !== strpos( $index_js, 'removeAllIcons' ), 'index.js implements removeAllIcons action' );
check( false !== strpos( $index_js, 'showIcons' ), 'index.js supports showIcons toggle' );

echo 'All Bottom Navbar block tests passed!' . PHP_EOL;
