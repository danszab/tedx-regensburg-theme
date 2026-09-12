<?php
/**
 * Stats Section Template Part (Figma: Stats)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$stat_1_val = tedx_mod( 'tedx_stat_1_value', '676' );
$stat_1_lbl = tedx_mod( 'tedx_stat_1_label', __( 'Guests', 'tedx-regensburg' ) );

$stat_2_val = tedx_mod( 'tedx_stat_2_value', '18' );
$stat_2_lbl = tedx_mod( 'tedx_stat_2_label', __( 'Talks', 'tedx-regensburg' ) );

$stat_3_val = tedx_mod( 'tedx_stat_3_value', '100+' );
$stat_3_lbl = tedx_mod( 'tedx_stat_3_label', __( 'Ideas', 'tedx-regensburg' ) );

$stats = array(
	array( 'val' => $stat_1_val, 'lbl' => $stat_1_lbl ),
	array( 'val' => $stat_2_val, 'lbl' => $stat_2_lbl ),
	array( 'val' => $stat_3_val, 'lbl' => $stat_3_lbl ),
);
?>

<section id="stats" class="bg-tedx-surface py-16 md:py-20 px-4 border-t border-white/5">
	<div class="max-w-figma mx-auto flex flex-wrap items-center justify-center gap-8 md:gap-16">
		
		<?php foreach ( $stats as $stat ) : ?>
			<div class="w-[147px] h-[147px] border-2 border-tedx-green rounded-[32px] p-4 flex flex-col items-center justify-between text-center bg-black/20 backdrop-blur-sm shadow-glow-green">
				
				<!-- Groups Icon -->
				<div class="text-tedx-green pt-1">
					<?php echo tedx_get_icon( 'groups', 'w-6 h-6' ); ?>
				</div>

				<!-- Number Value -->
				<div class="text-2xl font-bold text-white tracking-tight leading-none">
					<?php echo esc_html( $stat['val'] ); ?>
				</div>

				<!-- Label -->
				<div class="text-sm font-semibold text-tedx-muted pb-1">
					<?php echo esc_html( $stat['lbl'] ); ?>
				</div>

			</div>
		<?php endforeach; ?>

	</div>
</section>
