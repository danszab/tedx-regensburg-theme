<?php
/**
 * The template for displaying all pages
 *
 * @package TEDx_Regensburg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$page_id        = get_queried_object_id();
$has_full_width = tedx_page_has_full_width_blocks( $page_id );
$hide_navbar    = tedx_is_navbar_hidden( $page_id );
$has_top_thumb  = tedx_page_has_top_thumbnail( $page_id );

get_header();
?>

<?php if ( $has_top_thumb ) : ?>
	<div class="<?php echo esc_attr( $hide_navbar ? 'tedx-page-thumbnail-header w-full pt-0 bg-tedx-dark' : 'tedx-page-thumbnail-header w-full pt-[60px] lg:pt-[70px] bg-tedx-dark' ); ?>">
		<div class="tedx-page-thumbnail-inner w-full">
			<?php tedx_page_top_thumbnail( $page_id ); ?>
		</div>
	</div>
<?php endif; ?>

<main id="primary" class="<?php
	if ( $has_top_thumb ) {
		echo esc_attr( $has_full_width ? 'site-main flex-grow pt-0 pb-16 bg-tedx-dark tedx-full-width-page tedx-page-has-top-thumb' : 'site-main flex-grow pt-0 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark tedx-page-has-top-thumb' );
	} elseif ( $hide_navbar ) {
		echo esc_attr( $has_full_width ? 'site-main flex-grow pt-0 pb-16 bg-tedx-dark tedx-full-width-page' : 'site-main flex-grow pt-8 md:pt-12 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark' );
	} else {
		echo esc_attr( $has_full_width ? 'site-main flex-grow pt-[60px] lg:pt-[70px] pb-16 bg-tedx-dark tedx-full-width-page' : 'site-main flex-grow pt-28 md:pt-32 pb-16 px-4 md:px-8 lg:px-12 bg-tedx-dark' );
	}
?>">
	<div class="<?php echo esc_attr( $has_full_width ? 'tedx-full-width-page-inner' : 'max-w-4xl mx-auto' ); ?>">
		
		<?php
		while ( have_posts() ) :
			the_post();
			$tedx_page_title  = function_exists( 'get_the_title' ) ? get_the_title() : '';
			$has_page_title   = '' !== trim( (string) $tedx_page_title );
			$has_featured_img = ( ! $has_top_thumb && has_post_thumbnail() );
			$has_page_header  = $has_page_title || $has_featured_img;
			$article_class    = $has_full_width
				? ( 'tedx-full-width-page-article' . ( $has_page_header ? ' tedx-has-page-header' : ' tedx-no-page-header' ) )
				: 'bg-tedx-card rounded-3xl p-5 sm:p-6 md:p-12 border border-white/5 shadow-2xl';
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $article_class ); ?>>
				
				<?php
				if ( $has_page_title ) :
				?>
					<header class="entry-header mb-8<?php echo $has_top_thumb ? ' tedx-page-thumb-title' : ''; ?>">
						<h1 class="tedx-block-heading mb-4">
							<?php the_title(); ?>
						</h1>
					</header>
				<?php endif; ?>

				<?php if ( ! $has_top_thumb && has_post_thumbnail() ) : ?>
					<div class="mb-8 rounded-2xl overflow-hidden aspect-video<?php echo $has_full_width ? ' tedx-full-width-page-featured' : ''; ?>">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $has_full_width ? 'tedx-full-width-page-content text-white/90 leading-relaxed text-base' : 'entry-content text-white/90 leading-relaxed space-y-4 text-base' ); ?>">
					<?php the_content(); ?>
				</div>

			</article>
			<?php
		endwhile;
		?>

	</div>
</main>

<?php
get_footer();
