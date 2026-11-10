<?php
/**
 * Render template for TEDx Button Block
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes )
	: 'pt-8 md:pt-12 pb-8 md:pb-12 border-t border-white/5';

$anchor = ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : ( ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : '' );

$wrapper_classes = 'tedx-button-block w-full ' . $spacing_classes . ' px-4 md:px-8 lg:px-12';
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_args = array(
	'class' => $wrapper_classes,
);
if ( ! empty( $anchor ) ) {
	$wrapper_args['id'] = sanitize_html_class( $anchor );
}

$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );

$button_args = ! empty( $attributes ) && is_array( $attributes ) ? $attributes : array();
?>

<div <?php echo $wrapper_attributes; ?>>
	<div class="tedx-button-container max-w-figma mx-auto w-full">
		<?php
		if ( function_exists( 'tedx_render_button' ) ) {
			echo tedx_render_button( $button_args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
</div>
