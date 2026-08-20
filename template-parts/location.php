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
$venue_image = get_theme_mod( 'tedx_venue_image', '' );

// If on an Event Page, override with specific event location details
if ( is_page() ) {
	$page_id = get_the_ID();
	$meta_venue_name = get_post_meta( $page_id, '_event_venue_name', true );
	if ( ! empty( $meta_venue_name ) ) {
		$venue_name = $meta_venue_name;
		$venue_address_1 = get_post_meta( $page_id, '_event_venue_address_1', true );
		$venue_address_2 = get_post_meta( $page_id, '_event_venue_address_2', true );
		$venue_maps_url = get_post_meta( $page_id, '_event_venue_maps_url', true );
		$venue_image = get_post_meta( $page_id, '_event_venue_image', true );
	}
}
?>

<section class="bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full flex flex-col items-center border-t border-white/5">
	<div class="max-w-figma w-full flex flex-col md:flex-row items-center gap-10 md:gap-16">
		
		<!-- Location Image -->
		<div class="w-full md:w-1/2 aspect-square md:aspect-[4/3] rounded-3xl overflow-hidden relative shadow-lg bg-tedx-card border border-white/10">
			<?php if ( $venue_image ) : ?>
				<img alt="<?php echo esc_attr( $venue_name ); ?>" class="absolute inset-0 w-full h-full object-cover" src="<?php echo esc_url( $venue_image ); ?>" />
			<?php else : ?>
				<div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center text-white/30 bg-gradient-to-br from-[#333] to-[#222]">
					<?php tedx_get_icon( 'info', 'w-12 h-12 mb-2 opacity-50' ); ?>
					<span class="font-bold text-lg">Venue Image</span>
				</div>
			<?php endif; ?>
		</div>
		
		<!-- Location Info -->
		<div class="w-full md:w-1/2 flex flex-col items-center text-center md:items-start md:text-left break-words">
			<h2 class="text-5xl md:text-6xl font-black text-white tracking-tight leading-tight mb-6">
				Venue
			</h2>
			
			<div class="flex flex-col gap-2 mb-8">
				<h3 class="text-2xl font-bold text-tedx-red tracking-tight">
					<?php echo esc_html( $venue_name ); ?>
				</h3>
				<div class="text-base text-white/80 leading-relaxed font-normal space-y-1">
					<p><?php echo esc_html( $venue_address_1 ); ?></p>
					<p><?php echo esc_html( $venue_address_2 ); ?></p>
				</div>
			</div>
			
			<?php if ( $venue_maps_url ) : ?>
			<a href="<?php echo esc_url( $venue_maps_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center border border-white/20 text-white hover:border-tedx-red hover:bg-tedx-red text-sm font-medium px-6 py-3 rounded-xl transition-all duration-200 hover:scale-[1.02]">
				<span>VIEW ON GOOGLE MAPS</span>
			</a>
			<?php endif; ?>
		</div>
		
	</div>
</section>
