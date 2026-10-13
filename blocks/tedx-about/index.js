(function () {
	var H = window.tedxBlocks;

	H.registerServerBlock('tedx/about', function (props) {
		var a = props.attributes;

		return [
			H.panel('Content', [
				H.image(props, 'image', 'Image'),
				H.text(props, 'eyebrow', 'Heading'),
				H.toggle(props, 'showLogo', 'Show TEDxRegensburg logo'),
				H.textarea(props, 'description', 'Description', 7)
			]),
			H.panel('Button', [
				H.toggle(props, 'showButton', 'Show button'),
				a.showButton ? H.text(props, 'buttonText', 'Button text') : null,
				a.showButton ? H.text(props, 'buttonUrl', 'Button URL') : null
			]),
			H.panel('Layout', [
				H.select(props, 'imagePosition', 'Image position', [
					{ value: 'left', label: 'Left' },
					{ value: 'right', label: 'Right' }
				]),
				H.text(props, 'sectionId', 'Anchor ID')
			], false)
		];
	});
})();
