<?php
add_filter('render_block', 'tedx_custom_gallery_render', 10, 2);

function tedx_custom_gallery_render($block_content, $block) {
    if ( $block['blockName'] === 'core/gallery' && isset($block['attrs']['className']) && strpos($block['attrs']['className'], 'is-style-tedx-slider') !== false ) {
        
        // Extract images from innerBlocks (WP 5.9+)
        $images = array();
        if ( !empty($block['innerBlocks']) ) {
            foreach ( $block['innerBlocks'] as $inner_block ) {
                if ( $inner_block['blockName'] === 'core/image' ) {
                    $url = isset($inner_block['attrs']['url']) ? $inner_block['attrs']['url'] : '';
                    $alt = isset($inner_block['attrs']['alt']) ? $inner_block['attrs']['alt'] : '';
                    if (!$url) {
                        // fallback regex if url attr is missing
                        preg_match('/<img[^>]+src="([^">]+)"/', $inner_block['innerHTML'], $match);
                        if(isset($match[1])) $url = $match[1];
                    }
                    if ($url) {
                        $images[] = array('url' => $url, 'alt' => $alt);
                    }
                }
            }
        } else {
            // Fallback for older WP versions or if innerBlocks is empty, regex extract from block_content
            preg_match_all('/<img[^>]+src="([^">]+)"[^>]*alt="([^">]*)"/i', $block_content, $matches, PREG_SET_ORDER);
            foreach($matches as $match) {
                $images[] = array('url' => $match[1], 'alt' => $match[2]);
            }
        }

        if ( empty($images) ) {
            return $block_content;
        }

        // Split images into two rows
        $half = ceil(count($images) / 2);
        $row1_images = array_slice($images, 0, $half);
        $row2_images = array_slice($images, $half);

        ob_start();
        ?>
        <div class="tedx-gallery-wrapper bg-black py-[64px] relative overflow-hidden w-full max-w-[1512px] mx-auto">
            <div class="tedx-gallery-container relative w-full left-1/2 -translate-x-1/2 flex flex-col gap-[24px]">
                
                <!-- Row 1 -->
                <div class="tedx-gallery-row flex gap-[24px] items-center relative w-max px-[max(16px,calc((100vw-1000px)/2))]">
                    <!-- Title Block inside gallery row -->
                    <div class="gallery-title-block shrink-0 w-[231px] md:w-[317px] h-[120px] md:h-[154px] flex items-center">
                        <h2 class="font-['Helvetica_Neue_LT_Pro:73_Bold_Extended'] text-[48px] md:text-[64px] text-white tracking-[-3.2px] leading-[0.95] m-0">
                            Gallery
                        </h2>
                    </div>
                    <?php foreach($row1_images as $img): ?>
                        <div class="tedx-gallery-item shrink-0 w-[180px] md:w-[231px] h-[120px] md:h-[154px] rounded-[32px] overflow-hidden cursor-pointer">
                            <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Row 2 -->
                <div class="tedx-gallery-row flex gap-[24px] items-center relative w-max px-[max(16px,calc((100vw-1000px)/2))]">
                    <?php foreach($row2_images as $img): ?>
                        <div class="tedx-gallery-item shrink-0 w-[180px] md:w-[231px] h-[120px] md:h-[154px] rounded-[32px] overflow-hidden cursor-pointer">
                            <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Fades -->
            <div class="absolute left-0 top-0 h-full w-[100px] md:w-[256px] bg-gradient-to-r from-black to-transparent pointer-events-none z-10"></div>
            <div class="absolute right-0 top-0 h-full w-[100px] md:w-[256px] bg-gradient-to-l from-black to-transparent pointer-events-none z-10"></div>

            <!-- Navigation Controls -->
            <div class="gallery-controls flex gap-[24px] items-center justify-start mt-[48px] relative z-20 px-[max(16px,calc((100vw-1000px)/2))]">
                <button class="gallery-prev w-10 h-10 flex items-center justify-center text-white opacity-50 hover:opacity-100 transition">
                    <svg class="w-6 h-6 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                <div class="gallery-scrollbar h-[8px] w-[150px] bg-[#474747] rounded-full relative overflow-hidden">
                    <div class="gallery-scrollbar-thumb h-full w-[54px] bg-white rounded-full absolute left-0 cursor-pointer"></div>
                </div>
                <button class="gallery-next w-10 h-10 flex items-center justify-center text-white opacity-50 hover:opacity-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            
            <!-- Lightbox Modal -->
            <div class="tedx-lightbox fixed inset-0 z-[9999] bg-black/90 hidden items-center justify-center opacity-0 transition-opacity duration-300">
                <button class="lightbox-close absolute top-6 right-6 text-white text-4xl hover:text-gray-300 bg-transparent border-none cursor-pointer">&times;</button>
                <img class="lightbox-img max-h-[90vh] max-w-[90vw] object-contain rounded-lg transform scale-95 transition-transform duration-300" src="" alt="">
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    return $block_content;
}
