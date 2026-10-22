<?php
/**
 * What to Expect block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$title      = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'What to expect', 'tedx-regensburg' );
$eyebrow    = ! empty( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '';
$section_id = ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : 'what-to-expect';
$cards      = ! empty( $attributes['cards'] ) && is_array( $attributes['cards'] ) ? $attributes['cards'] : array();

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

		<?php if ( ! empty( $cards ) ) : ?>
			<div class="grid grid-cols-1 sm:grid-cols-3 min-[1000px]:grid-cols-4 gap-4 sm:gap-6">
				<?php foreach ( $cards as $card ) : ?>
					<?php
					$card_title = ! empty( $card['title'] ) ? $card['title'] : '';
					$card_desc  = ! empty( $card['description'] ) ? $card['description'] : '';
					$icon_key   = ! empty( $card['icon'] ) ? $card['icon'] : 'globe';
					$icon_html  = '';

					if ( 'custom' === $icon_key ) {
						$icon_id  = absint( $card['customIconId'] ?? 0 );
						$icon_url = '';
						if ( $icon_id ) {
							$mime = function_exists( 'get_post_mime_type' ) ? get_post_mime_type( $icon_id ) : '';
							if ( 'image/svg+xml' === $mime && function_exists( 'wp_get_attachment_url' ) ) {
								$icon_url = wp_get_attachment_url( $icon_id );
							} elseif ( function_exists( 'wp_get_attachment_image_url' ) ) {
								$icon_url = wp_get_attachment_image_url( $icon_id, 'thumbnail' );
							}
						}
						if ( empty( $icon_url ) && ! empty( $card['customIconUrl'] ) ) {
							$icon_url = $card['customIconUrl'];
						}

						if ( ! empty( $icon_url ) ) {
							$icon_html = '<span class="tedx-what-to-expect-custom-icon inline-block w-4 h-4 bg-tedx-red" aria-hidden="true" style="' . esc_attr( '-webkit-mask-image: url("' . esc_url( $icon_url ) . '"); mask-image: url("' . esc_url( $icon_url ) . '"); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;' ) . '"></span>';
						} elseif ( function_exists( 'tedx_block_editor_notice' ) ) {
							$icon_html = tedx_block_editor_notice( __( 'Select an icon or image from the Media Library.', 'tedx-regensburg' ) );
						}
					} else {
						// Check local theme SVG icon files first
						$candidate_files = array(
							"assets/images/icons/{$icon_key}.svg",
							"assets/images/icons/{$icon_key}.png",
						);
						foreach ( $candidate_files as $rel_file ) {
							$full_path = defined( 'TEDX_DIR' ) ? TEDX_DIR . '/' . $rel_file : dirname( __DIR__, 2 ) . '/' . $rel_file;
							if ( file_exists( $full_path ) ) {
								if ( 'svg' === pathinfo( $rel_file, PATHINFO_EXTENSION ) ) {
									$svg_data  = file_get_contents( $full_path );
									$svg_data  = preg_replace( '/<\?xml.*?\?>/i', '', $svg_data );
									$icon_html = '<div class="w-4 h-4 flex items-center justify-center [&>svg]:w-4 [&>svg]:h-4 [&>svg]:fill-current text-tedx-red">' . $svg_data . '</div>';
								} else {
									$icon_uri  = defined( 'TEDX_URI' ) ? TEDX_URI . '/' . $rel_file : $rel_file;
									$icon_html = '<img src="' . esc_url( $icon_uri ) . '" alt="" class="w-4 h-4 object-contain">';
								}
								break;
							}
						}

						if ( empty( $icon_html ) && function_exists( 'tedx_get_icon' ) ) {
							$icon_html = tedx_get_icon( $icon_key, 'w-4 h-4 fill-current' );
						}
					}
					?>
					<article class="tedx-what-to-expect-card border-2 border-tedx-card rounded-4xl p-4 sm:p-6 flex flex-col gap-3 bg-transparent h-full min-h-[178px] transition-all duration-300 hover:border-white/20">
						<div class="flex items-center gap-3 w-full">
							<div class="w-4 h-4 shrink-0 text-tedx-red flex items-center justify-center" aria-hidden="true">
								<?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</div>
							<h3 class="font-medium text-base leading-normal text-tedx-red flex-1 min-w-0 break-words">
								<?php echo esc_html( $card_title ); ?>
							</h3>
						</div>
						<?php if ( ! empty( $card_desc ) ) : ?>
							<p class="text-base text-white/90 font-normal leading-normal break-words">
								<?php echo esc_html( $card_desc ); ?>
							</p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
