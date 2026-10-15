<?php
/**
 * Per-embed, current-page consent. No provider HTML is emitted before consent.
 *
 * @package TEDx_Regensburg
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tedx_map_embed_url( $html ) {
	$document = new DOMDocument();
	$previous = libxml_use_internal_errors( true );
	$document->loadHTML( '<?xml encoding="UTF-8">' . $html, LIBXML_NONET );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	$frame = $document->getElementsByTagName( 'iframe' )->item( 0 );
	$url   = $frame ? $frame->getAttribute( 'src' ) : '';
	$parts = wp_parse_url( $url );
	if ( ! $parts || 'https' !== ( $parts['scheme'] ?? '' ) || ! in_array( strtolower( $parts['host'] ?? '' ), array( 'www.google.com', 'maps.google.com', 'www.google.de', 'maps.google.de' ), true ) || ! preg_match( '~^/maps(?:/|$)~', $parts['path'] ?? '' ) || isset( $parts['user'] ) || isset( $parts['port'] ) ) {
		return '';
	}
	return $url;
}

function tedx_youtube_embed_url( $url ) {
	$parts = wp_parse_url( $url );
	if ( ! $parts || ! in_array( $parts['scheme'] ?? '', array( 'https', 'http' ), true ) || isset( $parts['user'] ) || isset( $parts['port'] ) || ! in_array( strtolower( $parts['host'] ?? '' ), array( 'youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtube-nocookie.com' ), true ) ) {
		return '';
	}
	parse_str( $parts['query'] ?? '', $query );
	$path = trim( $parts['path'] ?? '', '/' );
	$id   = 'youtu.be' === strtolower( $parts['host'] ) ? $path : ( $query['v'] ?? '' );
	if ( preg_match( '~^(?:embed|shorts|live)/([A-Za-z0-9_-]{11})$~', $path, $match ) ) {
		$id = $match[1];
	}
	return is_string( $id ) && preg_match( '/^[A-Za-z0-9_-]{11}$/', $id ) ? 'https://www.youtube-nocookie.com/embed/' . $id : '';
}

function tedx_consent_embed( $url, $service, $title ) {
	$privacy = get_privacy_policy_url();
	ob_start();
	?>
	<div class="tedx-embed" data-tedx-embed data-embed-url="<?php echo esc_url( $url ); ?>" data-embed-title="<?php echo esc_attr( $title ); ?>">
		<div class="tedx-embed-placeholder">
			<p><strong><?php echo esc_html( $service ); ?></strong></p>
			<p><?php echo esc_html__( 'Anbieter: Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland.', 'tedx-regensburg' ); ?></p>
			<p><?php echo esc_html__( 'Erst nach Ihrer Einwilligung wird eine Verbindung zu Google hergestellt. Dabei werden personenbezogene Daten (z. B. Ihre IP-Adresse) übermittelt. Google kann Cookies und ähnliche Technologien verwenden; eine Verarbeitung in den USA ist möglich.', 'tedx-regensburg' ); ?></p>
			<p><?php echo esc_html__( 'Die Einwilligung gilt nur für diesen Inhalt bis zum Verlassen oder Neuladen der Seite. Sie können den Inhalt jederzeit wieder deaktivieren. Bereits übermittelte Daten und von Google gespeicherte Cookies werden dadurch nicht gelöscht.', 'tedx-regensburg' ); ?></p>
			<p>
				<?php if ( $privacy ) : ?>
					<a href="<?php echo esc_url( $privacy ); ?>"><?php echo esc_html__( 'Datenschutzerklärung', 'tedx-regensburg' ); ?></a> ·
				<?php endif; ?>
				<a href="https://policies.google.com/privacy?hl=de" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Datenschutz bei Google (externer Link)', 'tedx-regensburg' ); ?></a>
			</p>
			<?php if ( $url ) : ?>
				<button type="button" data-embed-accept hidden><?php echo esc_html( sprintf( __( 'Einwilligen und %s laden', 'tedx-regensburg' ), $service ) ); ?></button>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Extern bei Google öffnen', 'tedx-regensburg' ); ?></a>
			<?php else : ?>
				<p role="status"><?php echo esc_html__( 'Dieser Inhalt ist nicht verfügbar: Die hinterlegte Embed-URL ist ungültig.', 'tedx-regensburg' ); ?></p>
			<?php endif; ?>
			<noscript><?php echo esc_html__( 'Zum Laden auf dieser Seite benötigen Sie JavaScript. Alternativ können Sie den externen Link verwenden.', 'tedx-regensburg' ); ?></noscript>
		</div>
		<div class="tedx-embed-content" hidden></div>
		<button type="button" data-embed-revoke hidden><?php echo esc_html__( 'Inhalt deaktivieren / Einwilligung widerrufen', 'tedx-regensburg' ); ?></button>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Cover legacy HTML, shortcode/oEmbed output, and Gutenberg embeds before delivery.
 * Replace only the provider iframe, leaving surrounding content and other providers intact.
 */
function tedx_gate_content_embeds( $html ) {
	return preg_replace_callback(
		'~<iframe\b[^>]*>.*?</iframe\s*>~is',
		function ( $match ) {
			$document = new DOMDocument();
			$previous = libxml_use_internal_errors( true );
			$document->loadHTML( '<?xml encoding="UTF-8">' . $match[0], LIBXML_NONET );
			libxml_clear_errors();
			libxml_use_internal_errors( $previous );
			$frame = $document->getElementsByTagName( 'iframe' )->item( 0 );
			if ( ! $frame ) {
				return $match[0];
			}
			$source = $frame->getAttribute( 'src' );
			if ( 0 === strpos( $source, '//' ) ) {
				$source = 'https:' . $source;
			}
			$video = tedx_youtube_embed_url( $source );
			$map   = tedx_map_embed_url( '<iframe src="' . esc_attr( $source ) . '"></iframe>' );
			if ( ! $video && ! $map ) {
				return $match[0];
			}
			$service = $video ? 'YouTube' : 'Google Maps';
			return tedx_consent_embed( $video ?: $map, $service, $frame->getAttribute( 'title' ) ?: $service );
		},
		$html
	);
}
add_filter( 'embed_oembed_html', 'tedx_gate_content_embeds', 99 );
add_filter( 'the_content', 'tedx_gate_content_embeds', 99 );
