<?php
/**
 * Test suite for the shadcn Button component & block: php tests/button-block.php
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
define( 'ABSPATH', TEDX_DIR . '/' );

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return $value; }
function esc_url_raw( $value ) { return $value; }
function __( $value, $domain = '' ) { return $value; }
function sanitize_html_class( $value ) { return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $value ); }
function wp_parse_args( $args, $defaults = array() ) {
	return array_merge( $defaults, (array) $args );
}
function wp_get_attachment_url( $id ) {
	return $id ? 'https://example.test/uploads/icon-' . $id . '.svg' : false;
}
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

require_once TEDX_DIR . '/inc/template-tags.php';
require_once TEDX_DIR . '/inc/blocks.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_button_block( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-button/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Text-Only Button (Default state)
$html = render_button_block( array(
	'text' => 'Explore Program',
) );
check( false !== strpos( $html, 'tedx-button-block' ), 'Block wrapper rendered' );
check( false !== strpos( $html, 'px-4 md:px-8 lg:px-12' ), 'Standard responsive padding px-4 md:px-8 lg:px-12 on outer section' );
check( false !== strpos( $html, 'max-w-figma' ), 'Renders inside max-w-figma container (max width 1000px)' );
check( false !== strpos( $html, 'tedx-button-container' ), 'Renders tedx-button-container' );
check( false !== strpos( $html, 'class="tedx-button-wrap align-left"' ), 'Default alignment is left' );
check( false !== strpos( $html, 'Explore Program' ), 'Button label text rendered' );
check( false !== strpos( $html, 'class="tedx-btn btn-shadcn-anim size-md width-auto variant-default"' ), 'Default classes: shadcn-anim, size-md, width-auto, variant-default' );
check( false === strpos( $html, 'tedx-btn-icon' ), 'Text-only button contains no icon element' );
check( false !== strpos( $html, '<button type="button"' ), 'Renders as <button> when no URL is provided' );

// 2. Redirect Link with new tab
$html = render_button_block( array(
	'text'   => 'Get Tickets',
	'url'    => 'https://tedxregensburg.de/tickets',
	'newTab' => true,
) );
check( false !== strpos( $html, '<a href="https://tedxregensburg.de/tickets"' ), 'Renders as <a> when URL is provided' );
check( false !== strpos( $html, 'target="_blank" rel="noopener noreferrer"' ), 'Target _blank and safe rel attributes applied when newTab is true' );

// 3. Custom SVG Icon (Left and Right placement)
$html_left = render_button_block( array(
	'text'         => 'Watch Talk',
	'iconId'       => 42,
	'iconPosition' => 'left',
) );
check( false !== strpos( $html_left, 'class="tedx-btn-icon"' ), 'Icon container element rendered' );
check( false !== strpos( $html_left, '--btn-icon-url: url(\'https://example.test/uploads/icon-42.svg\')' ), 'Custom SVG URL resolved and applied in mask' );
// Check icon comes before text
$icon_pos = strpos( $html_left, 'tedx-btn-icon' );
$text_pos = strpos( $html_left, 'Watch Talk' );
check( $icon_pos < $text_pos, 'Icon is placed on the left (before text)' );

$html_right = render_button_block( array(
	'text'         => 'Next Speaker',
	'iconUrl'      => 'https://example.test/arrow.svg',
	'iconPosition' => 'right',
) );
$icon_pos_r = strpos( $html_right, 'tedx-btn-icon' );
$text_pos_r = strpos( $html_right, 'Next Speaker' );
check( $icon_pos_r > $text_pos_r, 'Icon is placed on the right (after text)' );

// 4. Width Presets (Auto, Sm, Md, Lg, Full)
$widths = array( 'auto', 'sm', 'md', 'lg', 'full' );
foreach ( $widths as $w ) {
	$html_w = render_button_block( array( 'width' => $w ) );
	check( false !== strpos( $html_w, 'width-' . $w ), 'Width preset width-' . $w . ' applied' );
}

// 5. Sizes (Small, Medium, Large)
$sizes = array( 'sm', 'md', 'lg' );
foreach ( $sizes as $s ) {
	$html_s = render_button_block( array( 'size' => $s ) );
	check( false !== strpos( $html_s, 'size-' . $s ), 'Size preset size-' . $s . ' applied' );
}

// 6. Alignment (Left, Center, Right)
$alignments = array( 'left', 'center', 'right' );
foreach ( $alignments as $al ) {
	$html_a = render_button_block( array( 'alignment' => $al ) );
	check( false !== strpos( $html_a, 'align-' . $al ), 'Alignment align-' . $al . ' wrapper applied' );
}

// 7. Background Variants & Custom Color
$variants = array( 'default', 'green', 'secondary', 'white', 'outline' );
foreach ( $variants as $v ) {
	$html_v = render_button_block( array( 'variant' => $v ) );
	check( false !== strpos( $html_v, 'variant-' . $v ), 'Variant variant-' . $v . ' applied' );
}

$html_custom = render_button_block( array(
	'variant'         => 'custom',
	'customBg'        => '#ffaa00',
	'customTextColor' => '#112233',
) );
check( false !== strpos( $html_custom, 'background-color: #ffaa00;' ), 'Custom background color applied via inline style' );
check( false !== strpos( $html_custom, 'color: #112233 !important;' ), 'Custom text color applied via inline style' );

// 8. Reusable PHP Helper function: tedx_render_button()
$php_output = tedx_render_button( array(
	'text'      => 'PHP Component',
	'url'       => '/php-test',
	'variant'   => 'green',
	'size'      => 'lg',
	'width'     => 'md',
	'alignment' => 'center',
) );
check( false !== strpos( $php_output, 'PHP Component' ), 'PHP helper renders button label' );
check( false !== strpos( $php_output, 'href="/php-test"' ), 'PHP helper renders target URL' );
check( false !== strpos( $php_output, 'variant-green' ), 'PHP helper renders green variant' );
check( false !== strpos( $php_output, 'size-lg' ), 'PHP helper renders size-lg' );
check( false !== strpos( $php_output, 'width-md' ), 'PHP helper renders width-md' );
check( false !== strpos( $php_output, 'align-center' ), 'PHP helper wraps in align-center' );

// 9. Block registration in tedx_get_block_dirs()
check( in_array( 'tedx-button', tedx_get_block_dirs(), true ), 'tedx-button is registered in tedx_get_block_dirs()' );
