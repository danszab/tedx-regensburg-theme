<?php
/**
 * Standalone media priority checks: php tests/media-priorities.php.
 */
define( 'ABSPATH', __DIR__ );
$mods = array( 'custom_logo' => 12, 'tedx_mobile_logo' => '/mobile-logo.svg' );
$actions = array();
$filters = array();
$removed = array();
$attached = array();
$url_ids = array();
define( 'TEDX_URI', 'https://example.test/theme' );
function get_theme_mod( $key, $default = '' ) { return $GLOBALS['mods'][$key] ?? $default; }
function wp_get_attachment_image_url( $id, $size ) { return 12 === $id ? '/desktop-logo.svg' : false; }
function attachment_url_to_postid( $url ) { return $GLOBALS['url_ids'][$url] ?? 0; }
function get_attached_file( $id ) { return $GLOBALS['attached'][$id] ?? ''; }
function remove_action( $hook, $callback, $priority = 10 ) { $GLOBALS['removed'][] = $hook . ':' . $callback; }
function remove_filter( $hook, $callback ) { $GLOBALS['removed'][] = $hook . ':' . $callback; }
function get_bloginfo( $key ) { return 'TEDx & Regensburg'; }
function home_url( $path ) { return 'https://example.test' . $path; }
function is_front_page() { return true; }
function add_action( $hook, $callback, $priority = 10 ) { $GLOBALS['actions'][$callback] = array( $hook, $priority ); }
function add_filter( $hook, $callback, $priority = 10, $args = 1 ) { $GLOBALS['filters'][$callback] = array( $hook, $priority, $args ); }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return esc_attr( $value ); }
function __( $value, $domain = '' ) { return $value; }
function sanitize_html_class( $value ) { return $value; }
function get_block_wrapper_attributes( $attrs ) { return 'class="' . esc_attr( $attrs['class'] ) . '"'; }
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}
require dirname( __DIR__ ) . '/inc/template-tags.php';
require dirname( __DIR__ ) . '/inc/hero-media.php';
require dirname( __DIR__ ) . '/inc/performance.php';

check( 'wp_head' === $actions['tedx_preload_header_logo'][0] && $actions['tedx_preload_header_logo'][1] < $actions['tedx_preload_hero_poster'][1], 'Logo preloads are registered before hero poster preloads.' );
check( array( 'wp_get_loading_optimization_attributes', 10, 4 ) === $filters['tedx_image_loading_priority'], 'WordPress image optimization filter receives all four arguments.' );

foreach ( array( 'both', 'desktop', 'mobile', 'none', 'invalid' ) as $scenario ) {
	$mods['custom_logo'] = in_array( $scenario, array( 'both', 'desktop' ), true ) ? 12 : ( 'invalid' === $scenario ? 99 : 0 );
	$mods['tedx_mobile_logo'] = in_array( $scenario, array( 'both', 'mobile' ), true ) ? '/mobile-logo.svg' : '';
	ob_start();
	tedx_preload_header_logo();
	$preloads = ob_get_clean();
	$count = in_array( $scenario, array( 'both', 'desktop' ), true ) ? 2 : ( 'mobile' === $scenario ? 1 : 0 );
	check( $count === substr_count( $preloads, 'fetchpriority="high"' ), $scenario . ': only available logos are preloaded.' );
	if ( 'both' === $scenario ) {
		check( false !== strpos( $preloads, 'href="/mobile-logo.svg" media="(max-width: 1023px)"' ) && false !== strpos( $preloads, 'href="/desktop-logo.svg" media="(min-width: 1024px)"' ), 'Logo preload media queries match the navbar lg breakpoint.' );
	} elseif ( 'desktop' === $scenario ) {
		check( 2 === substr_count( $preloads, 'href="/desktop-logo.svg"' ), 'Desktop-only logo is preloaded on either viewport.' );
	}
	ob_start();
	tedx_regensburg_logo( 'h-4 lg:h-6 w-auto', true, true );
	$html = ob_get_clean();
	$image_count = 'both' === $scenario ? 2 : ( in_array( $scenario, array( 'desktop', 'mobile' ), true ) ? 1 : 0 );
	check( $image_count === substr_count( $html, 'fetchpriority="high"' ) && $image_count === substr_count( $html, 'loading="eager"' ), $scenario . ': header logo images are eager and high priority.' );
	check( false !== strpos( $html, 'rel="home"' ) && false !== strpos( $html, 'TEDx &amp; Regensburg' ), $scenario . ': logo link and escaped accessible name are retained.' );
	if ( in_array( $scenario, array( 'none', 'invalid' ), true ) ) {
		check( false !== strpos( $html, 'Regensburg</span>' ), $scenario . ': text logo fallback is preserved.' );
	}
	ob_start();
	tedx_regensburg_logo( 'h-7 w-auto' );
	$normal = ob_get_clean();
	check( $image_count === substr_count( $normal, 'fetchpriority="auto"' ) && false === strpos( $normal, 'fetchpriority="high"' ), $scenario . ': non-header logo copies have normal priority.' );
}

