<?php
/**
 * TEDx Gutenberg blocks: registration, editor assets and shared render helpers.
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Block directories (relative to /blocks) registered by the theme.
 */
function tedx_get_block_dirs() {
	return array(
		'tedx-gallery',
		'tedx-press-releases',
		'tedx-timetable',
		'tedx-speakers',
		'tedx-speaker-coaches',
		'tedx-event',
		'tedx-about',
		'tedx-stats',
		'tedx-sponsors',
		'tedx-text-section',
		'tedx-team',
		'tedx-venue',
	);
}

/**
 * Register shared editor helper script and all blocks.
 */
function tedx_register_native_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	wp_register_script(
		'tedx-block-helpers',
		TEDX_URI . '/blocks/shared/editor-helpers.js',
		array( 'wp-element', 'wp-components', 'wp-block-editor', 'wp-blocks', 'wp-server-side-render' ),
		filemtime( TEDX_DIR . '/blocks/shared/editor-helpers.js' ),
		true
	);

	foreach ( tedx_get_block_dirs() as $dir ) {
		$block_type = register_block_type( TEDX_DIR . '/blocks/' . $dir );
		// Front-end block scripts are not needed for first paint; keep them out of the critical chain.
		foreach ( ( $block_type && ! empty( $block_type->view_script_handles ) ) ? $block_type->view_script_handles : array() as $handle ) {
			wp_script_add_data( $handle, 'strategy', 'defer' );
		}
	}
}
add_action( 'init', 'tedx_register_native_blocks' );

/**
 * "TEDx" block category.
 */
function tedx_block_categories( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'tedx',
			'title' => __( 'TEDx', 'tedx-regensburg' ),
		)
	);
	return $categories;
}
add_filter( 'block_categories_all', 'tedx_block_categories' );

/**
 * Distinct meta values (e.g. years) for a post type. Cached; flushed on save.
 */
function tedx_get_distinct_meta_values( $post_type, $meta_key ) {
	$cache_key = 'tedx_distinct_' . md5( $post_type . $meta_key );
	$values    = get_transient( $cache_key );

	if ( false === $values ) {
		global $wpdb;
		$values = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = %s AND p.post_type = %s
				AND p.post_status IN ( 'publish', 'draft', 'pending', 'private' )
				AND pm.meta_value <> ''
				ORDER BY pm.meta_value DESC",
				$meta_key,
				$post_type
			)
		);
		set_transient( $cache_key, $values, HOUR_IN_SECONDS );
	}

	return array_values( array_filter( (array) $values ) );
}

function tedx_flush_year_cache() {
	delete_transient( 'tedx_distinct_' . md5( 'speaker_year' . '_speaker_year' ) );
	delete_transient( 'tedx_distinct_' . md5( 'speaker_coach' . '_speaker_coach_year' ) );
	delete_transient( 'tedx_distinct_' . md5( 'speaker' . '_speaker_year' ) );
}
add_action( 'save_post_speaker', 'tedx_flush_year_cache' );
add_action( 'save_post_speaker_coach', 'tedx_flush_year_cache' );
add_action( 'deleted_post', 'tedx_flush_year_cache' );

/**
 * Icon names offered in the Stats block icon picker.
 */
function tedx_get_stat_icon_choices() {
	return array(
		'stat-1'      => __( 'Theme icon 1', 'tedx-regensburg' ),
		'stat-2'      => __( 'Theme icon 2', 'tedx-regensburg' ),
		'stat-3'      => __( 'Theme icon 3', 'tedx-regensburg' ),
		'groups'      => __( 'People', 'tedx-regensburg' ),
		'globe'       => __( 'Globe', 'tedx-regensburg' ),
		'send'        => __( 'Send', 'tedx-regensburg' ),
		'ticket'      => __( 'Ticket', 'tedx-regensburg' ),
		'info'        => __( 'Info', 'tedx-regensburg' ),
		'mail'        => __( 'Mail', 'tedx-regensburg' ),
		'youtube'     => __( 'YouTube', 'tedx-regensburg' ),
		'linkedin'    => __( 'LinkedIn', 'tedx-regensburg' ),
		'instagram'   => __( 'Instagram', 'tedx-regensburg' ),
		'facebook'    => __( 'Facebook', 'tedx-regensburg' ),
		'arrow-right' => __( 'Arrow', 'tedx-regensburg' ),
	);
}

/**
 * Editor data (years, icons, events) exposed to the block editor scripts.
 */
