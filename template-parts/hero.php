<?php
/**
 * Hero Section Template Part (Figma: Homepage - Hero alternative 2, node 2170:1875)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$event_year   = tedx_mod( 'tedx_event_year', '2026' );
$theme_name   = tedx_mod( 'tedx_theme_name', __( 'This years Theme', 'tedx-regensburg' ) );
?>

<section id="hero" class="relative min-h-[100vh] lg:min-h-[100vh] flex items-center overflow-hidden bg-black" style="height: clamp(600px, 100vh, 982px);">

	<!-- =============================================
		 Layer 1: Background Gradient (black → red → black)
		 Figma node 2170:1876 "Backgground gradient"
	     ============================================= -->
	<div class="absolute inset-0 z-0" style="background: linear-gradient(to bottom, #000000 0%, #eb0028 20.67%, #000000 77.98%);"></div>

	<!-- =============================================
		 Layer 2: Topographic Texture with luminance mask
		 Figma node 2170:1877 "Background Pattern"
		 ============================================= -->
	<div class="absolute inset-0 z-[1] opacity-[0.37]"
		 style="background-image: url('<?php echo esc_url( TEDX_URI . '/assets/img/topo-texture.png' ); ?>');
				background-size: 1024px 1024px;
				background-repeat: repeat;
				-webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,0.34) 0%, rgba(0,0,0,0.8) 23.6%, rgba(0,0,0,0.75) 74.3%, rgba(0,0,0,0) 100%);
				mask-image: linear-gradient(to bottom, rgba(0,0,0,0.34) 0%, rgba(0,0,0,0.8) 23.6%, rgba(0,0,0,0.75) 74.3%, rgba(0,0,0,0) 100%);">
	</div>

	<!-- =============================================
		 Layer 3: Bottom fade to black
		 Figma shows a gradient from transparent to black at ~65%
		 ============================================= -->
	<div class="absolute inset-0 z-[2]"
		 style="background: linear-gradient(to bottom, rgba(0,0,0,0) 65.39%, #000000 100%);">
	</div>

	<!-- =============================================
		 Layer 4: Regensburg Dom Cathedral Silhouette
		 Figma node 2170:1880 "Dom svg"
		 ============================================= -->
	<div class="absolute left-0 bottom-0 z-[3] w-full min-w-[900px] lg:min-w-[1200px] pointer-events-none">
		<svg class="h-[85vh]" preserveAspectRatio="xMinYMax meet" overflow="visible" viewBox="0 0 1513 842" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M2013.5 422.408L2013.5 841.5H0.5V422.408C12 422.408 70.3459 422.471 70.3459 362.045V307.156L74.4642 301.534V293.101L79.2689 266.269V254.258L84.5311 235.859L93.8199 221.293L103.063 202.893L104.207 157.406L108.326 153.317L125.028 36.7876C123.884 34.3173 122.282 27.9968 125.028 22.477C127.773 16.9572 124.189 16.9401 122.053 17.6216C121.672 15.492 126.172 0.499819 129.146 0.5C132.12 0.500181 136.467 17.6216 135.095 17.6216C133.722 17.6216 130.061 17.1104 133.035 22.477C136.01 27.8435 133.951 35.2544 133.035 36.7876L151.339 193.438L157.059 201.36L160.948 204.171V221.548L165.067 226.404V283.391L167.354 290.29V316.101H171.473V344.211L173.074 350.344V393.276L190.463 365.421V354.177L188.175 350.344V343.444L190.463 339.611V319.934L194.581 310.99H204.419L208.537 319.934V339.611L210.825 343.444V350.344L208.537 354.177V365.421L225.926 393.276V350.344L227.527 344.211V316.101H231.646V290.29L233.933 283.391V226.404L238.052 221.548V204.171L241.941 201.36L247.661 193.438L265.965 36.7876C265.049 35.2544 262.99 27.8435 265.965 22.477C268.939 17.1104 265.278 17.6216 263.905 17.6216C262.533 17.6216 266.88 0.500181 269.854 0.5C272.828 0.499819 277.328 15.492 276.947 17.6216C274.811 16.9401 271.227 16.9572 273.972 22.477C276.718 27.9968 275.116 34.3173 273.972 36.7876L290.674 153.317L294.793 157.406L295.937 202.893L305.18 221.293L314.469 235.859L319.731 254.258V266.269L324.536 293.101V301.534L328.654 307.156V372C328.654 554.592 1751 695.5 2013.5 422.408Z" fill="black" stroke="black"/>
</svg>
	</div>

	<!-- =============================================
		 Layer 5: Lamppost Illustration
		 Figma node 2170:1882 — positioned center with topo texture
		 ============================================= -->
	<div class="absolute z-[4] pointer-events-none hidden md:block"
		 style="left: 35.52%; top: 40.33%; width: 12.53%; bottom: 0;">
		<img
			src="<?php echo esc_url( TEDX_URI . '/assets/img/lamppost.png' ); ?>"
			alt=""
			class="w-full h-full object-contain object-bottom"
			aria-hidden="true"
			loading="eager"
		/>
	</div>

	<!-- =============================================
		 Content Layer
		 ============================================= -->
	<!-- Removed 'items-end lg:items-center' from the parent grid so we can control columns individually -->
	<div class="relative z-10 w-full max-w-figma-wide mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 px-6 sm:px-12 lg:px-[99px] py-16 md:py-24 min-h-[inherit]">

		<!-- Left Column: Welcome & Typography -->
		<!-- Added 'self-end' to push this column to the bottom of the section -->
		<!-- You can adjust the pb-0 or add mb-8 etc. here if you need to bump it slightly up or down -->
		<div class="lg:col-span-6 flex flex-col justify-end self-end pb-4 lg:pb-4 relative z-10">

			<div class="flex flex-col gap-6 md:gap-[20px]">
				<!-- "Welcome to" — Figma node 2170:1889 -->
				<p class="text-3xl md:text-4xl lg:text-5xl font-medium text-white tracking-[-0.05em] leading-[0.95] m-0"
				   style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
					<?php esc_html_e( 'Welcome to', 'tedx-regensburg' ); ?>
				</p>

				<!-- "TEDx Regensburg" block — Figma node 2170:1894 -->
				<div class="flex flex-col pl-4 md:pl-[54px]">
					<!-- TED + x — Figma node 2170:1893 -->
					<div class="flex gap-2 md:gap-4 items-end">
						<h1 class="text-6xl sm:text-7xl lg:text-8xl font-extrabold tracking-[-0.05em] leading-[0.95] text-white m-0"
						    style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">TED<span class="">x</span></h1>
					</div>
					<!-- Regensburg — Figma node 2170:1888 -->
					<span class="text-6xl sm:text-7xl lg:text-8xl font-normal text-white tracking-[-0.02em] leading-[0.95] block"
					      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
						Regensburg
					</span>
				</div>
			</div>

		</div>

		<!-- Right Column: Theme Hero Card -->
		<!-- Added 'self-center' to keep the card exactly vertically centered -->
		<div class="lg:col-span-6 flex justify-center lg:justify-end self-center relative z-10">
			<?php get_template_part( 'template-parts/event-card', null, array( 'context' => 'hero' ) ); ?>
		</div>

	</div>
</section>
