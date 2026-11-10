<?php
/**
 * Bottom navigation bar block template.
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$buttons = ! empty( $attributes['buttons'] ) && is_array( $attributes['buttons'] ) ? $attributes['buttons'] : array();
$show_icons = ! isset( $attributes['showIcons'] ) || (bool) $attributes['showIcons'];

if ( empty( $buttons ) ) {
	$buttons = array(
		array(
			'label'         => 'Timetable',
			'url'           => '#timetable',
			'newTab'        => false,
			'customIconUrl' => '',
		),
		array(
			'label'         => 'Speakers',
			'url'           => '#speakers',
			'newTab'        => false,
			'customIconUrl' => '',
		),
		array(
			'label'         => 'Floorplan',
			'url'           => '#floorplan',
			'newTab'        => false,
			'customIconUrl' => '',
		),
	);
}

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes )
	: 'pt-8 md:pt-12 pb-8 md:pb-12 border-t border-white/5';

$anchor = ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : ( ! empty( $attributes['sectionId'] ) ? $attributes['sectionId'] : '' );

$wrapper_classes = 'tedx-bottom-navbar-root ' . $spacing_classes;
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_args = array(
	'class' => $wrapper_classes,
);
if ( ! empty( $anchor ) ) {
	$wrapper_args['id'] = sanitize_html_class( $anchor );
}

$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );
?>

<nav <?php echo $wrapper_attributes; ?> aria-label="<?php esc_attr_e( 'Bottom Navigation', 'tedx-regensburg' ); ?>">
	<div class="tedx-bottom-navbar-bar">
		<div class="tedx-bottom-navbar-container<?php echo ! $show_icons ? ' tedx-no-icons' : ''; ?>">
			<?php foreach ( $buttons as $btn ) : ?>
				<?php
				$label     = ! empty( $btn['label'] ) ? $btn['label'] : '';
				$raw_url   = ! empty( $btn['url'] ) ? trim( $btn['url'] ) : '#';
				$is_hash   = 0 === strpos( $raw_url, '#' );
				$icon_url  = ! empty( $btn['customIconUrl'] ) ? $btn['customIconUrl'] : '';
				$new_tab   = ! empty( $btn['newTab'] );
				?>
				<a href="<?php echo esc_url( $raw_url ); ?>"
				   class="tedx-bottom-nav-item<?php echo ! $show_icons ? ' is-no-icon' : ''; ?>"
				   <?php if ( $is_hash ) : ?>
				   data-scroll-target="<?php echo esc_attr( substr( $raw_url, 1 ) ); ?>"
				   <?php endif; ?>
				   <?php echo ( ! $is_hash && $new_tab ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
					
					<?php if ( $show_icons ) : ?>
						<span class="tedx-bottom-nav-icon-box" aria-hidden="true">
							<?php if ( ! empty( $icon_url ) ) : ?>
								<span class="tedx-bottom-nav-custom-icon" style="-webkit-mask-image: url('<?php echo esc_url( $icon_url ); ?>'); mask-image: url('<?php echo esc_url( $icon_url ); ?>');"></span>
							<?php else : ?>
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="10"></circle>
									<polygon points="12 8 8 12 12 16 12 8"></polygon>
								</svg>
							<?php endif; ?>
						</span>
					<?php endif; ?>

					<span class="tedx-bottom-nav-label">
						<?php echo esc_html( $label ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</nav>
