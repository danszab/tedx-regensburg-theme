<?php
/**
 * Event Pitch Section Template Part (Figma: Event Pitch)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$pitch_title       = tedx_mod( 'tedx_pitch_title', __( 'This Events Title', 'tedx-regensburg' ) );
$pitch_meta        = tedx_mod( 'tedx_pitch_meta', 'TEDxREGENSBURG | NOV 14 | MARINAFORUM' );
$pitch_description = tedx_mod( 'tedx_pitch_description', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.' );
$ticket_url        = tedx_mod( 'tedx_ticket_url', '#tickets' );
$ticket_text       = tedx_mod( 'tedx_ticket_text', __( 'Tickets', 'tedx-regensburg' ) );
$show_more_url     = tedx_mod( 'tedx_show_more_url', '#about' );
$show_more_text    = tedx_mod( 'tedx_show_more_text', __( 'Show More', 'tedx-regensburg' ) );
$enable_tickets    = true;
$enable_show_more  = true;

if ( is_page() ) {
	$page_title = get_post_meta( get_the_ID(), '_event_pitch_title', true );
	if ( ! empty( $page_title ) ) {
		$pitch_title       = $page_title;
		$pitch_meta        = get_post_meta( get_the_ID(), '_event_pitch_meta', true );
		$pitch_description = get_post_meta( get_the_ID(), '_event_pitch_desc', true );
		$ticket_url        = get_post_meta( get_the_ID(), '_event_ticket_url', true ) ?: $ticket_url;
		$meta_show_more    = get_post_meta( get_the_ID(), '_event_show_more_url', true );
		if ( ! empty( $meta_show_more ) ) {
			$show_more_url = $meta_show_more;
		}
	}

	$meta_enable_tickets = get_post_meta( get_the_ID(), '_event_enable_tickets', true );
	if ( '' !== $meta_enable_tickets ) {
		$enable_tickets = ( '1' === $meta_enable_tickets );
	}

	$meta_enable_show_more = get_post_meta( get_the_ID(), '_event_enable_show_more', true );
	if ( '' !== $meta_enable_show_more ) {
		$enable_show_more = ( '1' === $meta_enable_show_more );
	}
}
?>

<section id="event-pitch" class="bg-tedx-surface py-16 md:py-24 px-4 md:px-8 lg:px-12 border-t border-white/5">
	<div class="max-w-figma mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
		
		<!-- Left Column: Event Information & Actions -->
		<div class="flex flex-col gap-6 items-start">
			
			<h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-none">
				<?php echo esc_html( $pitch_title ); ?>
			</h2>

			<div class="text-xs sm:text-sm font-semibold tracking-wider text-tedx-green uppercase">
				<?php echo esc_html( $pitch_meta ); ?>
			</div>

			<!-- Action Buttons -->
			<?php if ( $enable_tickets || $enable_show_more ) : ?>
			<div class="flex flex-wrap items-center gap-4 pt-2">
				<?php if ( $enable_tickets && ! empty( $ticket_url ) ) : ?>
				<a href="<?php echo esc_url( $ticket_url ); ?>" class="inline-flex items-center gap-2 bg-tedx-red hover:bg-tedx-red-hover text-white text-sm font-medium px-5 py-3 rounded-xl shadow-md transition-all duration-200 hover:scale-[1.02] active:scale-95">
					<?php echo tedx_get_icon( 'ticket', 'w-4 h-4 text-white' ); ?>
					<span><?php echo esc_html( $ticket_text ); ?></span>
				</a>
				<?php endif; ?>

				<?php if ( $enable_show_more && ! empty( $show_more_url ) ) : ?>
				<a href="<?php echo esc_url( $show_more_url ); ?>" class="inline-flex items-center justify-center border border-tedx-red text-tedx-red hover:bg-tedx-red/10 text-sm font-medium px-5 py-3 rounded-xl transition-all duration-200 hover:scale-[1.02]">
					<span><?php echo esc_html( $show_more_text ); ?></span>
				</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<!-- Event Pitch Body Copy -->
			<p class="text-base text-white/90 leading-relaxed font-normal mt-2">
				<?php echo esc_html( $pitch_description ); ?>
			</p>

		</div>

		<!-- Right Column: Visual Theme Graphic Card -->
		<div class="flex justify-center lg:justify-end">
			<div class="w-full max-w-[460px] aspect-[4/5] rounded-[40px] md:rounded-[48px] border border-[#555] bg-theme-gold-gradient relative p-8 md:p-10 flex flex-col justify-end shadow-2xl overflow-hidden group">
				<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent pointer-events-none"></div>
				
				<h3 class="relative z-10 text-3xl sm:text-4xl font-medium text-white tracking-tight leading-tight">
					<?php esc_html_e( 'This Events Theme', 'tedx-regensburg' ); ?>
				</h3>
			</div>
		</div>

	</div>
</section>
