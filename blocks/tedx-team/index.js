(function () {
	var H = window.tedxBlocks;

	H.registerServerBlock('tedx/team', function (props) {
		return [
			H.panel('Team Settings', [
				H.text(props, 'title', 'Section title'),
				H.text(props, 'sectionId', 'Anchor ID')
			])
		];
	});
})();
