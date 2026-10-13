(function () {
	var H = window.tedxBlocks;
	var el = H.el;

	H.registerServerBlock('tedx/speakers', function (props) {
		var a = props.attributes;
		var yearOpts = H.yearOptions(H.data.speakerYears);
		// Make sure the currently saved year is always selectable.
		if (a.year && H.data.speakerYears.indexOf(a.year) === -1) {
			yearOpts.unshift({ value: a.year, label: a.year });
		}

		return [
			H.panel('Year', [
				H.select(props, 'yearSource', 'Year source', [
					{ value: 'fixed', label: 'Fixed year (choose below)' },
					{ value: 'event', label: 'Year of the current event page' }
				], 'On an event page, "current event" uses that event\'s Event Year.'),
				a.yearSource === 'fixed' || true
					? (yearOpts.length
						? H.select(props, 'year', a.yearSource === 'event' ? 'Fallback year' : 'Year', yearOpts)
						: H.text(props, 'year', 'Year'))
					: null,
				H.toggle(props, 'showYear', 'Show year under the title')
			]),
			H.panel('Heading & event button', [
				H.text(props, 'title', 'Section title'),
				H.toggle(props, 'showEventButton', 'Show event button'),
				a.showEventButton ? H.text(props, 'eventButtonText', 'Button text') : null,
				a.showEventButton ? H.text(props, 'eventButtonUrl', 'Button URL') : null
			]),
			H.panel('Call for speakers card', [
				H.toggle(props, 'showCfs', 'Show "Your Name Here?" card'),
				a.showCfs ? H.text(props, 'cfsTitle', 'Card title') : null,
				a.showCfs ? H.textarea(props, 'cfsDescription', 'Card description', 3) : null,
				a.showCfs ? H.text(props, 'cfsButtonText', 'Button text') : null,
				a.showCfs ? H.text(props, 'cfsUrl', 'Button URL') : null
			], false)
		];
	});
})();
