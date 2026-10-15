<?php
/**
 * Category-filtered press-release cards.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$category_id = absint( $attributes['categoryId'] );
if ( ! $category_id || ! term_exists( $category_id, 'category' ) ) {
	echo tedx_block_editor_notice( __( 'Select a post category for the press releases.', 'tedx-regensburg' ) );
	return;
}

$releases = get_posts( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'has_password'  => false,
	'category'      => $category_id,
	'posts_per_page' => min( 24, max( 1, (int) $attributes['postsToShow'] ) ),
	'orderby'       => 'date',
	'order'         => 'DESC',
) );

if ( ! $releases ) {
	echo tedx_block_editor_notice( __( 'No published press releases found in this category.', 'tedx-regensburg' ) );
	return;
}

$heading_id = wp_unique_id( 'tedx-press-heading-' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'tedx-press-releases' ) ); ?>>
	<div class="tedx-press-heading">
		<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="tedx-block-heading"><?php echo esc_html( $attributes['title'] ); ?></h2>
	</div>
	<div class="tedx-press-scroll" tabindex="0" role="region" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
		<div class="tedx-press-grid<?php echo count( $releases ) > 3 ? ' tedx-press-grid-two-rows' : ''; ?>">
			<?php foreach ( $releases as $release ) :
				$title   = get_the_title( $release );
				$excerpt = $release->post_excerpt ? $release->post_excerpt : $release->post_content;
				$excerpt = preg_replace( '/<\/(?:p|h[1-6]|li|div|blockquote|tr)>/i', ' ', $excerpt );
				$excerpt = html_entity_decode( wp_strip_all_tags( strip_shortcodes( $excerpt ) ), ENT_QUOTES, 'UTF-8' );
				$excerpt = wp_trim_words( $excerpt, min( 80, max( 10, (int) $attributes['excerptLength'] ) ) );
				$pdf_url = add_query_arg( array( 'action' => 'tedx_press_release_pdf', 'post_id' => $release->ID ), admin_url( 'admin-post.php' ) );
				?>
				<article class="tedx-press-card">
					<a class="tedx-press-image" href="<?php echo esc_url( get_permalink( $release ) ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
						<?php if ( has_post_thumbnail( $release ) ) : ?>
							<?php echo get_the_post_thumbnail( $release, 'large', array( 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span class="tedx-press-placeholder" aria-hidden="true">TED<span>x</span></span>
						<?php endif; ?>
					</a>
					<div class="tedx-press-card-body">
						<h3><a href="<?php echo esc_url( get_permalink( $release ) ); ?>"><?php echo esc_html( $title ); ?></a></h3>
						<p class="tedx-press-excerpt"><?php echo esc_html( $excerpt ); ?></p>
						<div class="tedx-press-actions">
							<a class="tedx-press-more" href="<?php echo esc_url( get_permalink( $release ) ); ?>" aria-label="<?php echo esc_attr( $attributes['showMoreText'] . ': ' . $title ); ?>"><?php echo esc_html( $attributes['showMoreText'] ); ?></a>
							<a class="tedx-press-download" href="<?php echo esc_url( $pdf_url ); ?>" aria-label="<?php echo esc_attr( $attributes['downloadText'] . ': ' . $title ); ?>"><?php echo esc_html( $attributes['downloadText'] ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
