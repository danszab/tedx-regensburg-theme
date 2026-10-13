(function () {
	var H = window.tedxBlocks;
	var el = H.el;
	var be = wp.blockEditor;
	var c = wp.components;

	H.registerServerBlock('tedx/sponsors', function (props) {
		var a = props.attributes;
		var sponsors = a.sponsors || [];

		function updateSponsor(index, key, val) {
			var newSponsors = sponsors.slice();
			newSponsors[index] = Object.assign({}, newSponsors[index], { [key]: val });
			props.setAttributes({ sponsors: newSponsors });
		}

		function removeSponsor(index) {
			var newSponsors = sponsors.slice();
			newSponsors.splice(index, 1);
			props.setAttributes({ sponsors: newSponsors });
		}

		function onSelectLogos(media) {
			var newItems = sponsors.slice();
			media.forEach(function (m) {
				newItems.push({
					id: m.id,
					url: m.url,
					name: m.title || m.caption || '',
					link: ''
				});
			});
			props.setAttributes({ sponsors: newItems });
		}

		var sponsorCards = sponsors.map(function (s, idx) {
			return el(
				c.Card,
				{ key: idx, style: { marginBottom: '10px', padding: '10px', border: '1px solid #ddd' } },
				el(c.CardHeader, null,
					el('div', { style: { display: 'flex', alignItems: 'center', gap: '8px' } },
						s.url ? el('img', { src: s.url, style: { width: '40px', height: '24px', objectFit: 'contain', background: '#333' } }) : null,
						el('strong', null, s.name || ('Logo #' + (idx + 1)))
					)
				),
				el(c.CardBody, null,
					el(c.TextControl, {
						label: 'Company Name / Alt',
						value: s.name || '',
						onChange: function (val) { updateSponsor(idx, 'name', val); }
					}),
					el(c.TextControl, {
						label: 'Website Link',
						value: s.link || '',
						placeholder: 'https://...',
						onChange: function (val) { updateSponsor(idx, 'link', val); }
					}),
					el(c.Button, {
						isDestructive: true,
						variant: 'link',
						onClick: function () { removeSponsor(idx); }
					}, 'Remove Logo')
				)
			);
		});

		return [
			H.panel('Layout & Headings', [
				H.select(props, 'layout', 'Layout Style', [
					{ value: 'grid', label: 'Logo Grid (Other Sponsors)' },
					{ value: 'featured', label: 'Featured Partner (Single Large Partner)' }
				]),
				H.text(props, 'eyebrow', 'Eyebrow text'),
				H.text(props, 'title', 'Title'),
				a.layout === 'grid' ? H.textarea(props, 'description', 'Description', 3) : null,
				H.text(props, 'sectionId', 'Anchor ID', 'e.g. partner or sponsors')
			]),
			H.panel('Sponsor Logos (' + sponsors.length + ')', [
				el(
					be.MediaUploadCheck,
					null,
					el(be.MediaUpload, {
						allowedTypes: ['image'],
						multiple: true,
						onSelect: onSelectLogos,
						render: function (obj) {
							return el(c.Button, {
								variant: 'primary',
								style: { marginBottom: '12px', width: '100%' },
								onClick: obj.open
							}, '+ Add Sponsor Logos');
						}
					})
				),
				sponsorCards
			])
		];
	});
})();
