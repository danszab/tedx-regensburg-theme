/**
 * TEDx Timetable v2 Interactive Time Sync Controller
 * Synchronizes the continuous vertical progress bar and active card with the current time.
 * At each card's start time, the progress fill and thumb exactly reach that card's top border.
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
	 * Update a single timetable instance with card-aligned geometry.
	 *
	 * @param {HTMLElement} timelineRoot
	 */
	function updateTimeline(timelineRoot) {
		var showProgress = timelineRoot.getAttribute('data-show-progress') !== '0';
		var testMode = timelineRoot.getAttribute('data-test-mode') === '1';
		var eventDate = timelineRoot.getAttribute('data-event-date');
		var fillEl = timelineRoot.querySelector('.tedx-timetable-v2-progress-fill');
		var thumbEl = timelineRoot.querySelector('.tedx-timetable-v2-progress-thumb');
		var railEl = timelineRoot.querySelector('.tedx-timetable-v2-rail');
		var cards = Array.prototype.slice.call(timelineRoot.querySelectorAll('.tedx-timetable-v2-card'));

		if (!cards.length) return;

		var now = new Date();
		var todayStr = formatDateLocal(now);

		// If eventDate is specified and testMode is off, verify it is today
		if (eventDate && !testMode) {
			if (todayStr !== eventDate) {
				// Not event day: clear active state and reset progress
				cards.forEach(function (c) { c.classList.remove('is-active'); });
				timelineRoot.classList.remove('is-active-live');
				if (fillEl) fillEl.style.height = '0px';
				if (thumbEl) thumbEl.style.top = '0px';
				return;
			}
		}

		var nowMinutes = now.getHours() * 60 + now.getMinutes() + now.getSeconds() / 60;

		var itemRanges = [];
		var minStart = Infinity;
		var maxEnd = -Infinity;

		cards.forEach(function (card) {
			var startStr = card.getAttribute('data-start');
			var endStr = card.getAttribute('data-end');
			var startMin = parseTimeToMinutes(startStr);
			var endMin = parseTimeToMinutes(endStr);

			// Fallback if end time is missing: assume 45 minutes duration
			if (startMin !== null && endMin === null) {
				endMin = startMin + 45;
			}

			if (startMin !== null && endMin !== null) {
				if (startMin < minStart) minStart = startMin;
				if (endMin > maxEnd) maxEnd = endMin;
				itemRanges.push({ card: card, start: startMin, end: endMin });
			}
		});

		if (!itemRanges.length || minStart >= maxEnd) return;

		// If testMode is on and current time is outside event range, simulate an active session (session 2 start)
		if (testMode && (nowMinutes < minStart || nowMinutes >= maxEnd)) {
			nowMinutes = itemRanges[1] ? itemRanges[1].start : (minStart + 30);
		}

		// Align rail to span from top border of first card to bottom border of last card
		var card0 = cards[0];
		var cardLast = cards[cards.length - 1];

		var railTop = card0.offsetTop;
		var railBottom = cardLast.offsetTop + cardLast.offsetHeight;
		var railHeight = railBottom - railTop;

		if (railHeight <= 0) {
			railHeight = timelineRoot.offsetHeight || 100;
		}

		if (railEl) {
			railEl.style.top = railTop + 'px';
			railEl.style.height = railHeight + 'px';
			railEl.style.bottom = 'auto';
		}

		var isLive = false;
		var activeCard = null;
		var currentY = 0;

		if (nowMinutes < minStart) {
			// Event has not started yet
			currentY = 0;
			isLive = false;
		} else if (nowMinutes >= maxEnd) {
			// Event has concluded
			currentY = railHeight;
			isLive = false;
		} else {
			// Event is in progress
			isLive = true;

			for (var i = 0; i < itemRanges.length; i++) {
				var item = itemRanges[i];
				var cardTop = item.card.offsetTop - railTop;
				var cardBottom = (item.card.offsetTop + item.card.offsetHeight) - railTop;

				if (nowMinutes >= item.start && nowMinutes < item.end) {
					activeCard = item.card;
					var duration = item.end - item.start;
					var elapsed = nowMinutes - item.start;
					var fraction = duration > 0 ? (elapsed / duration) : 0;
					fraction = Math.max(0, Math.min(1, fraction));

					// At the card's starting time, exactly reaches the card's top border
					currentY = cardTop + ((cardBottom - cardTop) * fraction);
					break;
				} else if (i < itemRanges.length - 1) {
					var nextItem = itemRanges[i + 1];
					if (nowMinutes >= item.end && nowMinutes < nextItem.start) {
						// In gap between sessions: traverse from previous card bottom to next card top
						var nextCardTop = nextItem.card.offsetTop - railTop;
						var gapDuration = nextItem.start - item.end;
						var gapElapsed = nowMinutes - item.end;
						var gapFraction = gapDuration > 0 ? (gapElapsed / gapDuration) : 0;
						gapFraction = Math.max(0, Math.min(1, gapFraction));

						currentY = cardBottom + ((nextCardTop - cardBottom) * gapFraction);
						break;
					}
				}
			}
		}

		currentY = Math.max(0, Math.min(railHeight, currentY));

		// Update UI classes
		cards.forEach(function (card) {
			if (activeCard && card === activeCard) {
				card.classList.add('is-active');
			} else {
				card.classList.remove('is-active');
			}
		});

		if (isLive) {
			timelineRoot.classList.add('is-active-live');
		} else {
			timelineRoot.classList.remove('is-active-live');
		}

		if (showProgress) {
			if (fillEl) {
				fillEl.style.height = currentY + 'px';
			}
			if (thumbEl) {
				thumbEl.style.top = currentY + 'px';
			}
		}
	}

	function updateAll() {
		var timelines = document.querySelectorAll('.tedx-timetable-v2-timeline');
		for (var i = 0; i < timelines.length; i++) {
			updateTimeline(timelines[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			updateAll();
			setInterval(updateAll, 10000);
		});
	} else {
		updateAll();
		setInterval(updateAll, 10000);
	}

	window.addEventListener('resize', updateAll);
	window.addEventListener('load', updateAll);

	document.addEventListener('visibilitychange', function () {
		if (document.visibilityState === 'visible') {
			updateAll();
		}
	});

	// Expose for WordPress block editor / preview updates
	window.tedxInitTimetableV2 = updateAll;
})();
