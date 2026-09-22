<?php
/**
 * Exclusive Partner Section
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$show_partner = tedx_mod( 'tedx_show_partner_section', true );
$partner_logo = '';
$partner_name = __( 'Exclusive partner', 'tedx-regensburg' );
$partner_link = '';

// Templates may pass an explicit year (e.g. event pages); otherwise use the Customizer year.
$event_year = isset( $args['target_year'] ) ? trim( (string) $args['target_year'] ) : '';
if ( '' === $event_year ) {
	$event_year = trim( tedx_mod( 'tedx_sponsors_event_year', tedx_mod( 'tedx_event_year', '2026' ) ) );
}

if ( ! $show_partner || '' === $event_year ) {
	return;
}

$exclusive_sponsors = new WP_Query( array(
	'post_type'      => 'sponsor',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => array(
		'relation' => 'AND',
		array(
			'key'     => '_sponsor_exclusive',
			'value'   => array( '1', 1, true, 'true' ),
			'compare' => 'IN',
		),
		array(
			'key'     => '_sponsor_event_year',
			'value'   => $event_year,
			'compare' => '=',
		),
	),
) );

if ( $exclusive_sponsors->have_posts() ) {
	$exclusive_sponsors->the_post();

	$sponsor_id = get_the_ID();
	$logo_url   = get_the_post_thumbnail_url( $sponsor_id, 'full' );

	if ( ! empty( $logo_url ) ) {
		$partner_logo = $logo_url;
		$partner_name = get_the_title( $sponsor_id );
		$partner_link = get_post_meta( $sponsor_id, '_sponsor_link', true );
	}

	wp_reset_postdata();
}

if ( empty( $partner_logo ) ) {
	return;
}

$logo = sprintf(
	'<img src="%1$s" alt="%2$s" class="h-full w-auto max-w-full object-contain" loading="lazy" decoding="async">',
	esc_url( $partner_logo ),
	esc_attr( $partner_name )
);
?>

<section id="partner" class="bg-tedx-dark px-4 py-16 md:px-8" aria-labelledby="partner-heading">
	<div class="mx-auto flex max-w-figma flex-col items-start gap-6">
		<div class="flex w-full flex-col items-start gap-6 break-words font-medium">
			<?php tedx_category_header( __( 'Exclusive Partner', 'tedx-regensburg' ) ); ?>
			<h2 id="partner-heading" class="w-full text-5xl leading-none tracking-[-0.05em] text-white md:text-[64px]">
				<?php esc_html_e( 'Our Partner', 'tedx-regensburg' ); ?>
			</h2>
		</div>

		<div class="w-full max-w-[301px] py-16">
			<div class="flex h-10 items-center">
				<?php if ( ! empty( $partner_link ) ) : ?>
					<a href="<?php echo esc_url( $partner_link ); ?>" class="block h-full" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $partner_name ); ?>">
						<?php echo $logo; ?>
					</a>
				<?php else : ?>
					<?php echo $logo; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
