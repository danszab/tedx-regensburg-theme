<?php
/**
 * Speakers Section Template Part
 */

$target_year = trim( isset($args['target_year']) ? $args['target_year'] : get_query_var( 'target_year' ) );
$show_cfs    = tedx_mod( 'tedx_show_cfs', true );
$cfs_title   = tedx_mod( 'tedx_cfs_title', __( 'Your Name Here?', 'tedx-regensburg' ) );
$cfs_description = tedx_mod( 'tedx_cfs_description', __( 'Apply to be a speaker at our next TEDx Regensburg event.', 'tedx-regensburg' ) );
$cfs_url     = tedx_mod( 'tedx_cfs_url', home_url( '/apply' ) );

$query_args = array(
	'post_type'      => 'speaker',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
);

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

// 1. Collect all cards into an array of HTML strings
$all_cards = array();

ob_start();
?>
<div class="mb-4 pt-4 px-2">
	<span class="block text-5xl sm:text-6xl font-normal text-white tracking-tighter leading-none"><?php echo esc_html( $target_year ); ?></span>
	<h2 class="text-4xl sm:text-5xl font-medium text-white tracking-tight leading-tight"><?php esc_html_e( 'Speakers', 'tedx-regensburg' ); ?></h2>
</div>
<?php
$all_cards[] = ob_get_clean();

if ( $speakers_query->have_posts() ) {
	while ( $speakers_query->have_posts() ) {
		$speakers_query->the_post();
		ob_start();
		$speaker_id    = get_the_ID();
		$topic         = get_post_meta( $speaker_id, '_speaker_topic', true ) ?: __( 'Keynote Speaker', 'tedx-regensburg' );
		$language      = get_post_meta( $speaker_id, '_speaker_language', true ) ?: 'EN';
		$linkedin_url  = get_post_meta( $speaker_id, '_speaker_linkedin', true );
		$youtube_url   = get_post_meta( $speaker_id, '_speaker_youtube', true );
		$has_thumbnail = has_post_thumbnail();
		?>
		<article class="bg-tedx-card rounded-[32px] p-6 flex flex-col justify-between border border-white/5 hover:border-white/20 transition-all duration-300 shadow-xl group">
			<div>
				<div class="flex gap-4 items-start mb-4">
					<div class="w-[122px] h-[122px] rounded-2xl overflow-hidden shrink-0 bg-[#333] border border-white/10 relative">
						<?php if ( $has_thumbnail ) : ?>
							<?php the_post_thumbnail( 'tedx-speaker', array( 'class' => 'w-full h-full object-cover ' ) ); ?>
						<?php else : ?>
							<div class="w-full h-full flex items-center justify-center text-white/30 font-bold text-3xl bg-gradient-to-br from-[#333] to-[#222]">
								<?php echo mb_substr( get_the_title(), 0, 1 ); ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="flex-1 flex flex-col gap-1 min-w-0">
						<h3 class="text-xl font-bold text-white leading-tight break-words whitespace-normal"><?php the_title(); ?></h3>
						<span class="text-sm font-bold text-tedx-red break-words whitespace-normal"><?php echo esc_html( $topic ); ?></span>
						<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2 py-0.5 rounded text-[11px] font-medium text-white/80 w-fit mt-1">
							<?php echo tedx_get_icon( 'globe', 'w-3 h-3' ); ?>
							<span><?php echo esc_html( $language ); ?></span>
						</div>
					</div>
				</div>
								<?php
				$full_content = get_the_content();
				$show_more_option = get_post_meta( $speaker_id, '_speaker_show_more', true );
				if ( '' === $show_more_option ) { $show_more_option = '1'; } // Default to enabled
				$is_trimmed = ( '1' === $show_more_option );
				
				if ( has_excerpt() ) {
					echo '<div class="speaker-bio text-sm text-white/70 leading-relaxed font-normal mb-6 relative">';
					the_excerpt();
					echo '</div>';
				} else {
					$display_content = $is_trimmed ? wp_trim_words( $full_content, 25, '...' ) : $full_content;
					?>
					<div class="speaker-bio text-sm text-white/70 leading-relaxed font-normal mb-6 relative">
						<div class="bio-short"><?php echo wp_kses_post( $display_content ); ?></div>
						<?php if ( $is_trimmed ) : ?>
							<a href="<?php the_permalink(); ?>" class="inline-block text-white hover:text-white/70 font-bold text-xs mt-2 uppercase tracking-wider transition-colors">
								<?php esc_html_e( 'Show More', 'tedx-regensburg' ); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php
				}
				?>
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
		$all_cards[] = ob_get_clean();
	}
	wp_reset_postdata();
} else {
	$mock_speakers = array(
		array('name' => 'Elizabeth Pilz', 'topic' => 'Minimalism as a structural method to reduce complexity', 'lang' => 'DE', 'bio' => '', 'linkedin' => '#'),
		array('name' => 'Gabriel Chouchane', 'topic' => 'Changing the way of thinking about AI', 'lang' => 'EN', 'bio' => '', 'linkedin' => '#'),
		array('name' => 'Natasha Vorompiova', 'topic' => 'Self-sabotage as a protection mechanism', 'lang' => 'EN', 'bio' => '', 'linkedin' => '#'),
		array('name' => 'Erika Magyarosi', 'topic' => 'Becoming a finisher', 'lang' => 'EN', 'bio' => '', 'linkedin' => '#'),
		array('name' => 'Hanna Markovych', 'topic' => 'The influence of health and mental strenght', 'lang' => 'EN', 'bio' => '', 'linkedin' => '#'),
		array('name' => 'Petar Tumbov', 'topic' => 'Friendship as a 3rd concept of opportunity', 'lang' => 'EN', 'bio' => '', 'linkedin' => '#'),
		array('name' => 'Liya Khusnullina', 'topic' => 'Cross-cultural differences', 'lang' => 'EN', 'bio' => '', 'linkedin' => '#'),
	);
	foreach ( $mock_speakers as $mock ) {
		ob_start();
		?>
		<article class="bg-tedx-card rounded-[32px] p-6 flex flex-col justify-between border border-white/5 hover:border-white/20 transition-all duration-300 shadow-xl group">
			<div>
				<div class="flex gap-4 items-start mb-4">
					<div class="w-[122px] h-[122px] rounded-2xl overflow-hidden shrink-0 bg-gradient-to-br from-[#444] to-[#222] border border-white/10 flex items-center justify-center text-white/30 font-bold text-3xl">
						<?php echo mb_substr( $mock['name'], 0, 1 ); ?>
					</div>
					<div class="flex-1 flex flex-col gap-1 min-w-0">
						<h3 class="text-xl font-bold text-white leading-tight break-words whitespace-normal"><?php echo esc_html( $mock['name'] ); ?></h3>
						<span class="text-sm font-bold text-tedx-red break-words whitespace-normal"><?php echo esc_html( $mock['topic'] ); ?></span>
						<div class="inline-flex items-center gap-1 bg-[#4d4d4d] px-2 py-0.5 rounded text-[11px] font-medium text-white/80 w-fit mt-1">
							<?php echo tedx_get_icon( 'globe', 'w-3 h-3' ); ?>
							<span><?php echo esc_html( $mock['lang'] ); ?></span>
						</div>
					</div>
				</div>
				<?php if(!empty($mock['bio'])): ?>
				<p class="text-sm text-white/70 leading-relaxed font-normal mb-6"><?php echo esc_html( $mock['bio'] ); ?></p>
				<?php endif; ?>
			</div>
		</article>
		<?php
		$all_cards[] = ob_get_clean();
	}
}

