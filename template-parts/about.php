<?php
/**
 * About Section Template Part (Figma: About TEDx)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$about_title       = tedx_mod( 'tedx_about_title', __( 'About', 'tedx-regensburg' ) );
$about_description = tedx_mod( 'tedx_about_description', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.' );
$about_image_url   = tedx_mod( 'tedx_about_image', '' );
$about_btn_url     = tedx_mod( 'tedx_about_button_url', '#about' );
?>

<section id="about" class="bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 border-t border-white/5">
	<div class="max-w-figma mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
		
		<!-- Left Column: Featured Stage Photo with TEDx Letters -->
		<div class="relative w-full h-[360px] md:h-[420px] rounded-[32px] overflow-hidden border border-white/10 shadow-2xl bg-tedx-card flex items-center justify-center">
			<?php if ( ! empty( $about_image_url ) ) : ?>
				<img src="<?php echo esc_url( $about_image_url ); ?>" alt="<?php esc_attr_e( 'About TEDx Regensburg', 'tedx-regensburg' ); ?>" class="absolute inset-0 w-full h-full object-cover">
			<?php else : ?>
				<!-- Default Stylized Stage Atmosphere SVG / Graphic -->
				<div class="absolute inset-0 bg-gradient-to-tr from-black via-[#1c1c1c] to-[#2a1015]"></div>
				<div class="relative z-10 flex flex-col items-center justify-center p-6 text-center">
					<div class="text-6xl md:text-7xl font-black text-tedx-red tracking-tight filter drop-shadow-[0_10px_20px_rgba(235,0,40,0.4)]">
						TED<span class="text-white">x</span>
					</div>
					<div class="text-sm uppercase tracking-widest text-white/60 mt-2 font-medium">
						Ideas Worth Spreading
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Right Column: Heading, Logo, Description, Learn More Button -->
		<div class="flex flex-col gap-6 items-start">
			
			<div class="flex flex-col gap-6">
				<?php tedx_category_header( $about_title, '', 'h2' ); ?>
				<div>
					<?php tedx_regensburg_logo( 'h-[50px] w-auto', false ); ?>
				</div>
			</div>

			<p class="text-base text-white/90 leading-relaxed font-normal">
				<?php echo esc_html( $about_description ); ?>
			</p>

			<a href="<?php echo esc_url( $about_btn_url ); ?>" class="inline-flex items-center gap-2 border border-tedx-red text-tedx-red text-sm font-medium px-5 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim">
				<?php echo tedx_get_icon( 'info', 'w-4 h-4' ); ?>
				<span><?php esc_html_e( 'Learn More', 'tedx-regensburg' ); ?></span>
			</a>

		</div>

	</div>
</section>
