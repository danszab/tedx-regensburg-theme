<?php
/**
 * Event Card Component (Figma Node 166:1430)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// 1. Identify Context ('hero' or 'pitch')
$context = isset( $args['context'] ) && 'hero' === $args['context'] ? 'hero' : 'pitch';

if ( 'hero' === $context ) {
	$customizer_prefix = 'tedx_hero_card_';
	$meta_prefix       = '_hero_card_';
	$default_title     = tedx_mod( 'tedx_theme_name', __( 'This years Theme', 'tedx-regensburg' ) );
	$default_label     = 'TEDxRegensburg ' . tedx_mod( 'tedx_event_year', '2026' );
} else {
	$customizer_prefix = 'tedx_card_';
	$meta_prefix       = '_event_card_';
	$default_title     = __( 'This Events Theme', 'tedx-regensburg' );
	$default_label     = 'Nov. 14 | TEDxRegensburg';
}

$selected_event_id = 0;

// If we are on an event page, the pitch card inherently represents this event
if ( 'pitch' === $context && is_singular( 'tedx_event' ) ) {
	$selected_event_id = get_the_ID();
} else {
	// Otherwise, check if a global event is selected in the Customizer
	$selected_event_id = ( 'hero' === $context ) ? tedx_mod( 'tedx_hero_selected_event', 0 ) : tedx_mod( 'tedx_pitch_selected_event', 0 );
}

// If an event ID is resolved (either current page or from Customizer dropdown)
if ( ! empty( $selected_event_id ) && get_post_type( $selected_event_id ) === 'tedx_event' ) {
	$card_image        = get_post_meta( $selected_event_id, '_event_card_image', true );
	if ( empty( $card_image ) ) {
		$card_image = get_the_post_thumbnail_url( $selected_event_id, 'large' );
	}
	
	// Use customizer toggle for visibility, but pull text from the database event
	$card_show_label   = (bool) tedx_mod( $customizer_prefix . 'show_label', true );
	$card_label_text   = get_post_meta( $selected_event_id, '_event_card_label_text', true ) ?: 'TEDxRegensburg ' . (get_post_meta($selected_event_id, '_event_year', true) ?: '2026');
	
	// Use customizer toggle for clickability, but pull link from the database event
	$card_is_clickable = (bool) tedx_mod( $customizer_prefix . 'is_clickable', false );
	$card_link_url     = get_post_meta( $selected_event_id, '_event_card_link_url', true ) ?: get_permalink( $selected_event_id );
	
} else {
	// Fallback: Manual Customizer settings
	$card_image        = tedx_mod( $customizer_prefix . 'image', '' );
	$card_show_label   = (bool) tedx_mod( $customizer_prefix . 'show_label', true );
	$card_label_text   = tedx_mod( $customizer_prefix . 'label_text', $default_label );
	$card_is_clickable = (bool) tedx_mod( $customizer_prefix . 'is_clickable', false );
	$card_link_url     = tedx_mod( $customizer_prefix . 'link_url', '' );
}

// 2. Compute Clickable State & Animation Classes
$is_link = $card_is_clickable && ! empty( $card_link_url );
$tag = $is_link ? 'a' : 'div';
$link_attrs = $is_link ? 'href="' . esc_url( $card_link_url ) . '" target="_blank" rel="noopener noreferrer"' : '';

// Base container classes: exact Figma 166:1430 rounded-[48px] + border-2 border-white/40
$container_classes = 'event-card-container w-full max-w-[460px] aspect-[4/5] rounded-[40px] md:rounded-[48px] border-2 border-white/40 relative flex flex-col justify-between overflow-hidden shadow-2xl p-6 sm:p-8 ';
if ( $is_link ) {
	$container_classes .= 'event-card-clickable cursor-pointer hover:border-white/60 hover:scale-[1.01] active:scale-95 transition-all duration-500 group';
} else {
	$container_classes .= 'cursor-default transition-none select-none';
}
?>

<<?php echo $tag; ?> <?php echo $link_attrs; ?> class="<?php echo esc_attr( $container_classes ); ?>">
	
	<!-- Background Image / Visual Layer (Strictly fills the container) -->
	<?php if ( ! empty( $card_image ) ) : ?>
		<img 
			src="<?php echo esc_url( $card_image ); ?>" 
			alt="<?php echo esc_attr( $card_theme_title ); ?>" 
			class="event-card-image absolute inset-0 w-full h-full object-cover object-center pointer-events-none" 
		/>
	<?php else : ?>
		<!-- Default Background Gradient Layer -->
		<div class="event-card-image absolute inset-0 w-full h-full bg-gradient-to-br from-[#2a1015] via-[#1c1c1c] to-[#0a0a0a] pointer-events-none"></div>
		<div class="absolute inset-0 w-full h-full bg-radial-gradient from-tedx-red/20 to-transparent opacity-60 pointer-events-none"></div>
	<?php endif; ?>


	<!-- Top Pill Badge (Date / Event Label - Optional via checkbox) -->
	<div class="relative z-10 w-full flex justify-center">
		<?php if ( $card_show_label && ! empty( $card_label_text ) ) : ?>
			<div class="bg-black/60 border-2 border-black/40 px-5 py-2.5 rounded-full shadow-lg backdrop-blur-sm flex items-center justify-center">
				<span class="text-white font-bold text-sm sm:text-[16px] tracking-[0.8px] whitespace-nowrap text-center">
					<?php echo esc_html( $card_label_text ); ?>
				</span>
			</div>
		<?php endif; ?>
	</div>

</<?php echo $tag; ?>>
