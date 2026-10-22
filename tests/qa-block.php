<?php
/**
 * Test suite for the "Q&A" Gutenberg block: php tests/qa-block.php
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
function wp_unique_id( $prefix = '' ) { static $c = 0; return $prefix . (++$c); }
function wpautop( $pee, $br = true ) { return '<p>' . $pee . '</p>'; }
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

require_once TEDX_DIR . '/inc/template-tags.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function render_qa( $attributes = array() ) {
	$render = function( $attributes ) {
		ob_start();
		include TEDX_DIR . '/blocks/tedx-qa/render.php';
		return ob_get_clean();
	};
	return $render( $attributes );
}

// 1. Default heading and design principles
$html = render_qa();
check( false !== strpos( $html, 'Frequently Asked Questions' ), 'Renders default title "Frequently Asked Questions"' );
check( false !== strpos( $html, 'class="tedx-block-heading' ), 'Title uses standard .tedx-block-heading styling' );
check( false !== strpos( $html, 'max-w-figma' ), 'Renders inside max-w-figma container (max width 1000px)' );
check( false !== strpos( $html, 'Q&amp;A' ), 'Eyebrow "Q&A" rendered' );

// 2. Custom heading & anchor
$html = render_qa( array(
	'title'     => 'General Inquiries',
	'eyebrow'   => 'Help Center',
	'sectionId' => 'help',
) );
check( false !== strpos( $html, 'General Inquiries' ), 'Custom admin heading is rendered' );
check( false !== strpos( $html, 'Help Center' ), 'Custom eyebrow is rendered' );
check( false !== strpos( $html, 'id="help"' ), 'Custom anchor ID is applied to wrapper' );

// 3. shadcn / Base UI Accordion structure
$html = render_qa( array(
	'items' => array(
		array(
			'question' => 'How can I register?',
			'answer'   => 'You can register online through our tickets page.',
		),
	),
) );
check( false !== strpos( $html, 'class="tedx-accordion-trigger' ), 'Question rendered as shadcn button trigger' );
check( false !== strpos( $html, 'aria-controls=' ), 'Trigger has aria-controls attribute' );
check( false !== strpos( $html, 'aria-expanded="false"' ), 'Trigger initializes in aria-expanded="false" state' );
check( false !== strpos( $html, 'class="tedx-accordion-chevron' ), 'Chevron icon rendered with transition classes' );
check( false !== strpos( $html, 'class="tedx-accordion-content' ), 'Content container rendered with transition class' );
check( false !== strpos( $html, 'role="region"' ), 'Content container has role="region"' );
check( false !== strpos( $html, 'aria-labelledby=' ), 'Content container has aria-labelledby attribute' );
check( false !== strpos( $html, 'How can I register?' ), 'Question text is present' );
check( false !== strpos( $html, 'You can register online through our tickets page.' ), 'Answer text is present' );

// 4. Block registration check
require_once TEDX_DIR . '/inc/blocks.php';
check( in_array( 'tedx-qa', tedx_get_block_dirs(), true ), 'tedx-qa is registered in tedx_get_block_dirs()' );
