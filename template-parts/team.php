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

<section class="py-16 md:py-24 px-4 md:px-8 lg:px-12 w-full border-t border-white/5 bg-tedx-dark">
	<div class="max-w-figma mx-auto w-full">
		
		<!-- Section Title -->
		<h2 class="text-4xl sm:text-5xl md:text-[64px] font-black text-white tracking-[-3.2px] leading-tight mb-12">
			Our Team
		</h2>
		
		<?php if ( $team_query->have_posts() ) : ?>
			
			<!-- Team Grid: 6 columns on desktop, responsive breakdown -->
			<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 w-full items-start">
				<?php
				while ( $team_query->have_posts() ) :
					$team_query->the_post();
					$role     = get_post_meta( get_the_ID(), '_team_member_role', true );
					$linkedin = get_post_meta( get_the_ID(), '_team_member_linkedin', true );
					$name     = get_the_title();
					?>
					<article class="flex flex-col w-full group">
						
						<!-- Photo (Square with 32px rounded corners) -->
						<div class="w-full aspect-square bg-[#d9d9d9] rounded-[32px] overflow-hidden relative border border-white/10 shadow-md">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300' ) ); ?>
							<?php else : ?>
								<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-3xl bg-gradient-to-br from-[#333] to-[#222]">
									<?php echo mb_substr( $name, 0, 1 ); ?>
								</div>
							<?php endif; ?>
						</div>
						
						<!-- Information Row (Left: Name + Role, Right: LinkedIn) -->
						<div class="flex items-start justify-between gap-2 w-full mt-3">
							
							<!-- Left Column: Name & Role -->
							<div class="flex flex-col flex-1 min-w-0">
								<h3 class="text-[18px] font-bold text-white leading-tight tracking-[-0.9px] break-words">
									<?php echo esc_html( $name ); ?>
								</h3>
								<p class="text-[12px] text-[#e8e8e8] font-normal leading-tight tracking-[-0.6px] mt-1 break-words">
									<?php echo esc_html( $role ); ?>
								</p>
							</div>
							
							<!-- Right Column: LinkedIn Button (Strictly square 40x40px) -->
							<?php if ( ! empty( $linkedin ) ) : ?>
								<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="shrink-0 w-10 h-10 min-w-[40px] min-h-[40px] aspect-square flex items-center justify-center bg-[#212121] rounded-[12px] transition-colors duration-200 shadow-sm" aria-label="<?php echo esc_attr( sprintf( __( '%s on LinkedIn', 'tedx-regensburg' ), $name ) ); ?>">
									<div class="w-5 h-5 flex items-center justify-center pointer-events-none">
										<?php echo tedx_get_icon( 'linkedin', 'w-5 h-5 text-white' ); ?>
									</div>
								</a>
							<?php endif; ?>
							
						</div>
						
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
			
		<?php else : ?>
			<p class="text-white/60">No team members found.</p>
		<?php endif; ?>
		
	</div>
</section>
