<?php
/**
 * Render template for TEDx Floorplan Map Block (tedx/floorplan)
 *
 * @package TEDx_Regensburg
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block inner content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_id       = ! empty( $attributes['sectionId'] ) ? sanitize_title( $attributes['sectionId'] ) : 'floorplan';
$eyebrow          = $attributes['eyebrow'] ?? 'VENUE MAP';
$heading          = $attributes['heading'] ?? 'Event Floorplan & Stages';
$description      = $attributes['description'] ?? '';
$svg_id           = absint( $attributes['svgId'] ?? 0 );
$svg_url          = $attributes['svgUrl'] ?? '';
$min_zoom         = floatval( $attributes['minZoom'] ?? 1.0 );
$max_zoom         = floatval( $attributes['maxZoom'] ?? 5.0 );
$show_controls    = (bool) ( $attributes['showControls'] ?? true );
$allow_fullscreen = (bool) ( $attributes['allowFullscreen'] ?? true );

// Safe SVG Sanitizer & Inliner
$svg_content = '';
if ( $svg_id && function_exists( 'get_attached_file' ) ) {
	$file_path = get_attached_file( $svg_id );
	if ( $file_path && file_exists( $file_path ) && 'svg' === strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) ) ) {
		$raw_svg = file_get_contents( $file_path );
		if ( $raw_svg ) {
			// Strip dangerous tags and handlers
			$clean_svg = preg_replace( '/<\s*(script|foreignObject|object|embed|iframe)[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $raw_svg );
			$clean_svg = preg_replace( '/\s*on[a-z]+\s*=\s*(["\']).*?\1/i', '', $clean_svg );
			$clean_svg = preg_replace( '/\s*href\s*=\s*(["\'])javascript:.*?\1/i', '', $clean_svg );
			$clean_svg = preg_replace( '/<\?xml.*?\?>/i', '', $clean_svg );
			$clean_svg = preg_replace( '/<!DOCTYPE.*?>/i', '', $clean_svg );

			// Inject responsive classes & preserveAspectRatio into root <svg>
			if ( preg_match( '/<svg\b([^>]*)>/i', $clean_svg, $matches ) ) {
				$svg_attrs = $matches[1];
				// Remove existing class if present
				$svg_attrs = preg_replace( '/\bclass\s*=\s*(["\']).*?\1/i', '', $svg_attrs );
				if ( ! preg_match( '/\bpreserveAspectRatio\s*=/i', $svg_attrs ) ) {
					$svg_attrs .= ' preserveAspectRatio="xMidYMid meet"';
				}
				$svg_attrs .= ' class="w-full h-full object-contain pointer-events-none"';
				$clean_svg = preg_replace( '/<svg\b[^>]*>/i', '<svg ' . trim( $svg_attrs ) . '>', $clean_svg, 1 );
				$svg_content = $clean_svg;
			}
		}
	}
}

// Fallback to URL image if inline SVG is empty
if ( empty( $svg_content ) && ! empty( $svg_url ) ) {
	$svg_content = sprintf(
		'<img src="%s" alt="%s" class="w-full h-full object-contain pointer-events-none" loading="lazy" />',
		esc_url( $svg_url ),
		esc_attr( $heading )
	);
}

// Default architectural venue illustration when no SVG is uploaded yet
if ( empty( $svg_content ) ) {
	$svg_content = '<svg viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid meet" class="w-full h-full object-contain pointer-events-none" xmlns="http://www.w3.org/2000/svg">
		<!-- Blueprint Grid Background -->
		<defs>
			<pattern id="tedx-floorplan-grid" width="50" height="50" patternUnits="userSpaceOnUse">
				<path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255, 255, 255, 0.05)" stroke-width="1"/>
			</pattern>
			<linearGradient id="stage-glow" x1="0%" y1="0%" x2="100%" y2="100%">
				<stop offset="0%" stop-color="#eb0028" stop-opacity="0.3"/>
				<stop offset="100%" stop-color="#eb0028" stop-opacity="0.05"/>
			</linearGradient>
		</defs>

		<rect width="1000" height="1000" fill="#121212" />
		<rect width="1000" height="1000" fill="url(#tedx-floorplan-grid)" />

		<!-- Main Auditorium / Outer Hall -->
		<rect x="80" y="80" width="840" height="840" rx="24" fill="#181818" stroke="rgba(255,255,255,0.12)" stroke-width="2" />

		<!-- Main Stage & Red Dot (Figma & TEDx brand signature) -->
		<rect x="250" y="140" width="500" height="180" rx="16" fill="url(#stage-glow)" stroke="#eb0028" stroke-width="2" />
		<circle cx="500" cy="230" r="45" fill="#eb0028" />
		<text x="500" y="236" fill="#ffffff" font-family="Helvetica Neue, Inter, sans-serif" font-size="14" font-weight="bold" text-anchor="middle">TEDx</text>
		<text x="500" y="175" fill="#ffffff" font-family="Helvetica Neue, Inter, sans-serif" font-size="16" font-weight="700" letter-spacing="1" text-anchor="middle">MAIN STAGE</text>

		<!-- Seating Rows (Auditorium Curved Layout) -->
		<g stroke="rgba(255,255,255,0.25)" stroke-width="2" fill="none">
			<path d="M 280 370 Q 500 410 720 370" />
			<path d="M 250 410 Q 500 455 750 410" />
			<path d="M 220 450 Q 500 500 780 450" />
			<path d="M 190 490 Q 500 545 810 490" />
			<path d="M 170 530 Q 500 590 830 530" />
		</g>
		<text x="500" y="470" fill="rgba(255,255,255,0.5)" font-family="Helvetica Neue, Inter, sans-serif" font-size="13" font-weight="600" letter-spacing="2" text-anchor="middle">SEATING AREA / AUDITORIUM</text>

		<!-- Breakout Spaces & Networking Lounge -->
		<rect x="120" y="620" width="340" height="180" rx="12" fill="#1f1f1f" stroke="rgba(255,255,255,0.1)" stroke-width="1.5" />
		<text x="290" y="680" fill="#00e575" font-family="Helvetica Neue, Inter, sans-serif" font-size="12" font-weight="700" letter-spacing="1.5" text-anchor="middle">WORKSHOPS & LOUNGE</text>
		<text x="290" y="720" fill="rgba(255,255,255,0.6)" font-family="Helvetica Neue, Inter, sans-serif" font-size="14" text-anchor="middle">Speaker Coaching Space</text>

		<!-- Catering & Pinsa Plaza -->
		<rect x="540" y="620" width="340" height="180" rx="12" fill="#1f1f1f" stroke="rgba(255,255,255,0.1)" stroke-width="1.5" />
		<text x="710" y="680" fill="#00e575" font-family="Helvetica Neue, Inter, sans-serif" font-size="12" font-weight="700" letter-spacing="1.5" text-anchor="middle">CATERING PLAZA</text>
		<text x="710" y="720" fill="rgba(255,255,255,0.6)" font-family="Helvetica Neue, Inter, sans-serif" font-size="14" text-anchor="middle">Coffee, Drinks & Pinsa</text>

		<!-- Entrance & Registration -->
		<rect x="360" y="830" width="280" height="60" rx="8" fill="#242424" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" />
		<text x="500" y="866" fill="#ffffff" font-family="Helvetica Neue, Inter, sans-serif" font-size="13" font-weight="700" letter-spacing="1.5" text-anchor="middle">ENTRANCE & CHECK-IN</text>

		<!-- Compass Indicator -->
		<g transform="translate(860, 140)">
			<circle cx="0" cy="0" r="24" fill="#181818" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
			<polygon points="0,-16 6,0 0,-4 -6,0" fill="#eb0028" />
			<polygon points="0,16 6,0 0,4 -6,0" fill="rgba(255,255,255,0.4)" />
			<text x="0" y="-28" fill="#ffffff" font-family="Helvetica Neue, Inter, sans-serif" font-size="10" font-weight="bold" text-anchor="middle">N</text>
		</g>
	</svg>';
}

$spacing_classes = function_exists( 'tedx_get_block_spacing_classes' )
	? tedx_get_block_spacing_classes( $attributes )
	: 'pt-8 md:pt-12 pb-8 md:pb-12 border-t border-white/5';

$wrapper_classes = 'tedx-floorplan-section bg-tedx-dark ' . $spacing_classes . ' px-4 md:px-8 lg:px-12 w-full text-white relative';
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes .= ' ' . $attributes['className'];
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => sanitize_html_class( $section_id ),
		'class' => $wrapper_classes,
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto w-full">

		<!-- Section Header -->
		<div class="mb-8 md:mb-10">
			<?php
			if ( ! empty( $eyebrow ) && function_exists( 'tedx_category_header' ) ) {
				tedx_category_header( $eyebrow, 'mb-3' );
			}
			?>
			<h2 class="tedx-block-heading mb-3">
				<?php echo esc_html( $heading ); ?>
			</h2>
			<?php if ( ! empty( $description ) ) : ?>
				<p class="text-white/70 text-base md:text-lg max-w-2xl leading-relaxed m-0">
					<?php echo esc_html( $description ); ?>
				</p>
			<?php endif; ?>
		</div>

		<!-- Square Interactive Floorplan Container -->
		<div
			class="tedx-floorplan-container aspect-square max-h-[85vh] w-full bg-tedx-card rounded-3xl border border-white/5 relative overflow-hidden shadow-2xl"
			data-min-zoom="<?php echo esc_attr( $min_zoom ); ?>"
			data-max-zoom="<?php echo esc_attr( $max_zoom ); ?>"
		>
			<!-- Interactive Viewport -->
			<div class="tedx-floorplan-viewport w-full h-full relative cursor-grab overflow-hidden">
				<div class="tedx-floorplan-stage w-full h-full flex items-center justify-center pointer-events-auto">
					<?php echo $svg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>

			<!-- Bottom Right Floating Controls -->
			<?php if ( $show_controls ) : ?>
				<div class="tedx-floorplan-controls absolute bottom-4 right-4 z-20 bg-[#181818]/90 backdrop-blur-md border border-white/10 rounded-2xl p-1.5 flex items-center gap-1 shadow-2xl">
					<button
						type="button"
						class="tedx-floorplan-btn tedx-floorplan-zoom-in text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-all duration-200 btn-shadcn-anim"
						aria-label="<?php esc_attr_e( 'Zoom in', 'tedx-regensburg' ); ?>"
						title="<?php esc_attr_e( 'Zoom in', 'tedx-regensburg' ); ?>"
					>
						<svg class="w-5 h-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"/>
							<line x1="21" y1="21" x2="16.65" y2="16.65"/>
							<line x1="11" y1="8" x2="11" y2="14"/>
							<line x1="8" y1="11" x2="14" y2="11"/>
						</svg>
					</button>

					<button
						type="button"
						class="tedx-floorplan-btn tedx-floorplan-zoom-out text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-all duration-200 btn-shadcn-anim"
						aria-label="<?php esc_attr_e( 'Zoom out', 'tedx-regensburg' ); ?>"
						title="<?php esc_attr_e( 'Zoom out', 'tedx-regensburg' ); ?>"
					>
						<svg class="w-5 h-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"/>
							<line x1="21" y1="21" x2="16.65" y2="16.65"/>
							<line x1="8" y1="11" x2="14" y2="11"/>
						</svg>
					</button>

					<button
						type="button"
						class="tedx-floorplan-btn tedx-floorplan-reset text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-all duration-200 btn-shadcn-anim"
						aria-label="<?php esc_attr_e( 'Reset view', 'tedx-regensburg' ); ?>"
						title="<?php esc_attr_e( 'Reset view', 'tedx-regensburg' ); ?>"
					>
						<svg class="w-5 h-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
							<path d="M3 3v5h5"/>
						</svg>
					</button>

					<?php if ( $allow_fullscreen ) : ?>
						<button
							type="button"
							class="tedx-floorplan-btn tedx-floorplan-fullscreen text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-xl transition-all duration-200 btn-shadcn-anim"
							aria-label="<?php esc_attr_e( 'Toggle fullscreen', 'tedx-regensburg' ); ?>"
							title="<?php esc_attr_e( 'Toggle fullscreen', 'tedx-regensburg' ); ?>"
						>
							<svg class="w-5 h-5 block enter-fullscreen-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
							</svg>
							<svg class="w-5 h-5 hidden exit-fullscreen-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
							</svg>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

	</div>
</section>
