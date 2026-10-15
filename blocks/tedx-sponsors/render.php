<?php
/**
 * Sponsors block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$sponsors = ! empty( $attributes['sponsors'] ) && is_array( $attributes['sponsors'] ) ? $attributes['sponsors'] : array();

if ( empty( $sponsors ) ) {
	echo tedx_block_editor_notice( __( 'No sponsor logos added yet. Add sponsor logos in the block settings.', 'tedx-regensburg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	return;
}

$layout     = $attributes['layout'] ?? 'grid';
$section_id = sanitize_html_class( $attributes['sectionId'] ?? ( 'featured' === $layout ? 'partner' : 'sponsors' ) );
$eyebrow    = $attributes['eyebrow'] ?? '';
$title      = $attributes['title'] ?? ( 'featured' === $layout ? 'Our Partner' : 'Other Sponsors' );
$desc       = $attributes['description'] ?? '';

$wrapper = get_block_wrapper_attributes(
	array(
		'id'              => $section_id,
		'class'           => 'bg-tedx-dark px-4 py-16 md:px-8',
		'aria-labelledby' => "{$section_id}-heading",
	)
);
?>

<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="mx-auto flex max-w-figma flex-col items-start gap-6">

		<?php if ( 'featured' === $layout ) : ?>
			<div class="flex w-full flex-col items-start gap-6 break-words font-medium">
				<?php if ( ! empty( $eyebrow ) ) : ?>
					<?php tedx_category_header( $eyebrow ); ?>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $section_id ); ?>-heading" class="w-full tedx-block-heading">
					<?php echo esc_html( $title ); ?>
				</h2>
			</div>

			<div class="w-full max-w-[301px] py-16">
				<?php
				$first_sponsor = reset( $sponsors );
				$sponsor_name  = $first_sponsor['name'] ?? __( 'Exclusive partner', 'tedx-regensburg' );
				$sponsor_link  = $first_sponsor['link'] ?? '';
				$logo_img      = tedx_block_image(
					$first_sponsor,
					'medium',
					array(
						'alt'   => $sponsor_name,
						'class' => 'tedx-sponsor-logo',
						'sizes' => '(max-width: 333px) calc(100vw - 32px), 301px',
					)
				);
				?>
				<div class="tedx-sponsor-logo-frame tedx-sponsor-logo-frame-featured">
					<?php if ( ! empty( $sponsor_link ) ) : ?>
						<a href="<?php echo esc_url( $sponsor_link ); ?>" class="tedx-sponsor-logo-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $sponsor_name ); ?>">
							<?php echo $logo_img; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php else : ?>
						<?php echo $logo_img; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>
			<?php if ( ! empty( $eyebrow ) ) : ?>
				<?php tedx_category_header( $eyebrow ); ?>
			<?php endif; ?>

			<h2 id="<?php echo esc_attr( $section_id ); ?>-heading" class="w-full tedx-block-heading">
				<?php echo esc_html( $title ); ?>
			</h2>

			<?php if ( ! empty( $desc ) ) : ?>
				<p class="w-full text-base leading-none text-white">
					<?php echo esc_html( $desc ); ?>
				</p>
			<?php endif; ?>

			<div class="grid w-full grid-cols-1 gap-6 overflow-hidden pt-10 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $sponsors as $s ) : ?>
					<?php
					$s_name = $s['name'] ?? '';
					$s_link = $s['link'] ?? '';
					$s_image = tedx_block_image(
						$s,
						'medium',
						array(
							'alt'   => $s_name,
							'class' => 'tedx-sponsor-logo',
							'sizes' => '(max-width: 639px) calc(100vw - 70px), (max-width: 767px) calc((100vw - 56px) / 2 - 38px), (max-width: 1023px) calc((100vw - 88px) / 2 - 38px), 280px',
						)
					);
					?>
					<article class="flex h-[92px] items-center justify-center overflow-hidden rounded-[32px] bg-tedx-card px-[19px] py-4">
						<div class="tedx-sponsor-logo-frame">
						<?php if ( $s_image ) : ?>
							<?php if ( ! empty( $s_link ) ) : ?>
								<a href="<?php echo esc_url( $s_link ); ?>" class="tedx-sponsor-logo-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $s_name ); ?>">
									<?php echo $s_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?>
								</a>
							<?php else : ?>
								<?php echo $s_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?>
							<?php endif; ?>
							</div>
						<?php else : ?>
							<span class="text-center text-sm font-medium text-white/60"><?php echo esc_html( $s_name ); ?></span>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
