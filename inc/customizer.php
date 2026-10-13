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
		'description' => __( 'Manage hero video, header navigation, location, and social links.', 'tedx-regensburg' ),
		'priority'    => 30,
	) );

	// --- Section: General & Hero ---
	$wp_customize->add_section( 'tedx_hero_section', array(
		'title'    => __( 'Hero & Navigation', 'tedx-regensburg' ),
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
	// Mobile Logo Control in Hero Section
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_mobile_logo_hero', array(
		'label'       => __( 'Mobile Navbar Logo (Optional)', 'tedx-regensburg' ),
		'description' => __( 'Upload an alternative logo image to display on mobile devices in the navbar.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_mobile_logo',
		'priority'    => 5,
	) ) );

	// Event Year (used for navbar / defaults)
	$wp_customize->add_setting( 'tedx_event_year', array(
		'default'           => '2026',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_event_year', array(
		'label'    => __( 'Active Event Year', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
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

	// Ticket URL (used in header)
	$wp_customize->add_setting( 'tedx_ticket_url', array(
		'default'           => '#tickets',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_ticket_url', array(
		'label'    => __( 'Navbar Ticket URL', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'url',
	) );

	// Ticket Button Text (used in header)
	$wp_customize->add_setting( 'tedx_ticket_text', array(
		'default'           => 'Tickets',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_ticket_text', array(
		'label'    => __( 'Navbar Ticket Button Text', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'type'     => 'text',
	) );

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

	// Location Section (Event Pages)
	$wp_customize->add_section( 'tedx_location_section', array(
		'title'    => __( 'Location / Venue', 'tedx-regensburg' ),
		'panel'    => 'tedx_event_panel',
		'priority' => 60,
	) );

	$wp_customize->add_setting( 'tedx_venue_name', array(
		'default'           => 'Marinaforum Regensburg',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_venue_name', array(
		'label'   => __( 'Default Venue Name', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'tedx_venue_address_1', array(
		'default'           => 'Johanna-Dachs-Straße 46',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_venue_address_1', array(
		'label'   => __( 'Default Address Line 1', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'tedx_venue_address_2', array(
		'default'           => '93055 Regensburg',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_venue_address_2', array(
		'label'   => __( 'Default Address Line 2', 'tedx-regensburg' ),
		'section' => 'tedx_location_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'tedx_venue_maps_embed', array(
		'default'           => '',
		'sanitize_callback' => 'wp_unslash',
	) );
	$wp_customize->add_control( 'tedx_venue_maps_embed', array(
		'label'       => __( 'Google Maps Embed HTML', 'tedx-regensburg' ),
		'description' => __( 'Paste the full iframe code from Google Maps. Overrides auto-generated map.', 'tedx-regensburg' ),
		'section'     => 'tedx_location_section',
		'type'        => 'textarea',
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
