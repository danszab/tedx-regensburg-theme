/**
 * Main JavaScript for TEDx Regensburg Theme
 */

document.addEventListener('DOMContentLoaded', () => {
	// 1. Mobile Menu Toggle
	const menuToggle = document.getElementById('mobile-menu-toggle');
	const mobileMenu = document.getElementById('mobile-menu');
	const openIcon = menuToggle ? menuToggle.querySelector('.open-icon') : null;
	const closeIcon = menuToggle ? menuToggle.querySelector('.close-icon') : null;

	if (menuToggle && mobileMenu) {
		menuToggle.addEventListener('click', () => {
			const isHidden = mobileMenu.classList.contains('hidden');
			if (isHidden) {
				mobileMenu.classList.remove('hidden');
				if (openIcon) openIcon.classList.add('hidden');
				if (closeIcon) closeIcon.classList.remove('hidden');
				document.body.classList.add('overflow-hidden'); // Prevent background scrolling
			} else {
				mobileMenu.classList.add('hidden');
				if (openIcon) openIcon.classList.remove('hidden');
				if (closeIcon) closeIcon.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			}
		});

		// Close mobile menu when a nav link is clicked
		mobileMenu.querySelectorAll('a').forEach(link => {
			link.addEventListener('click', function(e) {
				// If this link has a dropdown arrow, toggle the accordion instead of closing the menu
				const arrow = this.querySelector('.mobile-dropdown-arrow');
				if (arrow) {
					const subMenu = this.nextElementSibling;
					if (subMenu && subMenu.classList.contains('sub-menu')) {
						e.preventDefault();
						subMenu.classList.toggle('hidden');
						subMenu.classList.toggle('flex');
						arrow.classList.toggle('rotate-180-anim');
						return; // Stop here, don't close the mobile drawer
					}
				}
				
				// Normal link: close the mobile drawer
				mobileMenu.classList.add('hidden');
				if (openIcon) openIcon.classList.remove('hidden');
				if (closeIcon) closeIcon.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			});
		});
		
		// Reset state if resized to desktop
		window.addEventListener('resize', () => {
			if (window.innerWidth >= 1024 && !mobileMenu.classList.contains('hidden')) {
				mobileMenu.classList.add('hidden');
				if (openIcon) openIcon.classList.remove('hidden');
				if (closeIcon) closeIcon.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			}
		});
	}

	// 2. Sticky Header scroll effect
	const header = document.getElementById('site-header');
	if (header) {
		window.addEventListener('scroll', () => {
			if (window.scrollY > 50) {
				header.classList.add('bg-[#151515]/90', 'shadow-lg');
				header.classList.remove('bg-[#151515]/40');
			} else {
				header.classList.remove('bg-[#151515]/90', 'shadow-lg');
				header.classList.add('bg-[#151515]/40');
			}
		});
	}

	// 3. Smooth scrolling for hash links
	document.querySelectorAll('a[href*="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			// Only smooth scroll if the link is on the current page
			if (this.hostname === window.location.hostname && this.pathname.replace(/^\//, '') === window.location.pathname.replace(/^\//, '') && this.hash) {
				const targetElement = document.querySelector(this.hash);
				if (targetElement) {
					e.preventDefault();
					
					const headerOffset = 60; // Height of the sticky header
					const elementPosition = targetElement.getBoundingClientRect().top;
					const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

					window.scrollTo({
						top: offsetPosition,
						behavior: 'smooth'
					});
				}
			}
		});
	});

	// 4. Newsletter Form Submission Handling
	const newsletterForm = document.getElementById('tedx-newsletter-form');
	const newsletterFeedback = document.getElementById('tedx-newsletter-feedback');

	if (newsletterForm && newsletterFeedback) {
		newsletterForm.addEventListener('submit', (e) => {
			const action = newsletterForm.getAttribute('action');
			if (!action || action === '#') {
				e.preventDefault();
				const emailInput = newsletterForm.querySelector('input[type="email"]');
				if (emailInput && emailInput.value) {
					newsletterFeedback.textContent = 'Thank you for subscribing to TEDx Regensburg updates!';
					newsletterFeedback.classList.remove('hidden', 'text-red-400');
					newsletterFeedback.classList.add('text-tedx-green');
					newsletterForm.reset();
				}
			}
		});
	}

	// 5. TEDx Slider (Core Gallery Block Style) functionality
	const tedxSliders = document.querySelectorAll('.wp-block-gallery.is-style-tedx-slider');
	
	if (tedxSliders.length > 0) {
		// Create lightbox if it doesn't exist
		let lightbox = document.querySelector('.tedx-lightbox');
		if (!lightbox) {
			lightbox = document.createElement('div');
			lightbox.className = 'tedx-lightbox';
			lightbox.innerHTML = `
				<button class="lightbox-close">&times;</button>
				<img class="lightbox-img" src="" alt="">
			`;
			document.body.appendChild(lightbox);
			
			const closeBtn = lightbox.querySelector('.lightbox-close');
			const lightboxImg = lightbox.querySelector('.lightbox-img');
			
			const closeLightbox = () => {
				lightbox.classList.remove('active');
				setTimeout(() => {
					lightbox.style.display = 'none';
					lightboxImg.src = '';
					document.body.style.overflow = '';
				}, 300);
			};

			closeBtn.addEventListener('click', closeLightbox);
			lightbox.addEventListener('click', (e) => {
				if (e.target === lightbox) closeLightbox();
			});
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape' && lightbox.classList.contains('active')) closeLightbox();
			});
		}

		const lightboxImg = lightbox.querySelector('.lightbox-img');

		tedxSliders.forEach(slider => {
			// Add fade elements as siblings to avoid scrolling with the container
			const wrapper = document.createElement('div');
			wrapper.style.position = 'relative';
			wrapper.style.width = '100vw';
			wrapper.style.left = '50%';
			wrapper.style.transform = 'translateX(-50%)';
			wrapper.style.backgroundColor = '#1e1e1e';
			
			// Move slider styles to wrapper where appropriate
			slider.style.width = '100%';
			slider.style.left = '0';
			slider.style.transform = 'none';
			
			// Insert wrapper before slider, then move slider into wrapper
			slider.parentNode.insertBefore(wrapper, slider);
			wrapper.appendChild(slider);
			
			// Add fades to wrapper
			const fadeLeft = document.createElement('div');
			fadeLeft.className = 'tedx-slider-fade-left';
			const fadeRight = document.createElement('div');
			fadeRight.className = 'tedx-slider-fade-right';
			
			wrapper.appendChild(fadeLeft);
			wrapper.appendChild(fadeRight);

			// Add click events to images
			const images = slider.querySelectorAll('img');
			images.forEach(img => {
				img.addEventListener('click', (e) => {
					e.preventDefault();
					lightboxImg.src = img.src;
					lightboxImg.alt = img.alt || '';
					lightbox.style.display = 'flex';
					// Trigger reflow
					void lightbox.offsetWidth;
					lightbox.classList.add('active');
					document.body.style.overflow = 'hidden';
				});
			});
		});
	}
});

