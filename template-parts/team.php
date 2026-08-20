<?php
/**
 * Template part for displaying the Team grid
 *
 * @package TEDx_Regensburg
 */

$team_args = array(
	'post_type'      => 'team_member',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
);

$team_query = new WP_Query( $team_args );
?>

<section class="py-16 md:py-24 px-4 md:px-8 lg:px-12 flex flex-col items-center bg-tedx-dark border-t border-white/5 w-full">
	<div class="max-w-figma w-full flex flex-col items-center md:items-start">
		
		<h2 class="text-5xl md:text-6xl font-black text-white tracking-tight leading-tight w-full text-center md:text-left mb-12">
			Our Team
		</h2>
		
		<?php if ( $team_query->have_posts() ) : ?>
			<!-- Grid layout changed to max-w-5xl, increased cols, added max-w to images -->
			<div class="w-full max-w-5xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 md:gap-8">
				<?php
				while ( $team_query->have_posts() ) :
					$team_query->the_post();
					$role     = get_post_meta( get_the_ID(), '_team_member_role', true );
					$linkedin = get_post_meta( get_the_ID(), '_team_member_linkedin', true );
					$name     = get_the_title();
					?>
					<article class="flex flex-col gap-3 group items-center text-center">
						<!-- Photo Square (constrained size) -->
						<div class="w-32 sm:w-40 md:w-48 aspect-square bg-[#333] rounded-3xl overflow-hidden relative shadow-lg border border-white/10 shrink-0">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300' ) ); ?>
							<?php else: ?>
								<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-4xl bg-gradient-to-br from-[#333] to-[#222]">
									<?php echo mb_substr( $name, 0, 1 ); ?>
								</div>
							<?php endif; ?>
						</div>

						<!-- Info -->
						<div class="flex flex-col flex-grow items-center w-full">
							<div class="flex flex-col items-center justify-center gap-2">
								<h3 class="text-base sm:text-lg font-bold text-white leading-tight break-words w-full">
									<?php echo esc_html( $name ); ?>
								</h3>
								
								<?php if ( $linkedin ) : ?>
								<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="shrink-0 w-8 h-8 flex items-center justify-center bg-tedx-card rounded-lg hover:bg-tedx-red transition-colors duration-200 shadow-md">
									<?php echo tedx_get_icon( 'linkedin', 'w-4 h-4 text-white' ); ?>
								</a>
								<?php endif; ?>
							</div>
							
							<p class="text-xs sm:text-sm font-medium text-tedx-red mt-1">
								<?php echo esc_html( $role ); ?>
							</p>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="text-white/60">No team members found. Add some in the WordPress Admin.</p>
		<?php endif; ?>
		
	</div>
</section>
