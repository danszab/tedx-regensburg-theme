<?php
/**
 * Test suite for the "Timetable v2" Gutenberg block: php tests/timetable-v2-block.php
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

require_once TEDX_DIR . '/inc/template-tags.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_timetable_v2( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-timetable-v2/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Default heading and design principles
$html = render_timetable_v2();
check( false !== strpos( $html, 'Timetable' ), 'Renders default title "Timetable"' );
check( false !== strpos( $html, 'class="tedx-timetable-v2-title tedx-block-heading' ), 'Title uses standard .tedx-block-heading styling' );
check( false !== strpos( $html, 'max-w-figma' ), 'Renders inside max-w-figma container' );
check( false !== strpos( $html, 'SCHEDULE' ), 'Eyebrow "SCHEDULE" rendered' );
check( false === strpos( $html, 'border-t' ), 'Top divider (border-t) is removed from timetable-v2' );
check( false !== strpos( $html, 'class="tedx-timetable-v2-rail"' ), 'Continuous vertical progress rail rendered' );
check( false !== strpos( $html, 'class="tedx-timetable-v2-progress-fill"' ), 'Progress fill element present' );
check( false !== strpos( $html, 'class="tedx-timetable-v2-progress-thumb"' ), 'Progress thumb element present' );

// 2. Custom heading, eyebrow, event date, and test mode
$html = render_timetable_v2( array(
	'title'     => 'Program & Agenda',
	'eyebrow'   => 'DAY 1',
	'eventDate' => '2026-11-14',
	'testMode'  => true,
) );
check( false !== strpos( $html, 'Program &amp; Agenda' ), 'Custom section title is rendered' );
check( false !== strpos( $html, 'DAY 1' ), 'Custom eyebrow is rendered' );
check( false !== strpos( $html, 'data-event-date="2026-11-14"' ), 'Event date attribute is populated' );
check( false !== strpos( $html, 'data-test-mode="1"' ), 'Test mode attribute is enabled' );

// 3. Single-row card layout with title, description, and time range
$html = render_timetable_v2( array(
	'items' => array(
		array(
			'startTime'   => '15:00',
			'endTime'     => '15:45',
			'title'       => 'Keynote: The Power of AI',
			'description' => 'Exploration of generative AI frontiers.',
			'hasButton'   => false,
		),
	),
) );
check( false !== strpos( $html, 'class="tedx-timetable-v2-card"' ), 'Card rendered with .tedx-timetable-v2-card' );
check( false !== strpos( $html, 'data-start="15:00"' ), 'Start time data attribute attached' );
check( false !== strpos( $html, 'data-end="15:45"' ), 'End time data attribute attached' );
check( false !== strpos( $html, '15:00' ) && false !== strpos( $html, '15:45' ), 'Start and End times displayed in card' );
check( false !== strpos( $html, 'Keynote: The Power of AI' ), 'Session title is rendered' );
check( false !== strpos( $html, 'Exploration of generative AI frontiers.' ), 'Session description is rendered' );
check( false === strpos( $html, 'tedx-timetable-v2-btn' ), 'No button rendered when hasButton is false' );

// 4. Optional small shadcn button with custom text, URL, new tab, and variant
$html = render_timetable_v2( array(
	'items' => array(
		array(
			'startTime'     => '16:00',
			'endTime'       => '17:00',
			'title'         => 'Interactive Workshop',
			'description'   => 'Hands-on breakout sessions.',
			'hasButton'     => true,
			'buttonText'    => 'Register Now',
			'buttonUrl'     => 'https://example.com/register',
			'buttonNewTab'  => true,
			'buttonVariant' => 'outline',
		),
	),
) );
check( false !== strpos( $html, 'class="tedx-timetable-v2-btn variant-outline btn-shadcn-anim"' ), 'Small shadcn button rendered with variant-outline and btn-shadcn-anim' );
check( false !== strpos( $html, 'href="https://example.com/register"' ), 'Button href properly points to target URL' );
check( false !== strpos( $html, 'Register Now' ), 'Custom button text is rendered' );
check( false !== strpos( $html, 'target="_blank" rel="noopener noreferrer"' ), 'New tab attributes applied when buttonNewTab is true' );

// 5. Block registration check
require_once TEDX_DIR . '/inc/blocks.php';
check( in_array( 'tedx-timetable-v2', tedx_get_block_dirs(), true ), 'tedx-timetable-v2 is registered in tedx_get_block_dirs()' );

// 6. Uniform card padding and zero-margin overrides
$style_css = file_get_contents( TEDX_DIR . '/blocks/tedx-timetable-v2/style.css' );
check( false !== strpos( $style_css, 'padding: 20px !important;' ), 'Timetable v2 style.css enforces uniform 20px padding' );
check( false !== strpos( $style_css, 'margin: 0 !important;' ), 'Timetable v2 style.css resets session title margin to prevent top edge displacement' );

$input_css = file_get_contents( TEDX_DIR . '/assets/css/input.css' );
check( false !== strpos( $input_css, '.tedx-timetable-v2-card h3' ), 'Global stylesheet resets timetable-v2 card heading margins' );
check( false !== strpos( $input_css, '.tedx-timetable-v2-card p' ), 'Global stylesheet resets timetable-v2 card paragraph margins' );
check( false === strpos( $input_css, '.tedx-page-has-top-thumb article {' ), 'Global stylesheet does not strip padding-top from nested <article> cards' );
