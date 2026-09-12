<?php
/**
 * Template Name: About Us Page
 * 
 * @package TEDx_Regensburg
 */

get_header();

$about_ted            = get_theme_mod( 'tedx_about_ted_text', "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet." );
$about_tedx           = get_theme_mod( 'tedx_about_tedx_text', "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet." );
$about_tedxregensburg = get_theme_mod( 'tedx_about_tedxregensburg_text', "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet." );
?>

<main id="primary" class="site-main flex-grow bg-tedx-dark w-full">
	
	<!-- About Us Content Section -->
	<section class="pt-28 pb-16 md:py-24 px-4 md:px-8 lg:px-12 w-full">
		<div class="max-w-figma mx-auto w-full">
			
			<!-- Headline -->
			<h1 class="text-4xl sm:text-5xl md:text-[64px] font-black text-white tracking-[-3.2px] leading-tight mb-12">
				About TED & TEDxRegensburg
			</h1>
			
			<div class="flex flex-col gap-10">
				
				<!-- About TED -->
				<div class="flex flex-col gap-2">
					<h2 class="text-[22px] font-bold text-tedx-red tracking-[-1.1px] leading-snug">
						About TED
					</h2>
					<div class="text-[16px] text-white/90 leading-[1.6] font-normal space-y-4">
						<?php echo wp_kses_post( wpautop( $about_ted ) ); ?>
					</div>
				</div>
				
				<!-- About TEDx -->
				<div class="flex flex-col gap-2">
					<h2 class="text-[22px] font-bold text-tedx-red tracking-[-1.1px] leading-snug">
						About TEDx
					</h2>
					<div class="text-[16px] text-white/90 leading-[1.6] font-normal space-y-4">
						<?php echo wp_kses_post( wpautop( $about_tedx ) ); ?>
					</div>
				</div>
				
				<!-- About TEDxRegensburg -->
				<div class="flex flex-col gap-2">
					<h2 class="text-[22px] font-bold text-tedx-red tracking-[-1.1px] leading-snug">
						About TEDxRegensburg
					</h2>
					<div class="text-[16px] text-white/90 leading-[1.6] font-normal space-y-4">
						<?php echo wp_kses_post( wpautop( $about_tedxregensburg ) ); ?>
					</div>
				</div>

			</div>

		</div>
	</section>

	<?php
	// Include the Team grid
	get_template_part( 'template-parts/team' );
	?>

</main>

<?php
get_footer();
