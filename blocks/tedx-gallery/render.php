<?php
$images = isset($attributes['images']) ? $attributes['images'] : array();

if ( empty($images) ) {
    return;
}

$block_classes = 'tedx-gallery-wrapper';
if (!empty($attributes['className'])) {
    $block_classes .= ' ' . $attributes['className'];
}
?>
<div class="<?php echo esc_attr($block_classes); ?>" <?php echo get_block_wrapper_attributes(); ?>>
    
    <div class="tedx-gallery-scroll-container">
        <div class="tedx-gallery-grid">
            <!-- Title is forced to Row 1 -->
            <div class="gallery-title-block">
                <h2>Gallery</h2>
            </div>
            
            <?php foreach($images as $img): ?>
                <div class="tedx-gallery-item">
                    <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" />
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
        <img class="lightbox-img" src="" alt="">
    </div>
</div>
