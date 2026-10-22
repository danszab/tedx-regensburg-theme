/**
 * shadcn / Base UI Accordion interactive controller.
 * Handles single/multiple expansion, smooth height animations, and full WAI-ARIA compliance.
 */
(function () {
	'use strict';

	function initAccordion(root) {
		if (root._tedxAccordionInitialized) return;
		root._tedxAccordionInitialized = true;

		var isSingle = root.getAttribute('data-accordion-type') !== 'multiple';
		var isCollapsible = root.getAttribute('data-accordion-collapsible') !== 'false';
		var triggers = Array.prototype.slice.call(root.querySelectorAll('.tedx-accordion-trigger'));

		function setOpen(item, trigger, content, open) {
			if (open) {
				item.setAttribute('data-state', 'open');
				trigger.setAttribute('data-state', 'open');
				trigger.setAttribute('aria-expanded', 'true');
				content.setAttribute('data-state', 'open');
				content.style.visibility = 'visible';

				// Measure scrollHeight for smooth CSS animation
				content.style.height = '0px';
				// Force reflow
				void content.offsetHeight;
				var targetHeight = content.scrollHeight;
				content.style.height = targetHeight + 'px';

				var onEnd = function (e) {
					if (e.target === content && e.propertyName === 'height') {
						content.removeEventListener('transitionend', onEnd);
						if (content.getAttribute('data-state') === 'open') {
							content.style.height = 'auto';
						}
					}
				};
				content.addEventListener('transitionend', onEnd);
			} else {
				// Lock from auto to explicit height to trigger transition
				var currentHeight = content.scrollHeight;
				content.style.height = currentHeight + 'px';
				void content.offsetHeight; // Force reflow

				item.setAttribute('data-state', 'closed');
				trigger.setAttribute('data-state', 'closed');
				trigger.setAttribute('aria-expanded', 'false');
				content.setAttribute('data-state', 'closed');
				content.style.height = '0px';

				var onCloseEnd = function (e) {
					if (e.target === content && e.propertyName === 'height') {
						content.removeEventListener('transitionend', onCloseEnd);
						if (content.getAttribute('data-state') === 'closed') {
							content.style.visibility = 'hidden';
						}
					}
				};
				content.addEventListener('transitionend', onCloseEnd);
			}
		}

		triggers.forEach(function (trigger, index) {
			var contentId = trigger.getAttribute('aria-controls');
			var content = root.querySelector('#' + contentId);
			var item = trigger.closest('.tedx-accordion-item');
			if (!content || !item) return;

			trigger.addEventListener('click', function (e) {
				e.preventDefault();
				var isOpen = trigger.getAttribute('data-state') === 'open';

				if (isOpen) {
					if (isCollapsible || !isSingle) {
						setOpen(item, trigger, content, false);
					}
				} else {
					if (isSingle) {
						// Close other items
						triggers.forEach(function (otherTrigger) {
							if (otherTrigger !== trigger) {
								var otherContentId = otherTrigger.getAttribute('aria-controls');
								var otherContent = root.querySelector('#' + otherContentId);
								var otherItem = otherTrigger.closest('.tedx-accordion-item');
								if (otherContent && otherItem && otherTrigger.getAttribute('data-state') === 'open') {
									setOpen(otherItem, otherTrigger, otherContent, false);
								}
							}
						});
					}
					setOpen(item, trigger, content, true);
				}
			});

			// WAI-ARIA keyboard navigation (Up, Down, Home, End)
			trigger.addEventListener('keydown', function (e) {
				var targetTrigger = null;
				if (e.key === 'ArrowDown') {
					e.preventDefault();
					targetTrigger = triggers[(index + 1) % triggers.length];
				} else if (e.key === 'ArrowUp') {
					e.preventDefault();
					targetTrigger = triggers[(index - 1 + triggers.length) % triggers.length];
				} else if (e.key === 'Home') {
					e.preventDefault();
					targetTrigger = triggers[0];
				} else if (e.key === 'End') {
					e.preventDefault();
					targetTrigger = triggers[triggers.length - 1];
				}
				if (targetTrigger) {
					targetTrigger.focus();
				}
			});
		});
	}

	function initAll() {
		var accordions = document.querySelectorAll('.tedx-accordion');
		for (var i = 0; i < accordions.length; i++) {
			initAccordion(accordions[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}

	// Expose for dynamic / editor environments
	window.tedxInitAccordions = initAll;
})();
