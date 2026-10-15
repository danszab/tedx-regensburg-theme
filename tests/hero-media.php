<?php
/**
 * Standalone hero media checks: php tests/hero-media.php.
 */
define( 'ABSPATH', __DIR__ );
$mods = array(
	'tedx_hero_video_poster' => '/desktop-original.jpg',
	'tedx_hero_video_mobile_poster' => '/mobile-original.jpg',
);
$front_page = true;
function tedx_mod( $key, $default = '' ) { return $GLOBALS['mods'][$key] ?? $default; }
function attachment_url_to_postid( $url ) { return '/desktop-original.jpg' === $url ? 1 : 2; }
function wp_get_attachment_image_url( $id, $size ) { return '/' . ( 1 === $id ? 'desktop' : 'mobile' ) . '-' . $size . '.jpg'; }
function is_front_page() { return $GLOBALS['front_page']; }
function add_action( $hook, $callback, $priority ) {}
function add_filter( $hook, $callback, $priority, $accepted_args ) {}
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return esc_attr( $value ); }
function __( $value, $domain ) { return $value; }
function esc_attr_e( $value, $domain ) { echo esc_attr( $value ); }
function esc_html_e( $value, $domain ) { echo esc_attr( $value ); }
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}
require dirname( __DIR__ ) . '/inc/hero-media.php';
$posters = tedx_hero_posters();
check( '/desktop-large.jpg' === $posters['desktop'] && '/mobile-large.jpg' === $posters['mobile'], 'Hero posters use generated large images.' );
ob_start();
tedx_preload_hero_poster();
$preloads = ob_get_clean();
check( 2 === substr_count( $preloads, 'fetchpriority="high"' ), 'Hero posters have high-priority preload hints.' );
check( false !== strpos( $preloads, '(max-width: 767px)' ) && false !== strpos( $preloads, '(min-width: 768px)' ), 'Poster preloads select only the current viewport.' );
$front_page = false;
ob_start();
tedx_preload_hero_poster();
check( '' === ob_get_clean(), 'Other pages do not preload homepage media.' );
foreach ( array( 'none', 'desktop', 'mobile', 'both' ) as $scenario ) {
	foreach ( array( 'desktop', 'mobile' ) as $device ) {
		$prefix = 'desktop' === $device ? 'tedx_hero_video_' : 'tedx_hero_video_mobile_';
		foreach ( array( 'webm', 'mp4' ) as $format ) {
			$mods[$prefix . $format] = ( $scenario === $device || 'both' === $scenario ) ? '/' . $device . '.' . $format : '';
		}
	}
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/hero-video.php';
	$html = ob_get_clean();
	check( substr_count( $html, '<video ' ) === ( 'none' === $scenario ? 0 : 1 ), $scenario . ': only one video element can load or play.' );
	check( false !== strpos( $html, 'loading="eager"' ) && false !== strpos( $html, 'fetchpriority="high"' ), $scenario . ': hero poster loads eagerly at high priority.' );
	check( false !== strpos( $html, '/desktop-large.jpg' ) && false === strpos( $html, '/desktop-original.jpg' ), $scenario . ': generated poster is used in the actual hero.' );
	if ( 'none' !== $scenario ) {
		check( false !== strpos( $html, 'preload="none"' ) && false === strpos( $html, 'preload="auto"' ), $scenario . ': video does not download during initial load.' );
		check( false === strpos( $html, ' autoplay' ) && 0 === preg_match( '/<source[^>]+\ssrc=/', $html ), $scenario . ': video sources stay in data-src until the load event.' );
		check( false !== strpos( $html, "addEventListener('load', start" ) && false !== strpos( $html, 'prefers-reduced-motion: reduce' ) && false !== strpos( $html, 'saveData' ), $scenario . ': video starts after load unless reduced motion or data saving is requested.' );
		check( false !== strpos( $html, 'loop muted playsinline' ) && false !== strpos( $html, 'video.play()' ), $scenario . ': muted inline looping playback is preserved.' );
		check( false !== strpos( $html, 'opacity-0' ), $scenario . ': still stays visible until the video is playing.' );
		check( 1 === preg_match( '/<video[^>]+class="[^"]*opacity-0"/', $html ), $scenario . ': video appears without an opacity transition when playback starts.' );
	}
	if ( 'both' === $scenario ) {
		check( 4 === preg_match_all( '/<source data-src="[^"]+" type="video\/[^"]+" media="\((?:max|min)-width: (?:767|768)px\)"/', $html ), 'Mobile and desktop videos use media-qualified deferred sources, not CSS-hidden video elements.' );
	} elseif ( 'none' !== $scenario ) {
		check( 2 === substr_count( $html, 'media="all"' ), $scenario . ': single orientation is available on all devices.' );
	}
}
