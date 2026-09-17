document.addEventListener('DOMContentLoaded', function() {
    var galleryWrappers = document.querySelectorAll('.tedx-gallery-wrapper');
    
    galleryWrappers.forEach(function(wrapper) {
        var scrollContainer = wrapper.querySelector('.tedx-gallery-scroll-container');
        var prevBtn = wrapper.querySelector('.gallery-prev');
        var nextBtn = wrapper.querySelector('.gallery-next');
        var scrollbarThumb = wrapper.querySelector('.gallery-scrollbar-thumb');
        var scrollbarTrack = wrapper.querySelector('.gallery-scrollbar');
        
        if (!scrollContainer) return;

        // Native scroll synchronization with custom thumb
        scrollContainer.addEventListener('scroll', function() {
            if (scrollbarTrack && scrollbarThumb) {
                var maxScrollLeft = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                if (maxScrollLeft > 0) {
                    var scrollPct = scrollContainer.scrollLeft / maxScrollLeft;
                    var trackSpace = scrollbarTrack.offsetWidth - scrollbarThumb.offsetWidth;
                    scrollbarThumb.style.left = (scrollPct * trackSpace) + 'px';
                }
            }
        });

        // Navigation buttons
        // Scroll by most of the visible width so each click advances several items
        var getScrollAmount = function() {
            return scrollContainer.clientWidth * 0.9;
        };

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });
        }
        
        // Lightbox logic
        var lightbox = wrapper.querySelector('.tedx-lightbox');
        var lightboxImg = wrapper.querySelector('.lightbox-img');
        var closeBtn = wrapper.querySelector('.lightbox-close');
        
        if (lightbox) {
            var items = wrapper.querySelectorAll('.tedx-gallery-item img');
            items.forEach(function(img) {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    lightboxImg.src = img.src;
                    lightboxImg.alt = img.alt || '';
                    lightbox.style.display = 'flex';
                    void lightbox.offsetWidth; // Reflow
                    lightbox.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            var closeLightbox = function() {
                lightbox.classList.remove('active');
                setTimeout(function() {
                    lightbox.style.display = 'none';
                    lightboxImg.src = '';
                    document.body.style.overflow = '';
                }, 300);
            };
            
            if (closeBtn) {
                closeBtn.addEventListener('click', closeLightbox);
            }
            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) closeLightbox();
            });
        }
    });
});
