/**
 * Interactive Floorplan Map Front-End Script
 * Supports: Zoom In/Out, Reset, Fullscreen, Drag Panning, Pinch-to-Zoom, and Smart Mobile Scrolling.
 *
 * @package TEDx_Regensburg
 */

(function () {
	'use strict';

	function initFloorplan(container) {
		if (container.dataset.floorplanInitialized) {
			return;
		}
		container.dataset.floorplanInitialized = 'true';

		var viewport = container.querySelector('.tedx-floorplan-viewport');
		var stage = container.querySelector('.tedx-floorplan-stage');
		if (!viewport || !stage) {
			return;
		}

		var minZoom = parseFloat(container.dataset.minZoom) || 1.0;
		var maxZoom = parseFloat(container.dataset.maxZoom) || 5.0;

		var scale = 1.0;
		var translateX = 0;
		var translateY = 0;

		var isDragging = false;
		var dragStartX = 0;
		var dragStartY = 0;
		var initialTranslateX = 0;
		var initialTranslateY = 0;

		var isPinching = false;
		var initialPinchDistance = 0;
		var initialPinchScale = 1.0;
		var pinchMidpointX = 0;
		var pinchMidpointY = 0;

		var zoomInBtn = container.querySelector('.tedx-floorplan-zoom-in');
		var zoomOutBtn = container.querySelector('.tedx-floorplan-zoom-out');
		var resetBtn = container.querySelector('.tedx-floorplan-reset');
		var fullscreenBtn = container.querySelector('.tedx-floorplan-fullscreen');

		function clamp(val, min, max) {
			return Math.min(Math.max(val, min), max);
		}

		function getBounds() {
			var rect = viewport.getBoundingClientRect();
			var maxPanX = Math.max(0, (rect.width * (scale - 1)) / 2);
			var maxPanY = Math.max(0, (rect.height * (scale - 1)) / 2);
			return {
				minX: -maxPanX,
				maxX: maxPanX,
				minY: -maxPanY,
				maxY: maxPanY
			};
		}

		function updateTransform(animate) {
			var bounds = getBounds();
			if (scale <= 1.001) {
				translateX = 0;
				translateY = 0;
				container.classList.remove('is-zoomed');
			} else {
				translateX = clamp(translateX, bounds.minX, bounds.maxX);
				translateY = clamp(translateY, bounds.minY, bounds.maxY);
				container.classList.add('is-zoomed');
			}

			stage.style.transition = animate ? 'transform 0.28s cubic-bezier(0.2, 0, 0, 1)' : 'none';
			stage.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(' + scale + ')';

			if (zoomInBtn) {
				zoomInBtn.disabled = scale >= maxZoom - 0.01;
			}
			if (zoomOutBtn) {
				zoomOutBtn.disabled = scale <= minZoom + 0.01;
			}
			if (resetBtn) {
				resetBtn.style.opacity = (scale > 1.05 || translateX !== 0 || translateY !== 0) ? '1' : '0.7';
			}
		}

		function zoomTo(newScale, focalX, focalY, animate) {
			newScale = clamp(newScale, minZoom, maxZoom);
			if (Math.abs(newScale - scale) < 0.001 && newScale === scale) {
				return;
			}

			if (focalX === undefined || focalY === undefined) {
				var rect = viewport.getBoundingClientRect();
				focalX = rect.width / 2;
				focalY = rect.height / 2;
			}

			var scaleFactor = newScale / scale;
			translateX = focalX - (focalX - translateX) * scaleFactor;
			translateY = focalY - (focalY - translateY) * scaleFactor;
			scale = newScale;

			updateTransform(animate !== false);
		}

		// Button Controls
		if (zoomInBtn) {
			zoomInBtn.addEventListener('click', function (e) {
				e.preventDefault();
				zoomTo(scale * 1.5, undefined, undefined, true);
			});
		}

		if (zoomOutBtn) {
			zoomOutBtn.addEventListener('click', function (e) {
				e.preventDefault();
				zoomTo(scale / 1.5, undefined, undefined, true);
			});
		}

		if (resetBtn) {
			resetBtn.addEventListener('click', function (e) {
				e.preventDefault();
				scale = minZoom;
				translateX = 0;
				translateY = 0;
				updateTransform(true);
			});
		}

		if (fullscreenBtn) {
			var enterIcon = fullscreenBtn.querySelector('.enter-fullscreen-icon');
			var exitIcon = fullscreenBtn.querySelector('.exit-fullscreen-icon');

			function updateFullscreenIcons() {
				var isFs = !!(document.fullscreenElement || document.webkitFullscreenElement);
				if (enterIcon) enterIcon.classList.toggle('hidden', isFs);
				if (exitIcon) exitIcon.classList.toggle('hidden', !isFs);
				setTimeout(function () {
					updateTransform(false);
				}, 100);
			}

			fullscreenBtn.addEventListener('click', function (e) {
				e.preventDefault();
				var isFs = !!(document.fullscreenElement || document.webkitFullscreenElement);
				if (!isFs) {
					if (container.requestFullscreen) {
						container.requestFullscreen();
					} else if (container.webkitRequestFullscreen) {
						container.webkitRequestFullscreen();
					}
				} else {
					if (document.exitFullscreen) {
						document.exitFullscreen();
					} else if (document.webkitExitFullscreen) {
						document.webkitExitFullscreen();
					}
				}
			});

			document.addEventListener('fullscreenchange', updateFullscreenIcons);
			document.addEventListener('webkitfullscreenchange', updateFullscreenIcons);
		}

		// Desktop Mouse Drag Panning
		viewport.addEventListener('mousedown', function (e) {
			if (e.button !== 0) return; // Left click only
			isDragging = true;
			dragStartX = e.clientX;
			dragStartY = e.clientY;
			initialTranslateX = translateX;
			initialTranslateY = translateY;
			viewport.classList.add('is-dragging');
			e.preventDefault();
		});

		window.addEventListener('mousemove', function (e) {
			if (!isDragging) return;
			var dx = e.clientX - dragStartX;
			var dy = e.clientY - dragStartY;
			translateX = initialTranslateX + dx;
			translateY = initialTranslateY + dy;
			updateTransform(false);
		});

		window.addEventListener('mouseup', function () {
			if (!isDragging) return;
			isDragging = false;
			viewport.classList.remove('is-dragging');
			updateTransform(true);
		});

		// Desktop Double-Click Zoom
		viewport.addEventListener('dblclick', function (e) {
			e.preventDefault();
			var rect = viewport.getBoundingClientRect();
			var clickX = e.clientX - rect.left;
			var clickY = e.clientY - rect.top;
			if (scale < maxZoom - 0.5) {
				zoomTo(Math.min(maxZoom, scale * 1.8), clickX, clickY, true);
			} else {
				zoomTo(minZoom, undefined, undefined, true);
			}
		});

		// Touch Gestures: Pinch-to-Zoom & Smart Panning
		function getTouchDistance(t1, t2) {
			var dx = t1.clientX - t2.clientX;
			var dy = t1.clientY - t2.clientY;
			return Math.hypot(dx, dy);
		}

		function getTouchMidpoint(t1, t2, rect) {
			return {
				x: ((t1.clientX + t2.clientX) / 2) - rect.left,
				y: ((t1.clientY + t2.clientY) / 2) - rect.top
			};
		}

		var singleTouchStartX = 0;
		var singleTouchStartY = 0;
		var isTouchPanning = false;

		viewport.addEventListener('touchstart', function (e) {
			if (e.touches.length === 2) {
				isPinching = true;
				isTouchPanning = false;
				initialPinchDistance = getTouchDistance(e.touches[0], e.touches[1]);
				initialPinchScale = scale;
				var rect = viewport.getBoundingClientRect();
				var mid = getTouchMidpoint(e.touches[0], e.touches[1], rect);
				pinchMidpointX = mid.x;
				pinchMidpointY = mid.y;
				e.preventDefault();
			} else if (e.touches.length === 1) {
				singleTouchStartX = e.touches[0].clientX;
				singleTouchStartY = e.touches[0].clientY;
				initialTranslateX = translateX;
				initialTranslateY = translateY;
				// If already zoomed in, capture single-touch for map panning
				if (scale > 1.05) {
					isTouchPanning = true;
				} else {
					isTouchPanning = false;
				}
			}
		}, { passive: false });

		viewport.addEventListener('touchmove', function (e) {
			var rect = viewport.getBoundingClientRect();

			if (isPinching && e.touches.length === 2) {
				e.preventDefault();
				var currentDistance = getTouchDistance(e.touches[0], e.touches[1]);
				if (initialPinchDistance > 0) {
					var rawScale = initialPinchScale * (currentDistance / initialPinchDistance);
					var targetScale = clamp(rawScale, minZoom, maxZoom);

					var scaleFactor = targetScale / scale;
					translateX = pinchMidpointX - (pinchMidpointX - translateX) * scaleFactor;
					translateY = pinchMidpointY - (pinchMidpointY - translateY) * scaleFactor;
					scale = targetScale;
					updateTransform(false);
				}
			} else if (isTouchPanning && e.touches.length === 1) {
				// Prevent page scroll only when actively panning the zoomed map
				e.preventDefault();
				var dx = e.touches[0].clientX - singleTouchStartX;
				var dy = e.touches[0].clientY - singleTouchStartY;
				translateX = initialTranslateX + dx;
				translateY = initialTranslateY + dy;
				updateTransform(false);
			}
		}, { passive: false });

		viewport.addEventListener('touchend', function (e) {
			if (e.touches.length < 2) {
				isPinching = false;
			}
			if (e.touches.length === 0) {
				isTouchPanning = false;
				updateTransform(true);
			} else if (e.touches.length === 1 && scale > 1.05) {
				singleTouchStartX = e.touches[0].clientX;
				singleTouchStartY = e.touches[0].clientY;
				initialTranslateX = translateX;
				initialTranslateY = translateY;
				isTouchPanning = true;
			}
		});

		viewport.addEventListener('touchcancel', function () {
			isPinching = false;
			isTouchPanning = false;
			updateTransform(true);
		});

		// Initial transform setup
		updateTransform(false);
	}

	function initAllFloorplans() {
		var containers = document.querySelectorAll('.tedx-floorplan-container');
		for (var i = 0; i < containers.length; i++) {
			initFloorplan(containers[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAllFloorplans);
	} else {
		initAllFloorplans();
	}
})();
