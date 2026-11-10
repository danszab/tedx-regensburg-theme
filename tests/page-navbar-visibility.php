<?php
/**
 * Test page navigation bar visibility options and rendering: php tests/page-navbar-visibility.php.
 */
define( 'ABSPATH', __DIR__ );

$posts = array();
$post_meta = array();
$filters = array();
$actions = array();
$meta_boxes = array();
$current_user_can_edit = true;
$is_autosave = false;
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
function wp_get_attachment_image_url( $id, $size ) { return ''; }

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
function get_the_ID() { return 50; }
$queried_id = 50;
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
function parse_blocks( $content ) { return array(); }

function have_posts() { global $remaining; return $remaining; }
function the_post() { global $remaining; $remaining = false; }
function the_ID() { echo 50; }
function post_class( $classes ) { echo 'class="' . esc_attr( $classes ) . '"'; }
function the_title() { echo 'Page Title'; }
function has_post_thumbnail() { return false; }
function the_content() { echo '<p>Content</p>'; }

// Require template tags and page options
require dirname( __DIR__ ) . '/inc/template-tags.php';
require dirname( __DIR__ ) . '/inc/page-options.php';

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

// -------------------------------------------------------------
// Test 1: Helper function tedx_is_navbar_hidden()
// -------------------------------------------------------------
$queried_id = 50;
$post_meta[50] = array();
check( false === tedx_is_navbar_hidden(), 'Default page without meta has navigation visible.' );
check( false === tedx_is_navbar_hidden( 50 ), 'Explicit post ID with no meta has navigation visible.' );

$post_meta[50]['_tedx_hide_navbar'] = '1';
check( true === tedx_is_navbar_hidden(), 'Page with _tedx_hide_navbar="1" hides navigation.' );
check( true === tedx_is_navbar_hidden( 50 ), 'Explicit post ID with _tedx_hide_navbar="1" hides navigation.' );

$post_meta[50]['_tedx_hide_navbar'] = true;
check( true === tedx_is_navbar_hidden( 50 ), 'Boolean true meta hides navigation.' );

$post_meta[50]['_tedx_hide_navbar'] = '0';
check( false === tedx_is_navbar_hidden( 50 ), '_tedx_hide_navbar="0" does not hide navigation.' );

$post_meta[50]['_tedx_hide_navbar'] = false;
check( false === tedx_is_navbar_hidden( 50 ), 'Boolean false meta does not hide navigation.' );

$post_meta[50]['_tedx_hide_navbar'] = '';
check( false === tedx_is_navbar_hidden( 50 ), 'Empty meta does not hide navigation.' );

// Page template check
$post_meta[50] = array();
$page_templates[50] = 'page-no-navigation.php';
check( true === tedx_is_navbar_hidden( 50 ), 'page-no-navigation.php template hides navigation.' );
$page_templates[50] = '';

// Filter override
add_filter( 'tedx_hide_navbar', function( $is_hidden, $post_id ) {
	if ( 99 === $post_id ) {
		return true;
	}
	return $is_hidden;
}, 10, 2 );
check( true === tedx_is_navbar_hidden( 99 ), 'Filter hook tedx_hide_navbar can override visibility.' );

// -------------------------------------------------------------
// Test 2: Meta box registration and rendering
// -------------------------------------------------------------
tedx_add_page_options_meta_box();
check( isset( $meta_boxes['page']['tedx_page_display_settings'] ), 'Meta box is registered on page post type.' );
check( 'side' === $meta_boxes['page']['tedx_page_display_settings']['context'], 'Meta box is registered in side context.' );

// Render unchecked
$post_obj = (object) array( 'ID' => 50 );
$post_meta[50] = array();
ob_start();
tedx_render_page_options_meta_box( $post_obj );
$box_html = ob_get_clean();
check( false !== strpos( $box_html, 'id="tedx_hide_navbar"' ), 'Meta box renders checkbox input.' );
check( false !== strpos( $box_html, 'name="tedx_page_options_nonce"' ), 'Meta box renders security nonce.' );
check( ! preg_match( '/id="tedx_hide_navbar"[^>]*checked/i', $box_html ), 'Navbar checkbox is unchecked when meta is not set.' );
check( false !== strpos( $box_html, 'Hide navigation bar' ), 'Meta box contains descriptive label.' );

// Render checked
$post_meta[50]['_tedx_hide_navbar'] = '1';
ob_start();
tedx_render_page_options_meta_box( $post_obj );
$box_html_checked = ob_get_clean();
check( false !== strpos( $box_html_checked, 'checked="checked"' ), 'Checkbox is checked when meta is "1".' );

// -------------------------------------------------------------
// Test 3: Save post handler
// -------------------------------------------------------------
// Invalid nonce should not save
$_POST = array(
	'tedx_page_options_nonce' => 'invalid_nonce',
	'tedx_hide_navbar'        => '1',
);
$post_meta[50] = array();
tedx_save_page_options_meta_data( 50 );
check( empty( $post_meta[50]['_tedx_hide_navbar'] ), 'Invalid nonce prevents updating meta.' );

