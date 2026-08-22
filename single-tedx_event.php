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
	get_template_part( 'template-parts/speakers' );

	// 3. Location Section
	get_template_part( 'template-parts/location' );
	?>

</main>

<?php
get_footer();
