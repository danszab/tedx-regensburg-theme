<?php
/**
 * Test standard paragraph styles matching TEDx Text section block.
 * Run with: php tests/paragraph-styles.php
 */

$css_file = dirname( __DIR__ ) . '/assets/css/style.css';
if ( ! file_exists( $css_file ) ) {
	echo "FAIL: style.css not found.\n";
	exit( 1 );
}

$css = file_get_contents( $css_file );

function check( $condition, $message ) {
	if ( ! $condition ) {
		echo "FAIL: $message\n";
		exit( 1 );
	}
	echo "PASS: $message\n";
}

// 1. Textsize: 16px
check(
	strpos( $css, 'font-size:16px' ) !== false,
	'Paragraph text size is 16px'
);

// 2. Typography: font-family and font-weight 400
check(
	strpos( $css, 'font-family:Helvetica Neue,Inter,sans-serif' ) !== false && strpos( $css, 'font-weight:400' ) !== false,
	'Paragraph typography uses Helvetica Neue / Inter and font-weight 400'
);

// 3. Color: rgba(255, 255, 255, 0.9) (Tailwind minifies to hsla(0,0%,100%,.9) or rgba(255,255,255,.9))
check(
	strpos( $css, 'color:hsla(0,0%,100%,.9)' ) !== false || strpos( $css, 'color:rgba(255,255,255,.9)' ) !== false,
	'Paragraph color matches rgba(255, 255, 255, 0.9)'
);

// 4. Line-height: 1.6
check(
	strpos( $css, 'line-height:1.6' ) !== false,
	'Paragraph line-height is 1.6'
);

// 5. Spacing: 1rem between paragraphs
check(
	strpos( $css, 'margin-top:1rem' ) !== false && ( strpos( $css, 'p+p' ) !== false || strpos( $css, 'wp-block-paragraph+.wp-block-paragraph' ) !== false ),
	'Paragraph consecutive spacing is 1rem'
);

// 6. Linewidth: max-width 1000px and auto margins (mx-auto)
check(
	strpos( $css, 'max-width:1000px;margin-left:auto;margin-right:auto' ) !== false,
	'Paragraph linewidth is max-width 1000px centered'
);

// 6b. Heading & block alignment: wp-block-heading shares the 1000px centered linewidth
check(
	strpos( $css, 'wp-block-heading:not([data-align=full])' ) !== false || strpos( $css, 'wp-block-heading:not(.alignfull)' ) !== false,
	'Headings share the max-width 1000px centered linewidth with paragraphs'
);

// 6c. Headings typography: Responsive H1, H2, H3
check(
	strpos( $css, 'color:#eb0028' ) !== false && ( strpos( $css, 'font-size:1.5rem' ) !== false || strpos( $css, 'font-size:24px' ) !== false ),
	'H2 heading uses TEDx red #eb0028 and responsive font-size'
);

check(
	strpos( $css, 'font-size:1.25rem' ) !== false || strpos( $css, 'font-size:20px' ) !== false,
	'H3 heading uses responsive font-size'
);

check(
	strpos( $css, 'font-size:2.125rem' ) !== false,
	'H1 heading uses responsive font-size'
);

// 7. Background color: NOT implemented on paragraph
preg_match_all( '/([^}{]+)\{([^}]+)\}/', $css, $matches, PREG_SET_ORDER );
$paragraph_has_bg = false;
foreach ( $matches as $rule ) {
	$selectors = $rule[1];
	$declarations = $rule[2];
	if ( preg_match( '/(^|,|\s)(p|\.wp-block-paragraph)($|,|\s|:)/', $selectors ) ) {
		if ( strpos( $declarations, 'background-color' ) !== false || strpos( $declarations, 'background:' ) !== false ) {
			// Ensure it's not a button or selection
			if ( strpos( $selectors, 'selection' ) === false && strpos( $selectors, 'btn' ) === false ) {
				$paragraph_has_bg = true;
				echo "Found background on: $selectors -> $declarations\n";
			}
		}
	}
}

check(
	! $paragraph_has_bg,
	'Standard paragraph explicitly does not have background color'
);

echo "All paragraph style checks passed!\n";
