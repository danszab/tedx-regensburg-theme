<?php
/**
 * Text section block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content.
 * @var WP_Block $block      Block instance.
 */

$section_id = ! empty( $attributes['sectionId'] ) ? sanitize_html_class( $attributes['sectionId'] ) : '';

$wrapper_args = array(
	'class' => 'pt-28 pb-16 md:py-24 px-4 md:px-8 lg:px-12 w-full bg-tedx-dark',
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