document.addEventListener('DOMContentLoaded', () => {
    // Advanced TEDx Gallery Logic
    const galleryWrappers = document.querySelectorAll('.tedx-gallery-wrapper');
    
    galleryWrappers.forEach(wrapper => {
        const container = wrapper.querySelector('.tedx-gallery-container');
        const rows = wrapper.querySelectorAll('.tedx-gallery-row');
        const prevBtn = wrapper.querySelector('.gallery-prev');
        const nextBtn = wrapper.querySelector('.gallery-next');
        const scrollbarThumb = wrapper.querySelector('.gallery-scrollbar-thumb');
        const scrollbarTrack = wrapper.querySelector('.gallery-scrollbar');
        
        let currentTranslate = 0;
        // Find max scroll distance. Since w-max is used, calculate row width
        const getScrollBounds = () => {
            const firstRow = rows[0];
            const maxScroll = (firstRow.scrollWidth - wrapper.offsetWidth);
            return Math.max(0, maxScroll + 100); // add padding
        };

        const updateScroll = (newTranslate) => {
            const max = getScrollBounds();
            currentTranslate = Math.max(-max, Math.min(0, newTranslate));
            
            rows.forEach(row => {
                row.style.transform = `translateX(${currentTranslate}px)`;
                row.style.transition = 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1)';
            });

            // Update thumb position
            if (scrollbarTrack && scrollbarThumb && max > 0) {
                const scrollPct = Math.abs(currentTranslate) / max;
                const trackSpace = scrollbarTrack.offsetWidth - scrollbarThumb.offsetWidth;
                scrollbarThumb.style.left = `${scrollPct * trackSpace}px`;
                scrollbarThumb.style.transition = 'left 0.5s cubic-bezier(0.25, 1, 0.5, 1)';
            }
        };

        if (prevBtn && nextBtn) {
            nextBtn.addEventListener('click', () => {
                updateScroll(currentTranslate - 300);
            });
            prevBtn.addEventListener('click', () => {
                updateScroll(currentTranslate + 300);
            });
        }
        
        // Lightbox logic for advanced gallery
        const lightbox = wrapper.querySelector('.tedx-lightbox');
        const lightboxImg = wrapper.querySelector('.lightbox-img');
        const closeBtn = wrapper.querySelector('.lightbox-close');
        
        if (lightbox) {
            const items = wrapper.querySelectorAll('.tedx-gallery-item img');
            items.forEach(img => {
                img.addEventListener('click', (e) => {
                    e.preventDefault();
                    lightboxImg.src = img.src;
                    lightboxImg.alt = img.alt || '';
                    lightbox.style.display = 'flex';
                    void lightbox.offsetWidth; // Reflow
                    lightbox.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            const closeLightbox = () => {
                lightbox.classList.remove('active');
                setTimeout(() => {
                    lightbox.style.display = 'none';
                    lightboxImg.src = '';
                    document.body.style.overflow = '';
                }, 300);
            };
            
            closeBtn.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) closeLightbox();
            });
        }

        // Navigation Active State Handling
        const navLinks = document.querySelectorAll('a.nav-link, #mobile-menu a, .menu-item a');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Ignore accordion toggles, they are handled separately
                if (this.querySelector('.mobile-dropdown-arrow') || this.querySelector('.nav-dropdown-arrow')) {
                    return;
                }

                // Check if it's a link to an anchor on the *current* page
                if (this.hostname === window.location.hostname && this.pathname.replace(/^\//, '') === window.location.pathname.replace(/^\//, '') && this.hash) {
                    navLinks.forEach(l => l.classList.remove('active-nav-link'));
                    this.classList.add('active-nav-link');
                    
                    const mobileMenu = document.getElementById('mobile-menu');
                    const menuToggle = document.getElementById('mobile-menu-toggle');
                    if (mobileMenu && !mobileMenu.classList.contains('hidden') && menuToggle) {
                        menuToggle.click();
                    }
                }
            });
        });
    });
});
