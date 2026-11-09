<?php
/**
 * Test eyebrow to heading spacing in tedx-speakers and tedx-event blocks.
 * Run with: php tests/speaker-event-blocks.php
 */
define( 'ABSPATH', __DIR__ );

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {}
function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {}
function apply_filters( $hook, $value, ...$args ) { return $value; }

function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

function absint( $value ) { return abs( (int) $value ); }
function esc_attr( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_html_e( $text, $domain = '' ) { echo esc_html( $text ); }
function esc_url( $text ) { return esc_attr( $text ); }
function sanitize_html_class( $class, $fallback = '' ) {
	$class = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $class );
	return '' !== $class ? $class : $fallback;
}
function __( $text, $domain = '' ) { return $text; }
function wp_parse_args( $args, $defaults = array() ) {
	return array_merge( $defaults, (array) $args );
}
function wp_reset_postdata() {}
function get_the_ID() { return 1; }
function get_the_title( $id = 0 ) { return 'Sample Speaker'; }
function the_title() { echo 'Sample Speaker'; }
function get_permalink( $id = 0 ) { return 'https://example.test/speaker-1'; }
function the_permalink() { echo 'https://example.test/speaker-1'; }
function get_post_type( $id = 0 ) { return 'speaker'; }
function get_post_meta( $id, $key, $single = false ) { return ''; }
function has_post_thumbnail( $id = null ) { return false; }
function has_excerpt( $id = null ) { return false; }
function get_the_content() { return 'Speaker bio'; }
function wp_trim_words( $text, $num_words = 55, $more = null ) { return $text; }
function wp_kses_post( $text ) { return $text; }
function get_block_wrapper_attributes( $extra = array() ) {
	$cls = isset( $extra['class'] ) ? $extra['class'] : '';
	$id  = isset( $extra['id'] ) ? ' id="' . esc_attr( $extra['id'] ) . '"' : '';
	return 'class="' . esc_attr( $cls ) . '"' . $id;
}

class WP_Query {
	public static $mock_has_posts = false;
	private $remaining = false;
	public function __construct( $args = array() ) {
		$this->remaining = self::$mock_has_posts;
	}
	public function have_posts() {
		return $this->remaining;
	}
	public function the_post() {
		$this->remaining = false;
	}
}

require_once dirname( __DIR__ ) . '/inc/template-tags.php';
require_once dirname( __DIR__ ) . '/inc/blocks.php';

// -------------------------------------------------------------
// Test 1: tedx-event block eyebrow to heading spacing
// -------------------------------------------------------------
$attributes = array(
	'sectionId'      => 'event-pitch',
	'eyebrow'        => 'TEDxREGENSBURG | NOV 14 | MARINAFORUM',
	'title'          => 'This Events Title',
	'description'    => 'Event description here.',
	'showTickets'    => true,
	'ticketText'     => 'Tickets',
	'ticketUrl'      => '#tickets',
	'showMore'       => true,
	'showMoreText'   => 'Show More',
	'showMoreUrl'    => '#more',
	'linkedEventId'  => 0,
	'cardImage'      => array(),
	'showCardLabel'  => false,
	'cardLabel'      => '',
	'cardClickable'  => false,
	'cardUrl'        => '',
	'reverseColumns' => false,
);

ob_start();
include dirname( __DIR__ ) . '/blocks/tedx-event/render.php';
$event_html = ob_get_clean();

check( false !== strpos( $event_html, 'flex flex-col gap-2 md:gap-3 items-start' ), 'Event block groups eyebrow and heading in a tight flex container.' );
check( 1 === preg_match( '/flex flex-col gap-2 md:gap-3 items-start[^>]*>\s*<p[^>]*>TEDxREGENSBURG \| NOV 14 \| MARINAFORUM<\/p>\s*<h2[^>]*tedx-block-heading/', $event_html ), 'Event block eyebrow directly precedes heading with gap-2 on mobile (8px) and md:gap-3 (12px).' );

// -------------------------------------------------------------
// Test 2: tedx-speakers block eyebrow to heading spacing
// -------------------------------------------------------------
$speakers_html = tedx_render_people_grid( array(
	'post_type' => 'speaker',
	'year'      => '2026',
	'title'     => 'Speakers',
	'show_year' => true,
	'show_cfs'  => true,
) );

check( false !== strpos( $speakers_html, 'flex flex-col gap-2 md:gap-3 items-start' ), 'Speakers block groups year eyebrow and heading in a tight flex container.' );
check( 1 === preg_match( '/flex flex-col gap-2 md:gap-3 items-start[^>]*>\s*<p[^>]*>2026<\/p>\s*<h2[^>]*tedx-block-heading/', $speakers_html ), 'Speakers block year eyebrow directly precedes heading with gap-2 on mobile (8px) and md:gap-3 (12px).' );

// -------------------------------------------------------------
// Test 3: tedx-speakers card title alignment & margin reset
// -------------------------------------------------------------
WP_Query::$mock_has_posts = true;
$speaker_cards_html = tedx_render_people_grid( array(
	'post_type' => 'speaker',
	'title'     => 'Speakers',
) );
check( false !== strpos( $speaker_cards_html, 'tedx-speaker-card' ), 'Speaker card renders with tedx-speaker-card class.' );
check( false !== strpos( $speaker_cards_html, '<h3 class="m-0' ), 'Speaker card title has m-0 to align flush with portrait top edge.' );

$min_css = file_get_contents( dirname( __DIR__ ) . '/assets/css/style.css' );
check( false !== strpos( $min_css, '.tedx-speaker-card h2' ) && false !== strpos( $min_css, 'margin:0!important' ), 'CSS enforces margin: 0 !important on speaker card headings.' );

echo 'All speaker and event block spacing tests passed!' . PHP_EOL;
