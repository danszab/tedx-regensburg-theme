<?php
/**
 * Team block render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner content.
 * @var WP_Block $block      Block instance.
 */

$title      = ! empty( $attributes['title'] ) ? $attributes['title'] : __( 'Our Team', 'tedx-regensburg' );
$section_id = ! empty( $attributes['sectionId'] ) ? sanitize_html_class( $attributes['sectionId'] ) : 'team';

$team_args = array(
	'post_type'      => 'team_member',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
);

$team_query = new WP_Query( $team_args );

$wrapper = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full border-t border-white/5 bg-tedx-dark',
	)
);
?>

<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="max-w-figma mx-auto w-full">

		<h2 class="tedx-block-heading mb-12">
			<?php echo esc_html( $title ); ?>
		</h2>

		<?php if ( $team_query->have_posts() ) : ?>
			<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 w-full items-start">
				<?php
				while ( $team_query->have_posts() ) :
					$team_query->the_post();
					$member_id = get_the_ID();
					$role      = get_post_meta( $member_id, '_team_member_role', true );
					$linkedin  = get_post_meta( $member_id, '_team_member_linkedin', true );
					$name      = get_the_title();
					?>
					<article class="flex flex-col w-full group">
						<div class="w-full aspect-square bg-[#d9d9d9] rounded-[32px] overflow-hidden relative border border-white/10 shadow-md">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300' ) ); ?>
							<?php else : ?>
								<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-3xl bg-gradient-to-br from-[#333] to-[#222]">
									<?php echo esc_html( mb_substr( $name, 0, 1 ) ); ?>
								</div>
							<?php endif; ?>
						</div>

						<div class="flex items-start justify-between gap-2 w-full mt-3">
							<div class="flex flex-col flex-1 min-w-0">
								<h3 class="text-[18px] font-bold text-white leading-tight tracking-[-0.9px] break-words">
									<?php echo esc_html( $name ); ?>
								</h3>
								<p class="text-[12px] text-[#e8e8e8] font-normal leading-tight tracking-[-0.6px] mt-1 break-words">
									<?php echo esc_html( $role ); ?>
								</p>
							</div>

							<?php if ( ! empty( $linkedin ) ) : ?>
								<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="shrink-0 w-10 h-10 min-w-[40px] min-h-[40px] aspect-square flex items-center justify-center bg-[#212121] rounded-[12px] transition-colors duration-200 shadow-sm" aria-label="<?php echo esc_attr( sprintf( __( '%s on LinkedIn', 'tedx-regensburg' ), $name ) ); ?>">
									<div class="w-5 h-5 flex items-center justify-center pointer-events-none">
										<?php echo tedx_get_icon( 'linkedin', 'w-5 h-5 text-white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</div>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="text-white/60"><?php esc_html_e( 'No team members found.', 'tedx-regensburg' ); ?></p>
		<?php endif; ?>

	</div>
</section>
