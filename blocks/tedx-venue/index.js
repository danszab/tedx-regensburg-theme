(function () {
	var H = window.tedxBlocks;

	H.registerServerBlock('tedx/venue', function (props) {
		var a = props.attributes;

		return [
			H.panel('Content', [
				H.text(props, 'title', 'Section title'),
				H.text(props, 'venueName', 'Venue name'),
				H.text(props, 'addressLine1', 'Address line 1'),
				H.text(props, 'addressLine2', 'Address line 2')
			]),
			H.panel('Map & Directions', [
				H.text(props, 'mapsUrl', 'Google Maps link (optional)', 'Leave empty to auto-generate a Google Maps search link from the address.'),
				H.textarea(props, 'mapsEmbed', 'Custom Google Maps Embed HTML / iframe (optional)', 4)
			]),
			H.panel('Button', [
				H.toggle(props, 'showButton', 'Show directions button'),
				a.showButton ? H.text(props, 'buttonText', 'Button text') : null
			]),
			H.panel('Settings', [
				H.toggle(props, 'syncWithEvent', 'Sync with Event details', 'When placed on an Event page, automatically inherit venue meta if defined on the event.'),
				H.text(props, 'sectionId', 'Anchor ID')
			], false)
		];
	});
})();
