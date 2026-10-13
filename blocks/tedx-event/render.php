<?php
/**
 * Event block render (event pitch + visual theme card).
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$linked_id   = (int) $attributes['linkedEventId'];
$linked_url  = ( $linked_id && 'tedx_event' === get_post_type( $linked_id ) ) ? get_permalink( $linked_id ) : '';

$show_more_url = ! empty( $attributes['showMoreUrl'] ) ? $attributes['showMoreUrl'] : $linked_url;
$card_url      = ! empty( $attributes['cardUrl'] ) ? $attributes['cardUrl'] : $linked_url;

$enable_tickets   = ! empty( $attributes['showTickets'] ) && ! empty( $attributes['ticketUrl'] );
$enable_show_more = ! empty( $attributes['showMore'] ) && ! empty( $show_more_url );

$card_image = ! empty( $attributes['cardImage']['url'] ) ? $attributes['cardImage']['url'] : '';
$card_alt   = ! empty( $attributes['cardImage']['alt'] ) ? $attributes['cardImage']['alt'] : $attributes['title'];

$is_link = ! empty( $attributes['cardClickable'] ) && ! empty( $card_url );
$tag     = $is_link ? 'a' : 'div';

$container_classes = 'event-card-container w-full max-w-[460px] aspect-[4/5] rounded-[40px] md:rounded-[48px] border-2 border-white/40 relative flex flex-col justify-between overflow-hidden shadow-2xl p-6 sm:p-8 ';
$container_classes .= $is_link
	? 'event-card-clickable cursor-pointer hover:border-white/60 hover:scale-[1.01] active:scale-95 transition-all duration-500 group'
	: 'cursor-default transition-none select-none';

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $attributes['sectionId'] ),
		'class' => 'bg-tedx-surface py-16 md:py-24 px-4 md:px-8 lg:px-12 border-t border-white/5',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

		<div class="flex flex-col gap-6 items-start">
			<?php tedx_category_header( $attributes['eyebrow'] ); ?>

			<h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-none">
				<?php echo esc_html( $attributes['title'] ); ?>
			</h2>

			<?php if ( $enable_tickets || $enable_show_more ) : ?>
				<div class="flex flex-wrap items-center gap-4 pt-2">
					<?php
					if ( $enable_tickets ) {
						echo tedx_block_button( $attributes['ticketText'] ?: __( 'Tickets', 'tedx-regensburg' ), $attributes['ticketUrl'], 'solid', 'ticket' ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					if ( $enable_show_more ) {
						echo tedx_block_button( $attributes['showMoreText'] ?: __( 'Show More', 'tedx-regensburg' ), $show_more_url, 'outline' ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $attributes['description'] ) ) : ?>
				<p class="text-base text-white/90 leading-relaxed font-normal mt-2">
					<?php echo esc_html( $attributes['description'] ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="flex justify-center <?php echo ! empty( $attributes['reverseColumns'] ) ? 'lg:justify-start lg:order-first' : 'lg:justify-end'; ?>">
			<<?php echo $tag; ?> <?php echo $is_link ? 'href="' . esc_url( $card_url ) . '"' : ''; ?> class="<?php echo esc_attr( $container_classes ); ?>">
				<?php if ( $card_image ) : ?>
					<img src="<?php echo esc_url( $card_image ); ?>" alt="<?php echo esc_attr( $card_alt ); ?>" class="event-card-image absolute inset-0 w-full h-full object-cover object-center pointer-events-none" />
				<?php else : ?>
					<div class="event-card-image absolute inset-0 w-full h-full bg-gradient-to-br from-[#2a1015] via-[#1c1c1c] to-[#0a0a0a] pointer-events-none"></div>
					<div class="absolute inset-0 w-full h-full bg-radial-gradient from-tedx-red/20 to-transparent opacity-60 pointer-events-none"></div>
				<?php endif; ?>

				<div class="relative z-10 w-full flex justify-center">
					<?php if ( ! empty( $attributes['showCardLabel'] ) && ! empty( $attributes['cardLabel'] ) ) : ?>
						<div class="bg-black/60 border-2 border-black/40 px-5 py-2.5 rounded-full shadow-lg backdrop-blur-sm flex items-center justify-center">
							<span class="text-white font-bold text-sm sm:text-[16px] tracking-[0.8px] whitespace-nowrap text-center">
								<?php echo esc_html( $attributes['cardLabel'] ); ?>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</<?php echo $tag; ?>>
		</div>

	</div>
</section>
