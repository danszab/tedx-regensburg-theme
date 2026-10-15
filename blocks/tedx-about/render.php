<?php
/**
 * About block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$image_alt = ! empty( $attributes['image']['alt'] ) ? $attributes['image']['alt'] : __( 'About TEDx Regensburg', 'tedx-regensburg' );
$image_html = tedx_block_image(
	$attributes['image'] ?? array(),
	'large',
	array(
		'alt'   => $image_alt,
		'class' => 'absolute inset-0 w-full h-full object-cover',
		'sizes' => '(min-width: 1024px) 600px, 100vw',
	)
);
$text_col  = ( 'right' === $attributes['imagePosition'] ) ? 'lg:order-first' : '';

$button_url = $attributes['buttonUrl'];
if ( 0 === strpos( $button_url, '/' ) ) {
	$button_url = home_url( $button_url );
}

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $attributes['sectionId'] ),
		'class' => 'bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 border-t border-white/5',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

		<div class="relative w-full h-[360px] md:h-[420px] rounded-[32px] overflow-hidden border border-white/10 shadow-2xl bg-tedx-card flex items-center justify-center <?php echo ( 'right' === $attributes['imagePosition'] ) ? 'lg:order-last' : ''; ?>">
			<?php if ( $image_html ) : ?>
				<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?>
			<?php else : ?>
				<div class="absolute inset-0 bg-gradient-to-tr from-black via-[#1c1c1c] to-[#2a1015]"></div>
				<div class="relative z-10 flex flex-col items-center justify-center p-6 text-center">
					<div class="text-6xl md:text-7xl font-black text-tedx-red tracking-tight filter drop-shadow-[0_10px_20px_rgba(235,0,40,0.4)]">
						TED<span class="text-white">x</span>
					</div>
					<div class="text-sm uppercase tracking-widest text-white/60 mt-2 font-medium">Ideas Worth Spreading</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="flex flex-col gap-6 items-start <?php echo esc_attr( $text_col ); ?>">
			<div class="flex flex-col gap-6">
				<?php tedx_category_header( $attributes['eyebrow'], '', 'h2' ); ?>
				<?php if ( ! empty( $attributes['showLogo'] ) ) : ?>
					<div><?php tedx_regensburg_logo( 'h-[50px] w-auto', false ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $attributes['description'] ) ) : ?>
				<div class="text-base text-white/90 leading-relaxed font-normal space-y-4">
					<?php echo wp_kses_post( wpautop( $attributes['description'] ) ); ?>
				</div>
			<?php endif; ?>

			<?php
			if ( ! empty( $attributes['showButton'] ) && ! empty( $button_url ) ) {
				echo tedx_block_button( $attributes['buttonText'] ?: __( 'Learn More', 'tedx-regensburg' ), $button_url, 'outline', 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</div>

	</div>
</section>
