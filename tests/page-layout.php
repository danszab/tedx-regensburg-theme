<?php
/**
 * Standalone page alignment checks: php tests/page-layout.php.
 */
define( 'ABSPATH', __DIR__ );
$remaining = true;
$posts = array();
$parsed_content = array();
function add_action( $hook, $callback, $priority = 10 ) {}
function absint( $value ) { return abs( (int) $value ); }
function get_post( $id ) { return $GLOBALS['posts'][$id] ?? null; }
function parse_blocks( $content ) { return $GLOBALS['parsed_content'][$content] ?? array(); }
require dirname( __DIR__ ) . '/inc/template-tags.php';
function get_queried_object_id() { return 42; }
function get_header() {}
function get_footer() {}
function esc_attr( $text ) { return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' ); }
function have_posts() { return $GLOBALS['remaining']; }
function the_post() { $GLOBALS['remaining'] = false; }
function the_ID() { echo 42; }
function post_class( $classes ) { echo 'class="' . esc_attr( $classes ) . '"'; }
function the_title() { echo 'Press'; }
function has_post_thumbnail() { return true; }
function the_post_thumbnail( $size, $attrs ) { echo '<img alt="Featured image">'; }
function the_content() { echo '<section class="alignfull">Page block content</section>'; }
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}
function block_fixture( $name, $attrs = array(), $children = array() ) {
	return array( 'blockName' => $name, 'attrs' => $attrs, 'innerBlocks' => $children );
}

$posts[100] = (object) array( 'post_type' => 'wp_block', 'post_content' => 'synced' );
$parsed_content['synced'] = array( block_fixture( 'core/cover', array( 'align' => 'full' ) ) );
$posts[101] = (object) array( 'post_type' => 'wp_block', 'post_content' => 'cycle-one' );
$posts[102] = (object) array( 'post_type' => 'wp_block', 'post_content' => 'cycle-two' );
$parsed_content['cycle-one'] = array( block_fixture( 'core/block', array( 'ref' => 102 ) ) );
$parsed_content['cycle-two'] = array( block_fixture( 'core/block', array( 'ref' => 101 ) ) );

$cases = array(
	'None alignment' => array( false, array( block_fixture( 'tedx/press-releases' ) ) ),
	'Explicit none alignment' => array( false, array( block_fixture( 'core/image', array( 'align' => 'none' ) ) ) ),
	'Wide is not full width' => array( false, array( block_fixture( 'core/image', array( 'align' => 'wide' ) ) ) ),
	'Full-width press block' => array( true, array( block_fixture( 'tedx/press-releases', array( 'align' => 'full' ) ) ) ),
	'Full-width core block' => array( true, array( block_fixture( 'core/cover', array( 'align' => 'full' ) ) ) ),
	'Mixed alignments' => array( true, array( block_fixture( 'core/paragraph' ), block_fixture( 'tedx/gallery', array( 'align' => 'full' ) ) ) ),
	'Nested full-width block' => array( true, array( block_fixture( 'core/group', array(), array( block_fixture( 'core/image', array( 'align' => 'full' ) ) ) ) ) ),
	'Synced full-width pattern' => array( true, array( block_fixture( 'core/block', array( 'ref' => 100 ) ) ) ),
	'Missing synced pattern' => array( false, array( block_fixture( 'core/block', array( 'ref' => 999 ) ) ) ),
	'Cyclic synced patterns' => array( false, array( block_fixture( 'core/block', array( 'ref' => 101 ) ) ) ),
	'Full-width sibling after a cycle' => array( true, array( block_fixture( 'core/block', array( 'ref' => 101 ) ), block_fixture( 'core/image', array( 'align' => 'full' ) ) ) ),
	'Empty page' => array( false, array() ),
);

check( ! tedx_page_has_full_width_blocks( 999 ), 'Missing page does not enable full-width layout.' );
foreach ( $cases as $label => $case ) {
	list( $has_full_width, $blocks ) = $case;
	$posts[42] = (object) array( 'post_type' => 'page', 'post_content' => 'page-content' );
	$parsed_content['page-content'] = $blocks;
	check( $has_full_width === tedx_page_has_full_width_blocks( 42 ), $label . ': alignment detected correctly.' );
	$remaining = true;
	ob_start();
	include dirname( __DIR__ ) . '/page.php';
	$html = ob_get_clean();
	if ( $has_full_width ) {
		check( false !== strpos( $html, 'tedx-full-width-page-article' ), $label . ': uses the section layout.' );
		check( false === strpos( $html, 'max-w-4xl' ), $label . ': no default article width constraint.' );
		check( false === strpos( $html, 'bg-tedx-card' ), $label . ': no outer rounded background card.' );
		check( false !== strpos( $html, 'tedx-full-width-page-content' ), $label . ': uses full-width content wrapper.' );
	} else {
		check( false !== strpos( $html, 'bg-tedx-card rounded-3xl p-8 md:p-12' ), 'Ordinary page card styles are unchanged.' );
		check( false !== strpos( $html, 'max-w-4xl mx-auto' ), 'Ordinary page width is unchanged.' );
		check( false !== strpos( $html, 'entry-content text-white/90 leading-relaxed space-y-4 text-base' ), 'Ordinary page content styles are unchanged.' );
	}
	check( false !== strpos( $html, '>Press</h1>' ) || 1 === preg_match( '/<h1[^>]*>\s*Press\s*<\/h1>/', $html ), 'Page title is preserved.' );
	check( false !== strpos( $html, 'alt="Featured image"' ) && false !== strpos( $html, 'Page block content' ), 'Featured image and page content are preserved.' );
}
