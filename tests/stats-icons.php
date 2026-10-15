<?php
/**
 * Standalone custom Stats icon checks: php tests/stats-icons.php.
 */
define( 'TEDX_DIR', dirname( __DIR__ ) );
define( 'TEDX_URI', 'https://example.test/theme' );
function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return $value; }
function __( $value, $domain ) { return $value; }
function sanitize_html_class( $value ) { return $value; }
function get_block_wrapper_attributes( $attrs ) { return 'class="' . esc_attr( $attrs['class'] ) . '"'; }
function get_post_mime_type( $id ) { return 12 === $id ? 'image/svg+xml' : 'image/png'; }
function wp_get_attachment_url( $id ) { return 'https://example.test/uploads/icon.svg'; }
function tedx_block_editor_notice( $message ) { return '<p>' . esc_html( $message ) . '</p>'; }
function tedx_get_icon( $key, $classes ) { return '<svg data-built-in="' . esc_attr( $key ) . '"></svg>'; }
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}
function render_stats( $item ) {
	$attributes = array( 'items' => array( array_merge( array( 'value' => '100+', 'label' => 'Guests' ), $item ) ) );
	ob_start();
	include TEDX_DIR . '/blocks/tedx-stats/render.php';
	return ob_get_clean();
}
$html = render_stats( array( 'icon' => 'custom', 'customIconId' => 12 ) );
check( false !== strpos( $html, 'class="tedx-stat-custom-icon"' ), 'Valid custom SVG uses the green mask class.' );
check( false !== strpos( $html, '--tedx-stat-icon: url(&quot;https://example.test/uploads/icon.svg&quot;)' ), 'Mask URL is escaped and uses the attachment URL.' );
check( false === strpos( $html, '<svg' ), 'Uploaded SVG markup is not inlined.' );
check( false !== strpos( $html, '100+' ) && false !== strpos( $html, 'Guests' ), 'Stat value and label remain unchanged.' );
foreach ( array( 0, 13 ) as $id ) {
	$html = render_stats( array( 'icon' => 'custom', 'customIconId' => $id ) );
	check( false === strpos( $html, 'class="tedx-stat-custom-icon"' ), 'Missing and non-SVG attachments are not rendered as masks.' );
	check( false !== strpos( $html, 'Select an SVG' ), 'Invalid custom icon shows an editor notice.' );
}
$html = render_stats( array( 'icon' => 'groups', 'customIconId' => 12 ) );
check( false !== strpos( $html, 'data-built-in="groups"' ), 'Switching back to a built-in icon ignores the saved custom selection.' );
