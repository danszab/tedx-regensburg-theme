<?php
/**
 * Q&A Block Render (shadcn / Base UI Accordion pattern).
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$title       = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'Frequently Asked Questions', 'tedx-regensburg' );
$eyebrow     = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : 'Q&A';
$section_id  = ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : 'faq';
$type        = ! empty( $attributes['type'] ) ? $attributes['type'] : 'single';
$collapsible = isset( $attributes['collapsible'] ) ? (bool) $attributes['collapsible'] : true;
$items       = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

$unique_id = 'tedx-accordion-' . wp_unique_id();

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $section_id ),
		'class' => 'bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full border-t border-white/5',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto w-full">

		<?php if ( ! empty( $eyebrow ) ) : ?>
			<div class="mb-3">
				<?php
				if ( function_exists( 'tedx_category_header' ) ) {
					tedx_category_header( $eyebrow, '', 'p' );
				} else {
					echo '<p class="block w-full text-[13px] font-medium uppercase leading-none tracking-[0.65px] text-tedx-green">' . esc_html( $eyebrow ) . '</p>';
				}
				?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="tedx-block-heading mb-8 md:mb-12">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div
				class="tedx-accordion w-full divide-y divide-white/10"
				data-accordion-type="<?php echo esc_attr( $type ); ?>"
				data-accordion-collapsible="<?php echo $collapsible ? 'true' : 'false'; ?>"
			>
				<?php foreach ( $items as $index => $item ) : ?>
					<?php
					$question = ! empty( $item['question'] ) ? $item['question'] : '';
					$answer   = ! empty( $item['answer'] ) ? $item['answer'] : '';
					$item_id  = $unique_id . '-item-' . $index;
					$trig_id  = $item_id . '-trigger';
					$cont_id  = $item_id . '-content';
					?>
					<div class="tedx-accordion-item border-b border-white/10" data-state="closed">
						<h3 class="m-0 p-0 font-normal">
							<button
								type="button"
								id="<?php echo esc_attr( $trig_id ); ?>"
								class="tedx-accordion-trigger group flex w-full flex-1 items-center justify-between py-5 text-left text-lg sm:text-xl font-medium text-white transition-all hover:underline hover:underline-offset-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-tedx-red"
								aria-controls="<?php echo esc_attr( $cont_id ); ?>"
								aria-expanded="false"
								data-state="closed"
							>
								<span class="pr-4"><?php echo esc_html( $question ); ?></span>
								<span class="tedx-accordion-chevron shrink-0 text-white/60 transition-transform duration-200 group-hover:text-white" aria-hidden="true">
									<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<polyline points="6 9 12 15 18 9"></polyline>
									</svg>
								</span>
							</button>
						</h3>
						<div
							id="<?php echo esc_attr( $cont_id ); ?>"
							class="tedx-accordion-content overflow-hidden text-sm sm:text-base text-white/80"
							role="region"
							aria-labelledby="<?php echo esc_attr( $trig_id ); ?>"
							data-state="closed"
						>
							<div class="tedx-accordion-body pb-5 pt-1 leading-relaxed">
								<?php echo wp_kses_post( wpautop( $answer ) ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
