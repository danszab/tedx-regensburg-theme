<?php
/** Standalone checks: php tests/venue-embed.php. */
define( 'ABSPATH', __DIR__ );
function add_filter( $hook, $callback, $priority = 10 ) {}
function __( $value, $domain = '' ) { return $value; }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return esc_attr( $value ); }
function esc_html__( $value, $domain = '' ) { return esc_html( $value ); }
function esc_url( $value ) { return esc_attr( $value ); }
function get_privacy_policy_url() { return '/privacy/'; }
function wp_parse_url( $url ) { return parse_url( $url ); }
function get_theme_mod( $key, $default = '' ) { return $default; }
function sanitize_html_class( $value ) { return $value; }
function get_block_wrapper_attributes( $attrs ) { return 'class="' . esc_attr( $attrs['class'] ) . '"'; }
function get_the_ID() { return 123; }
function get_post_type( $id ) { return 'tedx_event'; }
function get_post_meta( $id, $key, $single ) {
	return '_event_venue_name' === $key ? 'Event Venue' : ( '_event_venue_maps_embed' === $key ? '<iframe src="https://www.google.com/maps/embed?pb=event"></iframe>' : '' );
}
function check( $condition, $message ) {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
	echo 'PASS: ' . $message . PHP_EOL;
}
require dirname( __DIR__ ) . '/inc/embed-consent.php';
foreach ( array(
	array( 'venueName' => 'Marina & Forum', 'showButton' => true ),
	array( 'mapsEmbed' => '<script src="https://evil.test/x.js"></script><iframe src="https://www.google.com/maps/embed?pb=abc"></iframe>' ),
	array( 'syncWithEvent' => true ),
	array( 'mapsEmbed' => '<iframe src="https://evil.test/"></iframe>' ),
) as $attributes ) {
	ob_start();
	include dirname( __DIR__ ) . '/blocks/tedx-venue/render.php';
	$html = ob_get_clean();
	check( false === strpos( $html, '<iframe' ) && false === strpos( $html, '<script' ) && false === strpos( $html, 'evil.test' ), 'Map output contains no active third-party content, including custom/event embeds.' );
	check( false !== strpos( $html, 'data-tedx-embed' ) && false !== strpos( $html, 'href="/privacy/"' ), 'German disclosure and privacy policy link are rendered.' );
}
check( false !== strpos( $html, 'role="status"' ), 'Invalid custom URL has an explicit unavailable message.' );
check( '' === tedx_map_embed_url( '<iframe src="javascript:alert(1)"></iframe>' ), 'Non-HTTPS custom map URL is rejected.' );
foreach ( array( 'https://youtu.be/dQw4w9WgXcQ', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'https://www.youtube.com/shorts/dQw4w9WgXcQ' ) as $url ) {
	check( 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ' === tedx_youtube_embed_url( $url ), 'YouTube URLs become privacy-enhanced embed URLs without oEmbed requests.' );
}
check( '' === tedx_youtube_embed_url( 'https://evil.test/watch?v=dQw4w9WgXc' ), 'Unknown video host rejected.' );
$html = tedx_consent_embed( tedx_youtube_embed_url( 'https://youtu.be/dQw4w9WgXcQ' ), 'YouTube', 'A & B' );
check( false === strpos( $html, '<iframe' ) && false !== strpos( $html, 'data-embed-title="A &amp; B"' ), 'Video output is inert and title is escaped.' );
$speaker = file_get_contents( dirname( __DIR__ ) . '/single-speaker.php' );
check( false === strpos( $speaker, 'wp_oembed_get' ) && false !== strpos( $speaker, 'tedx_consent_embed(' ), 'Speaker video uses consent helper instead of active oEmbed.' );

foreach ( array(
	'<figure class="wp-block-embed"><div class="wp-block-embed__wrapper"><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Talk"></iframe></div><figcaption>Caption</figcaption></figure>',
	'<iframe src="//www.youtube-nocookie.com/embed/dQw4w9WgXcQ"></iframe>',
	'<iframe width="600" src="https://www.google.com/maps/embed?pb=test&amp;x=1" height="450"></iframe>',
) as $embed ) {
	$gated = tedx_gate_content_embeds( $embed );
	check( false === strpos( $gated, '<iframe' ) && false !== strpos( $gated, 'data-tedx-embed' ), 'Legacy/oEmbed iframe is replaced server-side before any provider request.' );
	check( $gated === tedx_gate_content_embeds( $gated ), 'Repeated oEmbed/content filtering does not duplicate the consent gate.' );
}
$other = '<p>Before</p><iframe src="https://example.org/embed"></iframe><p>After</p>';
check( $other === tedx_gate_content_embeds( $other ), 'Unrelated embeds and surrounding content are preserved.' );
$mixed = tedx_gate_content_embeds( '<p>Before</p><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe><iframe src="https://www.google.com/maps?output=embed"></iframe><p>After</p>' );
check( 2 === substr_count( $mixed, 'data-tedx-embed ' ) && false !== strpos( $mixed, '<p>After</p>' ), 'Multiple provider embeds each receive a separate consent gate.' );
