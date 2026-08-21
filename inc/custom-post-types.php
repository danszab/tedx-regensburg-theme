<?php
/**
 * Register Custom Post Types and Taxonomies for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Speaker Custom Post Type
 */
function tedx_register_speaker_cpt() {
	$labels = array(
		'name'                  => _x( 'Speakers', 'Post Type General Name', 'tedx-regensburg' ),
		'singular_name'         => _x( 'Speaker', 'Post Type Singular Name', 'tedx-regensburg' ),
		'menu_name'             => __( 'Speakers', 'tedx-regensburg' ),
		'name_admin_bar'        => __( 'Speaker', 'tedx-regensburg' ),
		'archives'              => __( 'Speaker Archives', 'tedx-regensburg' ),
		'attributes'            => __( 'Speaker Attributes', 'tedx-regensburg' ),
		'all_items'             => __( 'All Speakers', 'tedx-regensburg' ),
		'add_new_item'          => __( 'Add New Speaker', 'tedx-regensburg' ),
		'add_new'               => __( 'Add New', 'tedx-regensburg' ),
		'new_item'              => __( 'New Speaker', 'tedx-regensburg' ),
		'edit_item'             => __( 'Edit Speaker', 'tedx-regensburg' ),
		'update_item'           => __( 'Update Speaker', 'tedx-regensburg' ),
		'view_item'             => __( 'View Speaker', 'tedx-regensburg' ),
		'view_items'            => __( 'View Speakers', 'tedx-regensburg' ),
		'search_items'          => __( 'Search Speaker', 'tedx-regensburg' ),
		'featured_image'        => __( 'Speaker Photo', 'tedx-regensburg' ),
		'set_featured_image'    => __( 'Set speaker photo', 'tedx-regensburg' ),
		'remove_featured_image' => __( 'Remove speaker photo', 'tedx-regensburg' ),
		'use_featured_image'    => __( 'Use as speaker photo', 'tedx-regensburg' ),
	);

	$args = array(
		'label'                 => __( 'Speaker', 'tedx-regensburg' ),
		'description'           => __( 'TEDx Regensburg Event Speakers', 'tedx-regensburg' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-microphone',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
		'rewrite'               => array( 'slug' => 'speakers' ),
	);

	register_post_type( 'speaker', $args );
}
add_action( 'init', 'tedx_register_speaker_cpt', 0 );

/**
 * Add Meta Box for Speaker Details
 */
function tedx_add_speaker_meta_boxes() {
	add_meta_box(
		'tedx_speaker_details',
		__( 'Speaker Details', 'tedx-regensburg' ),
		'tedx_render_speaker_meta_box',
		'speaker',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tedx_add_speaker_meta_boxes' );

/**
 * Render Speaker Meta Box Form
 */
function tedx_render_speaker_meta_box( $post ) {
	wp_nonce_field( 'tedx_save_speaker_meta', 'tedx_speaker_meta_nonce' );

	$topic     = get_post_meta( $post->ID, '_speaker_topic', true );
	$language  = get_post_meta( $post->ID, '_speaker_language', true ) ?: 'EN';
	$linkedin  = get_post_meta( $post->ID, '_speaker_linkedin', true );
	$year      = get_post_meta( $post->ID, '_speaker_year', true ) ?: '2026';
	?>
	<table class="form-table" style="width: 100%;">
		<tr>
			<th scope="row"><label for="speaker_topic"><?php _e( 'Topic / Field', 'tedx-regensburg' ); ?></label></th>
			<td>
				<input type="text" id="speaker_topic" name="speaker_topic" value="<?php echo esc_attr( $topic ); ?>" class="regular-text" placeholder="e.g. Content Creation, AI Ethics, Architecture" />
				<p class="description"><?php _e( 'The topic or subtitle shown in red below the speaker name.', 'tedx-regensburg' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="speaker_language"><?php _e( 'Talk Language', 'tedx-regensburg' ); ?></label></th>
			<td>
				<select id="speaker_language" name="speaker_language">
					<option value="EN" <?php selected( $language, 'EN' ); ?>>EN</option>
					<option value="DE" <?php selected( $language, 'DE' ); ?>>DE</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="speaker_year"><?php _e( 'Event Year', 'tedx-regensburg' ); ?></label></th>
			<td>
				<input type="text" id="speaker_year" name="speaker_year" value="<?php echo esc_attr( $year ); ?>" class="small-text" placeholder="2026" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="speaker_linkedin"><?php _e( 'LinkedIn URL', 'tedx-regensburg' ); ?></label></th>
			<td>
				<input type="url" id="speaker_linkedin" name="speaker_linkedin" value="<?php echo esc_url( $linkedin ); ?>" class="regular-text" placeholder="https://linkedin.com/in/username" />
				<p class="description"><?php _e( 'URL for "VIEW LINKEDIN" link.', 'tedx-regensburg' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Speaker Meta Box Data
 */
function tedx_save_speaker_meta_data( $post_id ) {
	if ( ! isset( $_POST['tedx_speaker_meta_nonce'] ) || ! wp_verify_nonce( $_POST['tedx_speaker_meta_nonce'], 'tedx_save_speaker_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['speaker_topic'] ) ) {
		update_post_meta( $post_id, '_speaker_topic', sanitize_text_field( $_POST['speaker_topic'] ) );
	}

	if ( isset( $_POST['speaker_language'] ) ) {
		update_post_meta( $post_id, '_speaker_language', sanitize_text_field( $_POST['speaker_language'] ) );
	}

	if ( isset( $_POST['speaker_year'] ) ) {
		update_post_meta( $post_id, '_speaker_year', sanitize_text_field( $_POST['speaker_year'] ) );
	}

	if ( isset( $_POST['speaker_linkedin'] ) ) {
		update_post_meta( $post_id, '_speaker_linkedin', esc_url_raw( $_POST['speaker_linkedin'] ) );
	}
}
add_action( 'save_post_speaker', 'tedx_save_speaker_meta_data' );


/**
 * Register Team Member Custom Post Type
 */
function tedx_register_team_member_cpt() {
	$labels = array(
		'name'                  => _x( 'Team Members', 'Post Type General Name', 'tedx-regensburg' ),
		'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'tedx-regensburg' ),
		'menu_name'             => __( 'Team', 'tedx-regensburg' ),
		'name_admin_bar'        => __( 'Team Member', 'tedx-regensburg' ),
		'all_items'             => __( 'All Team Members', 'tedx-regensburg' ),
		'add_new_item'          => __( 'Add New Team Member', 'tedx-regensburg' ),
		'add_new'               => __( 'Add New', 'tedx-regensburg' ),
		'new_item'              => __( 'New Team Member', 'tedx-regensburg' ),
		'edit_item'             => __( 'Edit Team Member', 'tedx-regensburg' ),
		'update_item'           => __( 'Update Team Member', 'tedx-regensburg' ),
		'view_item'             => __( 'View Team Member', 'tedx-regensburg' ),
		'search_items'          => __( 'Search Team Member', 'tedx-regensburg' ),
	);

	$args = array(
		'label'                 => __( 'Team Member', 'tedx-regensburg' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', 'page-attributes' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
	);

	register_post_type( 'team_member', $args );
}
add_action( 'init', 'tedx_register_team_member_cpt', 0 );

/**
 * Add Meta Box for Team Member Details
 */
function tedx_add_team_member_meta_boxes() {
	add_meta_box(
		'tedx_team_member_details',
		__( 'Team Member Details', 'tedx-regensburg' ),
		'tedx_render_team_member_meta_box',
		'team_member',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tedx_add_team_member_meta_boxes' );

/**
 * Render Team Member Meta Box Form
 */
function tedx_render_team_member_meta_box( $post ) {
	wp_nonce_field( 'tedx_save_team_member_meta', 'tedx_team_member_meta_nonce' );

	$role = get_post_meta( $post->ID, '_team_member_role', true );
	$linkedin = get_post_meta( $post->ID, '_team_member_linkedin', true );
	?>
	<table class="form-table" style="width: 100%;">
		<tr>
			<th scope="row"><label for="team_member_role"><?php _e( 'Role', 'tedx-regensburg' ); ?></label></th>
			<td>
				<input type="text" id="team_member_role" name="team_member_role" value="<?php echo esc_attr( $role ); ?>" class="regular-text" placeholder="e.g. Organizer, Design, etc." />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="team_member_linkedin"><?php _e( 'LinkedIn URL', 'tedx-regensburg' ); ?></label></th>
			<td>
				<input type="url" id="team_member_linkedin" name="team_member_linkedin" value="<?php echo esc_url( $linkedin ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Team Member Meta Box Data
 */
function tedx_save_team_member_meta_data( $post_id ) {
	if ( ! isset( $_POST['tedx_team_member_meta_nonce'] ) || ! wp_verify_nonce( $_POST['tedx_team_member_meta_nonce'], 'tedx_save_team_member_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['team_member_role'] ) ) {
		update_post_meta( $post_id, '_team_member_role', sanitize_text_field( $_POST['team_member_role'] ) );
	}

	if ( isset( $_POST['team_member_linkedin'] ) ) {
		update_post_meta( $post_id, '_team_member_linkedin', esc_url_raw( $_POST['team_member_linkedin'] ) );
	}
}
add_action( 'save_post_team_member', 'tedx_save_team_member_meta_data' );



/**
 * Add Meta Box for Page Templates (Event)
 */
function tedx_add_page_event_meta_boxes() {
	add_meta_box(
		'tedx_page_event_details',
		__( 'Event Page Details', 'tedx-regensburg' ),
		'tedx_render_page_event_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tedx_add_page_event_meta_boxes' );

function tedx_render_page_event_meta_box( $post ) {
	wp_nonce_field( 'tedx_save_page_event_meta', 'tedx_page_event_meta_nonce' );

	$pitch_title = get_post_meta( $post->ID, '_event_pitch_title', true );
	$pitch_meta = get_post_meta( $post->ID, '_event_pitch_meta', true );
	$pitch_desc = get_post_meta( $post->ID, '_event_pitch_desc', true );
	$ticket_url = get_post_meta( $post->ID, '_event_ticket_url', true );
	$show_more_url = get_post_meta( $post->ID, '_event_show_more_url', true );
	$enable_tickets = get_post_meta( $post->ID, '_event_enable_tickets', true );
	if ( '' === $enable_tickets ) {
		$enable_tickets = '1';
	}
	$enable_show_more = get_post_meta( $post->ID, '_event_enable_show_more', true );
	if ( '' === $enable_show_more ) {
		$enable_show_more = '1';
	}
	$event_year = get_post_meta( $post->ID, '_event_year', true );
	
	// Location Meta
	$venue_name = get_post_meta( $post->ID, '_event_venue_name', true );
	$venue_address_1 = get_post_meta( $post->ID, '_event_venue_address_1', true );
	$venue_address_2 = get_post_meta( $post->ID, '_event_venue_address_2', true );
	$venue_maps_url = get_post_meta( $post->ID, '_event_venue_maps_url', true );
	$venue_image = get_post_meta( $post->ID, '_event_venue_image', true );
	?>
	<p><em><?php _e('These fields are used if this page is set to the "Event Page" template.', 'tedx-regensburg'); ?></em></p>
	<table class="form-table" style="width: 100%;">
		<tr>
			<th scope="row" colspan="2"><h3 style="margin: 0; padding-top: 15px; border-bottom: 1px solid #ccc;"><?php _e('General Settings', 'tedx-regensburg'); ?></h3></th>
		</tr>
		<tr>
			<th scope="row"><label for="event_year"><?php _e( 'Event Year (for Speakers)', 'tedx-regensburg' ); ?></label></th>
			<td><input type="text" id="event_year" name="event_year" value="<?php echo esc_attr( $event_year ); ?>" class="regular-text" placeholder="e.g. 2026" /> <span class="description">Which year's speakers should be shown?</span></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_pitch_title"><?php _e( 'Event Title', 'tedx-regensburg' ); ?></label></th>
			<td><input type="text" id="event_pitch_title" name="event_pitch_title" value="<?php echo esc_attr( $pitch_title ); ?>" class="large-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_pitch_meta"><?php _e( 'Event Meta (Date/Location)', 'tedx-regensburg' ); ?></label></th>
			<td><input type="text" id="event_pitch_meta" name="event_pitch_meta" value="<?php echo esc_attr( $pitch_meta ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_pitch_desc"><?php _e( 'Event Description', 'tedx-regensburg' ); ?></label></th>
			<td><textarea id="event_pitch_desc" name="event_pitch_desc" class="large-text" rows="4"><?php echo esc_textarea( $pitch_desc ); ?></textarea></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_ticket_url"><?php _e( 'Ticket / Action URL', 'tedx-regensburg' ); ?></label></th>
			<td><input type="url" id="event_ticket_url" name="event_ticket_url" value="<?php echo esc_url( $ticket_url ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_show_more_url"><?php _e( 'Show More / Subpage URL', 'tedx-regensburg' ); ?></label></th>
			<td><input type="url" id="event_show_more_url" name="event_show_more_url" value="<?php echo esc_url( $show_more_url ); ?>" class="regular-text" placeholder="<?php echo esc_attr( home_url( '/about' ) ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Button Controls', 'tedx-regensburg' ); ?></th>
			<td>
				<label for="event_enable_tickets" style="display: inline-block; margin-right: 20px; margin-bottom: 6px;">
					<input type="checkbox" id="event_enable_tickets" name="event_enable_tickets" value="1" <?php checked( $enable_tickets, '1' ); ?> />
					<strong><?php _e( 'Enable Tickets Button', 'tedx-regensburg' ); ?></strong>
				</label>
				<label for="event_enable_show_more" style="display: inline-block; margin-bottom: 6px;">
					<input type="checkbox" id="event_enable_show_more" name="event_enable_show_more" value="1" <?php checked( $enable_show_more, '1' ); ?> />
					<strong><?php _e( 'Enable Show More Button', 'tedx-regensburg' ); ?></strong>
				</label>
				<p class="description"><?php _e( 'Uncheck both buttons to completely hide all action buttons (e.g. for past events).', 'tedx-regensburg' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><h3 style="margin: 0; padding-top: 15px; border-bottom: 1px solid #ccc;"><?php _e('Location Settings', 'tedx-regensburg'); ?></h3></th>
		</tr>
		<tr>
			<th scope="row"><label for="event_venue_name"><?php _e( 'Venue Name', 'tedx-regensburg' ); ?></label></th>
			<td><input type="text" id="event_venue_name" name="event_venue_name" value="<?php echo esc_attr( $venue_name ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_venue_address_1"><?php _e( 'Address Line 1', 'tedx-regensburg' ); ?></label></th>
			<td><input type="text" id="event_venue_address_1" name="event_venue_address_1" value="<?php echo esc_attr( $venue_address_1 ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_venue_address_2"><?php _e( 'Address Line 2', 'tedx-regensburg' ); ?></label></th>
			<td><input type="text" id="event_venue_address_2" name="event_venue_address_2" value="<?php echo esc_attr( $venue_address_2 ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_venue_maps_url"><?php _e( 'Google Maps URL', 'tedx-regensburg' ); ?></label></th>
			<td><input type="url" id="event_venue_maps_url" name="event_venue_maps_url" value="<?php echo esc_url( $venue_maps_url ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="event_venue_image"><?php _e( 'Venue Image URL', 'tedx-regensburg' ); ?></label></th>
			<td><input type="url" id="event_venue_image" name="event_venue_image" value="<?php echo esc_url( $venue_image ); ?>" class="regular-text" /></td>
		</tr>
	</table>
	<?php
}

function tedx_save_page_event_meta_data( $post_id ) {
	if ( ! isset( $_POST['tedx_page_event_meta_nonce'] ) || ! wp_verify_nonce( $_POST['tedx_page_event_meta_nonce'], 'tedx_save_page_event_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_page', $post_id ) ) { return; }

	update_post_meta( $post_id, '_event_pitch_title', sanitize_text_field( $_POST['event_pitch_title'] ?? '' ) );
	update_post_meta( $post_id, '_event_pitch_meta', sanitize_text_field( $_POST['event_pitch_meta'] ?? '' ) );
	update_post_meta( $post_id, '_event_pitch_desc', sanitize_textarea_field( $_POST['event_pitch_desc'] ?? '' ) );
	update_post_meta( $post_id, '_event_ticket_url', esc_url_raw( $_POST['event_ticket_url'] ?? '' ) );
	update_post_meta( $post_id, '_event_show_more_url', esc_url_raw( $_POST['event_show_more_url'] ?? '' ) );
	update_post_meta( $post_id, '_event_enable_tickets', isset( $_POST['event_enable_tickets'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_event_enable_show_more', isset( $_POST['event_enable_show_more'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_event_year', sanitize_text_field( $_POST['event_year'] ?? '' ) );
	update_post_meta( $post_id, '_event_venue_name', sanitize_text_field( $_POST['event_venue_name'] ?? '' ) );
	update_post_meta( $post_id, '_event_venue_address_1', sanitize_text_field( $_POST['event_venue_address_1'] ?? '' ) );
	update_post_meta( $post_id, '_event_venue_address_2', sanitize_text_field( $_POST['event_venue_address_2'] ?? '' ) );
	update_post_meta( $post_id, '_event_venue_maps_url', esc_url_raw( $_POST['event_venue_maps_url'] ?? '' ) );
	update_post_meta( $post_id, '_event_venue_image', esc_url_raw( $_POST['event_venue_image'] ?? '' ) );
}
add_action( 'save_post_page', 'tedx_save_page_event_meta_data' );
