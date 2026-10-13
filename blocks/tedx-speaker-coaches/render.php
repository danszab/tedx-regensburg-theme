<?php
/**
 * Speaker Coaches block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

echo tedx_render_people_grid( array( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper.
	'post_type'     => 'speaker_coach',
	'year'          => $attributes['year'],
	'title'         => $attributes['title'],
	'show_more'     => $attributes['showMore'],
	'wrapper_attrs' => get_block_wrapper_attributes(
		array(
			'id'    => 'speaker-coaches',
			'class' => 'relative bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 overflow-hidden border-t border-white/5',
		)
	),
) );
