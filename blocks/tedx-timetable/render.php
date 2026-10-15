<?php
/**
 * Render template for TEDx Timetable Block
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title     = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'Timetable', 'tedx-regensburg' );
$eyebrow   = ! empty( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '';
$show_line = isset( $attributes['showLine'] ) ? (bool) $attributes['showLine'] : true;
$items     = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

if ( empty( $items ) ) {
	// Default items matching Figma design
	$items = array(
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => true ),
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => false ),
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => false ),
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => false ),
		array( 'time' => '15:00', 'title' => 'Break', 'isHighlight' => true ),
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => false ),
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => false ),
		array( 'time' => '15:00', 'title' => 'Start', 'isHighlight' => false ),
		array( 'time' => '15:00', 'title' => 'End',   'isHighlight' => true ),
	);
}

$wrapper_classes = 'tedx-timetable-block py-12 md:py-20 px-4 md:px-8 text-white';
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( array(
	'class' => $wrapper_classes,
) );
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="tedx-timetable-container max-w-figma mx-auto">
		
		<?php if ( ! empty( $eyebrow ) ) : ?>
			<div class="mb-3">
				<?php 
				if ( function_exists( 'tedx_category_header' ) ) {
					tedx_category_header( $eyebrow, '', 'p' );
				} else {
					echo '<p class="text-[13px] font-medium uppercase tracking-[0.65px] text-tedx-green leading-none">' . esc_html( $eyebrow ) . '</p>';
				}
				?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="tedx-timetable-title tedx-block-heading mb-8 md:mb-10">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<div class="tedx-timetable-list relative flex flex-col gap-6 py-4">
			<?php if ( $show_line ) : ?>
				<!-- Continuous Timeline Line running through dot centers -->
				<div class="tedx-timetable-line absolute left-[7px] top-[14px] bottom-[14px] w-[2px] bg-white/20 pointer-events-none" aria-hidden="true"></div>
			<?php endif; ?>

			<?php foreach ( $items as $item ) : ?>
				<?php
				$is_highlight = ! empty( $item['isHighlight'] );
				$time         = ! empty( $item['time'] ) ? $item['time'] : '';
				$session_name = ! empty( $item['title'] ) ? $item['title'] : '';
				$row_class    = $is_highlight ? 'is-highlight' : 'is-standard';
				$color_class  = $is_highlight ? 'text-tedx-green' : 'text-white';
				$dot_class    = $is_highlight ? 'bg-tedx-green shadow-glow-green' : 'bg-white shadow-[0_0_8px_rgba(255,255,255,0.4)]';
				?>
				<div class="tedx-timetable-row relative z-10 flex items-center gap-6 md:gap-[59px] <?php echo esc_attr( $row_class ); ?>">
					
					<!-- 16px Timeline Dot -->
					<div class="tedx-timetable-dot w-4 h-4 rounded-full shrink-0 <?php echo esc_attr( $dot_class ); ?>" aria-hidden="true"></div>

					<!-- Time Column -->
					<div class="tedx-timetable-time w-[80px] md:w-[112px] shrink-0 font-bold text-base <?php echo esc_attr( $color_class ); ?>">
						<?php echo esc_html( $time ); ?>
					</div>

					<!-- Activity / Session Title -->
					<div class="tedx-timetable-text flex-1 font-medium text-base <?php echo esc_attr( $color_class ); ?>">
						<?php echo esc_html( $session_name ); ?>
					</div>

				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
