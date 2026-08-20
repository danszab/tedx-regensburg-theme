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
			} else {
				mobileMenu.classList.add('hidden');
				if (openIcon) openIcon.classList.remove('hidden');
				if (closeIcon) closeIcon.classList.add('hidden');
			}
		});

		// Close mobile menu when a nav link is clicked
		mobileMenu.querySelectorAll('a').forEach(link => {
			link.addEventListener('click', () => {
				mobileMenu.classList.add('hidden');
				if (openIcon) openIcon.classList.remove('hidden');
				if (closeIcon) closeIcon.classList.add('hidden');
			});
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
	document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			const targetId = this.getAttribute('href');
			if (targetId && targetId !== '#' && targetId.length > 1) {
				const targetElement = document.querySelector(targetId);
				if (targetElement) {
					e.preventDefault();
					const headerOffset = 70;
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
});