$optimized = array( 'loading' => 'lazy', 'decoding' => 'async', 'fetchpriority' => 'high' );
$normal = tedx_image_loading_priority( $optimized, 'img', array(), 'the_content' );
check( array( 'loading' => 'lazy', 'decoding' => 'async', 'fetchpriority' => 'auto' ) === $normal, 'Unrelated content images cannot be automatically promoted; other optimizations are preserved.' );
foreach ( array( 'high', 'auto', 'low' ) as $priority ) {
	$result = tedx_image_loading_priority( $optimized, 'img', array( 'fetchpriority' => $priority ), 'wp_get_attachment_image' );
	check( $priority === $result['fetchpriority'], 'Explicit ' . $priority . ' image hints are respected.' );
}
check( $optimized === tedx_image_loading_priority( $optimized, 'iframe', array(), 'the_content' ), 'Non-image loading behavior is unchanged.' );

$attributes = array(
	'linkedEventId' => 0,
	'title' => 'Event',
	'eyebrow' => '',
	'sectionId' => 'event-pitch',
	'cardImage' => array( 'url' => '/event.jpg', 'alt' => 'Event & theme' ),
);
ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-event/render.php';
$html = ob_get_clean();
check( 1 === preg_match( '/<img[^>]+src="\/event.jpg"[^>]+loading="eager"[^>]+fetchpriority="high"[^>]+decoding="async"/', $html ), 'Event image is eager and high priority without a head preload.' );
check( false !== strpos( $html, 'alt="Event &amp; theme"' ), 'Event image alternative text stays escaped.' );
$attributes['cardImage'] = array();
ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-event/render.php';
check( false === strpos( ob_get_clean(), '<img' ), 'Missing event image retains its CSS fallback without a request.' );

// Inline header SVG logo.
$tmp = sys_get_temp_dir() . '/tedx-logo-test-' . getmypid();
@mkdir( $tmp );
$logo_svg = '<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" viewBox="0 0 2135 260" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" onload="alert(1)"><clipPath id="_clip1"><rect width="10" height="10"/></clipPath><script>alert(2)</script><a href="javascript:alert(3)"><rect/></a><image xlink:href="https://evil.test/x.png"/><g clip-path="url(#_clip1)"><path d="M0,0l1,1Z" style="fill:#eb0028"/></g><use xlink:href="#_clip1"/><foreignObject><div/></foreignObject><set attributeName="href" to="javascript:alert(4)"/></svg>';
file_put_contents( $tmp . '/desktop.svg', $logo_svg );
file_put_contents( $tmp . '/mobile.svg', $logo_svg );
file_put_contents( $tmp . '/styled.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"><style>.a{fill:red}</style></svg>' );
file_put_contents( $tmp . '/logo.png', 'png' );
$attached = array( 12 => $tmp . '/desktop.svg', 13 => $tmp . '/mobile.svg' );
$url_ids = array( '/uploads/mobile-inline.svg' => 13 );
$mods = array( 'custom_logo' => 12, 'tedx_mobile_logo' => '/uploads/mobile-inline.svg' );

ob_start();
tedx_regensburg_logo( 'h-4 lg:h-6 w-auto', true, true );
$html = ob_get_clean();
check( 2 === substr_count( $html, '<svg ' ) && false === strpos( $html, '<img' ), 'Local SVG header logos are inlined, so the LCP logo needs no request.' );
check( false === stripos( $html, 'script' ) && false === stripos( $html, 'javascript' ) && false === stripos( $html, 'onload' ) && false === stripos( $html, 'foreignObject' ) && false === strpos( $html, 'evil.test' ) && false === strpos( $html, '<set' ) && false === strpos( $html, '<a href="javascript' ) && false === strpos( $html, '<!DOCTYPE' ), 'Inline SVG is sanitized (scripts, handlers, links, external refs, animations, doctype removed).' );
check( false !== strpos( $html, 'id="tedx-logo-desktop-_clip1"' ) && false !== strpos( $html, 'url(#tedx-logo-desktop-_clip1)' ) && false !== strpos( $html, 'xlink:href="#tedx-logo-desktop-_clip1"' ) && false !== strpos( $html, 'id="tedx-logo-mobile-_clip1"' ), 'Internal references survive with per-instance ID prefixes (no duplicate IDs).' );
check( false !== strpos( $html, 'width="2135" height="260"' ) && false === strpos( $html, '100%' ), 'Intrinsic size comes from the viewBox.' );
check( false !== strpos( $html, 'role="img"' ) && false !== strpos( $html, 'aria-label="TEDx &amp; Regensburg"' ) && false !== strpos( $html, 'rel="home"' ), 'Inline logo keeps an escaped accessible name and home link.' );
check( false !== strpos( $html, 'class="h-4 lg:h-6 w-auto object-contain block"' ) && false !== strpos( $html, 'class="h-6 w-auto object-contain block lg:hidden"' ), 'Responsive desktop/mobile logo classes are preserved.' );
ob_start();
tedx_preload_header_logo();
check( '' === ob_get_clean(), 'Inlined logos are not preloaded as images.' );
ob_start();
tedx_regensburg_logo( 'h-7 w-auto' );
$footer = ob_get_clean();
check( false === strpos( $footer, '<svg' ) && 2 === substr_count( $footer, 'fetchpriority="auto"' ), 'Non-header logo copies stay cacheable images at normal priority.' );

