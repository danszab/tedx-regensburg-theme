<?php
/**
 * Stats block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$items = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

if ( empty( $items ) ) {
	return;
}

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $attributes['sectionId'] ?? 'stats' ),
		'class' => 'bg-tedx-surface py-16 md:py-20 px-4 border-t border-white/5',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto flex flex-wrap items-center justify-center gap-8 md:gap-16">
		<?php foreach ( $items as $stat ) : ?>
			<?php
			$val       = $stat['value'] ?? '';
			$lbl       = $stat['label'] ?? '';
			$icon_key  = $stat['icon'] ?? 'groups';
			$icon_html = '';

			// Check local theme SVG files for stat-1, stat-2, stat-3
			$candidate_files = array(
				"assets/images/icons/{$icon_key}.svg",
				"assets/images/icons/{$icon_key}.png",
			);
			foreach ( $candidate_files as $rel_file ) {
				$full_path = TEDX_DIR . '/' . $rel_file;
				if ( file_exists( $full_path ) ) {
					if ( 'svg' === pathinfo( $rel_file, PATHINFO_EXTENSION ) ) {
						$svg_data  = file_get_contents( $full_path );
						$svg_data  = preg_replace( '/<\?xml.*?\?>/i', '', $svg_data );
						$icon_html = '<div class="w-6 h-6 flex items-center justify-center [&>svg]:w-6 [&>svg]:h-6 [&>svg]:fill-current">' . $svg_data . '</div>';
					} else {
						$icon_html = '<img src="' . esc_url( TEDX_URI . '/' . $rel_file ) . '" alt="" class="w-6 h-6 object-contain">';
					}
					break;
				}
			}

			if ( empty( $icon_html ) && function_exists( 'tedx_get_icon' ) ) {
				$icon_html = tedx_get_icon( $icon_key, 'w-6 h-6' );
			}
			?>
			<div class="w-[147px] h-[147px] border-2 border-tedx-green rounded-[32px] p-4 flex flex-col items-center justify-between text-center bg-black/20 backdrop-blur-sm shadow-glow-green">
				<div class="text-tedx-green pt-1 flex items-center justify-center">
					<?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="text-2xl font-bold text-white tracking-tight leading-none">
					<?php echo esc_html( $val ); ?>
				</div>
				<div class="text-sm font-semibold text-tedx-muted pb-1">
					<?php echo esc_html( $lbl ); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
