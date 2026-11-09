<?php
/**
 * Speaker Coaches block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes )
	: 'pt-8 md:pt-12 pb-8 md:pb-12 border-t border-white/5';

echo tedx_render_people_grid( array( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper.
	'post_type'     => 'speaker_coach',
	'year'          => $attributes['year'],
	'title'         => $attributes['title'],
	'show_more'     => $attributes['showMore'],
	'wrapper_attrs' => get_block_wrapper_attributes(
		array(
			'id'    => 'speaker-coaches',
			'class' => 'relative bg-tedx-dark ' . $spacing_classes . ' px-4 md:px-8 lg:px-12 overflow-hidden',
		)
	),
) );
