<?php
/**
 * Page Options and Display Settings for TEDx Regensburg Theme
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register post meta for pages.
 */
function tedx_register_page_meta() {
	register_post_meta( 'page', '_tedx_hide_navbar', array(
		'show_in_rest'      => true,
		'single'           => true,
		'type'             => 'boolean',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'auth_callback'    => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'page', '_tedx_page_thumbnail_id', array(
		'show_in_rest'      => true,
		'single'           => true,
		'type'             => 'integer',
		'sanitize_callback' => 'absint',
		'auth_callback'    => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'page', '_tedx_page_thumbnail_url', array(
		'show_in_rest'      => true,
		'single'           => true,
		'type'             => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'    => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'page', '_tedx_page_thumbnail_top', array(
		'show_in_rest'      => true,
		'single'           => true,
		'type'             => 'boolean',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'auth_callback'    => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'tedx_register_page_meta' );

/**
 * Register the Page Settings meta box.
 */
function tedx_add_page_options_meta_box() {
	add_meta_box(
		'tedx_page_display_settings',
		__( 'Page Settings', 'tedx-regensburg' ),
		'tedx_render_page_options_meta_box',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'tedx_add_page_options_meta_box' );

/**
 * Render the Page Settings meta box.
 *
 * @param WP_Post $post Current post object.
 */
function tedx_render_page_options_meta_box( $post ) {
	wp_nonce_field( 'tedx_save_page_options', 'tedx_page_options_nonce' );

	$hide_navbar = ! empty( get_post_meta( $post->ID, '_tedx_hide_navbar', true ) );

	$thumb_id   = absint( get_post_meta( $post->ID, '_tedx_page_thumbnail_id', true ) );
	$thumb_url  = get_post_meta( $post->ID, '_tedx_page_thumbnail_url', true );
	$show_top   = get_post_meta( $post->ID, '_tedx_page_thumbnail_top', true );
	// Default to checked ('1') for new pages / when meta hasn't been set yet
	$is_top_checked = ( '' === $show_top || '1' === (string) $show_top || true === $show_top );

	$preview_src = '';
	if ( $thumb_id && function_exists( 'wp_get_attachment_image_url' ) ) {
		$preview_src = wp_get_attachment_image_url( $thumb_id, 'medium' );
	} elseif ( $thumb_url ) {
		$preview_src = $thumb_url;
	} elseif ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post->ID ) && function_exists( 'get_post_thumbnail_id' ) ) {
		$feat_id = get_post_thumbnail_id( $post->ID );
		if ( $feat_id && function_exists( 'wp_get_attachment_image_url' ) ) {
			$preview_src = wp_get_attachment_image_url( $feat_id, 'medium' );
		}
	}
	?>
	<div class="tedx-page-options-field" style="margin-top: 6px;">
		<label for="tedx_hide_navbar" style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
			<input
				type="checkbox"
				id="tedx_hide_navbar"
				name="tedx_hide_navbar"
				value="1"
				<?php checked( $hide_navbar ); ?>
				style="margin-top: 2px;"
			/>
			<span>
				<strong><?php esc_html_e( 'Hide navigation bar', 'tedx-regensburg' ); ?></strong>
				<span class="description" style="display: block; margin-top: 4px; color: #666; font-size: 12px; line-height: 1.4;">
					<?php esc_html_e( 'Hide the sticky top navigation header and logo on this specific page.', 'tedx-regensburg' ); ?>
				</span>
			</span>
		</label>
	</div>

	<hr style="margin: 16px 0; border: 0; border-top: 1px solid #ddd;" />

	<div class="tedx-page-options-thumbnail" style="margin-top: 12px;">
		<p style="margin: 0 0 8px 0;"><strong><?php esc_html_e( 'Page Thumbnail Image', 'tedx-regensburg' ); ?></strong></p>

		<label for="tedx_page_thumbnail_top" style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer; margin-bottom: 12px;">
			<input
				type="checkbox"
				id="tedx_page_thumbnail_top"
				name="tedx_page_thumbnail_top"
				value="1"
				<?php checked( $is_top_checked ); ?>
				style="margin-top: 2px;"
			/>
			<span>
				<strong><?php esc_html_e( 'Render thumbnail on top', 'tedx-regensburg' ); ?></strong>
				<span class="description" style="display: block; margin-top: 4px; color: #666; font-size: 12px; line-height: 1.4;">
					<?php esc_html_e( 'Renders full-width at the very top under the default navigation bar with no padding, conserving original aspect ratio.', 'tedx-regensburg' ); ?>
				</span>
			</span>
		</label>

		<div id="tedx_page_thumbnail_preview" style="margin-bottom: 10px;">
			<?php if ( ! empty( $preview_src ) ) : ?>
				<img src="<?php echo esc_url( $preview_src ); ?>" alt="<?php esc_attr_e( 'Thumbnail Preview', 'tedx-regensburg' ); ?>" style="max-width: 100%; height: auto; border-radius: 6px; display: block; border: 1px solid #ddd;" />
			<?php endif; ?>
		</div>

		<input type="hidden" id="tedx_page_thumbnail_id" name="tedx_page_thumbnail_id" value="<?php echo esc_attr( $thumb_id ? $thumb_id : '' ); ?>" />
		<input type="hidden" id="tedx_page_thumbnail_url" name="tedx_page_thumbnail_url" value="<?php echo esc_attr( $thumb_url ); ?>" />

		<div style="display: flex; gap: 8px; flex-wrap: wrap;">
			<button type="button" class="button" id="tedx_select_page_thumbnail_btn">
				<?php esc_html_e( 'Select Thumbnail Image', 'tedx-regensburg' ); ?>
			</button>
			<button type="button" class="button" id="tedx_remove_page_thumbnail_btn" style="<?php echo ( empty( $thumb_id ) && empty( $thumb_url ) ) ? 'display: none;' : ''; ?>">
				<?php esc_html_e( 'Remove Image', 'tedx-regensburg' ); ?>
			</button>
		</div>
		<p class="description" style="margin-top: 8px; color: #666; font-size: 11px; line-height: 1.3;">
			<?php esc_html_e( 'Choose a custom thumbnail or use the standard Featured Image panel. If custom is chosen, it takes precedence.', 'tedx-regensburg' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Save Page Settings meta box data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function tedx_save_page_options_meta_data( $post_id ) {
	if ( ! isset( $_POST['tedx_page_options_nonce'] ) || ! wp_verify_nonce( $_POST['tedx_page_options_nonce'], 'tedx_save_page_options' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['tedx_hide_navbar'] ) ) {
		update_post_meta( $post_id, '_tedx_hide_navbar', '1' );
	} else {
		delete_post_meta( $post_id, '_tedx_hide_navbar' );
	}

	if ( isset( $_POST['tedx_page_thumbnail_top'] ) ) {
		update_post_meta( $post_id, '_tedx_page_thumbnail_top', '1' );
	} else {
		update_post_meta( $post_id, '_tedx_page_thumbnail_top', '0' );
	}

	if ( isset( $_POST['tedx_page_thumbnail_id'] ) ) {
		$thumb_id = absint( $_POST['tedx_page_thumbnail_id'] );
		if ( $thumb_id > 0 ) {
			update_post_meta( $post_id, '_tedx_page_thumbnail_id', $thumb_id );
			if ( function_exists( 'set_post_thumbnail' ) && function_exists( 'get_post_thumbnail_id' ) && ! get_post_thumbnail_id( $post_id ) ) {
				set_post_thumbnail( $post_id, $thumb_id );
			}
		} else {
			delete_post_meta( $post_id, '_tedx_page_thumbnail_id' );
		}
	}

	if ( isset( $_POST['tedx_page_thumbnail_url'] ) ) {
		$thumb_url = esc_url_raw( trim( $_POST['tedx_page_thumbnail_url'] ) );
		if ( ! empty( $thumb_url ) ) {
			update_post_meta( $post_id, '_tedx_page_thumbnail_url', $thumb_url );
		} else {
			delete_post_meta( $post_id, '_tedx_page_thumbnail_url' );
		}
	}
}
add_action( 'save_post_page', 'tedx_save_page_options_meta_data' );

/**
 * Enqueue WordPress media scripts and uploader JS for page edit screen.
 *
 * @param string $hook Admin page hook.
 */
function tedx_page_options_admin_scripts( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}

	$custom_js = "
	jQuery(document).ready(function($) {
		var file_frame;
		$('#tedx_select_page_thumbnail_btn').on('click', function(e) {
			e.preventDefault();
			if (file_frame) {
				file_frame.open();
				return;
			}
			file_frame = wp.media({
				title: '" . esc_js( __( 'Select Thumbnail Image', 'tedx-regensburg' ) ) . "',
				button: { text: '" . esc_js( __( 'Use this image', 'tedx-regensburg' ) ) . "' },
				multiple: false
			});
			file_frame.on('select', function() {
				var attachment = file_frame.state().get('selection').first().toJSON();
				$('#tedx_page_thumbnail_id').val(attachment.id);
				$('#tedx_page_thumbnail_url').val(attachment.url);
				var displayUrl = (attachment.sizes && attachment.sizes.medium) ? attachment.sizes.medium.url : attachment.url;
				$('#tedx_page_thumbnail_preview').html('<img src=\"' + displayUrl + '\" style=\"max-width:100%;height:auto;border-radius:6px;display:block;border:1px solid #ddd;\" />');
				$('#tedx_remove_page_thumbnail_btn').show();
			});
			file_frame.open();
		});

		$('#tedx_remove_page_thumbnail_btn').on('click', function(e) {
			e.preventDefault();
			$('#tedx_page_thumbnail_id').val('');
			$('#tedx_page_thumbnail_url').val('');
			$('#tedx_page_thumbnail_preview').html('');
			$(this).hide();
		});
	});
	";
	if ( function_exists( 'wp_add_inline_script' ) ) {
		wp_add_inline_script( 'media-editor', $custom_js );
	}
}
add_action( 'admin_enqueue_scripts', 'tedx_page_options_admin_scripts' );

/**
 * Add body classes when navigation bar is hidden.
 *
 * @param array $classes Array of body class names.
 * @return array Modified array of body class names.
 */
function tedx_hide_navbar_body_class( $classes ) {
	if ( function_exists( 'tedx_is_navbar_hidden' ) && tedx_is_navbar_hidden() ) {
		$classes[] = 'tedx-no-navbar';
		$classes[] = 'no-navbar';
	}
	return $classes;
}
add_filter( 'body_class', 'tedx_hide_navbar_body_class' );

/**
 * Add body classes when page has a top thumbnail image.
 *
 * @param array $classes Array of body class names.
 * @return array Modified array of body class names.
 */
function tedx_page_thumbnail_body_class( $classes ) {
	if ( function_exists( 'tedx_page_has_top_thumbnail' ) && tedx_page_has_top_thumbnail() ) {
		$classes[] = 'tedx-has-top-thumbnail';
		$classes[] = 'has-top-thumbnail';
	}
	return $classes;
}
add_filter( 'body_class', 'tedx_page_thumbnail_body_class' );
