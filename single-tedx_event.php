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

	// Output Gutenberg Blocks added to the event
	while ( have_posts() ) :
		the_post();
		?>
		<div class="tedx-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;

	// 2. Speakers Section
	$event_year = get_post_meta( get_the_ID(), '_event_year', true );
	if ( ! empty( $event_year ) ) {
		set_query_var( 'target_year', $event_year );
	}
	get_template_part( 'template-parts/speakers', null, array( 'target_year' => $event_year ) );

	// 3. Sponsors Blocks (exclusive partner & sponsors grid for this event year)
	$show_sponsors_meta = get_post_meta( get_the_ID(), '_event_show_sponsors', true );
	$show_event_sponsors = ( '' !== $show_sponsors_meta )
		? ( '1' === $show_sponsors_meta )
		: (bool) tedx_mod( 'tedx_show_event_sponsors', false );

	if ( $show_event_sponsors ) {
		// Exclusive Partner Section (sponsors for this event year only)
		get_template_part( 'template-parts/partner', null, array( 'target_year' => $event_year ) );

		// Sponsors Section (sponsors for this event year only)
		get_template_part( 'template-parts/sponsors', null, array( 'target_year' => $event_year ) );
	}

	// 4. Location Section
	get_template_part( 'template-parts/location' );
	?>

</main>

<?php
get_footer();