function tedx_enqueue_block_editor_data() {
	$icons = array();
	foreach ( tedx_get_stat_icon_choices() as $value => $label ) {
		$icons[] = array(
			'value' => $value,
			'label' => $label,
		);
	}

	$events = array( array( 'value' => 0, 'label' => __( '— None —', 'tedx-regensburg' ) ) );
	foreach ( get_posts( array( 'post_type' => 'tedx_event', 'posts_per_page' => 100, 'post_status' => array( 'publish', 'draft' ) ) ) as $event ) {
		$events[] = array(
			'value' => $event->ID,
			'label' => get_the_title( $event ),
		);
	}

	$data = array(
		'speakerYears' => tedx_get_distinct_meta_values( 'speaker', '_speaker_year' ),
		'coachYears'   => tedx_get_distinct_meta_values( 'speaker_coach', '_speaker_coach_year' ),
		'icons'        => $icons,
		'events'       => $events,
		'postCategories' => array_map(
			function ( $category ) {
				return array( 'value' => $category->term_id, 'label' => $category->name );
			},
			get_categories( array( 'hide_empty' => false ) )
		),
	);

	wp_add_inline_script( 'tedx-block-helpers', 'window.tedxBlockData = ' . wp_json_encode( $data ) . ';', 'before' );
}
add_action( 'enqueue_block_editor_assets', 'tedx_enqueue_block_editor_data' );

/**
 * Load the compiled Tailwind stylesheet inside the editor canvas, so block
 * previews look like the front end.
 */
function tedx_enqueue_editor_canvas_styles() {
	if ( ! is_admin() ) {
		return;
	}

	$compiled = TEDX_DIR . '/assets/css/style.css';
	if ( file_exists( $compiled ) ) {
		wp_enqueue_style( 'tedx-editor-tailwind', TEDX_URI . '/assets/css/style.css', array(), filemtime( $compiled ) );
	}
	wp_add_inline_style(
		'tedx-editor-tailwind',
		'.editor-styles-wrapper{background:#0a0a0a;color:#fff;font-family:"Helvetica Neue",Inter,sans-serif;}'
	);
}
add_action( 'enqueue_block_assets', 'tedx_enqueue_editor_canvas_styles' );

/**
 * Notice shown only in the editor (REST/ServerSideRender) when a block has nothing to render.
 */
function tedx_block_editor_notice( $message ) {
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return '<div class="max-w-figma mx-auto px-4 py-10 text-sm text-white/60">' . esc_html( $message ) . '</div>';
	}
	return '';
}

/**
 * Shared button markup used across blocks.
 *
 * @param string $text    Label.
 * @param string $url     Target URL.
 * @param string $variant 'solid' | 'outline'.
 * @param string $icon    Optional tedx_get_icon() key.
 */
function tedx_block_button( $text, $url, $variant = 'outline', $icon = '' ) {
	$base = ( 'solid' === $variant )
		? 'inline-flex items-center gap-2 bg-tedx-red text-white text-sm font-medium px-5 py-3 rounded-xl shadow-md transition-all duration-200 btn-shadcn-anim'
		: 'inline-flex items-center justify-center gap-2 border border-tedx-red text-tedx-red text-sm font-medium px-5 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim';

	$html  = '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $base ) . '">';
	if ( $icon && function_exists( 'tedx_get_icon' ) ) {
		$html .= tedx_get_icon( $icon, 'w-4 h-4' );
	}
	$html .= '<span>' . esc_html( $text ) . '</span></a>';
	return $html;
}

/**
 * Resolve the post ID the block is rendered for (works on the front end and in the editor).
 */
function tedx_block_post_id( $block ) {
	if ( $block && ! empty( $block->context['postId'] ) ) {
		return (int) $block->context['postId'];
	}
	return (int) get_the_ID();
}

/**
 * Render a Media Library image at a generated size, preserving URL-only images.
 */
