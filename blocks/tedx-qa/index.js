(function () {
	var H = window.tedxBlocks;
	var el = H.el;
	var c = wp.components;
	var __ = wp.i18n.__;

	H.registerServerBlock('tedx/qa', function (props) {
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
				question: __('New Question', 'tedx-regensburg'),
				answer: __('Enter the answer here.', 'tedx-regensburg')
			}]);
			props.setAttributes({ items: newItems });
		}

		var itemControls = items.map(function (item, idx) {
			return el(
				c.Card,
				{ key: idx, style: { marginBottom: '14px', border: '1px solid #e0e0e0' } },
				el(c.CardHeader, null,
					el('strong', null, (idx + 1) + '. ' + (item.question || __('Question', 'tedx-regensburg'))),
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
					el(c.TextControl, {
						label: __('Question', 'tedx-regensburg'),
						value: item.question || '',
						onChange: function (val) { updateItem(idx, 'question', val); }
					}),
					el(c.TextareaControl, {
						label: __('Answer', 'tedx-regensburg'),
						rows: 4,
						value: item.answer || '',
						onChange: function (val) { updateItem(idx, 'answer', val); }
					}),
					el(c.Button, {
						isDestructive: true,
						variant: 'link',
						onClick: function () { removeItem(idx); }
					}, __('Remove Question', 'tedx-regensburg'))
				)
			);
		});

		return [
			H.panel(__('Heading & Eyebrow', 'tedx-regensburg'), [
				H.text(props, 'title', __('Heading', 'tedx-regensburg')),
				H.text(props, 'eyebrow', __('Eyebrow', 'tedx-regensburg'))
			]),
			H.panel(__('Questions & Answers', 'tedx-regensburg'), [
				itemControls,
				el(c.Button, {
					variant: 'primary',
					style: { width: '100%', marginTop: '6px' },
					onClick: addItem
				}, __('+ Add Question', 'tedx-regensburg'))
			]),
			H.panel(__('Accordion Behavior', 'tedx-regensburg'), [
				H.select(props, 'type', __('Selection Mode', 'tedx-regensburg'), [
					{ value: 'single', label: __('Single (one open at a time)', 'tedx-regensburg') },
					{ value: 'multiple', label: __('Multiple (multiple can be open)', 'tedx-regensburg') }
				]),
				a.type === 'single' ? H.toggle(props, 'collapsible', __('Allow collapsing all items', 'tedx-regensburg')) : null,
				H.text(props, 'sectionId', __('Anchor ID', 'tedx-regensburg'))
			], false)
		];
	});
})();
