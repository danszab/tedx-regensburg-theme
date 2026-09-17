<?php
/**
 * Template part for displaying the Location section
 *
 * @package TEDx_Regensburg
 */

// Fallbacks from Customizer
$venue_name = get_theme_mod( 'tedx_venue_name', 'Marinaforum Regensburg' );
$venue_address_1 = get_theme_mod( 'tedx_venue_address_1', 'Johanna-Dachs-Straße 46' );
$venue_address_2 = get_theme_mod( 'tedx_venue_address_2', '93055 Regensburg' );
$venue_maps_url = get_theme_mod( 'tedx_venue_maps_url', '#' );
$venue_maps_embed = get_theme_mod( 'tedx_venue_maps_embed', '' );

// If on an Event Page, override with specific event location details
if ( is_page() || is_singular( 'tedx_event' ) ) {
	$page_id = get_the_ID();
	$meta_venue_name = get_post_meta( $page_id, '_event_venue_name', true );
	if ( ! empty( $meta_venue_name ) ) {
		$venue_name = $meta_venue_name;
		$venue_address_1 = get_post_meta( $page_id, '_event_venue_address_1', true );
		$venue_address_2 = get_post_meta( $page_id, '_event_venue_address_2', true );
		$venue_maps_url = get_post_meta( $page_id, '_event_venue_maps_url', true );
		$venue_maps_embed = get_post_meta( $page_id, '_event_venue_maps_embed', true );
	}
}

if ( empty( $venue_maps_url ) || '#' === $venue_maps_url ) {
	$venue_maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( $venue_name . ', ' . $venue_address_1 . ', ' . $venue_address_2 );
}
?>

<section id="location" class="bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full border-t border-white/5">
	<div class="max-w-figma mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center">
		
				<!-- Left Column: Location / Venue Image -->
		<div class="w-full aspect-[4/3] rounded-[32px] overflow-hidden relative shadow-2xl bg-tedx-card border border-white/10">
			<?php if ( ! empty( $venue_maps_embed ) ) : ?>
				<div class="absolute inset-0 w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0 grayscale hover:grayscale-0 transition-all duration-500">
					<?php echo $venue_maps_embed; // No wp_kses_post here because iframes get stripped by KSES if not configured properly, but it's an admin field so it's fine. Actually wp_kses_post is fine if configured. But since it's already saved with wp_kses_post... wait. wp_kses_post strips iframes by default in standard WP unless unfiltered_html is allowed. We will just output it. ?>
				</div>
			<?php else : ?>
				<?php
				$map_query = $venue_name . ', ' . $venue_address_1 . ', ' . $venue_address_2;
				$map_embed_url = 'https://www.google.com/maps?q=' . urlencode( $map_query ) . '&output=embed';
				?>
				<iframe class="absolute inset-0 w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-500" src="<?php echo esc_url( $map_embed_url ); ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			<?php endif; ?>
		</div>
		
		<!-- Right Column: Location / Venue Info -->
		<div class="flex flex-col items-start text-left break-words">
			<h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-tight mb-4">
				Venue
			</h2>
			
			<div class="flex flex-col gap-1 mb-8">
				<h3 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
					<?php echo esc_html( $venue_name ); ?>
				</h3>
				<div class="text-base text-white/80 leading-relaxed font-normal mt-1">
					<p><?php echo esc_html( $venue_address_1 ); ?></p>
					<p><?php echo esc_html( $venue_address_2 ); ?></p>
				</div>
			</div>
			
			<?php if ( $venue_maps_url ) : ?>
			<a href="<?php echo esc_url( $venue_maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center border border-white/20 hover:border-tedx-red hover:text-tedx-red text-white text-sm font-semibold px-6 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim uppercase tracking-wider">
				<span>VIEW ON GOOGLE MAPS</span>
			</a>
			<?php endif; ?>
		</div>
		
	</div>
</section>
