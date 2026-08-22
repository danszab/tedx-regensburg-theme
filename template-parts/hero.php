<?php
/**
 * Hero Section Template Part (Figma: Homepage - Hero alternative)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$event_year   = tedx_mod( 'tedx_event_year', '2026' );
$theme_name   = tedx_mod( 'tedx_theme_name', __( 'This years Theme', 'tedx-regensburg' ) );
?>

<section id="hero" class="relative min-h-[85vh] lg:min-h-[920px] flex items-center justify-center overflow-hidden bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12">
	
	<!-- Background Topography Lines & Glow Effect -->
	<div class="absolute inset-0 pointer-events-none z-0">
		<div class="absolute inset-0 bg-[#0a0a0a]"></div>
		<!-- Topographic Contour Curves SVG Background -->
		<svg class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-screen" viewBox="0 0 1512 982" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M-100 200 C 300 100, 500 400, 800 250 C 1100 100, 1400 350, 1600 200" stroke="#2ad17e" stroke-width="1.5" stroke-opacity="0.35" fill="none" />
			<path d="M-100 350 C 250 250, 450 600, 850 400 C 1150 250, 1350 500, 1600 350" stroke="#2ad17e" stroke-width="1.5" stroke-opacity="0.3" fill="none" />
			<path d="M-50 500 C 350 400, 600 750, 950 550 C 1250 400, 1450 650, 1650 500" stroke="#2ad17e" stroke-width="1.5" stroke-opacity="0.25" fill="none" />
			<path d="M-150 650 C 200 550, 500 900, 900 700 C 1300 550, 1500 800, 1700 650" stroke="#2ad17e" stroke-width="1.5" stroke-opacity="0.2" fill="none" />
			<ellipse cx="250" cy="550" rx="350" ry="250" stroke="#2ad17e" stroke-width="1.2" stroke-opacity="0.25" fill="none" />
			<ellipse cx="250" cy="550" rx="200" ry="140" stroke="#2ad17e" stroke-width="1.2" stroke-opacity="0.3" fill="none" />
			<ellipse cx="250" cy="550" rx="80" ry="50" stroke="#2ad17e" stroke-width="1.5" stroke-opacity="0.4" fill="none" />
		</svg>
		<!-- Bottom Gradient Fade -->
		<div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-tedx-dark"></div>
	</div>

	<div class="max-w-figma mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center relative z-10">
		
		<!-- Left Column: Welcome & Typography & Lamp Beam -->
		<div class="lg:col-span-6 flex flex-col justify-center relative">
			
			<div class="relative z-10 space-y-2">
				<p class="text-2xl md:text-3xl lg:text-4xl font-medium text-white/90 tracking-tight">
					<?php esc_html_e( 'Welcome to', 'tedx-regensburg' ); ?>
				</p>

				<div class="flex items-baseline flex-wrap gap-2 text-white">
					<h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tighter leading-none m-0">
						TED<span class="text-tedx-red">x</span>
					</h1>
					<span class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight leading-none">
						Regensburg
					</span>
				</div>
			</div>

			<!-- Artistic Streetlight / Lamp Illustration with Light Cone -->
			<div class="relative mt-8 h-64 md:h-80 w-full max-w-sm">
				<!-- Light Cone / Beam -->
				<div class="absolute -top-12 left-12 w-96 h-96 rounded-full bg-gradient-to-tr from-amber-400/25 via-yellow-500/10 to-transparent blur-2xl pointer-events-none"></div>
				
				<!-- Streetlamp SVG Illustration -->
				<svg class="relative z-10 w-48 h-64 text-tedx-red" viewBox="0 0 150 200" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M75 190V60" stroke="#888" stroke-width="4" stroke-linecap="round" />
					<path d="M50 60C50 45 65 30 75 30C85 30 100 45 100 60H50Z" fill="#eb0028" />
					<ellipse cx="75" cy="60" rx="25" ry="6" fill="#fff" fill-opacity="0.8" />
					<circle cx="75" cy="60" r="14" fill="#ffd15c" filter="drop-shadow(0 0 12px #ffd15c)" />
					<path d="M40 190H110" stroke="#888" stroke-width="4" stroke-linecap="round" />
				</svg>
			</div>

		</div>

		<!-- Right Column: Theme Hero Card (Figma 166:1430) -->
		<div class="lg:col-span-6 flex justify-center lg:justify-end">
			<?php get_template_part( 'template-parts/event-card', null, array( 'context' => 'hero' ) ); ?>
		</div>

	</div>
</section>
