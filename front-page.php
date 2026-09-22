<?php
/**
 * Front Page Template for TEDx Regensburg Theme
 * Exactly renders the Figma design structure
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main flex-grow">
	
	<?php
	// 1. Hero Section (Homepage - Hero alternative)
	get_template_part( 'template-parts/hero' );

	// 2. Event Pitch Section (This Events Title & Theme)
	get_template_part( 'template-parts/event-pitch' );

	// Output Gutenberg Blocks added to the page
	while ( have_posts() ) :
		the_post();
		?>
		<div class="tedx-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;

	// 3. About TEDx Section (Stage photo & About copy)
	get_template_part( 'template-parts/about' );

	// 4. 2026 Speakers Section (Speaker cards & CFS card)
	$event_year = get_theme_mod( 'tedx_speaker_year', '2026' );
	set_query_var( 'target_year', $event_year );
	get_template_part( 'template-parts/speakers', null, array( 'target_year' => $event_year ) );

	// 5. Stats Section (Counter squircle cards)
	get_template_part( 'template-parts/stats' );

	// 6. Exclusive Partner Section
	get_template_part( 'template-parts/partner' );

	// 7. Sponsors Section
	get_template_part( 'template-parts/sponsors' );

	
	?>

</main>

<?php
get_footer();
