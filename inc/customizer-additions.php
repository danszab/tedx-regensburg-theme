<?php
function tedx_add_new_hero_customizer_settings( $wp_customize ) {
	// 0. Hero Style / Layout Selector
	$wp_customize->add_setting( 'tedx_hero_type', array(
		'default'           => 'default',
		'sanitize_callback' => 'sanitize_key',
	) );
	$wp_customize->add_control( 'tedx_hero_type', array(
		'label'       => __( 'Hero Layout / Style', 'tedx-regensburg' ),
		'description' => __( 'Choose between the Default (Typography & Event Poster) and the Video Hero.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'type'        => 'select',
		'choices'     => array(
			'default' => __( 'Default (Typography & Event Poster)', 'tedx-regensburg' ),
			'video'   => __( 'Video Hero (Looping Video & Bottom Button)', 'tedx-regensburg' ),
		),
		'priority'    => 1,
	) );

	// Hero Background Image (Default hero)
	$wp_customize->add_setting( 'tedx_hero_background_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_hero_background_image', array(
		'label'       => __( 'Default Hero: Background Image', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_background_image',
	) ) );

	// Hero Poster Image (Default hero)
	$wp_customize->add_setting( 'tedx_hero_poster_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_hero_poster_image', array(
		'label'       => __( 'Default Hero: Event Poster Image', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_poster_image',
	) ) );

	// --- Video Hero Settings (Figma 166:2617) ---

	// Horizontal / Desktop Video: WebM
	$wp_customize->add_setting( 'tedx_hero_video_webm', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'tedx_hero_video_webm', array(
		'label'       => __( 'Desktop Video: WebM (Horizontal)', 'tedx-regensburg' ),
		'description' => __( 'Upload horizontal / landscape video in .webm format for desktop and tablet screens.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_video_webm',
	) ) );

	// Horizontal / Desktop Video: H.264 / MP4 (Fallback)
	$wp_customize->add_setting( 'tedx_hero_video_mp4', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'tedx_hero_video_mp4', array(
		'label'       => __( 'Desktop Video: MP4 / H.264 Fallback (Horizontal)', 'tedx-regensburg' ),
		'description' => __( 'Upload horizontal fallback video in .mp4 (H.264) format for desktop.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_video_mp4',
	) ) );

	// Horizontal / Desktop Starting Frame (Poster)
	$wp_customize->add_setting( 'tedx_hero_video_poster', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_hero_video_poster', array(
		'label'       => __( 'Desktop Starting Frame / Preview (Horizontal)', 'tedx-regensburg' ),
		'description' => __( 'Upload horizontal preview frame image for desktop before video plays.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_video_poster',
	) ) );

	// Vertical / Mobile Video: WebM
	$wp_customize->add_setting( 'tedx_hero_video_mobile_webm', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'tedx_hero_video_mobile_webm', array(
		'label'       => __( 'Mobile Video: WebM (Vertical / Portrait - Optional)', 'tedx-regensburg' ),
		'description' => __( 'Upload vertical (9:16) video in .webm format for mobile screens. If empty, desktop video is used.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_video_mobile_webm',
	) ) );

	// Vertical / Mobile Video: H.264 / MP4 (Fallback)
	$wp_customize->add_setting( 'tedx_hero_video_mobile_mp4', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'tedx_hero_video_mobile_mp4', array(
		'label'       => __( 'Mobile Video: MP4 / H.264 Fallback (Vertical - Optional)', 'tedx-regensburg' ),
		'description' => __( 'Upload vertical fallback video in .mp4 (H.264) format for mobile.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_video_mobile_mp4',
	) ) );

	// Vertical / Mobile Starting Frame (Poster)
	$wp_customize->add_setting( 'tedx_hero_video_mobile_poster', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_hero_video_mobile_poster', array(
		'label'       => __( 'Mobile Starting Frame / Preview (Vertical - Optional)', 'tedx-regensburg' ),
		'description' => __( 'Upload vertical preview frame image for mobile screens before video plays.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'settings'    => 'tedx_hero_video_mobile_poster',
	) ) );

	// Video Hero: Button Text
	$wp_customize->add_setting( 'tedx_hero_video_btn_text', array(
		'default'           => 'This Years Event',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'tedx_hero_video_btn_text', array(
		'label'       => __( 'Video Hero: Button Text', 'tedx-regensburg' ),
		'description' => __( 'Text for the button at the bottom of the video hero.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'type'        => 'text',
	) );

	// Video Hero: Button Link URL
	$wp_customize->add_setting( 'tedx_hero_video_btn_url', array(
		'default'           => '#event-pitch',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'tedx_hero_video_btn_url', array(
		'label'       => __( 'Video Hero: Button Link URL', 'tedx-regensburg' ),
		'description' => __( 'Destination URL when the button is clicked (e.g. #event-pitch or an event page).', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'type'        => 'url',
	) );

	// Video Hero: Show Button Toggle
	$wp_customize->add_setting( 'tedx_hero_video_btn_show', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'tedx_hero_video_btn_show', array(
		'label'       => __( 'Video Hero: Show Button', 'tedx-regensburg' ),
		'description' => __( 'Toggle visibility of the button at the bottom of the video hero.', 'tedx-regensburg' ),
		'section'     => 'tedx_hero_section',
		'type'        => 'checkbox',
	) );
}
add_action( 'customize_register', 'tedx_add_new_hero_customizer_settings', 20 );
