<?php
/**
 * Test page thumbnail image options and rendering: php tests/page-thumbnail.php.
 */
define( 'ABSPATH', __DIR__ );

$posts = array();
$post_meta = array();
$filters = array();
$actions = array();
$meta_boxes = array();
$registered_meta = array();
$current_user_can_edit = true;
$page_templates = array();

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	global $actions;
	$actions[ $hook ][] = array( 'callback' => $callback, 'priority' => $priority );
}

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	global $filters;
	$filters[ $hook ][] = array( 'callback' => $callback, 'priority' => $priority );
}

function apply_filters( $hook, $value, ...$args ) {
	global $filters;
	if ( ! empty( $filters[ $hook ] ) ) {
		foreach ( $filters[ $hook ] as $filter ) {
			$value = call_user_func_array( $filter['callback'], array_merge( array( $value ), $args ) );
		}
	}
	return $value;
}

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $text ) { return esc_attr( $text ); }
function esc_url_raw( $text ) { return (string) $text; }
function esc_js( $text ) { return addslashes( (string) $text ); }
function esc_attr_e( $text, $domain = '' ) { echo esc_attr( $text ); }
function esc_html_e( $text, $domain = '' ) { echo esc_html( $text ); }
function __( $text, $domain = '' ) { return $text; }

function get_post( $id ) {
	global $posts;
	return $posts[ $id ] ?? null;
}

function get_post_meta( $post_id, $key, $single = false ) {
	global $post_meta;
	return $post_meta[ $post_id ][ $key ] ?? '';
}

function update_post_meta( $post_id, $key, $value ) {
	global $post_meta;
	$post_meta[ $post_id ][ $key ] = $value;
	return true;
}

function delete_post_meta( $post_id, $key ) {
	global $post_meta;
	unset( $post_meta[ $post_id ][ $key ] );
	return true;
}

function get_page_template_slug( $post_id ) {
	global $page_templates;
	return $page_templates[ $post_id ] ?? '';
}

function get_attached_file( $id ) { return ''; }
$theme_mods = array();
function get_theme_mod( $name, $default = '' ) {
	global $theme_mods;
	return $theme_mods[ $name ] ?? $default;
}

function wp_get_attachment_image_url( $id, $size = 'thumbnail' ) {
	return $id ? 'https://example.test/uploads/image-' . $id . '.jpg' : '';
}

function wp_get_attachment_image( $id, $size = 'thumbnail', $icon = false, $attr = '' ) {
	$class = is_array( $attr ) && isset( $attr['class'] ) ? $attr['class'] : '';
	return '<img src="https://example.test/uploads/image-' . $id . '.jpg" alt="Attachment Image ' . $id . '" class="' . esc_attr( $class ) . '" />';
}

function current_user_can( $capability, $post_id = null ) {
	global $current_user_can_edit;
	return $current_user_can_edit;
}

function wp_verify_nonce( $nonce, $action ) {
	return 'valid_nonce_' . $action === $nonce;
}

function wp_nonce_field( $action, $name = '_wpnonce' ) {
	echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="valid_nonce_' . esc_attr( $action ) . '" />';
}

function checked( $checked, $current = true, $echo = true ) {
	$result = ( (string) $checked === (string) $current ) ? ' checked="checked"' : '';
	if ( $echo ) {
		echo $result;
	}
	return $result;
}

function register_post_meta( $post_type, $meta_key, $args = array() ) {
	global $registered_meta;
	$registered_meta[ $post_type ][ $meta_key ] = $args;
}

function rest_sanitize_boolean( $value ) {
	return (bool) $value;
}

function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default' ) {
	global $meta_boxes;
	$meta_boxes[ $screen ][ $id ] = array(
		'title'    => $title,
		'callback' => $callback,
		'context'  => $context,
		'priority' => $priority,
	);
}

