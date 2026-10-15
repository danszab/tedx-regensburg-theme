<?php
$images = isset($attributes['images']) ? $attributes['images'] : array();

if ( empty($images) ) {
    return;
}

?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tedx-gallery-wrapper' ) ); ?>>
    
    <div class="tedx-gallery-scroll-container">
        <div class="tedx-gallery-grid">
            <!-- Title is forced to Row 1 -->
            <div class="gallery-title-block">
                <h2 class="tedx-block-heading">Gallery</h2>
            </div>
            
            <?php foreach($images as $img):
                $image_id = absint( $img['id'] ?? 0 );
                if ( ! $image_id && ! empty( $img['url'] ) ) {
                    $image_id = attachment_url_to_postid( $img['url'] );
                }
                $lightbox_src = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : false;
                $lightbox_srcset = $image_id ? wp_get_attachment_image_srcset( $image_id, 'large' ) : false;
                ?>
                <div class="tedx-gallery-item">
                    <?php echo tedx_block_image(
                        array( 'id' => $image_id, 'url' => $img['url'] ?? '' ),
                        'medium',
                        array(
                            'alt' => $img['alt'] ?? '',
                            'sizes' => '(max-width: 768px) 180px, 231px',
                            'data-lightbox-src' => $lightbox_src ? $lightbox_src : ( $img['url'] ?? '' ),
                            'data-lightbox-srcset' => $lightbox_srcset ? $lightbox_srcset : '',
                        )
                    ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Fades -->
    <div class="tedx-gallery-fade-left"></div>
    <div class="tedx-gallery-fade-right"></div>

    <!-- Navigation Controls -->
    <div class="gallery-controls-wrapper">
        <div class="gallery-controls">
            <button class="gallery-prev">
                <svg style="width:24px;height:24px;transform:rotate(180deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div class="gallery-scrollbar">
                <div class="gallery-scrollbar-thumb"></div>
            </div>
            <button class="gallery-next">
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>
    
    <!-- Lightbox Modal -->
    <div class="tedx-lightbox">
        <button class="lightbox-close">&times;</button>
        <img class="lightbox-img" alt="" decoding="async">
    </div>
</div>
