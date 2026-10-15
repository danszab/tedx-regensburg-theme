<?php
/**
 * Media fetch priorities, hero selection and early poster loading.
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tedx_hero_posters() {
	static $posters;
	if ( isset( $posters ) ) {
		return $posters;
	}
	$posters = array();
	foreach ( array( 'desktop' => 'tedx_hero_video_poster', 'mobile' => 'tedx_hero_video_mobile_poster' ) as $device => $key ) {
		$url = tedx_mod( $key, '' );
		$id  = $url ? attachment_url_to_postid( $url ) : 0;
		$preview = $id ? wp_get_attachment_image_url( $id, 'large' ) : false;
		$posters[ $device ] = $preview ? $preview : $url;
	}
	$posters['mobile']  = $posters['mobile'] ?: $posters['desktop'];
	$posters['desktop'] = $posters['desktop'] ?: $posters['mobile'];
	return $posters;
}

function tedx_preload_hero_poster() {
	if ( ! is_front_page() ) {
		return;
	}
	$posters = tedx_hero_posters();
	foreach ( array( 'mobile' => '(max-width: 767px)', 'desktop' => '(min-width: 768px)' ) as $device => $media ) {
		if ( $posters[ $device ] ) {
			printf(
				'<link rel="preload" as="image" href="%s" media="%s" fetchpriority="high">' . "\n",
				esc_url( $posters[ $device ] ),
				esc_attr( $media )
			);
		}
	}
}
add_action( 'wp_head', 'tedx_preload_hero_poster', 2 );

/**
 * Keep WordPress from promoting unrelated images to high priority.
 */
function tedx_image_loading_priority( $loading_attrs, $tag_name, $attrs, $context ) {
	if ( 'img' === $tag_name ) {
		$loading_attrs['fetchpriority'] = $attrs['fetchpriority'] ?? 'auto';
	}
	return $loading_attrs;
}
add_filter( 'wp_get_loading_optimization_attributes', 'tedx_image_loading_priority', 10, 4 );
