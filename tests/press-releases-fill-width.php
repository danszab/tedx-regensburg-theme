<?php
/**
 * Test fill-width 1000px condition for tedx-press-releases and standard paragraphs.
 * Run with: php tests/press-releases-fill-width.php
 */

function check( $condition, $message ) {
	if ( ! $condition ) {
		echo "FAIL: $message\n";
		exit( 1 );
	}
	echo "PASS: $message\n";
}

// 1. tedx/press-releases block.json defaults align to full
$block_json_file = dirname( __DIR__ ) . '/blocks/tedx-press-releases/block.json';
check( file_exists( $block_json_file ), 'blocks/tedx-press-releases/block.json exists' );
$block_data = json_decode( file_get_contents( $block_json_file ), true );
check( isset( $block_data['attributes']['align']['default'] ) && 'full' === $block_data['attributes']['align']['default'], 'tedx/press-releases defaults align attribute to "full"' );

// 2. tedx-press-releases CSS width calculations
$press_css_file = dirname( __DIR__ ) . '/blocks/tedx-press-releases/style.css';
check( file_exists( $press_css_file ), 'blocks/tedx-press-releases/style.css exists' );
$press_css = file_get_contents( $press_css_file );

check(
	strpos( $press_css, 'max-width: 1000px' ) !== false,
	'tedx-press-releases heading max-width is 1000px'
);

check(
	strpos( $press_css, 'min-width: 1000px' ) !== false && strpos( $press_css, 'padding-inline: 0' ) !== false,
	'tedx-press-releases heading removes horizontal padding at 1000px+ viewport'
);

check(
	strpos( $press_css, 'calc((100% - 1000px) / 2)' ) !== false && strpos( $press_css, 'calc((100% - 1000px) / 2 + 16px)' ) === false,
	'tedx-press-releases grid aligns to 1000px boundary without 16px inward inset'
);

check(
	strpos( $press_css, '(1000px - 48px) / 3' ) !== false || strpos( $press_css, '317.333px' ) !== false,
	'tedx-press-releases card width calculates to fill 1000px (3 cards + 2x 24px gaps = 1000px)'
);

// 3. Compiled style.css paragraph and content width checks
$main_css_file = dirname( __DIR__ ) . '/assets/css/style.css';
check( file_exists( $main_css_file ), 'assets/css/style.css exists' );
$main_css = file_get_contents( $main_css_file );

check(
	strpos( $main_css, 'max-width:1000px' ) !== false,
	'Standard paragraph and content blocks have max-width 1000px'
);

check(
	strpos( $main_css, '@media (min-width:1000px)' ) !== false && strpos( $main_css, 'padding-left:0' ) !== false && strpos( $main_css, 'padding-right:0' ) !== false,
	'Standard paragraph and content blocks remove horizontal padding on viewports >= 1000px to satisfy 1000px fill width'
);

echo "All fill-width condition tests passed!\n";
