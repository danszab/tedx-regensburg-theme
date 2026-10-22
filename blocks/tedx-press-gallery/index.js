(function (wp) {
	var H = window.tedxBlocks;
	var el = H.el;
	var c = wp.components;
	var be = wp.blockEditor;
	var __ = wp.i18n.__;

	H.registerServerBlock('tedx/press-gallery', function (props) {
		var a = props.attributes;
		var items = a.items || [];

		function updateItem(index, key, val) {
			var newItems = items.slice();
			var updated = Object.assign({}, newItems[index]);
			updated[key] = val;
			newItems[index] = updated;
			props.setAttributes({ items: newItems });
		}

		function removeItem(index) {
			var newItems = items.slice();
			newItems.splice(index, 1);
			props.setAttributes({ items: newItems });
		}

		function moveItem(index, direction) {
			var target = index + direction;
			if (target < 0 || target >= items.length) {
				return;
			}
			var newItems = items.slice();
			var temp = newItems[index];
			newItems[index] = newItems[target];
			newItems[target] = temp;
			props.setAttributes({ items: newItems });
		}

		function addItem() {
			var newItems = items.concat([{
				image: null,
				description: ''
			}]);
			props.setAttributes({ items: newItems });
		}

		function addMultipleImages(mediaList) {
			var newEntries = mediaList.map(function (m) {
				return {
					image: { id: m.id, url: m.url, alt: m.alt || '' },
					description: m.caption || m.description || ''
				};
			});
			props.setAttributes({ items: items.concat(newEntries) });
		}

		var itemControls = items.map(function (item, idx) {
			var img = item.image || {};
			return el(
				c.Card,
				{ key: idx, style: { marginBottom: '16px', border: '1px solid #e0e0e0' } },
				el(c.CardHeader, null,
					el('strong', null, (idx + 1) + '. ' + (img.alt || img.url ? __('Image #' + (idx + 1), 'tedx-regensburg') : __('New Press Item', 'tedx-regensburg'))),
					el('div', { style: { display: 'flex', gap: '4px' } },
						el(c.Button, {
							size: 'small',
							variant: 'tertiary',
							disabled: idx === 0,
							onClick: function () { moveItem(idx, -1); }
						}, '↑'),
						el(c.Button, {
							size: 'small',
							variant: 'tertiary',
							disabled: idx === items.length - 1,
							onClick: function () { moveItem(idx, 1); }
						}, '↓')
					)
				),
				el(c.CardBody, null,
					H.imagePicker(img, __('Press Image', 'tedx-regensburg'), function (newImg) {
						updateItem(idx, 'image', newImg);
					}),
					el(c.TextareaControl, {
						label: __('Multi-line Description', 'tedx-regensburg'),
						rows: 4,
						help: __('Caption, photo credits, or press usage guidelines.', 'tedx-regensburg'),
						value: item.description || '',
						onChange: function (val) { updateItem(idx, 'description', val); }
					}),
					el(c.Button, {
						isDestructive: true,
						variant: 'link',
						onClick: function () { removeItem(idx); }
					}, __('Remove Image', 'tedx-regensburg'))
				)
			);
		});

		return [
			H.panel(__('Heading & Settings', 'tedx-regensburg'), [
				H.text(props, 'title', __('Heading', 'tedx-regensburg')),
				H.text(props, 'eyebrow', __('Eyebrow', 'tedx-regensburg')),
				H.text(props, 'downloadText', __('Download Button Label', 'tedx-regensburg')),
				H.text(props, 'sectionId', __('Anchor ID', 'tedx-regensburg'))
			]),
			H.panel(__('Press Images', 'tedx-regensburg'), [
				itemControls,
				el('div', { style: { display: 'flex', gap: '8px', marginTop: '12px' } },
					el(c.Button, {
						variant: 'primary',
						style: { flex: 1 },
						onClick: addItem
					}, __('+ Add Image', 'tedx-regensburg')),
					el(
						be.MediaUploadCheck,
						null,
						el(be.MediaUpload, {
							allowedTypes: ['image'],
							multiple: true,
							gallery: true,
							onSelect: addMultipleImages,
							render: function (picker) {
								return el(c.Button, {
									variant: 'secondary',
									onClick: picker.open
								}, __('Bulk Add', 'tedx-regensburg'));
							}
						})
					)
				)
			])
		];
	});
})(window.wp);
