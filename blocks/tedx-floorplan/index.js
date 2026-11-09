(function () {
	var H = window.tedxBlocks;
	var el = H.el;
	var c = wp.components;
	var be = wp.blockEditor;
	var __ = wp.i18n.__;

	H.registerServerBlock('tedx/floorplan', function (props) {
		var attrs = props.attributes;

		function setAttr(key) {
			return function (val) {
				var obj = {};
				obj[key] = val;
				props.setAttributes(obj);
			};
		}

		// Panel 1: Header & Section
		var headerPanel = H.panel(__('Header & Section', 'tedx-regensburg'), [
			H.text(props, 'sectionId', __('Section ID (for anchor links)', 'tedx-regensburg'), __('Anchor ID, e.g. floorplan', 'tedx-regensburg')),
			H.text(props, 'eyebrow', __('Eyebrow Label', 'tedx-regensburg'), __('Small uppercase label, e.g. VENUE MAP', 'tedx-regensburg')),
			H.text(props, 'heading', __('Heading Title', 'tedx-regensburg')),
			H.textarea(props, 'description', __('Description Text', 'tedx-regensburg'), 3)
		]);

		// Panel 2: SVG File Selection
		var svgPanel = H.panel(__('Floorplan SVG Media', 'tedx-regensburg'), [
			el('div', { key: 'svg-picker', className: 'tedx-image-picker', style: { marginBottom: '16px' } },
				el('p', { style: { margin: '0 0 8px', fontWeight: 600 } }, __('Floorplan SVG File', 'tedx-regensburg')),
				attrs.svgUrl ? el('div', {
					style: {
						maxWidth: '100%',
						height: '120px',
						marginBottom: '10px',
						background: '#1a1a1a',
						borderRadius: '8px',
						border: '1px solid #333',
						display: 'flex',
						alignItems: 'center',
						justifyContent: 'center',
						overflow: 'hidden',
						padding: '8px'
					}
				}, el('img', {
					src: attrs.svgUrl,
					alt: __('SVG Floorplan preview', 'tedx-regensburg'),
					style: { maxHeight: '100%', maxWidth: '100%', objectFit: 'contain' }
				})) : null,
				el(
					be.MediaUploadCheck,
					null,
					el(be.MediaUpload, {
						allowedTypes: ['image', 'image/svg+xml'],
						value: attrs.svgId,
						onSelect: function (media) {
							props.setAttributes({
								svgId: media.id,
								svgUrl: media.url
							});
						},
						render: function (obj) {
							return el(c.Button, {
								variant: 'secondary',
								onClick: obj.open
							}, attrs.svgUrl ? __('Replace SVG File', 'tedx-regensburg') : __('Upload or Select SVG', 'tedx-regensburg'));
						}
					})
				),
				attrs.svgUrl ? el(c.Button, {
					variant: 'link',
					isDestructive: true,
					style: { marginLeft: '8px' },
					onClick: function () {
						props.setAttributes({ svgId: 0, svgUrl: '' });
					}
				}, __('Remove SVG', 'tedx-regensburg')) : null,
				el('p', {
					className: 'description',
					style: { fontSize: '12px', color: '#666', marginTop: '8px' }
				}, __('Upload a clean .svg file containing your venue floorplan or seating layout.', 'tedx-regensburg'))
			),
			H.text(props, 'svgUrl', __('External or Direct SVG URL', 'tedx-regensburg'), __('Optional fallback or direct SVG file path.', 'tedx-regensburg'))
		]);

		// Panel 3: Zoom & Interaction Settings
		var zoomPanel = H.panel(__('Zoom & Controls Settings', 'tedx-regensburg'), [
			H.toggle(props, 'showControls', __('Show Bottom-Right Controls Bar', 'tedx-regensburg'), __('Displays floating zoom in/out, reset, and fullscreen buttons.', 'tedx-regensburg')),
			H.toggle(props, 'allowFullscreen', __('Allow Fullscreen Mode', 'tedx-regensburg'), __('Enables fullscreen expand button in controls bar.', 'tedx-regensburg')),
			el(c.RangeControl, {
				key: 'minZoom',
				label: __('Minimum Zoom', 'tedx-regensburg'),
				value: attrs.minZoom || 1,
				onChange: setAttr('minZoom'),
				min: 0.5,
				max: 2,
				step: 0.1
			}),
			el(c.RangeControl, {
				key: 'maxZoom',
				label: __('Maximum Zoom', 'tedx-regensburg'),
				value: attrs.maxZoom || 5,
				onChange: setAttr('maxZoom'),
				min: 2,
				max: 10,
				step: 0.5
			})
		]);

		return [headerPanel, svgPanel, zoomPanel];
	});
})();