function is_admin() { return false; }
function is_singular() { return true; }
function get_the_ID() { return 60; }
$queried_id = 60;
function get_queried_object_id() { global $queried_id; return $queried_id; }
function is_front_page() { return false; }
function get_option( $name ) { return ''; }

function language_attributes() { echo 'lang="en"'; }
function bloginfo( $name ) { echo 'UTF-8'; }
function get_bloginfo( $name ) { return 'TEDx Regensburg'; }
function wp_head() {}
function wp_body_open() {}
function get_header() {}
function get_footer() {}
function body_class( $class = '' ) {
	$classes = array_filter( explode( ' ', $class ) );
	$classes = apply_filters( 'body_class', $classes );
	echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
}

function has_nav_menu( $location ) { return false; }
function home_url( $path = '' ) { return 'https://example.test' . $path; }
$parsed_blocks = array();
function parse_blocks( $content ) {
	global $parsed_blocks;
	if ( isset( $parsed_blocks[ $content ] ) ) {
		return $parsed_blocks[ $content ];
	}
	if ( false !== strpos( $content, '"align":"full"' ) || false !== strpos( $content, 'alignfull' ) ) {
		return array( array( 'blockName' => 'tedx/floorplan', 'attrs' => array( 'align' => 'full' ), 'innerBlocks' => array() ) );
	}
	return array();
}

$remaining = true;
function have_posts() { global $remaining; return $remaining; }
function the_post() { global $remaining; $remaining = false; }
function the_ID() { echo 60; }
function post_class( $classes ) { echo 'class="' . esc_attr( $classes ) . '"'; }
function the_title() { echo 'Page With Thumbnail'; }
function get_the_title( $id = 0 ) { return 'Page With Thumbnail'; }

$has_thumbnail_flag = false;
$post_thumbnail_id = 0;
function has_post_thumbnail( $id = null ) { global $has_thumbnail_flag; return $has_thumbnail_flag; }
function get_post_thumbnail_id( $id = null ) { global $post_thumbnail_id; return $post_thumbnail_id; }
function the_post_thumbnail( $size, $attrs = array() ) {
	$class = is_array( $attrs ) && isset( $attrs['class'] ) ? $attrs['class'] : '';
	echo '<img alt="Featured image" class="' . esc_attr( $class ) . '" />';
}
function get_the_post_thumbnail( $id, $size, $attrs = array() ) {
	$class = is_array( $attrs ) && isset( $attrs['class'] ) ? $attrs['class'] : '';
	return '<img alt="Featured image" class="' . esc_attr( $class ) . '" />';
}
function set_post_thumbnail( $post_id, $thumb_id ) {
	global $post_thumbnail_id, $has_thumbnail_flag;
	$post_thumbnail_id = $thumb_id;
	$has_thumbnail_flag = true;
	return true;
}
function the_content() { echo '<p>Main page content here.</p>'; }

// Require template tags and page options
require_once dirname( __DIR__ ) . '/inc/template-tags.php';
require_once dirname( __DIR__ ) . '/inc/page-options.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

// -------------------------------------------------------------
// Test 1: Post meta registration
// -------------------------------------------------------------
tedx_register_page_meta();
check( isset( $registered_meta['page']['_tedx_page_thumbnail_id'] ), '_tedx_page_thumbnail_id meta registered.' );
check( isset( $registered_meta['page']['_tedx_page_thumbnail_url'] ), '_tedx_page_thumbnail_url meta registered.' );
check( isset( $registered_meta['page']['_tedx_page_thumbnail_top'] ), '_tedx_page_thumbnail_top meta registered.' );

// -------------------------------------------------------------
// Test 2: Helper function tedx_page_has_top_thumbnail()
// -------------------------------------------------------------
$queried_id = 60;
$post_meta[60] = array();
$has_thumbnail_flag = false;

check( false === tedx_page_has_top_thumbnail(), 'No thumbnail media returns false.' );
check( false === tedx_page_has_top_thumbnail( 60 ), 'Explicit post ID with no thumbnail returns false.' );

