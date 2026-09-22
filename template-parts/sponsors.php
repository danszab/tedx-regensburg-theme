<?php
/**
 * Sponsors Section
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$show_sponsors = tedx_mod( 'tedx_show_sponsors_section', true );

// Templates may pass an explicit year (e.g. event pages); otherwise use the Customizer year.
$sponsors_year = isset( $args['target_year'] ) ? trim( (string) $args['target_year'] ) : '';
if ( '' === $sponsors_year ) {
	$sponsors_year = trim( tedx_mod( 'tedx_sponsors_event_year', tedx_mod( 'tedx_event_year', '2026' ) ) );
}

if ( ! $show_sponsors || '' === $sponsors_year ) {
	return;
}

$sponsors_query = new WP_Query( array(
	'post_type'      => 'sponsor',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => array(
		'relation' => 'AND',
		array(
			'relation' => 'OR',
			array(
				'key'     => '_sponsor_exclusive',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_sponsor_exclusive',
				'value'   => array( '0', 0, false, 'false', '' ),
				'compare' => 'IN',
			),
		),
		array(
			'key'     => '_sponsor_event_year',
			'value'   => $sponsors_year,
			'compare' => '=',
		),
	),
) );

if ( ! $sponsors_query->have_posts() ) {
	return;
}
?>

<section id="sponsors" class="bg-tedx-dark px-4 py-16 md:px-8" aria-labelledby="sponsors-heading">
	<div class="mx-auto flex max-w-figma flex-col items-start gap-6">
		<?php tedx_category_header( __( 'Partners', 'tedx-regensburg' ) ); ?>

		<h2 id="sponsors-heading" class="w-full text-5xl font-medium leading-none tracking-[-0.05em] text-white md:text-[64px]">
			<?php esc_html_e( 'Other Sponsors', 'tedx-regensburg' ); ?>
		</h2>

		<p class="w-full text-base leading-none text-white">
			<?php esc_html_e( 'Proudly supported by local companies and organizations standing behind TEDxRegensburg.', 'tedx-regensburg' ); ?>
		</p>

		<div class="grid w-full grid-cols-1 gap-6 overflow-hidden pt-10 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( $sponsors_query->have_posts() ) :
				$sponsors_query->the_post();

				$sponsor_id   = get_the_ID();
				$sponsor_name = get_the_title( $sponsor_id );
				$sponsor_link = get_post_meta( $sponsor_id, '_sponsor_link', true );
				$sponsor_logo = get_the_post_thumbnail_url( $sponsor_id, 'full' );
				?>
				<article class="flex h-[92px] items-center justify-center overflow-hidden rounded-[32px] bg-tedx-card px-[19px] py-4">
					<?php if ( ! empty( $sponsor_logo ) ) : ?>
						<?php if ( ! empty( $sponsor_link ) ) : ?>
							<a href="<?php echo esc_url( $sponsor_link ); ?>" class="flex h-full w-full items-center justify-center" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $sponsor_name ); ?>">
								<img src="<?php echo esc_url( $sponsor_logo ); ?>" alt="<?php echo esc_attr( $sponsor_name ); ?>" class="max-h-full w-auto max-w-full object-contain" loading="lazy" decoding="async">
							</a>
						<?php else : ?>
							<img src="<?php echo esc_url( $sponsor_logo ); ?>" alt="<?php echo esc_attr( $sponsor_name ); ?>" class="max-h-full w-auto max-w-full object-contain" loading="lazy" decoding="async">
						<?php endif; ?>
					<?php else : ?>
						<span class="text-center text-sm font-medium text-white/60"><?php echo esc_html( $sponsor_name ); ?></span>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>

<?php
wp_reset_postdata();
