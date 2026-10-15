<?php
/**
 * Standalone block image checks: php tests/block-images.php.
 */
define( 'ABSPATH', __DIR__ );
$image_calls = array();
function add_action( $hook, $callback ) {}
function add_filter( $hook, $callback ) {}
function absint( $value ) { return abs( (int) $value ); }
function wp_parse_args( $args, $defaults ) { return array_merge( $defaults, $args ); }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_url( $value ) { return esc_attr( $value ); }
function __( $value, $domain = '' ) { return $value; }
function sanitize_html_class( $value ) { return $value; }
function attachment_url_to_postid( $url ) { return '/uploads/photo.jpg' === $url ? 12 : 0; }
function wp_get_attachment_image_url( $id, $size ) {
	return 99 === $id ? false : '/uploads/' . $id . '-' . $size . '.jpg';
}
function wp_get_attachment_image_srcset( $id, $size ) {
	return 99 === $id ? false : '/uploads/' . $id . '-medium.jpg 300w, /uploads/' . $id . '-large.jpg 1024w';
}
function wp_get_attachment_image( $id, $size, $icon, $attrs ) {
	$GLOBALS['image_calls'][] = array( 'id' => $id, 'size' => $size, 'attrs' => $attrs );
	if ( 99 === $id ) {
		return '';
	}
	$html = '<img src="' . esc_url( wp_get_attachment_image_url( $id, $size ) ) . '" width="300" height="200" srcset="' . esc_attr( wp_get_attachment_image_srcset( $id, $size ) ) . '"';
	foreach ( $attrs as $key => $value ) {
		$html .= ' ' . $key . '="' . esc_attr( $value ) . '"';
	}
	return $html . '>';
}
function get_block_wrapper_attributes( $attrs ) {
	return 'class="' . esc_attr( $attrs['class'] ) . ' custom-gallery"';
}
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}
require dirname( __DIR__ ) . '/inc/blocks.php';

$html = tedx_block_image( array( 'id' => 12, 'url' => '/original.jpg' ), 'medium', array( 'alt' => 'A & B' ) );
check( false !== strpos( $html, 'src="/uploads/12-medium.jpg"' ), 'Attachment image requests a generated medium file.' );
check( false !== strpos( $html, 'srcset=' ) && false !== strpos( $html, 'width="300"' ), 'WordPress responsive candidates and intrinsic dimensions are retained.' );
check( false !== strpos( $html, 'loading="lazy"' ) && false !== strpos( $html, 'decoding="async"' ), 'Lazy loading and async decoding are requested.' );
check( false !== strpos( $html, 'fetchpriority="auto"' ), 'Shared block images have normal fetch priority.' );
check( false !== strpos( $html, 'alt="A &amp; B"' ), 'Custom alternative text is escaped.' );
$html = tedx_block_image( array( 'url' => '/uploads/photo.jpg' ), 'medium' );
check( false !== strpos( $html, '/uploads/12-medium.jpg' ), 'Legacy URL-only Media Library image is resolved.' );
$html = tedx_block_image( array( 'url' => 'https://example.test/logo.svg' ), 'medium', array( 'alt' => 'Logo' ) );
check( false !== strpos( $html, 'src="https://example.test/logo.svg"' ), 'External and SVG URL-only images remain usable.' );
check( false !== strpos( $html, 'fetchpriority="auto"' ), 'URL-only block images also have normal fetch priority.' );
$html = tedx_block_image( array( 'id' => 99, 'url' => '/retained.jpg' ), 'medium' );
check( false !== strpos( $html, 'src="/retained.jpg"' ), 'Unavailable attachment metadata preserves the saved image.' );

$attributes = array( 'images' => array( array( 'id' => 12, 'url' => '/original.jpg', 'alt' => 'Gallery' ) ) );
ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-gallery/render.php';
$html = ob_get_clean();
check( false !== strpos( $html, 'src="/uploads/12-medium.jpg"' ), 'Gallery grid renders medium images instead of originals.' );
check( false !== strpos( $html, 'sizes="(max-width: 768px) 180px, 231px"' ), 'Gallery image sizes match mobile and desktop cards.' );
check( false !== strpos( $html, 'fetchpriority="auto"' ), 'Gallery preview requests have normal priority.' );
check( false !== strpos( $html, 'data-lightbox-src="/uploads/12-large.jpg"' ), 'Larger lightbox image is a deferred data attribute.' );
check( false !== strpos( $html, 'data-lightbox-srcset=' ), 'Lightbox responsive candidates are available on demand.' );
preg_match( '/<img class="lightbox-img"[^>]*>/', $html, $lightbox );
check( false === strpos( $lightbox[0], ' src=' ) && false === strpos( $lightbox[0], ' srcset=' ), 'Closed lightbox does not request an image.' );
check( 1 === preg_match( '/<div class="tedx-gallery-wrapper custom-gallery">/', $html ), 'Gallery root has one merged class attribute.' );
$functions_php = file_get_contents( dirname( __DIR__ ) . '/functions.php' );
check( false !== strpos( $functions_php, "add_image_size( 'tedx-gallery-thumb', 480, 0, false )" ), 'Uncropped 480px gallery size fills the srcset gap between 300w and 768w.' );
check( false === strpos( $functions_php, 'set_post_thumbnail_size(' ) && false === strpos( $functions_php, "add_image_size( 'tedx-hero'" ), 'Unused post-thumbnail and tedx-hero sizes are no longer registered.' );
foreach ( array( '1536x1536', '2048x2048' ) as $size ) {
	check( false !== strpos( $functions_php, "remove_image_size( '" . $size . "' )" ), $size . ' is unregistered during theme setup.' );
}

