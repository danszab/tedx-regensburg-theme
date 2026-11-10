(function () {
	'use strict';

	function initBottomNavScroll() {
		var navItems = document.querySelectorAll('.tedx-bottom-nav-item[data-scroll-target]');
		navItems.forEach(function (item) {
			if (item._hasScrollHandler) return;
			item.addEventListener('click', function (e) {
				var targetId = item.getAttribute('data-scroll-target');
				if (!targetId) return;

				var el = document.getElementById(targetId) || document.querySelector('.' + targetId) || document.querySelector('[data-section="' + targetId + '"]');
				if (el) {
					e.preventDefault();
					el.scrollIntoView({ behavior: 'smooth', block: 'start' });
					if (history.pushState) {
						history.pushState(null, null, '#' + targetId);
					}
				}
			});
			item._hasScrollHandler = true;
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initBottomNavScroll);
	} else {
		initBottomNavScroll();
	}
})();
