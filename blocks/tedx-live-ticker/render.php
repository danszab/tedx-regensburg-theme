<?php
/**
 * Render template for TEDx Live Event Ticker Block
 *
 * Minimalist, floating one-liner pill anchored at the top of the viewport.
 * Shows LIVE session, or UP NEXT, or FINISHED.
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow_live     = ! empty( $attributes['eyebrowLive'] ) ? $attributes['eyebrowLive'] : 'LIVE';
$eyebrow_next     = ! empty( $attributes['eyebrowNext'] ) ? $attributes['eyebrowNext'] : 'UP NEXT';
$eyebrow_finished = ! empty( $attributes['eyebrowFinished'] ) ? $attributes['eyebrowFinished'] : 'FINISHED';
$jump_to_table    = isset( $attributes['jumpToTimetable'] ) ? (bool) $attributes['jumpToTimetable'] : true;
$event_date       = ! empty( $attributes['eventDate'] ) ? trim( $attributes['eventDate'] ) : '';
$test_mode        = ! empty( $attributes['testMode'] );
$items            = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

if ( empty( $items ) ) {
	$items = array(
		array(
			'startTime' => '14:00',
			'endTime'   => '14:30',
			'title'     => 'Registration & Welcome Reception',
		),
		array(
			'startTime' => '14:30',
			'endTime'   => '15:00',
			'title'     => 'Opening Session & Introduction',
		),
		array(
			'startTime' => '15:00',
			'endTime'   => '16:30',
			'title'     => 'Session 1: Ideas Worth Spreading',
		),
		array(
			'startTime' => '16:30',
			'endTime'   => '17:15',
			'title'     => 'Networking Break & Food',
		),
		array(
			'startTime' => '17:15',
			'endTime'   => '18:45',
			'title'     => 'Session 2: Future Horizons',
		),
		array(
			'startTime' => '18:45',
			'endTime'   => '19:15',
			'title'     => 'Closing Remarks & Afterparty',
		),
	);
}

// Initial state calculation for server-side rendering
$initial_state = 'next';
$initial_item  = $items[0];

// Check navbar visibility from WordPress helper
$navbar_hidden = function_exists( 'tedx_is_navbar_hidden' ) ? tedx_is_navbar_hidden() : false;

$wrapper_classes = 'tedx-live-ticker-block' . ( $navbar_hidden ? ' tedx-navbar-hidden' : ' tedx-has-navbar' );
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( array(
	'class' => $wrapper_classes,
) );
?>

<div <?php echo $wrapper_attributes; ?>
	 data-eyebrow-live="<?php echo esc_attr( $eyebrow_live ); ?>"
	 data-eyebrow-next="<?php echo esc_attr( $eyebrow_next ); ?>"
	 data-eyebrow-finished="<?php echo esc_attr( $eyebrow_finished ); ?>"
	 data-jump="<?php echo $jump_to_table ? '1' : '0'; ?>"
	 data-event-date="<?php echo esc_attr( $event_date ); ?>"
	 data-test-mode="<?php echo $test_mode ? '1' : '0'; ?>"
	 data-items="<?php echo esc_attr( wp_json_encode( $items ) ); ?>"
	 role="region"
	 aria-label="<?php esc_attr_e( 'Current Event Status', 'tedx-regensburg' ); ?>">

	<div class="tedx-live-ticker-floating-wrapper">
		<a href="<?php echo $jump_to_table ? '#timetable' : 'javascript:void(0)'; ?>"
		   class="tedx-live-ticker-pill"
		   tabindex="<?php echo $jump_to_table ? '0' : '-1'; ?>">

			<!-- Status indicator badge -->
			<span class="tedx-live-ticker-badge badge-<?php echo esc_attr( $initial_state ); ?>">
				<span class="tedx-live-ticker-dot" aria-hidden="true"></span>
				<span class="tedx-live-ticker-badge-text"><?php echo esc_html( 'live' === $initial_state ? $eyebrow_live : ( 'finished' === $initial_state ? $eyebrow_finished : $eyebrow_next ) ); ?></span>
			</span>

			<!-- Session Title -->
			<span class="tedx-live-ticker-title">
				<?php echo esc_html( $initial_item['title'] ); ?>
			</span>

			<!-- Time badge -->
			<span class="tedx-live-ticker-time">
				<?php echo esc_html( $initial_item['startTime'] ); ?>
				<?php if ( ! empty( $initial_item['endTime'] ) ) : ?>
					&ndash; <?php echo esc_html( $initial_item['endTime'] ); ?>
				<?php endif; ?>
			</span>

			<?php if ( $jump_to_table ) : ?>
				<!-- Chevron Icon -->
				<span class="tedx-live-ticker-arrow" aria-hidden="true">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<path d="M6 9l6 6 6-6"></path>
					</svg>
				</span>
			<?php endif; ?>

		</a>
	</div>

</div>
