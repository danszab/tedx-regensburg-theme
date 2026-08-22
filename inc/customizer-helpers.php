<?php
/**
 * Customizer Helper Functions
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get all Events for a Customizer Dropdown
 *
 * @return array
 */
function tedx_get_events_choices() {
	$choices = array( '' => __( '-- Select an Event --', 'tedx-regensburg' ) );
	$events = get_posts( array(
		'post_type'      => 'tedx_event',
		'posts_per_page' => -1,
		'post_status'    => array( 'publish', 'draft', 'private' ),
	) );
	if ( $events ) {
		foreach ( $events as $event ) {
			$choices[ $event->ID ] = $event->post_title;
		}
	}
	return $choices;
}
