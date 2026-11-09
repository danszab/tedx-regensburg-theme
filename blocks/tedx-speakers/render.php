<?php
/**
 * Speakers block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$post_id = tedx_block_post_id( $block );
$year    = trim( (string) $attributes['year'] );

if ( 'event' === $attributes['yearSource'] && 'tedx_event' === get_post_type( $post_id ) ) {
	$event_year = trim( (string) get_post_meta( $post_id, '_event_year', true ) );
	if ( '' !== $event_year ) {
		$year = $event_year;
	}
}

$cfs_url = $attributes['cfsUrl'];
if ( 0 === strpos( $cfs_url, '/' ) ) {
	$cfs_url = home_url( $cfs_url );
}

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes )
	: 'pt-8 md:pt-12 pb-8 md:pb-12 border-t border-white/5';

echo tedx_render_people_grid( array( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper.
	'post_type'         => 'speaker',
	'year'              => $year,
	'title'             => $attributes['title'],
	'show_year'         => $attributes['showYear'],
	'show_event_button' => $attributes['showEventButton'],
	'event_button_text' => $attributes['eventButtonText'],
	'event_button_url'  => $attributes['eventButtonUrl'],
	'show_cfs'          => $attributes['showCfs'],
	'cfs_title'         => $attributes['cfsTitle'],
	'cfs_description'   => $attributes['cfsDescription'],
	'cfs_url'           => $cfs_url,
	'cfs_button_text'   => $attributes['cfsButtonText'],
	'wrapper_attrs'     => get_block_wrapper_attributes(
		array(
			'id'    => 'speakers',
			'class' => 'relative bg-tedx-dark ' . $spacing_classes . ' px-4 md:px-8 lg:px-12 overflow-hidden',
		)
	),
) );
