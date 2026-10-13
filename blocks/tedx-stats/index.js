(function () {
	var H = window.tedxBlocks;
	var el = H.el;
	var c = wp.components;

	H.registerServerBlock('tedx/stats', function (props) {
		var items = props.attributes.items || [];
		var iconOptions = H.data.icons || [];

		function updateItem(index, key, val) {
			var newItems = items.slice();
			newItems[index] = Object.assign({}, newItems[index], { [key]: val });
			props.setAttributes({ items: newItems });
		}

		function removeItem(index) {
			var newItems = items.slice();
			newItems.splice(index, 1);
			props.setAttributes({ items: newItems });
		}

		function addItem() {
			var newItems = items.concat([{ icon: 'groups', value: '100+', label: 'New Stat' }]);
			props.setAttributes({ items: newItems });
		}

		var itemControls = items.map(function (item, idx) {
			return el(
				c.Card,
				{ key: idx, style: { marginBottom: '12px', padding: '12px', border: '1px solid #ddd' } },
				el(c.CardHeader, null, el('strong', null, 'Stat #' + (idx + 1))),
				el(c.CardBody, null,
					el(c.SelectControl, {
						label: 'Icon',
						value: item.icon || 'groups',
						options: iconOptions,
						onChange: function (val) { updateItem(idx, 'icon', val); }
					}),
					el(c.TextControl, {
						label: 'Number / Value',
						value: item.value || '',
						onChange: function (val) { updateItem(idx, 'value', val); }
					}),
					el(c.TextControl, {
						label: 'Label',
						value: item.label || '',
						onChange: function (val) { updateItem(idx, 'label', val); }
					}),
					el(c.Button, {
						isDestructive: true,
						variant: 'link',
						onClick: function () { removeItem(idx); }
					}, 'Remove Stat')
				)
			);
		});

		return [
			H.panel('Stat Items', [
				itemControls,
				el(c.Button, {
					variant: 'primary',
					style: { marginTop: '8px', width: '100%' },
					onClick: addItem
				}, '+ Add Stat')
			]),
			H.panel('Settings', [
				H.text(props, 'sectionId', 'Anchor ID')
			], false)
		];
	});
})();
