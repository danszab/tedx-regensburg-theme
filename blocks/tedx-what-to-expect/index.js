(function () {
	var H = window.tedxBlocks;
	var el = H.el;
	var c = wp.components;
	var be = wp.blockEditor;
	var __ = wp.i18n.__;

	H.registerServerBlock('tedx/what-to-expect', function (props) {
		var cards = props.attributes.cards || [];
		var iconOptions = (H.data.icons || []).concat([{ value: 'custom', label: __('Custom SVG / Image', 'tedx-regensburg') }]);

		function updateCard(index, key, val) {
			var newCards = cards.slice();
			var updated = Object.assign({}, newCards[index]);
			updated[key] = val;
			newCards[index] = updated;
			props.setAttributes({ cards: newCards });
		}

		function removeCard(index) {
			var newCards = cards.slice();
			newCards.splice(index, 1);
			props.setAttributes({ cards: newCards });
		}

		function moveCard(index, direction) {
			var target = index + direction;
			if (target < 0 || target >= cards.length) {
				return;
			}
			var newCards = cards.slice();
			var temp = newCards[index];
			newCards[index] = newCards[target];
			newCards[target] = temp;
			props.setAttributes({ cards: newCards });
		}

		function addCard() {
			var newCards = cards.concat([{
				title: __('New Benefit', 'tedx-regensburg'),
				icon: 'globe',
				description: __('Benefit description goes here.', 'tedx-regensburg')
			}]);
			props.setAttributes({ cards: newCards });
		}

		var cardControls = cards.map(function (card, idx) {
			return el(
				c.Card,
				{ key: idx, style: { marginBottom: '14px', border: '1px solid #e0e0e0' } },
				el(c.CardHeader, null,
					el('strong', null, (idx + 1) + '. ' + (card.title || __('Card', 'tedx-regensburg'))),
					el('div', { style: { display: 'flex', gap: '4px' } },
						el(c.Button, {
							size: 'small',
							variant: 'tertiary',
							disabled: idx === 0,
							onClick: function () { moveCard(idx, -1); }
						}, '↑'),
						el(c.Button, {
							size: 'small',
							variant: 'tertiary',
							disabled: idx === cards.length - 1,
							onClick: function () { moveCard(idx, 1); }
						}, '↓')
					)
				),
				el(c.CardBody, null,
					el(c.TextControl, {
						label: __('Card Title', 'tedx-regensburg'),
						value: card.title || '',
						onChange: function (val) { updateCard(idx, 'title', val); }
					}),
					el(c.SelectControl, {
						label: __('Icon', 'tedx-regensburg'),
						value: card.icon || 'globe',
						options: iconOptions,
						onChange: function (val) { updateCard(idx, 'icon', val); }
					}),
					card.icon === 'custom' ? el(
						'div',
						{ style: { marginBottom: '12px' } },
						el(
							be.MediaUploadCheck,
							null,
							el(be.MediaUpload, {
								allowedTypes: ['image'],
								value: card.customIconId || 0,
								onSelect: function (media) {
									updateCard(idx, 'customIconId', media.id);
									updateCard(idx, 'customIconUrl', media.url);
								},
								render: function (picker) {
									return el(c.Button, {
										variant: 'secondary',
										onClick: picker.open
									}, card.customIconId ? __('Replace Icon (SVG/Image)', 'tedx-regensburg') : __('Upload or Select Icon', 'tedx-regensburg'));
								}
							})
						),
						card.customIconId ? el(c.Button, {
							variant: 'link',
							isDestructive: true,
							onClick: function () {
								updateCard(idx, 'customIconId', 0);
								updateCard(idx, 'customIconUrl', '');
							}
						}, __('Remove Custom Icon', 'tedx-regensburg')) : null
					) : null,
					el(c.TextareaControl, {
						label: __('Description', 'tedx-regensburg'),
						rows: 3,
						value: card.description || '',
						onChange: function (val) { updateCard(idx, 'description', val); }
					}),
					el(c.Button, {
						isDestructive: true,
						variant: 'link',
						onClick: function () { removeCard(idx); }
					}, __('Remove Card', 'tedx-regensburg'))
				)
			);
		});

		return [
			H.panel(__('Heading & Eyebrow', 'tedx-regensburg'), [
				H.text(props, 'title', __('Heading', 'tedx-regensburg')),
				H.text(props, 'eyebrow', __('Eyebrow (Optional)', 'tedx-regensburg'))
			]),
			H.panel(__('Cards', 'tedx-regensburg'), [
				cardControls,
				el(c.Button, {
					variant: 'primary',
					style: { width: '100%', marginTop: '6px' },
					onClick: addCard
				}, __('+ Add Card', 'tedx-regensburg'))
			]),
			H.panel(__('Settings', 'tedx-regensburg'), [
				H.text(props, 'sectionId', __('Anchor ID', 'tedx-regensburg'))
			], false)
		];
	});
})();
