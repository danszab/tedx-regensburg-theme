(function (wp) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;

	var ALLOWED = ['core/heading', 'core/paragraph', 'core/list', 'core/buttons', 'core/quote'];
	var TEMPLATE = [
		['core/heading', { level: 1, content: 'About TED & TEDxRegensburg' }],
		['core/heading', { level: 2, content: 'About TED' }],
		['core/paragraph', { placeholder: 'About TED text...' }],
		['core/heading', { level: 2, content: 'About TEDx' }],
		['core/paragraph', { placeholder: 'About TEDx text...' }],
		['core/heading', { level: 2, content: 'About TEDxRegensburg' }],
		['core/paragraph', { placeholder: 'About TEDxRegensburg text...' }]
	];

	wp.blocks.registerBlockType('tedx/text-section', {
		edit: function (props) {
			var blockProps = be.useBlockProps({
				className: 'tedx-text-section-wrapper py-12 px-4 md:px-8 bg-tedx-dark text-white'
			});

			return el(
				'section',
				blockProps,
				el(
					be.InspectorControls,
					null,
					el(
						c.PanelBody,
						{ title: 'Settings' },
						el(c.TextControl, {
							label: 'Anchor ID',
							value: props.attributes.sectionId || '',
							onChange: function (val) { props.setAttributes({ sectionId: val }); }
						})
					)
				),
				el(
					'div',
					{ className: 'max-w-figma mx-auto w-full tedx-prose' },
					el(be.InnerBlocks, {
						allowedBlocks: ALLOWED,
						template: TEMPLATE,
						templateLock: false
					})
				)
			);
		},
		save: function () {
			return el(be.InnerBlocks.Content);
		}
	});
})(window.wp);
