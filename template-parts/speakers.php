<?php
/**
 * Speakers Section Template Part (Figma: 2026 Speakers)
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$event_year      = tedx_mod( 'tedx_event_year', '2026' );
$cfs_title       = tedx_mod( 'tedx_cfs_title', __( 'Your Name Here?', 'tedx-regensburg' ) );
$cfs_description = tedx_mod( 'tedx_cfs_description', __( 'We are curating for the final lineup for 2026. Bring your vision to the stage.', 'tedx-regensburg' ) );
$cfs_url         = tedx_mod( 'tedx_cfs_url', '#apply' );
$show_cfs = tedx_mod( 'tedx_show_cfs', true );

if ( is_page() ) {
	$meta_enable_tickets = get_post_meta( get_the_ID(), '_event_enable_tickets', true );
	if ( '0' === $meta_enable_tickets ) {
		$show_cfs = false;
	}
}

// Determine which year to show
$target_year = '';
if ( is_page() ) {
	$target_year = get_post_meta( get_the_ID(), '_event_year', true );
}
// Fallback to global Customizer event year (e.g. for homepage)
if ( empty( $target_year ) ) {
	$target_year = tedx_mod( 'tedx_event_year', '' );
}

// Query published speakers
$query_args = array(
	'post_type'      => 'speaker',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
);

// Filter by year if set
if ( ! empty( $target_year ) ) {
	$query_args['meta_query'] = array(
		array(
			'key'     => '_speaker_year',
			'value'   => $target_year,
			'compare' => '=',
		),
	);
}

$speakers_query = new WP_Query( $query_args );
?>

<section id="speakers" class="relative bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 overflow-hidden border-t border-white/5">
	
	<!-- Background subtle contour glow -->
	<div class="absolute left-0 top-1/4 w-[600px] h-[600px] bg-tedx-green/5 rounded-full blur-3xl pointer-events-none -translate-x-1/2"></div>

	<div class="max-w-figma mx-auto relative z-10">
		
		<!-- Section Header -->
		<div class="mb-12">
			<span class="block text-5xl sm:text-6xl font-black text-white tracking-tighter leading-none">
				<?php echo esc_html( $target_year ); ?>
			</span>
			<h2 class="text-4xl sm:text-5xl font-medium text-white tracking-tight leading-tight">
				<?php esc_html_e( 'Speakers', 'tedx-regensburg' ); ?>
			</h2>
		</div>

		<!-- Speakers Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">
			
			<?php
			if ( $speakers_query->have_posts() ) :
				while ( $speakers_query->have_posts() ) :
					$speakers_query->the_post();
					$speaker_id    = get_the_ID();
					$topic         = get_post_meta( $speaker_id, '_speaker_topic', true ) ?: __( 'Keynote Speaker', 'tedx-regensburg' );
					$language      = get_post_meta( $speaker_id, '_speaker_language', true ) ?: 'EN';
					$linkedin_url  = get_post_meta( $speaker_id, '_speaker_linkedin', true );
					$has_thumbnail = has_post_thumbnail();
					?>
					<article class="bg-tedx-card rounded-[32px] p-6 flex flex-col justify-between border border-white/5 hover:border-white/20 transition-all duration-300 shadow-xl group">
						<div>
							<!-- Header: Photo + Info -->
							<div class="flex gap-4 items-start mb-4">
								<div class="w-[122px] h-[122px] rounded-2xl overflow-hidden shrink-0 bg-[#333] border border-white/10 relative">
									<?php if ( $has_thumbnail ) : ?>
										<?php the_post_thumbnail( 'tedx-speaker', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300' ) ); ?>
									<?php else : ?>
										<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-3xl bg-gradient-to-br from-[#333] to-[#222]">
											<?php echo mb_substr( get_the_title(), 0, 1 ); ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="flex-1 flex flex-col gap-1 min-w-0">
									<h3 class="text-xl font-bold text-white leading-tight break-words whitespace-normal">
										<?php the_title(); ?>
									</h3>
									<span class="text-sm font-bold text-tedx-red break-words whitespace-normal">
										<?php echo esc_html( $topic ); ?>
									</span>
									<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2 py-0.5 rounded text-[11px] font-medium text-white/80 w-fit mt-1">
										<?php echo tedx_get_icon( 'globe', 'w-3 h-3' ); ?>
										<span><?php echo esc_html( $language ); ?></span>
									</div>
								</div>
							</div>

							<!-- Bio / Talk Description -->
							<div class="text-sm text-white/70 leading-relaxed font-normal mb-6">
								<?php
								if ( has_excerpt() ) {
									the_excerpt();
								} else {
									echo wp_trim_words( get_the_content(), 25, '...' );
								}
								?>
							</div>
						</div>

						<!-- LinkedIn Link -->
						<?php if ( ! empty( $linkedin_url ) ) : ?>
							<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-white/60 hover:text-white uppercase tracking-wider transition-colors pt-2 border-t border-white/5">
								<?php esc_html_e( 'VIEW LINKEDIN', 'tedx-regensburg' ); ?> &rarr;
							</a>
						<?php endif; ?>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				// Default Figma Mockup Speakers
				$mock_speakers = array(
					array(
						'name'     => 'Rainer Winkler',
						'topic'    => 'Content Creation',
						'lang'     => 'EN',
						'bio'      => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.',
						'linkedin' => 'https://linkedin.com'
					),
					array(
						'name'     => 'Surname Lastname',
						'topic'    => 'AI & Ethics',
						'lang'     => 'EN',
						'bio'      => 'Description of Speaker and Topic. Exploring sustainable technological advancements and ethical implications.',
						'linkedin' => 'https://linkedin.com'
					),
					array(
						'name'     => 'Elena Fischer',
						'topic'    => 'Urban Innovation',
						'lang'     => 'DE',
						'bio'      => 'How future cities will adapt to climate resilience and community-driven architecture in Regensburg and beyond.',
						'linkedin' => 'https://linkedin.com'
					),
					array(
						'name'     => 'Marcus Weber',
						'topic'    => 'Quantum Physics',
						'lang'     => 'EN',
						'bio'      => 'A journey into quantum entanglement, cryptography, and the future of secure communication.',
						'linkedin' => 'https://linkedin.com'
					),
					array(
						'name'     => 'Sarah Lindner',
						'topic'    => 'Bio-Engineering',
						'lang'     => 'EN',
						'bio'      => 'Engineering circular materials using synthetic biology for next-generation ecological solutions.',
						'linkedin' => 'https://linkedin.com'
					),
				);

				foreach ( $mock_speakers as $mock ) :
					?>
					<article class="bg-tedx-card rounded-[32px] p-6 flex flex-col justify-between border border-white/5 hover:border-white/20 transition-all duration-300 shadow-xl group">
						<div>
							<div class="flex gap-4 items-start mb-4">
								<div class="w-[122px] h-[122px] rounded-2xl overflow-hidden shrink-0 bg-gradient-to-br from-[#444] to-[#222] border border-white/10 flex items-center justify-center text-white/30 font-bold text-3xl">
									<?php echo mb_substr( $mock['name'], 0, 1 ); ?>
								</div>

								<div class="flex-1 flex flex-col gap-1 min-w-0">
									<h3 class="text-xl font-bold text-white leading-tight break-words whitespace-normal">
										<?php echo esc_html( $mock['name'] ); ?>
									</h3>
									<span class="text-sm font-bold text-tedx-red break-words whitespace-normal">
										<?php echo esc_html( $mock['topic'] ); ?>
									</span>
									<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2 py-0.5 rounded text-[11px] font-medium text-white/80 w-fit mt-1">
										<?php echo tedx_get_icon( 'globe', 'w-3 h-3' ); ?>
										<span><?php echo esc_html( $mock['lang'] ); ?></span>
									</div>
								</div>
							</div>

							<p class="text-sm text-white/70 leading-relaxed font-normal mb-6">
								<?php echo esc_html( $mock['bio'] ); ?>
							</p>
						</div>

						<a href="<?php echo esc_url( $mock['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-white/60 hover:text-white uppercase tracking-wider transition-colors pt-2 border-t border-white/5">
							<?php esc_html_e( 'VIEW LINKEDIN', 'tedx-regensburg' ); ?> &rarr;
						</a>
					</article>
					<?php
				endforeach;
			endif;
			?>

			<?php if ( $show_cfs ) : ?>
			<!-- Call for Speakers Card ("Your Name Here?") -->
			<div class="border-2 border-tedx-red bg-tedx-red/5 rounded-[32px] p-8 flex flex-col items-center justify-center text-center shadow-xl transition-all duration-300 hover:scale-[1.02]">
				<h3 class="text-2xl font-bold text-white mb-3">
					<?php echo esc_html( $cfs_title ); ?>
				</h3>
				
				<p class="text-sm text-white/80 leading-relaxed mb-6 max-w-xs">
					<?php echo esc_html( $cfs_description ); ?>
				</p>

				<a href="<?php echo esc_url( $cfs_url ); ?>" class="inline-flex items-center justify-center border border-tedx-red text-tedx-red text-sm font-bold uppercase tracking-wider px-5 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim">
					<?php esc_html_e( 'APPLY NOW', 'tedx-regensburg' ); ?> &rarr;
				</a>
			</div>
			<?php endif; ?>

		</div>

	</div>
</section>
