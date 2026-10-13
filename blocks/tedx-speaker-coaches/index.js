(function () {
	var H = window.tedxBlocks;

	H.registerServerBlock('tedx/speaker-coaches', function (props) {
		var a = props.attributes;
		var yearOpts = H.yearOptions(H.data.coachYears, 'All years');
		if (a.year && H.data.coachYears.indexOf(a.year) === -1) {
			yearOpts.push({ value: a.year, label: a.year });
		}

		return [
			H.panel('Coaches', [
				H.text(props, 'title', 'Section title'),
				H.select(props, 'year', 'Year', yearOpts, 'Years come from the "Year" field of each speaker coach. "All years" shows every coach.'),
				H.toggle(props, 'showMore', 'Trim bios and show "Show More" link')
			])
		];
	});
})();