// With standard featured image and top enabled
$has_thumbnail_flag = true;
$post_meta[60]['_tedx_page_thumbnail_top'] = '1';
check( true === tedx_page_has_top_thumbnail( 60 ), 'Standard featured image with _tedx_page_thumbnail_top="1" returns true.' );

// With standard featured image but top disabled ('0')
$post_meta[60]['_tedx_page_thumbnail_top'] = '0';
check( false === tedx_page_has_top_thumbnail( 60 ), 'Standard featured image with _tedx_page_thumbnail_top="0" returns false.' );

// With custom thumbnail ID
$has_thumbnail_flag = false;
$post_meta[60] = array( '_tedx_page_thumbnail_id' => 99 );
check( true === tedx_page_has_top_thumbnail( 60 ), 'Custom thumbnail ID defaults to top display.' );

// With custom thumbnail URL
$post_meta[60] = array( '_tedx_page_thumbnail_url' => 'https://example.test/banner.jpg' );
check( true === tedx_page_has_top_thumbnail( 60 ), 'Custom thumbnail URL defaults to top display.' );

// Filter hook override
add_filter( 'tedx_page_has_top_thumbnail', function( $enabled, $post_id ) {
	if ( 77 === $post_id ) {
		return true;
	}
	return $enabled;
}, 10, 2 );
check( true === tedx_page_has_top_thumbnail( 77 ), 'Filter hook can override top thumbnail display.' );

// -------------------------------------------------------------
// Test 3: Meta box rendering
// -------------------------------------------------------------
$post_obj = (object) array( 'ID' => 60 );
$post_meta[60] = array();
$has_thumbnail_flag = false;

ob_start();
tedx_render_page_options_meta_box( $post_obj );
$box_html = ob_get_clean();

check( false !== strpos( $box_html, 'id="tedx_page_thumbnail_top"' ), 'Meta box renders top thumbnail checkbox.' );
check( false !== strpos( $box_html, 'checked="checked"' ), 'Top thumbnail checkbox defaults to checked for new pages.' );
check( false !== strpos( $box_html, 'id="tedx_select_page_thumbnail_btn"' ), 'Meta box renders select image button.' );
check( false !== strpos( $box_html, 'id="tedx_remove_page_thumbnail_btn"' ), 'Meta box renders remove image button.' );
check( false !== strpos( $box_html, 'id="tedx_page_thumbnail_id"' ), 'Meta box renders hidden thumbnail ID field.' );
check( false !== strpos( $box_html, 'id="tedx_page_thumbnail_url"' ), 'Meta box renders hidden thumbnail URL field.' );

// Preview rendering when image is set
$post_meta[60]['_tedx_page_thumbnail_id'] = 456;
ob_start();
tedx_render_page_options_meta_box( $post_obj );
$box_html_preview = ob_get_clean();
check( false !== strpos( $box_html_preview, 'image-456.jpg' ), 'Meta box displays preview of selected image.' );

// -------------------------------------------------------------
// Test 4: Meta box saving
// -------------------------------------------------------------
// Nonce check
$_POST = array(
	'tedx_page_options_nonce' => 'invalid_nonce',
	'tedx_page_thumbnail_top' => '1',
	'tedx_page_thumbnail_id'  => '789',
);
$post_meta[60] = array();
tedx_save_page_options_meta_data( 60 );
check( empty( $post_meta[60]['_tedx_page_thumbnail_id'] ), 'Invalid nonce prevents saving thumbnail meta.' );

