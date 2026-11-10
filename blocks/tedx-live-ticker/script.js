/**
 * TEDx Live Event Ticker Interactive Time Sync
 * Automatically reads live timetable cards from Timetable v2 if present,
 * or falls back to data-items.
 * Updates state: LIVE, UP NEXT, or FINISHED.
 */
(function () {
	'use strict';

	/**
	 * Convert "HH:MM" string to minutes from start of day.
	 *
	 * @param {string} timeStr - Time string formatted as HH:MM.
	 * @return {number|null} Minutes from midnight or null if invalid.
	 */
	function parseTimeToMinutes(timeStr) {
		if (!timeStr || typeof timeStr !== 'string') return null;
		var parts = timeStr.trim().split(':');
		if (parts.length < 2) return null;
		var hours = parseInt(parts[0], 10);
		var minutes = parseInt(parts[1], 10);
		if (isNaN(hours) || isNaN(minutes)) return null;
		return hours * 60 + minutes;
	}

	/**
	 * Format date to YYYY-MM-DD in local timezone.
	 *
	 * @param {Date} date
	 * @return {string}
	 */
	function formatDateLocal(date) {
		var year = date.getFullYear();
		var month = String(date.getMonth() + 1).padStart(2, '0');
		var day = String(date.getDate()).padStart(2, '0');
		return year + '-' + month + '-' + day;
	}

	/**
	 * Smooth scroll to timetable block on page if jump is enabled.
	 *
	 * @param {Event} e
	 */
	function handlePillClick(e) {
		var targetId = 'timetable';
		var timetableEl = document.querySelector('.tedx-timetable-v2-block') || document.getElementById(targetId);
		if (timetableEl) {
			e.preventDefault();
			timetableEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	}

	/**
	 * Update ticker block instance.
	 *
	 * @param {HTMLElement} tickerRoot
	 */
	function updateTicker(tickerRoot) {
		var badgeEl = tickerRoot.querySelector('.tedx-live-ticker-badge');
		var badgeTextEl = tickerRoot.querySelector('.tedx-live-ticker-badge-text');
		var titleEl = tickerRoot.querySelector('.tedx-live-ticker-title');
		var timeEl = tickerRoot.querySelector('.tedx-live-ticker-time');
		var pillLink = tickerRoot.querySelector('.tedx-live-ticker-pill');

		if (!badgeEl || !badgeTextEl || !titleEl || !timeEl) return;

		var eyebrowLive = tickerRoot.getAttribute('data-eyebrow-live') || 'LIVE';
		var eyebrowNext = tickerRoot.getAttribute('data-eyebrow-next') || 'UP NEXT';
		var eyebrowFinished = tickerRoot.getAttribute('data-eyebrow-finished') || 'FINISHED';
		var testMode = tickerRoot.getAttribute('data-test-mode') === '1';
		var eventDate = tickerRoot.getAttribute('data-event-date');
		var jump = tickerRoot.getAttribute('data-jump') !== '0';

		if (jump && pillLink && !pillLink._hasJumpListener) {
			pillLink.addEventListener('click', handlePillClick);
			pillLink._hasJumpListener = true;
		}

		// 1. Try reading items dynamically from the page's Timetable v2 block
		var timetableRoot = document.querySelector('.tedx-timetable-v2-timeline');
		var parsedItems = [];

		if (timetableRoot) {
			// Inherit event-date and test-mode from timetable if not explicitly set on ticker
			if (!eventDate) {
				eventDate = timetableRoot.getAttribute('data-event-date');
			}
			if (timetableRoot.getAttribute('data-test-mode') === '1') {
				testMode = true;
			}

			var cards = timetableRoot.querySelectorAll('.tedx-timetable-v2-card');
			cards.forEach(function (card) {
				var s = card.getAttribute('data-start') || '';
				var e = card.getAttribute('data-end') || '';
				var titleNode = card.querySelector('.tedx-timetable-v2-session-title');
				var t = titleNode ? titleNode.textContent.trim() : '';
				if (s && t) {
					parsedItems.push({
						startTime: s,
						endTime: e,
						title: t,
					});
				}
			});
		}

		// Fallback to embedded attributes if no timetable on page or no cards
		if (!parsedItems.length) {
			try {
				var raw = tickerRoot.getAttribute('data-items');
				if (raw) {
					parsedItems = JSON.parse(raw);
				}
			} catch (err) {
				parsedItems = [];
			}
		}

		if (!parsedItems.length) return;

		var now = new Date();
		var todayStr = formatDateLocal(now);

		// If eventDate is specified and testMode is off, verify it is today
		if (eventDate && !testMode && todayStr !== eventDate) {
			// Not event day yet: display first session as UP NEXT
			renderState('next', parsedItems[0]);
			return;
		}

		var nowMinutes = now.getHours() * 60 + now.getMinutes() + now.getSeconds() / 60;

		var ranges = [];
		var minStart = Infinity;
		var maxEnd = -Infinity;

		parsedItems.forEach(function (item) {
			var sMin = parseTimeToMinutes(item.startTime);
			var eMin = parseTimeToMinutes(item.endTime);
			if (sMin !== null && eMin === null) {
				eMin = sMin + 45;
			}
			if (sMin !== null && eMin !== null) {
				if (sMin < minStart) minStart = sMin;
				if (eMin > maxEnd) maxEnd = eMin;
				ranges.push({
					item: item,
					start: sMin,
					end: eMin,
				});
			}
		});

		if (!ranges.length) return;

		// If testMode is on and current time is outside event range, simulate active session (Session 2 start)
		if (testMode && (nowMinutes < minStart || nowMinutes >= maxEnd)) {
			nowMinutes = ranges[1] ? ranges[1].start + 5 : (minStart + 15);
		}

		// Determine status: LIVE, UP NEXT, or FINISHED
		if (nowMinutes < minStart) {
			// Event has not started yet -> UP NEXT (first session)
			renderState('next', ranges[0].item);
		} else if (nowMinutes >= maxEnd) {
			// All events finished -> FINISHED (show final session)
			renderState('finished', ranges[ranges.length - 1].item);
		} else {
			// In event range: look for live session
			var activeSession = null;
			var nextSession = null;

			for (var i = 0; i < ranges.length; i++) {
				var r = ranges[i];
				if (nowMinutes >= r.start && nowMinutes < r.end) {
					activeSession = r.item;
					break;
				} else if (nowMinutes < r.start) {
					// In a gap or looking at next session
					nextSession = r.item;
					break;
				}
			}

			if (activeSession) {
				renderState('live', activeSession);
			} else if (nextSession) {
				renderState('next', nextSession);
			} else {
				renderState('finished', ranges[ranges.length - 1].item);
			}
		}

		function renderState(state, item) {
			// Update badge class and text
			badgeEl.classList.remove('badge-live', 'badge-next', 'badge-finished');
			badgeEl.classList.add('badge-' + state);

			if (state === 'live') {
				badgeTextEl.textContent = eyebrowLive;
			} else if (state === 'finished') {
				badgeTextEl.textContent = eyebrowFinished;
			} else {
				badgeTextEl.textContent = eyebrowNext;
			}

			// Update title
			titleEl.textContent = item.title || '';

			// Update time range
			var timeStr = item.startTime || '';
			if (item.endTime) {
				timeStr += ' \u2013 ' + item.endTime;
			}
			timeEl.textContent = timeStr;
		}
	}

	function initAllTickers() {
		// Detect whether #site-header actually exists and is not hidden
		var headerEl = document.getElementById('site-header');
		var isNavbarVisible = !!(headerEl && headerEl.offsetParent !== null);

		var tickers = document.querySelectorAll('.tedx-live-ticker-block');
		tickers.forEach(function (ticker) {
			if (!isNavbarVisible || document.body.classList.contains('tedx-no-navbar') || document.body.classList.contains('no-navbar')) {
				ticker.classList.remove('tedx-has-navbar');
				ticker.classList.add('tedx-navbar-hidden');
				var floatingWrap = ticker.querySelector('.tedx-live-ticker-floating-wrapper');
				if (floatingWrap) {
					floatingWrap.style.top = '12px';
				}
			} else {
				ticker.classList.remove('tedx-navbar-hidden');
				ticker.classList.add('tedx-has-navbar');
			}
			updateTicker(ticker);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAllTickers);
	} else {
		initAllTickers();
	}

	// Update live status every 10 seconds
	setInterval(initAllTickers, 10000);
})();
