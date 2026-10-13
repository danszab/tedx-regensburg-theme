<?php
/**
 * Venue block render (Location map + address + directions button).
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$title       = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'Venue', 'tedx-regensburg' );
$venue_name  = ! empty( $attributes['venueName'] ) ? $attributes['venueName'] : get_theme_mod( 'tedx_venue_name', 'Marinaforum Regensburg' );
$address_1   = ! empty( $attributes['addressLine1'] ) ? $attributes['addressLine1'] : get_theme_mod( 'tedx_venue_address_1', 'Johanna-Dachs-Straße 46' );
$address_2   = ! empty( $attributes['addressLine2'] ) ? $attributes['addressLine2'] : get_theme_mod( 'tedx_venue_address_2', '93055 Regensburg' );
$maps_url    = ! empty( $attributes['mapsUrl'] ) ? $attributes['mapsUrl'] : '';
$maps_embed  = ! empty( $attributes['mapsEmbed'] ) ? $attributes['mapsEmbed'] : '';
$show_button = ! empty( $attributes['showButton'] );
$button_text = ! empty( $attributes['buttonText'] ) ? $attributes['buttonText'] : __( 'VIEW ON GOOGLE MAPS', 'tedx-regensburg' );

// If syncWithEvent is enabled, check current post / event context for venue meta overrides
if ( ! empty( $attributes['syncWithEvent'] ) ) {
	$post_id = function_exists( 'tedx_block_post_id' ) ? tedx_block_post_id( $block ) : get_the_ID();
	if ( $post_id && ( 'tedx_event' === get_post_type( $post_id ) || is_singular( 'tedx_event' ) ) ) {
		$meta_name = get_post_meta( $post_id, '_event_venue_name', true );
		if ( ! empty( $meta_name ) ) {
			$venue_name  = $meta_name;
			$meta_addr_1 = get_post_meta( $post_id, '_event_venue_address_1', true );
			$meta_addr_2 = get_post_meta( $post_id, '_event_venue_address_2', true );
			$meta_url    = get_post_meta( $post_id, '_event_venue_maps_url', true );
			$meta_embed  = get_post_meta( $post_id, '_event_venue_maps_embed', true );

			if ( ! empty( $meta_addr_1 ) ) {
				$address_1 = $meta_addr_1;
			}
			if ( ! empty( $meta_addr_2 ) ) {
				$address_2 = $meta_addr_2;
			}
			if ( ! empty( $meta_url ) && empty( $attributes['mapsUrl'] ) ) {
				$maps_url = $meta_url;
			}
			if ( ! empty( $meta_embed ) && empty( $attributes['mapsEmbed'] ) ) {
				$maps_embed = $meta_embed;
			}
		}
	}
}

// Fallback Google Maps link
if ( empty( $maps_url ) || '#' === $maps_url ) {
	$customizer_url = get_theme_mod( 'tedx_venue_maps_url', '' );
	if ( ! empty( $customizer_url ) && '#' !== $customizer_url ) {
		$maps_url = $customizer_url;
	} else {
		$query    = trim( $venue_name . ', ' . $address_1 . ', ' . $address_2 );
		$maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $query );
	}
}

$section_id = ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : 'location';

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $section_id ),
		'class' => 'bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full border-t border-white/5',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center">

		<!-- Left Column: Location / Venue Image or Embed -->
		<div class="w-full aspect-[4/3] rounded-[32px] overflow-hidden relative shadow-2xl bg-tedx-card border border-white/10">
			<?php if ( ! empty( $maps_embed ) ) : ?>
				<div class="absolute inset-0 w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0 grayscale hover:grayscale-0 transition-all duration-500">
					<?php echo $maps_embed; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			<?php else : ?>
				<?php
				$map_query     = trim( $venue_name . ', ' . $address_1 . ', ' . $address_2 );
				$map_embed_url = 'https://www.google.com/maps?q=' . rawurlencode( $map_query ) . '&output=embed';
				?>
				<iframe class="absolute inset-0 w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-500" src="<?php echo esc_url( $map_embed_url ); ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			<?php endif; ?>
		</div>

		<!-- Right Column: Location / Venue Info -->
		<div class="flex flex-col items-start text-left break-words">
			<h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-tight mb-4">
				<?php echo esc_html( $title ); ?>
			</h2>

			<div class="flex flex-col gap-1 mb-8">
				<h3 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
					<?php echo esc_html( $venue_name ); ?>
				</h3>
				<div class="text-base text-white/80 leading-relaxed font-normal mt-1">
					<?php if ( ! empty( $address_1 ) ) : ?>
						<p><?php echo esc_html( $address_1 ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $address_2 ) ) : ?>
						<p><?php echo esc_html( $address_2 ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $show_button && ! empty( $maps_url ) ) : ?>
				<a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center border border-white/20 hover:border-tedx-red hover:text-tedx-red text-white text-sm font-semibold px-6 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim uppercase tracking-wider">
					<span><?php echo esc_html( $button_text ); ?></span>
				</a>
			<?php endif; ?>
		</div>

	</div>
</section>