// Valid save (checked)
$_POST = array(
	'tedx_page_options_nonce' => 'valid_nonce_tedx_save_page_options',
	'tedx_hide_navbar'        => '1',
);
tedx_save_page_options_meta_data( 50 );
check( '1' === ( $post_meta[50]['_tedx_hide_navbar'] ?? '' ), 'Valid submission with checkbox updates meta to "1".' );

// Valid save (unchecked -> deletes meta)
$_POST = array(
	'tedx_page_options_nonce' => 'valid_nonce_tedx_save_page_options',
);
tedx_save_page_options_meta_data( 50 );
check( ! isset( $post_meta[50]['_tedx_hide_navbar'] ), 'Unchecking checkbox deletes meta.' );

// User cannot edit
$current_user_can_edit = false;
$_POST = array(
	'tedx_page_options_nonce' => 'valid_nonce_tedx_save_page_options',
	'tedx_hide_navbar'        => '1',
);
tedx_save_page_options_meta_data( 50 );
check( empty( $post_meta[50]['_tedx_hide_navbar'] ), 'Unauthorized user cannot update meta.' );
$current_user_can_edit = true;

// -------------------------------------------------------------
// Test 4: Body class filter
// -------------------------------------------------------------
$post_meta[50] = array();
$classes = tedx_hide_navbar_body_class( array( 'page', 'custom-class' ) );
check( ! in_array( 'tedx-no-navbar', $classes, true ), 'Normal page does not receive tedx-no-navbar body class.' );

$post_meta[50]['_tedx_hide_navbar'] = '1';
$classes_hidden = tedx_hide_navbar_body_class( array( 'page', 'custom-class' ) );
check( in_array( 'tedx-no-navbar', $classes_hidden, true ), 'Hidden navbar page receives tedx-no-navbar body class.' );
check( in_array( 'no-navbar', $classes_hidden, true ), 'Hidden navbar page receives no-navbar body class.' );

// -------------------------------------------------------------
// Test 5: header.php rendering
// -------------------------------------------------------------
// Visible header
$post_meta[50] = array();
ob_start();
include dirname( __DIR__ ) . '/header.php';
$header_visible = ob_get_clean();
check( false !== strpos( $header_visible, 'id="site-header"' ), 'Default page renders site-header.' );
check( false !== strpos( $header_visible, 'skip-link' ), 'Default page renders skip-to-content link.' );

// Hidden header
$post_meta[50]['_tedx_hide_navbar'] = '1';
ob_start();
include dirname( __DIR__ ) . '/header.php';
$header_hidden = ob_get_clean();
check( false === strpos( $header_hidden, 'id="site-header"' ), 'Page with hidden navbar omits site-header completely.' );
check( false === strpos( $header_hidden, 'skip-link' ), 'Page with hidden navbar omits skip-to-content link.' );

// -------------------------------------------------------------
// Test 6: page.php padding adjustment
// -------------------------------------------------------------
$posts[50] = (object) array( 'post_type' => 'page', 'post_content' => 'Sample content' );

// Navbar visible: standard padding pt-28 md:pt-32
$post_meta[50] = array();
$remaining = true;
ob_start();
include dirname( __DIR__ ) . '/page.php';
$page_visible = ob_get_clean();
check( false !== strpos( $page_visible, 'pt-28 md:pt-32' ), 'Visible navbar page keeps pt-28 md:pt-32 padding.' );

// Navbar hidden: adjusted padding pt-8 md:pt-12
$post_meta[50]['_tedx_hide_navbar'] = '1';
$remaining = true;
ob_start();
include dirname( __DIR__ ) . '/page.php';
$page_hidden = ob_get_clean();
check( false !== strpos( $page_hidden, 'pt-8 md:pt-12' ), 'Hidden navbar page uses adjusted pt-8 md:pt-12 padding.' );
check( false === strpos( $page_hidden, 'pt-28 md:pt-32' ), 'Hidden navbar page does not have large pt-28 md:pt-32 padding.' );

// -------------------------------------------------------------
// Test 7: Header logo preload is suppressed when navbar is hidden
// -------------------------------------------------------------
$theme_mods['custom_logo'] = 123;
$post_meta[50]['_tedx_hide_navbar'] = '1';
ob_start();
tedx_preload_header_logo();
$preload_output = ob_get_clean();
check( '' === trim( $preload_output ), 'Header logo preload is suppressed when navigation bar is hidden.' );

// -------------------------------------------------------------
// Test 8: Autocollapse on scroll styles and script
// -------------------------------------------------------------
$compiled_css = file_get_contents( dirname( __DIR__ ) . '/assets/css/style.css' );
check( false !== strpos( $compiled_css, 'header-collapsed{transform:translateY(-100%)' ), 'Compiled CSS includes header-collapsed transform: translateY(-100%).' );
check( false !== strpos( $compiled_css, 'header-collapsed .tedx-live-ticker-floating-wrapper' ), 'Compiled CSS includes ticker docking adjustment on header-collapsed.' );

$main_js = file_get_contents( dirname( __DIR__ ) . '/assets/js/main.js' );
check( false !== strpos( $main_js, 'header.classList.add(\'header-collapsed\')' ), 'main.js adds header-collapsed on downscroll.' );
check( false !== strpos( $main_js, 'header.classList.remove(\'header-collapsed\')' ), 'main.js removes header-collapsed on upscroll.' );

echo 'All navbar visibility tests passed!' . PHP_EOL;