foreach ( array( 'styled.svg' => 'SVG with embedded <style>', 'logo.png' => 'Raster logo', 'missing.svg' => 'Missing file' ) as $file => $label ) {
	$attached = array( 12 => $tmp . '/' . $file );
	$mods = array( 'custom_logo' => 12, 'tedx_mobile_logo' => '' );
	ob_start();
	tedx_regensburg_logo( 'h-4 lg:h-6 w-auto', true, true );
	$html = ob_get_clean();
	check( false === strpos( $html, '<svg' ) && 1 === preg_match( '/<img[^>]+loading="eager"[^>]+fetchpriority="high"/', $html ), $label . ' falls back to an eager high-priority image.' );
	ob_start();
	tedx_preload_header_logo();
	check( 2 === substr_count( ob_get_clean(), 'rel="preload" as="image"' ), $label . ' fallback image is preloaded.' );
}
array_map( 'unlink', glob( $tmp . '/*' ) );
rmdir( $tmp );

// Self-hosted font and removed critical-path assets.
check( 'wp_head' === $actions['tedx_preload_fonts'][0] && $actions['tedx_preload_fonts'][1] > $actions['tedx_preload_hero_poster'][1], 'Font preload follows the logo and hero preloads.' );
ob_start();
tedx_preload_fonts();
$font = ob_get_clean();
check( false !== strpos( $font, 'as="font" type="font/woff2" href="https://example.test/theme/assets/fonts/inter-latin-var.woff2" crossorigin' ), 'Latin Inter subset is preloaded with CORS.' );
check( is_readable( dirname( __DIR__ ) . '/assets/fonts/inter-latin-var.woff2' ) && 'wOF2' === file_get_contents( dirname( __DIR__ ) . '/assets/fonts/inter-latin-var.woff2', false, null, 0, 4 ), 'Preloaded font file exists and is WOFF2.' );
check( is_readable( dirname( __DIR__ ) . '/assets/fonts/Inter-OFL.txt' ), 'Inter OFL license ships with the font.' );
$compiled = file_get_contents( dirname( __DIR__ ) . '/assets/css/style.css' );
check( 2 === substr_count( $compiled, '@font-face' ) && false !== strpos( $compiled, 'font-display:swap' ) && false !== strpos( $compiled, '../fonts/inter-latin-var.woff2' ), 'Compiled CSS declares self-hosted Inter with font-display swap.' );
check( false !== strpos( $compiled, '--tedx-navbar-logo-height' ) && false !== strpos( $compiled, 'tedx-full-width-page-content>.alignfull' ), 'Former root style.css rules are part of the single compiled stylesheet.' );
$theme_php = '';
foreach ( array_merge( glob( dirname( __DIR__ ) . '/*.php' ), glob( dirname( __DIR__ ) . '/inc/*.php' ), glob( dirname( __DIR__ ) . '/template-parts/*.php' ) ) as $file ) {
	$theme_php .= file_get_contents( $file );
}
check( false === strpos( $theme_php, 'fonts.googleapis.com' ) && false === strpos( $theme_php, 'fonts.gstatic.com' ), 'No Google Fonts requests or preconnects remain.' );
check( 1 === preg_match( "/wp_enqueue_script\( 'tedx-main-js'[^;]+'strategy' => 'defer'/", $theme_php ) && 1 === preg_match( '/if \( is_child_theme\(\) \)/', $theme_php ), 'main.js is deferred and the metadata-only root stylesheet is not enqueued.' );
$gallery = json_decode( file_get_contents( dirname( __DIR__ ) . '/blocks/tedx-gallery/block.json' ), true );
check( 'file:./script.js' === $gallery['viewScript'] && ! isset( $gallery['script'] ), 'Gallery script is front-end only (viewScript).' );
check( false !== strpos( $theme_php, "wp_script_add_data( \$handle, 'strategy', 'defer' )" ), 'Block view scripts are deferred.' );
check( 'init' === $actions['tedx_disable_emojis'][0], 'Emoji cleanup is registered on init.' );
tedx_disable_emojis();
check( in_array( 'wp_head:print_emoji_detection_script', $removed, true ) && in_array( 'wp_print_styles:print_emoji_styles', $removed, true ) && in_array( 'wp_enqueue_scripts:wp_enqueue_emoji_styles', $removed, true ), 'Emoji detection script and styles are removed.' );
