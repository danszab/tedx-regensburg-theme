<?php
/**
 * Render template for TEDx Button Block
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wrapper_classes = 'tedx-button-block w-full py-6 md:py-8 px-4 md:px-8 lg:px-12';
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( array(
	'class' => $wrapper_classes,
) );

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
