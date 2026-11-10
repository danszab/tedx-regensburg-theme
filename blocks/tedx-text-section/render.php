<?php
/**
 * Text section block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content.
 * @var WP_Block $block      Block instance.
 */

$section_id = sanitize_html_class( ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : ( ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : '' ) );

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes )
	: 'pt-8 md:pt-12 pb-8 md:pb-12 border-t border-white/5';

$wrapper_args = array(
	'class' => 'bg-tedx-dark ' . $spacing_classes . ' px-4 md:px-8 lg:px-12 w-full',
);
if ( ! empty( $section_id ) ) {
	$wrapper_args['id'] = $section_id;
}

$wrapper = get_block_wrapper_attributes( $wrapper_args );
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto w-full tedx-prose">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
