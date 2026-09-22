<?php
/**
 * Single Template for Events
 *
 * @package TEDx_Regensburg
 */

get_header();
?>

<main id="primary" class="site-main flex-grow bg-tedx-dark">
	
	<?php
	// 1. Event Pitch Section
	get_template_part( 'template-parts/event-pitch' );

	// 2. Speakers Section
	$event_year = get_post_meta( get_the_ID(), '_event_year', true );
	if ( ! empty( $event_year ) ) {
		set_query_var( 'target_year', $event_year );
	}
	get_template_part( 'template-parts/speakers', null, array( 'target_year' => $event_year ) );

	// 3. Location Section
	get_template_part( 'template-parts/location' );

	// 4. Exclusive Partner Section (sponsors for this event year only)
	get_template_part( 'template-parts/partner', null, array( 'target_year' => $event_year ) );

	// 5. Sponsors Section (sponsors for this event year only)
	get_template_part( 'template-parts/sponsors', null, array( 'target_year' => $event_year ) );
	?>

</main>

<?php
get_footer();