function tedx_block_image( $image, $size, $attrs = array() ) {
	$attrs = wp_parse_args( $attrs, array( 'loading' => 'lazy', 'decoding' => 'async', 'fetchpriority' => 'auto' ) );
	$id    = absint( $image['id'] ?? 0 );
	if ( ! $id && ! empty( $image['url'] ) ) {
		$id = attachment_url_to_postid( $image['url'] );
	}
	if ( $id ) {
		$html = wp_get_attachment_image( $id, $size, false, $attrs );
		if ( $html ) {
			return $html;
		}
	}
	if ( empty( $image['url'] ) ) {
		return '';
	}

	$html = '<img src="' . esc_url( $image['url'] ) . '"';
	foreach ( $attrs as $name => $value ) {
		$html .= ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
	return $html . '>';
}

/**
 * Render the speakers / speaker coaches grid (masonry cards).
 *
 * @param array $a Arguments, see defaults below.
 * @return string  HTML (empty when there is nothing to show).
 */
function tedx_render_people_grid( $a ) {
	$a = wp_parse_args(
		$a,
		array(
			'post_type'         => 'speaker',
			'year'              => '',
			'title'             => '',
			'show_year'         => true,
			'show_event_button' => false,
			'event_button_text' => '',
			'event_button_url'  => '#event-pitch',
			'show_cfs'          => false,
			'cfs_title'         => '',
			'cfs_description'   => '',
			'cfs_url'           => '',
			'cfs_button_text'   => '',
			'show_more'         => true,
			'wrapper_attrs'     => '',
		)
	);

	$is_coach = ( 'speaker_coach' === $a['post_type'] );
	$year     = trim( (string) $a['year'] );

	$query_args = array(
		'post_type'      => $a['post_type'],
		'no_found_rows'  => true,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);
	if ( '' !== $year ) {
		$query_args['meta_query'] = array(
			array(
				'key'     => $is_coach ? '_speaker_coach_year' : '_speaker_year',
				'value'   => $year,
				'compare' => '=',
			),
		);
	}
	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() && ! $a['show_cfs'] ) {
		wp_reset_postdata();
		$label = $is_coach ? __( 'No speaker coaches found.', 'tedx-regensburg' ) : __( 'No speakers found for this year.', 'tedx-regensburg' );
		return tedx_block_editor_notice( $label );
	}

	$cards = array();

	// Header "card": title, year and optional event button.
	ob_start();
	?>
	<div class="mb-4 pt-4 px-2 flex flex-col gap-6">
		<?php if ( $is_coach ) : ?>
			<h2 class="block tedx-block-heading normal-case"><?php echo esc_html( $a['title'] ); ?></h2>
		<?php else : ?>
			<?php tedx_category_header( $a['title'], '', 'h2' ); ?>
			<?php if ( $a['show_year'] && '' !== $year ) : ?>
				<span class="block tedx-block-heading"><?php echo esc_html( $year ); ?></span>
			<?php endif; ?>
			<?php if ( $a['show_event_button'] && '' !== trim( (string) $a['event_button_text'] ) ) : ?>
				<div class="pt-2">
					<a href="<?php echo esc_url( $a['event_button_url'] ); ?>" class="inline-flex items-center justify-center gap-2 border border-tedx-red text-tedx-red text-sm font-medium px-5 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim w-fit">
						<span><?php echo esc_html( $a['event_button_text'] ); ?></span>
						<span aria-hidden="true">&rarr;</span>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
	$cards[] = ob_get_clean();

	while ( $query->have_posts() ) {
		$query->the_post();
		$id            = get_the_ID();
		$default_role  = $is_coach ? __( 'Speaker Coach', 'tedx-regensburg' ) : __( 'Keynote Speaker', 'tedx-regensburg' );
		$topic         = get_post_meta( $id, '_speaker_topic', true ) ?: $default_role;
		$language      = get_post_meta( $id, '_speaker_language', true ) ?: 'EN';
		$linkedin_url  = get_post_meta( $id, '_speaker_coach_linkedin', true ) ?: get_post_meta( $id, '_speaker_linkedin', true );
		$youtube_url   = $is_coach ? '' : get_post_meta( $id, '_speaker_youtube', true );
		$show_more_opt = get_post_meta( $id, '_speaker_coach_show_more', true );
		if ( '' === $show_more_opt ) {
			$show_more_opt = get_post_meta( $id, '_speaker_show_more', true );
		}
		$is_trimmed = $a['show_more'] && ( '' === $show_more_opt || '1' === $show_more_opt );

		ob_start();
		?>
		<article class="bg-tedx-card rounded-[32px] p-6 flex flex-col justify-between border border-white/5 hover:border-white/20 transition-all duration-300 shadow-xl group">
			<div>
				<div class="flex gap-4 items-start mb-4">
					<div class="w-[122px] h-[122px] rounded-2xl overflow-hidden shrink-0 bg-[#333] border border-white/10 relative">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'tedx-speaker', array(
								'class'    => 'w-full h-full object-cover',
								'sizes'    => '122px',
								'loading'  => 'lazy',
								'fetchpriority' => 'auto',
								'decoding' => 'async',
							) ); ?>
						<?php else : ?>
							<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-3xl bg-gradient-to-br from-[#333] to-[#222]"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></div>
						<?php endif; ?>
					</div>
					<div class="flex-1 flex flex-col gap-1 min-w-0">
						<h3 class="text-xl font-bold text-white leading-tight break-words whitespace-normal"><?php the_title(); ?></h3>
						<?php if ( ! $is_coach ) : ?>
							<span class="text-sm font-bold text-tedx-red break-words whitespace-normal"><?php echo esc_html( $topic ); ?></span>
							<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2 py-0.5 rounded text-[11px] font-medium text-white/80 w-fit mt-1">
								<?php echo tedx_get_icon( 'globe', 'w-3 h-3' ); ?>
								<span><?php echo esc_html( $language ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( has_excerpt() ) : ?>
					<div class="speaker-bio text-sm text-white/70 leading-relaxed font-normal mb-6 relative"><?php the_excerpt(); ?></div>
				<?php else : ?>
					<div class="speaker-bio text-sm text-white/70 leading-relaxed font-normal mb-6 relative">
						<div class="bio-short"><?php echo wp_kses_post( $is_trimmed ? wp_trim_words( get_the_content(), 25, '...' ) : get_the_content() ); ?></div>
						<?php if ( $is_trimmed ) : ?>
							<a href="<?php the_permalink(); ?>" class="inline-block text-white hover:text-white/70 font-bold text-xs mt-2 uppercase tracking-wider transition-colors"><?php esc_html_e( 'Show More', 'tedx-regensburg' ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $linkedin_url ) || ! empty( $youtube_url ) ) : ?>
				<div class="flex flex-col gap-3 pt-4 border-t border-white/5 mt-auto">
					<?php if ( ! empty( $youtube_url ) ) : ?>
						<a href="<?php echo esc_url( $youtube_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 bg-tedx-red text-white text-[13px] font-bold px-4 py-3 rounded-xl btn-shadcn-anim w-full">
							<span class="flex items-center justify-center"><?php echo tedx_get_icon( 'youtube', 'w-4 h-4 block' ); ?></span>
							<span class="uppercase tracking-wider leading-none relative top-[1px]"><?php esc_html_e( 'Watch the Talk', 'tedx-regensburg' ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $linkedin_url ) ) : ?>
						<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-white hover:text-white/70 uppercase tracking-wider transition-colors text-center w-full block">
							<?php esc_html_e( 'VIEW LINKEDIN', 'tedx-regensburg' ); ?> &rarr;
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
		<?php
		$cards[] = ob_get_clean();
	}
	wp_reset_postdata();

	if ( $a['show_cfs'] && ! $is_coach ) {
		ob_start();
		?>
		<div class="border-2 border-tedx-red bg-tedx-red/5 rounded-[32px] p-8 flex flex-col items-center justify-center text-center shadow-xl transition-all duration-300 hover:scale-[1.02]">
			<h3 class="text-2xl font-bold text-white mb-3"><?php echo esc_html( $a['cfs_title'] ); ?></h3>
			<p class="text-sm text-white/80 leading-relaxed mb-6 max-w-xs"><?php echo esc_html( $a['cfs_description'] ); ?></p>
			<a href="<?php echo esc_url( $a['cfs_url'] ); ?>" class="inline-flex items-center justify-center border border-tedx-red text-tedx-red text-sm font-bold uppercase tracking-wider px-5 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim">
				<?php echo esc_html( $a['cfs_button_text'] ); ?> &rarr;
			</a>
		</div>
		<?php
		$cards[] = ob_get_clean();
	}

	ob_start();
	?>
	<section <?php echo $a['wrapper_attrs']; // phpcs:ignore WordPress.Security.EscapeOutput -- built by get_block_wrapper_attributes(). ?>>
		<div class="absolute left-0 top-1/4 w-[600px] h-[600px] bg-tedx-green/5 rounded-full blur-3xl pointer-events-none -translate-x-1/2"></div>
		<div class="max-w-figma mx-auto relative z-10">
			<div class="columns-1 md:columns-2 lg:columns-3 gap-6 lg:gap-8">
				<?php foreach ( $cards as $card ) : ?>
					<div class="inline-block w-full break-inside-avoid mb-6 lg:mb-8"><?php echo $card; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
