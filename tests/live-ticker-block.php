<?php
/**
 * Test suite for the "Live Event Ticker" Gutenberg block: php tests/live-ticker-block.php
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
function is_singular( $type = '' ) { return false; }

require_once TEDX_DIR . '/inc/template-tags.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_live_ticker( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-live-ticker/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Default render and structure
$html = render_live_ticker();
check( false !== strpos( $html, 'tedx-live-ticker-block' ), 'Renders outer wrapper with .tedx-live-ticker-block' );
check( false !== strpos( $html, 'class="tedx-live-ticker-floating-wrapper"' ), 'Renders floating wrapper' );
check( false !== strpos( $html, 'class="tedx-live-ticker-pill"' ), 'Renders floating minimalist pill' );
check( false !== strpos( $html, 'class="tedx-live-ticker-badge badge-next"' ), 'Default initial state is badge-next' );
check( false !== strpos( $html, 'tedx-live-ticker-dot' ), 'Status pulsing dot rendered' );
check( false !== strpos( $html, 'Registration &amp; Welcome Reception' ), 'Default initial session title is rendered' );
check( false !== strpos( $html, '14:00' ), 'Default start time is rendered' );
check( false !== strpos( $html, '14:30' ), 'Default end time is rendered' );
check( false !== strpos( $html, 'tedx-live-ticker-arrow' ), 'Jump-to-timetable chevron icon rendered by default' );

// 2. Custom attributes and jump toggle
$html_custom = render_live_ticker( array(
	'eyebrowLive'     => 'EN CE MOMENT',
	'eyebrowNext'     => 'À SUIVRE',
	'eyebrowFinished' => 'TERMINÉ',
	'jumpToTimetable' => false,
	'eventDate'       => '2026-11-20',
	'testMode'        => true,
	'items'           => array(
		array(
			'startTime' => '09:00',
			'endTime'   => '09:30',
			'title'     => 'Welcome Keynote',
		),
	),
) );
check( false !== strpos( $html_custom, 'data-eyebrow-live="EN CE MOMENT"' ), 'Custom LIVE label passed to data attribute' );
check( false !== strpos( $html_custom, 'data-eyebrow-next="À SUIVRE"' ), 'Custom NEXT label passed to data attribute' );
check( false !== strpos( $html_custom, 'data-eyebrow-finished="TERMINÉ"' ), 'Custom FINISHED label passed to data attribute' );
check( false !== strpos( $html_custom, 'data-jump="0"' ), 'Jump toggle disabled in data attribute' );
check( false === strpos( $html_custom, 'tedx-live-ticker-arrow' ), 'Jump arrow omitted when jumpToTimetable is false' );
check( false !== strpos( $html_custom, 'Welcome Keynote' ), 'Custom items session title rendered' );

// 3. Block registration and spacing exclusions
require_once TEDX_DIR . '/inc/blocks.php';
check( in_array( 'tedx-live-ticker', tedx_get_block_dirs(), true ), 'tedx-live-ticker is registered in tedx_get_block_dirs()' );

$spacing_attrs = tedx_register_block_spacing_attributes( array(), 'tedx/live-ticker' );
check( empty( $spacing_attrs['attributes'] ), 'tedx/live-ticker is excluded from section spacing attributes' );

// 4. Stylesheet check for floating positioning and navbar offset
$style_css = file_get_contents( TEDX_DIR . '/blocks/tedx-live-ticker/style.css' );
check( false !== strpos( $style_css, 'position: fixed;' ), 'style.css defines fixed positioning' );
check( false !== strpos( $style_css, 'top: 12px' ), 'style.css aligns pill compact to the top (12px) when navbar is hidden' );
check( false !== strpos( $style_css, 'backdrop-filter: blur(12px);' ), 'style.css uses backdrop-filter blur' );

echo 'All Live Event Ticker block tests passed!' . PHP_EOL;
