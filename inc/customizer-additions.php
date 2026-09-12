<?php
function tedx_add_new_hero_customizer_settings( $wp_customize ) {
	// Hero Background Image
	$wp_customize->add_setting( 'tedx_hero_background_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_hero_background_image', array(
		'label'    => __( 'Hero Background Image', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'settings' => 'tedx_hero_background_image',
	) ) );

	// Hero Poster Image (fixed aspect ratio in frontend)
	$wp_customize->add_setting( 'tedx_hero_poster_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tedx_hero_poster_image', array(
		'label'    => __( 'Hero Event Poster Image', 'tedx-regensburg' ),
		'section'  => 'tedx_hero_section',
		'settings' => 'tedx_hero_poster_image',
	) ) );
}
add_action( 'customize_register', 'tedx_add_new_hero_customizer_settings', 20 );
<?php
// Just append the mobile logo code to customizer.php instead.
