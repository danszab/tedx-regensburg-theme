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

	// 3. About TEDx Section (Stage photo & About copy)
	get_template_part( 'template-parts/about' );

	// 4. 2026 Speakers Section (Speaker cards & CFS card)
	get_template_part( 'template-parts/speakers' );

	// 5. Stats Section (Counter squircle cards)
	get_template_part( 'template-parts/stats' );
	?>

</main>

<?php
get_footer();