// Valid save
$_POST = array(
	'tedx_page_options_nonce'  => 'valid_nonce_tedx_save_page_options',
	'tedx_page_thumbnail_top'  => '1',
	'tedx_page_thumbnail_id'   => '789',
	'tedx_page_thumbnail_url'  => 'https://example.test/img.jpg',
);
$post_thumbnail_id = 0;
$has_thumbnail_flag = false;
tedx_save_page_options_meta_data( 60 );
check( '1' === ( $post_meta[60]['_tedx_page_thumbnail_top'] ?? '' ), 'Saves top thumbnail flag as "1".' );
check( 789 === ( $post_meta[60]['_tedx_page_thumbnail_id'] ?? 0 ), 'Saves thumbnail ID as integer.' );
check( 'https://example.test/img.jpg' === ( $post_meta[60]['_tedx_page_thumbnail_url'] ?? '' ), 'Saves thumbnail URL.' );

// Unchecking top thumbnail saves '0'
$_POST = array(
	'tedx_page_options_nonce' => 'valid_nonce_tedx_save_page_options',
	'tedx_page_thumbnail_id'  => '',
	'tedx_page_thumbnail_url' => '',
);
tedx_save_page_options_meta_data( 60 );
check( '0' === ( $post_meta[60]['_tedx_page_thumbnail_top'] ?? '' ), 'Unchecking top thumbnail updates meta to "0".' );
check( ! isset( $post_meta[60]['_tedx_page_thumbnail_id'] ), 'Emptying ID deletes meta.' );
check( ! isset( $post_meta[60]['_tedx_page_thumbnail_url'] ), 'Emptying URL deletes meta.' );

// -------------------------------------------------------------
// Test 5: page.php rendering with top thumbnail enabled
// -------------------------------------------------------------
$posts[60] = (object) array( 'post_type' => 'page', 'post_content' => 'Sample content' );
$post_meta[60] = array(
	'_tedx_page_thumbnail_top' => '1',
	'_tedx_page_thumbnail_id'  => 555,
);
$has_thumbnail_flag = true;
$post_thumbnail_id = 555;

$remaining = true;
ob_start();
include dirname( __DIR__ ) . '/page.php';
$html = ob_get_clean();

check( false !== strpos( $html, 'tedx-page-thumbnail-header' ), 'Top thumbnail header wrapper is rendered.' );
check( false !== strpos( $html, 'pt-[60px] lg:pt-[70px]' ), 'Renders directly under the default nav-bar with pt-[60px] lg:pt-[70px].' );
check( false !== strpos( $html, 'image-555.jpg' ), 'Attachment image is rendered in top thumbnail.' );
check( false !== strpos( $html, 'w-full h-auto block' ), 'Original aspect ratio conserved via w-full h-auto block.' );
check( false !== strpos( $html, 'max-w-none' ), 'Image is unconstrained by container max-width.' );
check( false !== strpos( $html, 'pt-0 pb-16' ), 'Main container uses pt-0 below top thumbnail to remove padding to the first block.' );
check( false === strpos( $html, 'pt-8 md:pt-12' ), 'Main container does not have pt-8 md:pt-12 when top thumbnail is enabled.' );
check( false !== strpos( $html, 'tedx-page-has-top-thumb' ), 'Main container includes qualifying class tedx-page-has-top-thumb.' );
check( false !== strpos( $html, 'bg-tedx-card rounded-3xl p-5 sm:p-6 md:p-12' ), 'Content card styling is preserved.' );
check( false !== strpos( $html, 'max-w-4xl mx-auto' ), 'Content container max-width is preserved.' );
check( false !== strpos( $html, 'Page With Thumbnail' ), 'Page title is preserved.' );
check( false !== strpos( $html, 'Main page content here.' ), 'Page content is preserved.' );
check( false === strpos( $html, 'aspect-video' ), 'aspect-video crop is NOT used for top thumbnail.' );

// -------------------------------------------------------------
// Test 6: page.php rendering with hidden navbar
// -------------------------------------------------------------
$post_meta[60]['_tedx_hide_navbar'] = '1';
$remaining = true;
ob_start();
include dirname( __DIR__ ) . '/page.php';
$html_no_navbar = ob_get_clean();

