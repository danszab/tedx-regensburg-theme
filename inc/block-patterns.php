<?php
/**
 * Block Patterns and Default Post Templates
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register TEDx Pattern Category and Patterns.
 */
function tedx_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category(
		'tedx',
		array( 'label' => __( 'TEDx', 'tedx-regensburg' ) )
	);

	// 1. Homepage Full Layout
	register_block_pattern(
		'tedx/homepage-layout',
		array(
			'title'       => __( 'TEDx Homepage Layout', 'tedx-regensburg' ),
			'description' => __( 'Complete layout for the TEDx homepage below the video hero.', 'tedx-regensburg' ),
			'categories'  => array( 'tedx' ),
			'content'     => '<!-- wp:tedx/event {"eyebrow":"TEDxREGENSBURG | NOV 14 | MARINAFORUM","title":"This Events Title","description":"Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua."} /-->
<!-- wp:tedx/gallery /-->
<!-- wp:tedx/about {"eyebrow":"About","title":"About TEDx Regensburg"} /-->
<!-- wp:tedx/speakers {"yearSource":"fixed","year":"2026","title":"Speakers","showYear":true,"showEventButton":true} /-->
<!-- wp:tedx/stats /-->
<!-- wp:tedx/sponsors {"layout":"featured","sectionId":"partner","eyebrow":"Exclusive Partner","title":"Our Partner"} /-->
<!-- wp:tedx/sponsors {"layout":"grid","sectionId":"sponsors","eyebrow":"Partners","title":"Other Sponsors"} /-->',
		)
	);

	// 2. Event Page Layout
	register_block_pattern(
		'tedx/event-layout',
		array(
			'title'       => __( 'TEDx Event Page Layout', 'tedx-regensburg' ),
			'description' => __( 'Standard block flow for an individual TEDx event page.', 'tedx-regensburg' ),
			'categories'  => array( 'tedx' ),
			'content'     => '<!-- wp:tedx/event {"sectionId":"event-pitch","eyebrow":"TEDxREGENSBURG | NOV 14 | MARINAFORUM"} /-->
<!-- wp:tedx/speakers {"yearSource":"event","title":"Speakers","showYear":true,"showEventButton":false} /-->
<!-- wp:tedx/timetable /-->
<!-- wp:tedx/sponsors {"layout":"featured","sectionId":"partner","eyebrow":"Exclusive Partner","title":"Our Partner"} /-->
<!-- wp:tedx/sponsors {"layout":"grid","sectionId":"sponsors","eyebrow":"Partners","title":"Other Sponsors"} /-->
<!-- wp:tedx/venue /-->',
		)
	);

	// 3. About Us Page Layout
	register_block_pattern(
		'tedx/about-us-layout',
		array(
			'title'       => __( 'TEDx About Us Layout', 'tedx-regensburg' ),
			'description' => __( 'Complete About Us layout with rich text, team grid, and speaker coaches.', 'tedx-regensburg' ),
			'categories'  => array( 'tedx' ),
			'content'     => '<!-- wp:tedx/text-section -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">About TED &amp; TEDxRegensburg</h1>
<!-- /wp:heading -->
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">About TED</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">About TEDx</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">About TEDxRegensburg</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
<!-- /wp:paragraph -->
<!-- /wp:tedx/text-section -->
<!-- wp:tedx/team /-->
<!-- wp:tedx/speaker-coaches {"title":"Speaker Coaches","year":""} /-->',
		)
	);
}
add_action( 'init', 'tedx_register_block_patterns' );

/**
 * Register default block template for tedx_event post type.
 */
function tedx_register_event_block_template() {
	$event_type_object = get_post_type_object( 'tedx_event' );
	if ( $event_type_object ) {
		$event_type_object->template = array(
			array( 'tedx/event', array( 'sectionId' => 'event-pitch' ) ),
			array( 'tedx/speakers', array( 'yearSource' => 'event' ) ),
			array( 'tedx/timetable', array() ),
			array( 'tedx/sponsors', array( 'layout' => 'featured', 'sectionId' => 'partner' ) ),
			array( 'tedx/sponsors', array( 'layout' => 'grid', 'sectionId' => 'sponsors' ) ),
			array( 'tedx/venue', array() ),
		);
	}
}
add_action( 'init', 'tedx_register_event_block_template', 20 );
