<?php
/**
 * Press Gallery Block Render (Simple grid of image previews with multi-line description and download button).
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title         = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'Press Gallery', 'tedx-regensburg' );
$eyebrow       = ! empty( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '';
$section_id    = ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : 'press-gallery';
$download_text = ! empty( $attributes['downloadText'] ) ? $attributes['downloadText'] : __( 'Download high-resolution image', 'tedx-regensburg' );
$items         = ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

if ( empty( $items ) ) {
	if ( function_exists( 'tedx_block_editor_notice' ) ) {
		echo tedx_block_editor_notice( __( 'Add images in the block settings to populate the Press Gallery.', 'tedx-regensburg' ) );
	}
	return;
}

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $section_id ),
		'class' => 'bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full border-t border-white/5',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto w-full">

		<?php if ( ! empty( $eyebrow ) && function_exists( 'tedx_category_header' ) ) : ?>
			<div class="mb-3">
				<?php tedx_category_header( $eyebrow, '', 'p' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="tedx-block-heading mb-8 md:mb-12">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$image = ! empty( $item['image'] ) ? $item['image'] : array();
				$image_id = absint( $image['id'] ?? 0 );
				$image_url = ! empty( $image['url'] ) ? $image['url'] : '';
				if ( ! $image_id && ! empty( $image_url ) && function_exists( 'attachment_url_to_postid' ) ) {
					$image_id = attachment_url_to_postid( $image_url );
				}

				// High resolution full download URL
				$download_url = '';
				if ( $image_id && function_exists( 'wp_get_attachment_url' ) ) {
					$download_url = wp_get_attachment_url( $image_id );
				}
				if ( empty( $download_url ) ) {
					$download_url = $image_url;
				}

				$desc = ! empty( $item['description'] ) ? $item['description'] : '';
				?>
				<article class="tedx-press-gallery-card flex flex-col items-start bg-transparent">
					<div class="tedx-press-gallery-image w-full aspect-[3/2] overflow-hidden rounded-3xl bg-tedx-card mb-4 border border-white/5">
						<?php
						if ( function_exists( 'tedx_block_image' ) ) {
							echo tedx_block_image(
								array(
									'id'  => $image_id,
									'url' => $image_url,
								),
								'medium_large',
								array(
									'class'   => 'w-full h-full object-cover',
									'alt'     => $image['alt'] ?? '',
									'loading' => 'lazy',
								)
							);
						} elseif ( ! empty( $image_url ) ) {
							echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image['alt'] ?? '' ) . '" class="w-full h-full object-cover" loading="lazy">';
						} else {
							echo '<div class="w-full h-full flex items-center justify-center text-white/30 text-sm">' . esc_html__( 'No image', 'tedx-regensburg' ) . '</div>';
						}
						?>
					</div>

					<?php if ( ! empty( $desc ) ) : ?>
						<div class="tedx-press-gallery-desc text-sm text-white/80 leading-snug mb-3">
							<?php echo wp_kses_post( nl2br( $desc ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $download_url ) ) : ?>
						<a
							href="<?php echo esc_url( $download_url ); ?>"
							download
							target="_blank"
							rel="noopener noreferrer"
							class="tedx-press-gallery-download inline-flex items-center gap-1.5 text-sm font-medium text-tedx-red hover:text-white hover:underline underline-offset-4 transition-colors mt-auto pt-1"
							aria-label="<?php echo esc_attr( sprintf( __( '%s: %s', 'tedx-regensburg' ), $download_text, $image['alt'] ?? basename( $download_url ) ) ); ?>"
						>
							<span><?php echo esc_html( $download_text ); ?></span>
							<svg class="w-4 h-4 shrink-0 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
								<polyline points="7 10 12 15 17 10"></polyline>
								<line x1="12" y1="15" x2="12" y2="3"></line>
							</svg>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
