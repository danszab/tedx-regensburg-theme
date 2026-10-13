(function () {
	var H = window.tedxBlocks;

	H.registerServerBlock('tedx/event', function (props) {
		var a = props.attributes;

		return [
			H.panel('Content', [
				H.text(props, 'eyebrow', 'Eyebrow (date / location)'),
				H.text(props, 'title', 'Event title'),
				H.textarea(props, 'description', 'Description', 6),
				H.select(props, 'linkedEventId', 'Linked event (optional)', H.data.events.map(function (e) {
					return { value: e.value, label: e.label };
				}), 'Used as default link for the card and the "Show More" button when those URLs are empty.')
			]),
			H.panel('Buttons', [
				H.toggle(props, 'showTickets', 'Show tickets button'),
				a.showTickets ? H.text(props, 'ticketText', 'Tickets button text') : null,
				a.showTickets ? H.text(props, 'ticketUrl', 'Tickets URL') : null,
				H.toggle(props, 'showMore', 'Show "Show More" button', 'Turn off both buttons to hide the button row (e.g. past events).'),
				a.showMore ? H.text(props, 'showMoreText', '"Show More" text') : null,
				a.showMore ? H.text(props, 'showMoreUrl', '"Show More" URL', 'Empty = linked event page.') : null
			]),
			H.panel('Visual card', [
				H.image(props, 'cardImage', 'Card image'),
				H.toggle(props, 'showCardLabel', 'Show label badge'),
				a.showCardLabel ? H.text(props, 'cardLabel', 'Label text') : null,
				H.toggle(props, 'cardClickable', 'Make card clickable'),
				a.cardClickable ? H.text(props, 'cardUrl', 'Card URL', 'Empty = linked event page.') : null
			], false),
			H.panel('Layout', [
				H.toggle(props, 'reverseColumns', 'Show card on the left'),
				H.text(props, 'sectionId', 'Anchor ID', 'Target for "#" links, e.g. the hero button (#event-pitch).')
			], false)
		];
	});
})();
