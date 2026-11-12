<?php
/**
 * Verify H2 font weight in the source and compiled theme stylesheets.
 */
function check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo 'PASS: ' . $message . PHP_EOL;
}

$source = file_get_contents( dirname( __DIR__ ) . '/assets/css/input.css' );
$compiled = file_get_contents( dirname( __DIR__ ) . '/assets/css/style.css' );

check( 1 === preg_match( '/\bh2\s*\{[^{}]*font-weight:\s*400\s*!important/', $source ), 'Base H2s use normal font weight.' );
check( 1 === preg_match( '/\.entry-content\s*>\s*h2\s*\{[^{}]*font-weight:\s*400\s*!important/', $source ), 'Prose and editor H2s use normal font weight.' );
check( 1 === preg_match( '/\bh2\s*\{[^{}]*font-weight:\s*400\s*!important/', $compiled ), 'Compiled CSS includes the normal H2 weight.' );
check( 1 === preg_match( '/\.entry-content\s*>\s*h2\b[^{}]*\{[^{}]*font-weight:\s*400\s*!important/', $compiled ), 'Compiled CSS includes the normal prose and editor H2 weight.' );