function home_url( $path = '' ) { return 'https://example.test' . $path; }
function wpautop( $text ) { return '<p>' . $text . '</p>'; }
function wp_kses_post( $html ) { return $html; }
function tedx_regensburg_logo( $classes = '', $link = true ) {}
$about_defaults = array( 'sectionId' => 'about', 'eyebrow' => 'About', 'imagePosition' => 'left', 'description' => '', 'showButton' => false, 'buttonText' => '', 'buttonUrl' => '', 'showLogo' => false );
$attributes = $about_defaults + array( 'image' => array( 'id' => 12, 'url' => '/original.jpg', 'alt' => 'About & us' ) );
ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-about/render.php';
$html = ob_get_clean();
$call = end( $image_calls );
check( 12 === $call['id'] && 'large' === $call['size'] && false === strpos( $html, '/original.jpg' ), 'About image renders a generated attachment size with srcset instead of the original.' );
check( '(min-width: 1024px) 600px, 100vw' === $call['attrs']['sizes'] && 'absolute inset-0 w-full h-full object-cover' === $call['attrs']['class'], 'About image has a display-width hint and keeps its cover styling.' );
check( 'lazy' === $call['attrs']['loading'] && 'async' === $call['attrs']['decoding'] && 'auto' === $call['attrs']['fetchpriority'], 'Below-the-fold About image is lazy at normal priority.' );
check( false !== strpos( $html, 'alt="About &amp; us"' ), 'About image alternative text is escaped.' );
$attributes = $about_defaults + array( 'image' => array( 'url' => 'https://example.test/about.jpg' ) );
ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-about/render.php';
$html = ob_get_clean();
check( false !== strpos( $html, '<img src="https://example.test/about.jpg"' ) && false !== strpos( $html, 'alt="About TEDx Regensburg"' ) && false !== strpos( $html, 'loading="lazy"' ), 'URL-only About image stays usable, lazy, with the default alt.' );
$attributes = $about_defaults + array( 'image' => array() );
ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-about/render.php';
$html = ob_get_clean();
check( false === strpos( $html, '<img' ) && false !== strpos( $html, 'Ideas Worth Spreading' ), 'Missing About image keeps the TEDx placeholder.' );

foreach ( array( 'grid', 'featured' ) as $layout ) {
	$attributes = array(
		'layout' => $layout,
		'sponsors' => array( array( 'id' => 12, 'url' => '/original.jpg', 'name' => 'Sponsor', 'link' => 'https://example.test/sponsor' ) ),
	);
	ob_start();
	include dirname( __DIR__ ) . '/blocks/tedx-sponsors/render.php';
	$html = ob_get_clean();
	check( false !== strpos( $html, 'src="/uploads/12-medium.jpg"' ), $layout . ' sponsor uses generated medium images.' );
	check( false !== strpos( $html, 'href="https://example.test/sponsor"' ) && false !== strpos( $html, 'alt="Sponsor"' ), $layout . ' sponsor preserves links and names.' );
	check( false !== strpos( $html, 'class="tedx-sponsor-logo"' ) && false !== strpos( $html, 'tedx-sponsor-logo-frame' ), $layout . ' sponsor uses a definite image frame instead of intrinsic auto sizing.' );
	check( false !== strpos( $html, 'class="tedx-sponsor-logo-link"' ), $layout . ' linked logo fills the same frame as unlinked logos.' );
}

class WP_Query {
	private $remaining = true;
	public function __construct( $args ) {}
	public function have_posts() { return $this->remaining; }
	public function the_post() { $this->remaining = false; }
}
function wp_reset_postdata() {}
function get_the_ID() { return 1; }
function get_post_meta( $id, $key, $single ) { return ''; }
function tedx_category_header( $title, $extra = '', $tag = '' ) {}
function has_post_thumbnail() { return true; }
function the_post_thumbnail( $size, $attrs ) { echo wp_get_attachment_image( 12, $size, false, $attrs ); }
function the_title() { echo 'Speaker'; }
function tedx_get_icon( $name, $classes ) { return ''; }
function has_excerpt() { return true; }
function the_excerpt() { echo 'Biography'; }
foreach ( array( 'speaker', 'speaker_coach' ) as $post_type ) {
	$html = tedx_render_people_grid( array( 'post_type' => $post_type ) );
	$call = end( $image_calls );
	check( 'tedx-speaker' === $call['size'] && '122px' === $call['attrs']['sizes'], $post_type . ' portrait uses a generated size with the correct display-width hint.' );
	check( 'lazy' === $call['attrs']['loading'] && 'async' === $call['attrs']['decoding'], $post_type . ' portrait enables deferred loading.' );
	check( 'auto' === $call['attrs']['fetchpriority'], $post_type . ' portrait requests have normal priority.' );
}