check( false !== strpos( $html_no_navbar, 'tedx-page-thumbnail-header w-full pt-0' ), 'When navbar is hidden, top thumbnail renders with pt-0.' );

// -------------------------------------------------------------
// Test 7: page.php when top thumbnail is disabled (legacy fallback)
// -------------------------------------------------------------
$post_meta[60]['_tedx_page_thumbnail_top'] = '0';
$post_meta[60]['_tedx_hide_navbar'] = '0';
$has_thumbnail_flag = true;

$remaining = true;
ob_start();
include dirname( __DIR__ ) . '/page.php';
$html_legacy = ob_get_clean();

check( false === strpos( $html_legacy, 'tedx-page-thumbnail-header' ), 'When top thumbnail is disabled, top header is omitted.' );
check( false !== strpos( $html_legacy, 'pt-28 md:pt-32' ), 'Standard pt-28 md:pt-32 padding is used.' );
check( false !== strpos( $html_legacy, 'aspect-video' ), 'Legacy inside-card thumbnail with aspect-video is used.' );

// -------------------------------------------------------------
// Test 8: Full-width page with top thumbnail has pt-0 and no gap
// -------------------------------------------------------------
$post_meta[70] = array(
	'_tedx_page_thumbnail_top' => '1',
	'_tedx_page_thumbnail_id'  => 555,
);
$posts[70] = (object) array(
	'post_type'    => 'page',
	'post_content' => '<!-- wp:tedx/floorplan {"align":"full"} /-->',
);
$queried_id = 70;
$remaining = true;
ob_start();
include dirname( __DIR__ ) . '/page.php';
$html_full_width = ob_get_clean();
$queried_id = 60;

check( false !== strpos( $html_full_width, 'pt-0 pb-16 bg-tedx-dark tedx-full-width-page tedx-page-has-top-thumb' ), 'Full-width page with top thumbnail renders pt-0 on main container.' );

// -------------------------------------------------------------
// Test 9: Body class helper adds tedx-has-top-thumbnail
// -------------------------------------------------------------
$post_meta[60]['_tedx_page_thumbnail_top'] = '1';
check( function_exists( 'tedx_page_thumbnail_body_class' ), 'tedx_page_thumbnail_body_class is registered.' );
$classes = tedx_page_thumbnail_body_class( array( 'page' ) );
check( in_array( 'tedx-has-top-thumbnail', $classes, true ), 'body_class includes tedx-has-top-thumbnail.' );

// -------------------------------------------------------------
// Test 10: CSS rules clear margin/border under top thumbnail without overriding card/block padding
// -------------------------------------------------------------
$css = file_get_contents( dirname( __DIR__ ) . '/assets/css/input.css' );
check( false !== strpos( $css, '.tedx-page-has-top-thumb .tedx-full-width-page-content > section:first-child' ), 'CSS contains rule targeting first section on full-width top thumbnail pages.' );
check( false !== strpos( $css, 'border-top-width: 0 !important' ), 'CSS resets border-top-width to 0 !important for first block below top thumbnail.' );
check( false === strpos( $css, '.tedx-page-has-top-thumb section.tedx-timetable-v2-block' ), 'Global stylesheet does not broadly target timetable-v2 blocks under top thumbnail.' );
check( false !== strpos( $css, '.tedx-timetable-v2-card' ) && false !== strpos( $css, 'padding: 20px !important' ), 'CSS enforces timetable-v2 card padding strictly.' );
check( false !== strpos( $css, '.tedx-what-to-expect-card' ) && false !== strpos( $css, 'padding: 1rem !important' ), 'CSS enforces what-to-expect card padding strictly.' );
check( false !== strpos( $css, '.tedx-speaker-card' ) && false !== strpos( $css, 'padding: 1.5rem !important' ), 'CSS enforces speaker card padding strictly.' );

echo 'All page thumbnail tests passed!' . PHP_EOL;
