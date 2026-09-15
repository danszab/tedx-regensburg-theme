<?php
/**
 * Theme Customizer Settings for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function tedx_customize_register( $wp_customize ) {

	// 1. Panel: TEDx Event Settings
	$wp_customize->add_panel( 'tedx_event_panel', array(
		'title'       => __( 'TEDx Event Settings', 'tedx-regensburg' ),
		'description' => __( 'Manage all event details, hero content, speakers call, stats and social links.', 'tedx-regensburg' ),
		'priority'    => 30,
	) );

	// --- Section: General & Hero ---
	$wp_customize->add_section( 'tedx_hero_section', array(
		'title'    => __( 'Hero & Event Pitch', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 10,
	) );

	// Mobile Logo Setting
	$wp_customize->add_setting( 'tedx_mobile_logo', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	// Mobile Logo Control in Site Identity (title_tagline)
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_mobile_logo_identity', array(
		'label'       => __( 'Mobile Logo (Optional)', 'tedx-regensburg' ),
		'description' => __( 'Upload an alternative logo image to display on mobile devices in the navbar.', 'tedx-regensburg' ),
		'section'     => 'title_tagline',
		'settings'    => 'tedx_mobile_logo',
		'priority'    => 9,
	) ) );
	// Mobile Logo Control in Hero & Event Pitch Section
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_mobile_logo_hero', array(
		'label'       => __( 'Mobile Navbar Logo (Optional)', 'tedx-regensburg' ),
		'description' => __( 'Upload an alternative logo image to display on mobile devices in the navbar.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_mobile_logo',
		'priority'    => 5,
	) ) );

	// Event Year
	$wp_customize->add_setting( 'tedx_event_year', array(
		'default'           => '2026',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_event_year', array(
		'label'    => __( 'Event Year', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

	// Event Theme Card Title (Hero Card)
	$wp_customize->add_setting( 'tedx_theme_name', array(
		'default'           => 'This years Theme',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_theme_name', array(
		'label'    => __( 'Hero Theme Card Title', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

	// Event Pitch Title
	$wp_customize->add_setting( 'tedx_pitch_title', array(
		'default'           => 'This Events Title',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_pitch_title', array(
		'label'    => __( 'Event Pitch Title', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

	// Event Pitch Tagline / Meta
	$wp_customize->add_setting( 'tedx_pitch_meta', array(
		'default'           => 'TEDxREGENSBURG | NOV 14 | MARINAFORUM',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_pitch_meta', array(
		'label'    => __( 'Event Date & Venue Tagline', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

	// Event Pitch Description
	$wp_customize->add_setting( 'tedx_pitch_description', array(
		'default'           => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'tedx_pitch_description', array(
		'label'    => __( 'Event Pitch Description', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'textarea',
	) );

	// Show Navbar Ticket Button Checkbox
	$wp_customize->add_setting( 'tedx_show_navbar_ticket', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'tedx_show_navbar_ticket', array(
		'label'       => __( 'Show Tickets Button in Navbar', 'tedx-regensburg' ),
		'description' => __( 'Toggle the visibility of the Tickets button in the header navigation.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'type'        => 'checkbox',
	) );

	// Ticket URL
	$wp_customize->add_setting( 'tedx_ticket_url', array(
		'default'           => '#tickets',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_ticket_url', array(
		'label'    => __( 'Ticket Shop URL', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'url',
	) );

	// Ticket Button Text
	$wp_customize->add_setting( 'tedx_ticket_text', array(
		'default'           => 'Tickets',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_ticket_text', array(
		'label'    => __( 'Ticket Button Text', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

	// Show More Button URL (Subpage Link)
	$wp_customize->add_setting( 'tedx_show_more_url', array(
		'default'           => '#about',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_show_more_url', array(
		'label'       => __( 'Show More Button URL / Subpage Link', 'tedx-regensburg' ),
		'description' => __( 'Destination URL for the Show More button on the homepage (e.g. /about or https://...).', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'type'        => 'url',
	) );

	// Show More Button Text
	$wp_customize->add_setting( 'tedx_show_more_text', array(
		'default'           => 'Show More',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_show_more_text', array(
		'label'    => __( 'Show More Button Text', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

	// --- Section: Event Pitch Card (Figma 166:1430) ---
	$wp_customize->add_section( 'tedx_card_section', array(
		'title'    => __( 'Event Pitch Card (Bottom Card)', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 15,
	) );

	// Select Event from Database
	$wp_customize->add_setting( 'tedx_pitch_selected_event', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'tedx_pitch_selected_event', array(
		'label'       => __( 'Select Event to Feature', 'tedx-regensburg' ),
		'description' => __( 'Select an Event to automatically pull its image and details from the database. (Overrides manual settings below).', 'tedx-regensburg' ),
		'section'     => 'tedx_card_section',
		'type'        => 'select',
		'choices'     => tedx_get_events_choices(),
	) );

	// Event Card Image
	$wp_customize->add_setting( 'tedx_card_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_card_image', array(
		'label'       => __( 'Custom Card Image', 'tedx-regensburg' ),
		'description' => __( 'Select or upload a custom image for the Event Card.', 'tedx-regensburg' ),
		'section'     => 'tedx_card_section',
		'settings'    => 'tedx_card_image',
	) ) );

	// Show Date Label Checkbox
	$wp_customize->add_setting( 'tedx_card_show_label', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'tedx_card_show_label', array(
		'label'       => __( 'Show Date / Badge Label', 'tedx-regensburg' ),
		'description' => __( 'Toggle the pill label badge at the top of the card.', 'tedx-regensburg' ),
		'section'     => 'tedx_card_section',
		'type'        => 'checkbox',
	) );

	// Date Label Text
	$wp_customize->add_setting( 'tedx_card_label_text', array(
		'default'           => 'Nov. 14 | TEDxRegensburg',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_card_label_text', array(
		'label'    => __( 'Date / Badge Label Text', 'tedx-regensburg' ),
		'section'  => 'tedx_card_section',
		'type'     => 'text',
	) );

	// Clickable / Link Enabled State Checkbox
	$wp_customize->add_setting( 'tedx_card_is_clickable', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'tedx_card_is_clickable', array(
		'label'       => __( 'Enable Card Click & Hover Animation', 'tedx-regensburg' ),
		'description' => __( 'When checked, the card becomes clickable and displays hover/click animations.', 'tedx-regensburg' ),
		'section'     => 'tedx_card_section',
		'type'        => 'checkbox',
	) );

	// Card Redirect URL
	$wp_customize->add_setting( 'tedx_card_link_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_card_link_url', array(
		'label'       => __( 'Card Redirect Link URL', 'tedx-regensburg' ),
		'description' => __( 'Destination URL when the card is clicked (only active if clickable is enabled).', 'tedx-regensburg' ),
		'section'     => 'tedx_card_section',
		'type'        => 'url',
	) );

	// --- Section: About TEDx ---
	$wp_customize->add_section( 'tedx_about_section', array(
		'title'    => __( 'About Section', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 20,
	) );

	// About Title
	$wp_customize->add_setting( 'tedx_about_title', array(
		'default'           => 'About',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_about_title', array(
		'label'    => __( 'About Title', 'tedx-regensburg' ),
		'section'  => 'tedx_about_section',
		'type'     => 'text',
	) );

	// About Description
	$wp_customize->add_setting( 'tedx_about_description', array(
		'default'           => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'tedx_about_description', array(
		'label'    => __( 'About Text', 'tedx-regensburg' ),
		'section'  => 'tedx_about_section',
		'type'     => 'textarea',
	) );

	// About Image
	$wp_customize->add_setting( 'tedx_about_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_about_image', array(
		'label'    => __( 'About Section Photo', 'tedx-regensburg' ),
		'section'  => 'tedx_about_section',
	) ) );

	// About Button URL
	$wp_customize->add_setting( 'tedx_about_button_url', array(
		'default'           => '#about',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_about_button_url', array(
		'label'    => __( 'About Button URL', 'tedx-regensburg' ),
		'section'  => 'tedx_about_section',
		'type'     => 'url',
	) );

	// --- Section: Speakers Call & Settings ---
	$wp_customize->add_section( 'tedx_speakers_section', array(
		'title'    => __( 'Speakers Section', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'tedx_show_cfs', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'tedx_show_cfs', array(
		'label'       => __( 'Show "Your Name Here" Card', 'tedx-regensburg' ),
		'description' => __( 'Toggle the visibility of the Call for Speakers placeholder card.', 'tedx-regensburg' ),
		'section'     => 'tedx_speakers_section',
		'type'        => 'checkbox',
	) );

	$wp_customize->add_setting( 'tedx_cfs_title', array(
		'default'           => 'Your Name Here?',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_cfs_title', array(
		'label'    => __( 'Call for Speakers Card Title', 'tedx-regensburg' ),
		'section'  => 'tedx_speakers_section',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'tedx_cfs_description', array(
		'default'           => 'We are curating for the final lineup for 2026. Bring your vision to the stage.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'tedx_cfs_description', array(
		'label'    => __( 'Call for Speakers Description', 'tedx-regensburg' ),
		'section'  => 'tedx_speakers_section',
		'type'     => 'textarea',
	) );

	$wp_customize->add_setting( 'tedx_cfs_url', array(
		'default'           => '#apply',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_cfs_url', array(
		'label'    => __( 'Call for Speakers Application URL', 'tedx-regensburg' ),
		'section'  => 'tedx_speakers_section',
		'type'     => 'url',
	) );

	// --- Section: Stats ---
	$wp_customize->add_section( 'tedx_stats_section', array(
		'title'    => __( 'Stats Section', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 40,
	) );

	for ( $i = 1; $i <= 3; $i++ ) {
		$wp_customize->add_setting( "tedx_stat_{$i}_value", array(
			'default'           => '676',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "tedx_stat_{$i}_value", array(
			'label'    => sprintf( __( 'Stat %d Value', 'tedx-regensburg' ), $i ),
			'section'  => 'tedx_stats_section',
			'type'     => 'text',
		) );

		$wp_customize->add_setting( "tedx_stat_{$i}_label", array(
			'default'           => $i === 1 ? 'Guests' : ( $i === 2 ? 'Talks' : 'Ideas' ),
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "tedx_stat_{$i}_label", array(
			'label'    => sprintf( __( 'Stat %d Label', 'tedx-regensburg' ), $i ),
			'section'  => 'tedx_stats_section',
			'type'     => 'text',
		) );

		// Custom Icon Upload
		$wp_customize->add_setting( "tedx_stat_{$i}_icon", array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "tedx_stat_{$i}_icon", array(
			'label'       => sprintf( __( 'Stat %d Custom Icon (Optional)', 'tedx-regensburg' ), $i ),
			'description' => __( 'Upload an icon (SVG, PNG) or save stat-1.svg, stat-2.svg, stat-3.svg in assets/images/icons/', 'tedx-regensburg' ),
			'section'     => 'tedx_stats_section',
		) ) );
	}

	// --- Section: Social & Footer ---
	$wp_customize->add_section( 'tedx_social_section', array(
		'title'    => __( 'Footer & Social Links', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'tedx_social_linkedin', array(
		'default'           => 'https://linkedin.com/company/tedxregensburg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_social_linkedin', array(
		'label'    => __( 'LinkedIn Profile URL', 'tedx-regensburg' ),
		'section'  => 'tedx_social_section',
		'type'     => 'url',
	) );

	$wp_customize->add_setting( 'tedx_social_instagram', array(
		'default'           => 'https://instagram.com/tedxregensburg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_social_instagram', array(
		'label'    => __( 'Instagram Profile URL', 'tedx-regensburg' ),
		'section'  => 'tedx_social_section',
		'type'     => 'url',
	) );

	$wp_customize->add_setting( 'tedx_social_facebook', array(
		'default'           => 'https://facebook.com/tedxregensburg',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_social_facebook', array(
		'label'    => __( 'Facebook Profile URL', 'tedx-regensburg' ),
		'section'  => 'tedx_social_section',
		'type'     => 'url',
	) );

	$wp_customize->add_setting( 'tedx_newsletter_action', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_newsletter_action', array(
		'label'    => __( 'Newsletter Form Endpoint URL', 'tedx-regensburg' ),
		'section'  => 'tedx_social_section',
		'type'     => 'url',
	) );



	// About Us Page Panel
	$wp_customize->add_panel( 'tedx_about_panel', array(
		'title'       => __( 'About Us Page', 'tedx-regensburg' ),
		'description' => __( 'Content for the About Us page.', 'tedx-regensburg' ),
		'priority'    => 30,
	) );

	// About TED
	$wp_customize->add_section( 'tedx_about_ted_section', array(
		'title' => __( 'About TED', 'tedx-regensburg' ),
		'panel' => 'tedx_about_panel',
	) );
	
	$wp_customize->add_setting( 'tedx_about_ted_text', array(
		'default'           => 'Lorem ipsum dolor sit amet...',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'tedx_about_ted_text', array(
		'label'   => __( 'About TED Text', 'tedx-regensburg' ),
		'section' => 'tedx_about_ted_section',
		'type'    => 'textarea',
	) );

	// About TEDx
	$wp_customize->add_section( 'tedx_about_tedx_section', array(
		'title' => __( 'About TEDx', 'tedx-regensburg' ),
		'panel' => 'tedx_about_panel',
	) );
	
	$wp_customize->add_setting( 'tedx_about_tedx_text', array(
		'default'           => 'Lorem ipsum dolor sit amet...',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'tedx_about_tedx_text', array(
		'label'   => __( 'About TEDx Text', 'tedx-regensburg' ),
		'section' => 'tedx_about_tedx_section',
		'type'    => 'textarea',
	) );

	// About TEDxRegensburg
	$wp_customize->add_section( 'tedx_about_tedxregensburg_section', array(
		'title' => __( 'About TEDxRegensburg', 'tedx-regensburg' ),
		'panel' => 'tedx_about_panel',
	) );
	
	$wp_customize->add_setting( 'tedx_about_tedxregensburg_text', array(
		'default'           => 'Lorem ipsum dolor sit amet...',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'tedx_about_tedxregensburg_text', array(
		'label'   => __( 'About TEDxRegensburg Text', 'tedx-regensburg' ),
		'section' => 'tedx_about_tedxregensburg_section',
		'type'    => 'textarea',
	) );

	// Location Section (Event Pages)
	$wp_customize->add_section( 'tedx_location_section', array(
		'title' => __( 'Location / Venue', 'tedx-regensburg' ),
		'panel' => 'tedx_homepage_panel', // Grouping in homepage panel or create its own
	) );
	
	$wp_customize->add_setting( 'tedx_venue_name', array(
		'default'           => 'Marinaforum Regensburg',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_venue_name', array(
		'label'   => __( 'Venue Name', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'text',
	) );
	
	$wp_customize->add_setting( 'tedx_venue_address_1', array(
		'default'           => 'Johanna-Dachs-Straße 46',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_venue_address_1', array(
		'label'   => __( 'Address Line 1', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'text',
	) );
	
	$wp_customize->add_setting( 'tedx_venue_address_2', array(
		'default'           => '93055 Regensburg',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_venue_address_2', array(
		'label'   => __( 'Address Line 2', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'text',
	) );
	
	$wp_customize->add_setting( 'tedx_venue_maps_url', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_venue_maps_url', array(
		'label'   => __( 'Google Maps URL', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'url',
	) );




}
add_action( 'customize_register', 'tedx_customize_register' );