if ( $show_cfs ) {
	ob_start();
	?>
	<div class="border-2 border-tedx-red bg-tedx-red/5 rounded-[32px] p-8 flex flex-col items-center justify-center text-center shadow-xl transition-all duration-300 hover:scale-[1.02]">
		<h3 class="text-2xl font-bold text-white mb-3"><?php echo esc_html( $cfs_title ); ?></h3>
		<p class="text-sm text-white/80 leading-relaxed mb-6 max-w-xs"><?php echo esc_html( $cfs_description ); ?></p>
		<a href="<?php echo esc_url( $cfs_url ); ?>" class="inline-flex items-center justify-center border border-tedx-red text-tedx-red text-sm font-bold uppercase tracking-wider px-5 py-3 rounded-xl transition-all duration-200 btn-shadcn-anim">
			<?php esc_html_e( 'APPLY NOW', 'tedx-regensburg' ); ?> &rarr;
		</a>
	</div>
	<?php
	$all_cards[] = ob_get_clean();
}

// 2. Distribute cards into 3 columns (Left-to-Right reading order)
$columns = array( array(), array(), array() );
$col_idx = 0;
foreach ( $all_cards as $card ) {
	$columns[ $col_idx ][] = $card;
	$col_idx = ( $col_idx + 1 ) % 3;
}
?>

<section id="speakers" class="relative bg-tedx-dark py-16 md:py-24 px-4 md:px-8 lg:px-12 overflow-hidden border-t border-white/5">
	<div class="absolute left-0 top-1/4 w-[600px] h-[600px] bg-tedx-green/5 rounded-full blur-3xl pointer-events-none -translate-x-1/2"></div>
	<div class="max-w-figma mx-auto relative z-10">
		

		<!-- Speakers Grid (List view nested in a Column view for precise 24px vertical gap) -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-start">
			<?php foreach ( $columns as $col_cards ) : ?>
				<?php if ( ! empty( $col_cards ) ) : ?>
					<div class="flex flex-col gap-6 lg:gap-8">
						<?php foreach ( $col_cards as $card ) { echo $card; } ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
