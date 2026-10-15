<?php
/**
 * Standalone footer rendering checks: php tests/footer-links.php.
 */
define( 'ABSPATH', __DIR__ );

$show_links = true;
$has_menu   = false;
$menu_items = array();

function tedx_mod( $name, $default ) { return $default; }
function get_theme_mod( $name, $default ) {
	global $show_links;
	return 'tedx_show_footer_links' === $name ? $show_links : $default;
}
function tedx_regensburg_logo( $classes ) {}
function tedx_get_icon( $name, $classes ) { return ''; }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $value ) { return esc_attr( $value ); }
function esc_attr_e( $value, $domain ) { echo esc_attr( $value ); }
function esc_html_e( $value, $domain ) { echo esc_attr( $value ); }
function home_url( $path ) { return 'https://example.test' . $path; }
function has_nav_menu( $location ) {
	global $has_menu;
	if ( 'footer' !== $location ) {
		throw new RuntimeException( 'Unexpected menu location.' );
	}
	return $has_menu;
}
function wp_nav_menu( $args ) {
	global $menu_items;
	if ( 'footer' !== $args['theme_location'] || false !== $args['fallback_cb'] ) {
		throw new RuntimeException( 'Footer menu settings changed unexpectedly.' );
	}
	echo '<ul>';
	foreach ( $menu_items as $item ) {
		echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_attr( $item['title'] ) . '</a></li>';
	}
	echo '</ul>';
}
function wp_footer() {}
function render_footer() {
	ob_start();
	include dirname( __DIR__ ) . '/footer.php';
	return ob_get_clean();
}
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

$html = render_footer();
check( false !== strpos( $html, '/imprint/' ) && false !== strpos( $html, '/privacy/' ), 'Unconfigured footer preserves default links.' );

$has_menu = true;
$menu_items = array(
	array( 'url' => '/impress/', 'title' => 'Impress' ),
	array( 'url' => '/legal/', 'title' => 'Legal' ),
);
$html = render_footer();
check( false !== strpos( $html, '>Impress</a>' ) && false !== strpos( $html, '>Legal</a>' ), 'Assigned footer menu renders administrator titles and URLs.' );
check( false === strpos( $html, '/imprint/' ), 'Assigned menu replaces hardcoded defaults.' );

$menu_items = array( array( 'url' => '/legal-updated/', 'title' => 'Updated Legal' ) );
$html = render_footer();
check( false === strpos( $html, '/impress/' ) && false !== strpos( $html, '/legal-updated/' ), 'Removed and edited menu items are reflected in the footer.' );

$menu_items = array();
$html = render_footer();
check( false === strpos( $html, '/imprint/' ) && false === strpos( $html, '/privacy/' ), 'Empty assigned menu does not reinsert default links.' );

foreach ( array( false, true ) as $has_menu ) {
	$show_links = false;
	$html = render_footer();
	check( false === strpos( $html, '<nav ' ), 'Visibility toggle hides navigation with or without an assigned menu.' );
	check( false !== strpos( $html, 'Newsletter' ) && false !== strpos( $html, 'All rights reserved.' ), 'Hiding links preserves other footer content.' );
}
