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
				<h2 id="<?php echo esc_attr( $section_id ); ?>-heading" class="w-full text-5xl leading-none tracking-[-0.05em] text-white md:text-[64px]">
					<?php echo esc_html( $title ); ?>
				</h2>
			</div>

			<div class="w-full max-w-[301px] py-16">
				<?php
				$first_sponsor = reset( $sponsors );
				$logo_url      = $first_sponsor['url'] ?? '';
				$sponsor_name  = $first_sponsor['name'] ?? __( 'Exclusive partner', 'tedx-regensburg' );
				$sponsor_link  = $first_sponsor['link'] ?? '';
				$logo_img      = sprintf(
					'<img src="%1$s" alt="%2$s" class="h-full w-auto max-w-full object-contain" loading="lazy" decoding="async">',
					esc_url( $logo_url ),
					esc_attr( $sponsor_name )
				);
				?>
				<div class="flex h-10 items-center">
					<?php if ( ! empty( $sponsor_link ) ) : ?>
						<a href="<?php echo esc_url( $sponsor_link ); ?>" class="block h-full" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $sponsor_name ); ?>">
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

			<h2 id="<?php echo esc_attr( $section_id ); ?>-heading" class="w-full text-5xl font-medium leading-none tracking-[-0.05em] text-white md:text-[64px]">
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
					$s_url  = $s['url'] ?? '';
					$s_name = $s['name'] ?? '';
					$s_link = $s['link'] ?? '';
					?>
					<article class="flex h-[92px] items-center justify-center overflow-hidden rounded-[32px] bg-tedx-card px-[19px] py-4">
						<?php if ( ! empty( $s_url ) ) : ?>
							<?php if ( ! empty( $s_link ) ) : ?>
								<a href="<?php echo esc_url( $s_link ); ?>" class="flex h-full w-full items-center justify-center" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $s_name ); ?>">
									<img src="<?php echo esc_url( $s_url ); ?>" alt="<?php echo esc_attr( $s_name ); ?>" class="max-h-full w-auto max-w-full object-contain" loading="lazy" decoding="async">
								</a>
							<?php else : ?>
								<img src="<?php echo esc_url( $s_url ); ?>" alt="<?php echo esc_attr( $s_name ); ?>" class="max-h-full w-auto max-w-full object-contain" loading="lazy" decoding="async">
							<?php endif; ?>
						<?php else : ?>
							<span class="text-center text-sm font-medium text-white/60"><?php echo esc_html( $s_name ); ?></span>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
