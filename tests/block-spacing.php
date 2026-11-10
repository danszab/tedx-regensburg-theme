<?php
/**
 * Test suite for TEDx block spacing, vertical rhythm, and Gutenberg spacing controls.
 * Run with: php tests/block-spacing.php
 */
define( 'ABSPATH', __DIR__ );
define( 'TEDX_DIR', dirname( __DIR__ ) );

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( 'FAIL: ' . $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function wp_parse_args( $args, $defaults = array() ) {
	return array_merge( $defaults, (array) $args );
}
function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
function apply_filters( $tag, $value, ...$args ) { return $value; }
function __( $text, $domain = '' ) { return $text; }

require_once TEDX_DIR . '/inc/blocks.php';

// -------------------------------------------------------------
// Test 1: Default section spacing classes
// -------------------------------------------------------------
$default_classes = tedx_get_block_spacing_classes( array() );
check( false !== strpos( $default_classes, 'pt-8 md:pt-12' ), 'Default top padding is pt-8 md:pt-12 (32px mobile / 48px desktop).' );
check( false !== strpos( $default_classes, 'pb-8 md:pb-12' ), 'Default bottom padding is pb-8 md:pb-12 (32px mobile / 48px desktop).' );
check( false !== strpos( $default_classes, 'border-t border-white/5' ), 'Default divider is border-t border-white/5.' );

// -------------------------------------------------------------
// Test 2: Custom presets: None, Compact, Large
// -------------------------------------------------------------
$none_classes = tedx_get_block_spacing_classes( array(
	'paddingTop'    => 'none',
	'paddingBottom' => 'none',
) );
check( false !== strpos( $none_classes, 'pt-0' ), 'paddingTop="none" renders pt-0.' );
check( false !== strpos( $none_classes, 'pb-0' ), 'paddingBottom="none" renders pb-0.' );

$compact_classes = tedx_get_block_spacing_classes( array(
	'paddingTop'    => 'compact',
	'paddingBottom' => 'compact',
) );
check( false !== strpos( $compact_classes, 'pt-4 md:pt-6' ), 'paddingTop="compact" renders pt-4 md:pt-6.' );
check( false !== strpos( $compact_classes, 'pb-4 md:pb-6' ), 'paddingBottom="compact" renders pb-4 md:pb-6.' );

$large_classes = tedx_get_block_spacing_classes( array(
	'paddingTop'    => 'large',
	'paddingBottom' => 'large',
) );
check( false !== strpos( $large_classes, 'pt-16 md:pt-24' ), 'paddingTop="large" renders pt-16 md:pt-24.' );
check( false !== strpos( $large_classes, 'pb-16 md:pb-24' ), 'paddingBottom="large" renders pb-16 md:pb-24.' );

// -------------------------------------------------------------
// Test 3: Divider toggle and custom defaults
// -------------------------------------------------------------
$hidden_border = tedx_get_block_spacing_classes( array(
	'hideBorderTop' => true,
) );
check( false !== strpos( $hidden_border, 'border-t-0' ), 'hideBorderTop=true renders border-t-0.' );
check( false === strpos( $hidden_border, 'border-white/5' ), 'hideBorderTop=true removes hairline border color.' );

$no_border_default = tedx_get_block_spacing_classes( array(), array( 'has_border' => false ) );
check( false === strpos( $no_border_default, 'border-t' ), 'Blocks with has_border=false (like timetable-v2) omit border by default.' );

$with_border_override = tedx_get_block_spacing_classes( array( 'showBorderTop' => true ), array( 'has_border' => false ) );
check( false !== strpos( $with_border_override, 'border-t border-white/5' ), 'showBorderTop=true enables top divider on blocks with has_border=false.' );

// -------------------------------------------------------------
// Test 4: Dynamic attribute injection via filter
// -------------------------------------------------------------
$speaker_args = tedx_register_block_spacing_attributes( array(), 'tedx/speakers' );
check( isset( $speaker_args['attributes']['paddingTop'] ), 'paddingTop attribute registered for tedx/speakers.' );
check( isset( $speaker_args['attributes']['paddingBottom'] ), 'paddingBottom attribute registered for tedx/speakers.' );
check( isset( $speaker_args['attributes']['hideBorderTop'] ), 'hideBorderTop attribute registered for tedx/speakers.' );
check( 'default' === $speaker_args['attributes']['paddingTop']['default'], 'Default paddingTop is "default".' );

$button_args = tedx_register_block_spacing_attributes( array(), 'tedx/button' );
check( isset( $button_args['attributes']['paddingTop'] ), 'Inline tedx/button block receives section spacing attributes.' );
check( isset( $button_args['attributes']['paddingBottom'] ), 'Inline tedx/button block receives paddingBottom attribute.' );
check( isset( $button_args['attributes']['hideBorderTop'] ), 'Inline tedx/button block receives hideBorderTop attribute.' );

// Ensure all blocks registered in the theme receive spacing attributes
foreach ( tedx_get_block_dirs() as $b_dir ) {
	$b_name = 'tedx/' . str_replace( 'tedx-', '', $b_dir );
	$b_args = tedx_register_block_spacing_attributes( array(), $b_name );
	check( isset( $b_args['attributes']['paddingTop'] ) && isset( $b_args['attributes']['paddingBottom'] ) && isset( $b_args['attributes']['hideBorderTop'] ) && isset( $b_args['attributes']['showBorderTop'] ), "Block {$b_name} receives padding and divider attributes." );
}

$core_args = tedx_register_block_spacing_attributes( array(), 'core/paragraph' );
check( empty( $core_args['attributes'] ), 'Non-tedx blocks do not receive section spacing attributes.' );

// -------------------------------------------------------------
// Test 5: Page template contextual article classes
// -------------------------------------------------------------
$post_has_title = true;
$post_has_thumb = false;
$GLOBALS['remaining'] = true;
function have_posts() { return $GLOBALS['remaining']; }
function the_post() { $GLOBALS['remaining'] = false; }
function the_ID() { echo 10; }
function post_class( $class ) { echo 'class="' . esc_attr( $class ) . '"'; }
function get_the_title( $id = 0 ) { return $GLOBALS['post_has_title'] ? 'Event Title' : ''; }
function the_title() { echo 'Event Title'; }
function has_post_thumbnail() { return $GLOBALS['post_has_thumb']; }
function the_content() { echo '<section>Content</section>'; }
function get_queried_object_id() { return 10; }
function get_header() {}
function get_footer() {}
function tedx_page_has_full_width_blocks( $id ) { return true; }
function tedx_is_navbar_hidden( $id = null ) { return false; }
function tedx_page_has_top_thumbnail( $id ) { return false; }

// Full-width page with title: container touches navbar with pt-[60px] lg:pt-[70px]
ob_start();
include TEDX_DIR . '/page.php';
$html_with_title = ob_get_clean();
check( false !== strpos( $html_with_title, 'pt-[60px] lg:pt-[70px]' ), 'Full-width page touches navbar edge with pt-[60px] lg:pt-[70px].' );
check( false !== strpos( $html_with_title, 'tedx-full-width-page-article tedx-has-page-header' ), 'Full-width page with title receives tedx-has-page-header class.' );

// Full-width page without title or thumbnail
$GLOBALS['post_has_title'] = false;
$GLOBALS['remaining'] = true;
ob_start();
include TEDX_DIR . '/page.php';
$html_no_title = ob_get_clean();
check( false !== strpos( $html_no_title, 'tedx-full-width-page-article tedx-no-page-header' ), 'Full-width page without title receives tedx-no-page-header class.' );

// Event page template touches navbar edges
ob_start();
include TEDX_DIR . '/single-tedx_event.php';
$event_template_html = ob_get_clean();
check( false !== strpos( $event_template_html, 'pt-[60px] lg:pt-[70px]' ), 'single-tedx_event.php container touches navbar edge with pt-[60px] lg:pt-[70px].' );

// -------------------------------------------------------------
// Test 6: CSS stylesheet rules
// -------------------------------------------------------------
$css_compiled = file_get_contents( TEDX_DIR . '/assets/css/style.css' );
check( false !== strpos( $css_compiled, 'tedx-no-page-header' ), 'Compiled CSS includes tedx-no-page-header rules.' );
check( false !== strpos( $css_compiled, 'border-top-width:0!important' ) || false !== strpos( $css_compiled, 'border-top-width: 0 !important' ), 'Compiled CSS zeroes top border on first blocks touching navbar.' );

// -------------------------------------------------------------
// Test 7: Editor helper script includes spacing controls
// -------------------------------------------------------------
$editor_js = file_get_contents( TEDX_DIR . '/blocks/shared/editor-helpers.js' );
check( false !== strpos( $editor_js, 'H.spacingPanel' ), 'editor-helpers.js defines H.spacingPanel.' );
check( false !== strpos( $editor_js, 'Spacing & Dividers' ), 'editor-helpers.js includes Spacing & Dividers panel label.' );
check( false !== strpos( $editor_js, 'paddingTop' ), 'editor-helpers.js includes paddingTop control.' );
check( false !== strpos( $editor_js, 'paddingBottom' ), 'editor-helpers.js includes paddingBottom control.' );
check( false !== strpos( $editor_js, 'hideBorderTop' ), 'editor-helpers.js includes hideBorderTop toggle.' );
check( false !== strpos( $editor_js, 'editor.BlockEdit' ), 'editor-helpers.js registers filter on editor.BlockEdit.' );

echo PHP_EOL . 'All block spacing and vertical rhythm tests passed!' . PHP_EOL;
