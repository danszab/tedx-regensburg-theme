(function (wp) {
	var H = window.tedxBlocks;
	var __ = wp.i18n.__;

	function range(props, key, label, min, max) {
		return H.el(wp.components.RangeControl, {
			key: key,
			label: label,
			value: props.attributes[key],
			min: min,
			max: max,
			onChange: function (value) {
				var attributes = {};
				attributes[key] = value;
				props.setAttributes(attributes);
			}
		});
	}

	H.registerServerBlock('tedx/press-releases', function (props) {
		var categories = [{ value: 0, label: __('Select a category', 'tedx-regensburg') }].concat(H.data.postCategories || []);
		return [
			H.panel(__('Press Release Settings', 'tedx-regensburg'), [
				H.text(props, 'title', __('Section title', 'tedx-regensburg')),
				H.el(wp.components.SelectControl, {
					label: __('Post category', 'tedx-regensburg'),
					value: props.attributes.categoryId,
					options: categories,
					onChange: function (value) { props.setAttributes({ categoryId: Number(value) }); }
				}),
				range(props, 'postsToShow', __('Number of posts', 'tedx-regensburg'), 1, 24),
				range(props, 'excerptLength', __('Excerpt length (words)', 'tedx-regensburg'), 10, 80),
				H.text(props, 'showMoreText', __('Show more label', 'tedx-regensburg')),
				H.text(props, 'downloadText', __('PDF download label', 'tedx-regensburg'))
			])
		];
	});
})(window.wp);
