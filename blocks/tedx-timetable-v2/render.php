<?php
/**
 * Render template for TEDx Timetable v2 Block
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title         = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'Timetable', 'tedx-regensburg' );
$eyebrow       = ! empty( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : __( 'SCHEDULE', 'tedx-regensburg' );
$event_date    = ! empty( $attributes['eventDate'] ) ? trim( $attributes['eventDate'] ) : '';
$show_progress = isset( $attributes['showProgress'] ) ? (bool) $attributes['showProgress'] : true;
$test_mode     = ! empty( $attributes['testMode'] );
$items         = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

if ( empty( $items ) ) {
	$items = array(
		array(
			'startTime'     => '14:00',
			'endTime'       => '14:30',
			'title'         => 'Registration & Welcome Reception',
			'description'   => 'Check in, collect your badge, and enjoy light refreshments in the lounge.',
			'hasButton'     => false,
			'buttonText'    => 'Details',
			'buttonUrl'     => '',
			'buttonNewTab'  => false,
			'buttonVariant' => 'outline',
		),
		array(
			'startTime'     => '14:30',
			'endTime'       => '15:00',
			'title'         => 'Opening Session & Introduction',
			'description'   => 'Opening remarks from the TEDx team and stage performance.',
			'hasButton'     => true,
			'buttonText'    => 'Livestream',
			'buttonUrl'     => '#livestream',
			'buttonNewTab'  => false,
			'buttonVariant' => 'outline',
		),
		array(
			'startTime'     => '15:00',
			'endTime'       => '16:30',
			'title'         => 'Session 1: Ideas Worth Spreading',
			'description'   => 'First series of inspiring talks from four distinguished guest speakers.',
			'hasButton'     => true,
			'buttonText'    => 'Speakers',
			'buttonUrl'     => '#speakers',
			'buttonNewTab'  => false,
			'buttonVariant' => 'outline',
		),
		array(
			'startTime'     => '16:30',
			'endTime'       => '17:15',
			'title'         => 'Networking Break & Food',
			'description'   => 'Connect with attendees, speakers, and partners with snacks and coffee.',
			'hasButton'     => false,
			'buttonText'    => 'Details',
			'buttonUrl'     => '',
			'buttonNewTab'  => false,
			'buttonVariant' => 'outline',
		),
		array(
			'startTime'     => '17:15',
			'endTime'       => '18:45',
			'title'         => 'Session 2: Future Horizons',
			'description'   => 'Second series of talks covering innovation, community, and technology.',
			'hasButton'     => true,
			'buttonText'    => 'Schedule',
			'buttonUrl'     => '#schedule',
			'buttonNewTab'  => false,
			'buttonVariant' => 'outline',
		),
		array(
			'startTime'     => '18:45',
			'endTime'       => '19:15',
			'title'         => 'Closing Remarks & Afterparty',
			'description'   => 'Final curtain call, thank-yous, and invitation to the evening reception.',
			'hasButton'     => false,
			'buttonText'    => 'Details',
			'buttonUrl'     => '',
			'buttonNewTab'  => false,
			'buttonVariant' => 'outline',
		),
	);
}

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes, array( 'has_border' => false ) )
	: 'pt-8 md:pt-12 pb-8 md:pb-12';

$wrapper_classes = 'tedx-timetable-v2-block bg-tedx-dark ' . $spacing_classes . ' px-4 md:px-8 lg:px-12 text-white';
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( array(
	'class' => $wrapper_classes,
) );
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="tedx-timetable-v2-container max-w-figma mx-auto">
		
		<?php if ( ! empty( $eyebrow ) ) : ?>
			<div class="mb-3">
				<?php 
				if ( function_exists( 'tedx_category_header' ) ) {
					tedx_category_header( $eyebrow, '', 'p' );
				} else {
					echo '<p class="text-[13px] font-medium uppercase tracking-[0.65px] text-tedx-green leading-none m-0">' . esc_html( $eyebrow ) . '</p>';
				}
				?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="tedx-timetable-v2-title tedx-block-heading mb-8 md:mb-10">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<div class="tedx-timetable-v2-timeline"
			 data-event-date="<?php echo esc_attr( $event_date ); ?>"
			 data-show-progress="<?php echo $show_progress ? '1' : '0'; ?>"
			 data-test-mode="<?php echo $test_mode ? '1' : '0'; ?>">

			<?php if ( $show_progress ) : ?>
				<!-- Continuous Vertical Progress Rail on the Left -->
				<div class="tedx-timetable-v2-rail" aria-hidden="true">
					<div class="tedx-timetable-v2-progress-fill"></div>
					<div class="tedx-timetable-v2-progress-thumb"></div>
				</div>
			<?php endif; ?>

			<!-- Uniform Cards Stack in Single-Row Layout -->
			<div class="tedx-timetable-v2-list">
				<?php foreach ( $items as $idx => $item ) : ?>
					<?php
					$start_time   = ! empty( $item['startTime'] ) ? $item['startTime'] : '';
					$end_time     = ! empty( $item['endTime'] ) ? $item['endTime'] : '';
					$item_title   = ! empty( $item['title'] ) ? $item['title'] : '';
					$item_desc    = ! empty( $item['description'] ) ? $item['description'] : '';
					$has_button   = ! empty( $item['hasButton'] );
					$btn_text     = ! empty( $item['buttonText'] ) ? $item['buttonText'] : __( 'Details', 'tedx-regensburg' );
					$btn_url      = ! empty( $item['buttonUrl'] ) ? trim( $item['buttonUrl'] ) : '';
					$btn_new_tab  = ! empty( $item['buttonNewTab'] );
					$btn_variant  = ! empty( $item['buttonVariant'] ) ? $item['buttonVariant'] : 'outline';
					?>
					<article class="tedx-timetable-v2-card"
							 data-index="<?php echo esc_attr( (string) $idx ); ?>"
							 data-start="<?php echo esc_attr( $start_time ); ?>"
							 data-end="<?php echo esc_attr( $end_time ); ?>">
						
						<!-- Time Range Column -->
						<div class="tedx-timetable-v2-time">
							<span class="tedx-timetable-v2-time-badge">
								<?php echo esc_html( $start_time ); ?>
								<?php if ( ! empty( $end_time ) ) : ?>
									&ndash; <?php echo esc_html( $end_time ); ?>
								<?php endif; ?>
							</span>
						</div>

						<!-- Content Column (Title & Description) -->
						<div class="tedx-timetable-v2-content">
							<h3 class="tedx-timetable-v2-session-title">
								<?php echo esc_html( $item_title ); ?>
							</h3>
							<?php if ( ! empty( $item_desc ) ) : ?>
								<p class="tedx-timetable-v2-description">
									<?php echo esc_html( $item_desc ); ?>
								</p>
							<?php endif; ?>
						</div>

						<!-- Optional Action Button (shadcn small) -->
						<?php if ( $has_button && ! empty( $btn_url ) ) : ?>
							<div class="tedx-timetable-v2-action">
								<a href="<?php echo esc_url( $btn_url ); ?>"
								   class="tedx-timetable-v2-btn variant-<?php echo esc_attr( sanitize_html_class( $btn_variant ) ); ?> btn-shadcn-anim"
								   <?php echo $btn_new_tab ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
									<span><?php echo esc_html( $btn_text ); ?></span>
								</a>
							</div>
						<?php endif; ?>

					</article>
				<?php endforeach; ?>
			</div>

		</div>

	</div>
</section>
