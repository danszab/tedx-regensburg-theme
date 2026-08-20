<?php
/**
 * Template Name: About Us Page
 * 
 * @package TEDx_Regensburg
 */

get_header();

$about_ted = get_theme_mod( 'tedx_about_ted_text', "TED is a nonprofit organization devoted to Ideas Worth Spreading..." );
$about_tedx = get_theme_mod( 'tedx_about_tedx_text', "In the spirit of ideas worth spreading, TEDx is a program of local, self-organized events..." );
$about_tedxregensburg = get_theme_mod( 'tedx_about_tedxregensburg_text', "TEDxRegensburg is a local, independently organized event..." );
?>

<main id="primary" class="site-main flex-grow bg-tedx-dark">
	
	<!-- Hero Header Section -->
	<div class="relative w-full pt-24 pb-16 px-4 md:px-8 lg:px-12 flex flex-col items-center">
		<div class="max-w-figma w-full flex flex-col items-center md:items-start text-center md:text-left gap-10">
			<h1 class="text-5xl md:text-6xl font-black text-white tracking-tight leading-tight w-full">
				About TED & TEDxRegensburg
			</h1>
			
			<div class="w-full flex flex-col gap-12 mt-4">
				
				<!-- About TED -->
				<div class="flex flex-col items-start text-left w-full">
					<h2 class="text-3xl font-bold text-tedx-red tracking-tight mb-4">
						About TED
					</h2>
					<div class="text-base md:text-lg text-white/90 leading-relaxed font-normal w-full space-y-4">
						<?php echo wp_kses_post( wpautop( $about_ted ) ); ?>
					</div>
				</div>
				
				<!-- About TEDx -->
				<div class="flex flex-col items-start text-left w-full">
					<h2 class="text-3xl font-bold text-tedx-red tracking-tight mb-4">
						About TEDx
					</h2>
					<div class="text-base md:text-lg text-white/90 leading-relaxed font-normal w-full space-y-4">
						<?php echo wp_kses_post( wpautop( $about_tedx ) ); ?>
					</div>
				</div>
				
				<!-- About TEDxRegensburg -->
				<div class="flex flex-col items-start text-left w-full">
					<h2 class="text-3xl font-bold text-tedx-red tracking-tight mb-4">
						About TEDxRegensburg
					</h2>
					<div class="text-base md:text-lg text-white/90 leading-relaxed font-normal w-full space-y-4">
						<?php echo wp_kses_post( wpautop( $about_tedxregensburg ) ); ?>
					</div>
				</div>

			</div>
		</div>
	</div>

	<?php
	// Include the Team grid
	get_template_part( 'template-parts/team' );
	?>

</main>

<?php
get_footer();
