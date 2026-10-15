<?php
/**
 * Public, on-demand PDF downloads of published, non-password-protected posts.
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tedx_press_release_pdf_html( $post ) {
	$allowed = array(
		'p' => array(), 'br' => array(), 'hr' => array(),
		'h2' => array(), 'h3' => array(), 'h4' => array(),
		'strong' => array(), 'b' => array(), 'em' => array(), 'i' => array(),
		'u' => array(), 's' => array(), 'blockquote' => array(),
		'ul' => array(), 'ol' => array(), 'li' => array(),
		'a' => array( 'href' => true ),
		'table' => array(), 'thead' => array(), 'tbody' => array(),
		'tr' => array(), 'th' => array(), 'td' => array(),
		'pre' => array(), 'code' => array(),
	);
	// Use saved article HTML, not dynamic blocks or shortcodes that can execute code or fetch resources.
	$body = wp_kses( wpautop( strip_shortcodes( $post->post_content ) ), $allowed );

	return '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
		@page { margin: 48pt; }
		body { font-family: "DejaVu Sans", sans-serif; font-size: 11pt; line-height: 1.6; color: #222; }
		h1 { font-size: 24pt; line-height: 1.2; }
		h2, h3, h4 { page-break-after: avoid; }
		a { color: #c80022; overflow-wrap: break-word; }
		.brand { color: #eb0028; font-weight: bold; }
		.meta { color: #666; font-size: 9pt; }
		table { width: 100%; border-collapse: collapse; }
		td, th { border: 1px solid #ddd; padding: 6pt; }
		pre { white-space: pre-wrap; }
		</style></head><body><p class="brand">' . esc_html( get_bloginfo( 'name' ) ) . '</p>'
		. '<h1>' . esc_html( get_the_title( $post ) ) . '</h1>'
		. '<p class="meta">' . esc_html( get_the_date( '', $post ) ) . '</p>'
		. $body
		. '<hr><p class="meta"><a href="' . esc_url( get_permalink( $post ) ) . '">'
		. esc_html( get_permalink( $post ) ) . '</a></p></body></html>';
}

function tedx_download_press_release_pdf() {
	$post_id = isset( $_GET['post_id'] ) && is_scalar( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
	$post    = $post_id ? get_post( $post_id ) : null;

	if ( ! $post || 'post' !== $post->post_type || 'publish' !== $post->post_status || '' !== $post->post_password ) {
		wp_die( esc_html__( 'This press release is not available.', 'tedx-regensburg' ), '', array( 'response' => 404 ) );
	}

	$autoload = TEDX_DIR . '/vendor/autoload.php';
	if ( ! is_readable( $autoload ) ) {
		error_log( 'TEDx PDF downloads unavailable: vendor/autoload.php is missing or unreadable.' );
		wp_die( esc_html__( 'PDF downloads are unavailable because the PDF library is missing. Please ask the site administrator to deploy the theme with its vendor directory.', 'tedx-regensburg' ), '', array( 'response' => 503 ) );
	}
	$missing_extensions = array();
	foreach ( array( 'dom', 'mbstring' ) as $extension ) {
		if ( ! extension_loaded( $extension ) ) {
			$missing_extensions[] = $extension;
		}
	}
	if ( $missing_extensions ) {
		$message = sprintf(
			/* translators: %s: Comma-separated PHP extension names. */
			__( 'PDF downloads require these PHP extensions: %s. Please ask the site administrator to enable them.', 'tedx-regensburg' ),
			implode( ', ', $missing_extensions )
		);
		error_log( 'TEDx PDF downloads unavailable: missing PHP extensions ' . implode( ', ', $missing_extensions ) . '.' );
		wp_die( esc_html( $message ), '', array( 'response' => 503 ) );
	}

	try {
		require_once $autoload;
		$options = new \Dompdf\Options();
		$options->set( 'isRemoteEnabled', false );
		$options->set( 'isPhpEnabled', false );
		$options->set( 'isJavascriptEnabled', false );
		$options->set( 'chroot', TEDX_DIR . '/vendor/dompdf/dompdf/lib/fonts' );
		$pdf = new \Dompdf\Dompdf( $options );
		$pdf->loadHtml( tedx_press_release_pdf_html( $post ), 'UTF-8' );
		$pdf->setPaper( 'A4' );
		$pdf->render();
		$output = $pdf->output();
	} catch ( \Throwable $error ) {
		error_log( 'TEDx press release PDF generation failed for post ' . $post_id . ': ' . $error->getMessage() );
		wp_die( esc_html__( 'The PDF could not be generated. Please try again later.', 'tedx-regensburg' ), '', array( 'response' => 500 ) );
	}

	nocache_headers();
	header( 'Content-Type: application/pdf' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Content-Disposition: attachment; filename="press-release-' . $post_id . '.pdf"' );
	header( 'Content-Length: ' . strlen( $output ) );
	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- binary PDF.
	exit;
}
add_action( 'admin_post_tedx_press_release_pdf', 'tedx_download_press_release_pdf' );
add_action( 'admin_post_nopriv_tedx_press_release_pdf', 'tedx_download_press_release_pdf' );
