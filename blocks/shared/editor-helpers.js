/**
 * Shared helpers for TEDx block editor scripts (plain ES5, no build step).
 * Exposes window.tedxBlocks.
 */
(function (wp) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	var data = window.tedxBlockData || { speakerYears: [], coachYears: [], icons: [], events: [] };

	var H = { el: el, data: data };

	function setter(props, key) {
		return function (value) {
			var o = {};
			o[key] = value;
			props.setAttributes(o);
		};
	}

	H.panel = function (title, children, open) {
		return el(c.PanelBody, { title: title, initialOpen: open !== false }, children);
	};

	H.text = function (props, key, label, help) {
		return el(c.TextControl, {
			key: key,
			label: label,
			help: help,
			value: props.attributes[key] || '',
			onChange: setter(props, key)
		});
	};

	H.textarea = function (props, key, label, rows) {
		return el(c.TextareaControl, {
			key: key,
			label: label,
			rows: rows || 5,
			value: props.attributes[key] || '',
			onChange: setter(props, key)
		});
	};

	H.toggle = function (props, key, label, help) {
		return el(c.ToggleControl, {
			key: key,
			label: label,
			help: help,
			checked: !!props.attributes[key],
			onChange: setter(props, key)
		});
	};

	H.select = function (props, key, label, options, help) {
		return el(c.SelectControl, {
			key: key,
			label: label,
			help: help,
			value: props.attributes[key],
			options: options,
			onChange: setter(props, key)
		});
	};

	/** Year dropdown built from existing years; allows an optional empty "all years" choice. */
	H.yearOptions = function (years, allLabel) {
		var opts = [];
		if (allLabel) {
			opts.push({ value: '', label: allLabel });
		}
		years.forEach(function (y) {
			opts.push({ value: y, label: y });
		});
		return opts;
	};

	/**
	 * Generic image picker working on an object value {id,url,alt}.
	 * onChange receives the new object (or null when removed).
	 */
	H.imagePicker = function (value, label, onChange) {
		value = value || {};
		return el(
			'div',
			{ className: 'tedx-image-picker', style: { marginBottom: '16px' } },
			el('p', { style: { margin: '0 0 6px', fontWeight: 600 } }, label),
			value.url ? el('img', { src: value.url, alt: '', style: { maxWidth: '100%', maxHeight: '110px', display: 'block', marginBottom: '8px', background: '#222' } }) : null,
			el(
				be.MediaUploadCheck,
				null,
				el(be.MediaUpload, {
					allowedTypes: ['image'],
					value: value.id,
					onSelect: function (m) {
						onChange({ id: m.id, url: m.url, alt: m.alt || '' });
					},
					render: function (o) {
						return el(c.Button, { variant: 'secondary', onClick: o.open }, value.url ? 'Replace image' : 'Select image');
					}
				})
			),
			value.url
				? el(c.Button, { variant: 'link', isDestructive: true, style: { marginLeft: '8px' }, onClick: function () { onChange(null); } }, 'Remove')
				: null
		);
	};

	H.image = function (props, key, label) {
		return H.imagePicker(props.attributes[key], label, function (v) {
			setter(props, key)(v || {});
		});
	};

	/** Server-side rendered preview. */
	H.preview = function (block, attributes) {
		return el(wp.serverSideRender, { block: block, attributes: attributes });
	};

	/**
	 * Standard edit(): sidebar panels + preview inside a block wrapper.
	 * Must be called from inside a block's edit function (uses hooks).
	 */
	H.edit = function (panels, preview) {
		var blockProps = be.useBlockProps({ className: 'tedx-editor-block' });
		return el('div', blockProps, el(be.InspectorControls, null, panels), preview);
	};

	/** Register a block whose front end is rendered by PHP and whose editor preview is server-side. */
	H.registerServerBlock = function (name, panelsFn) {
		wp.blocks.registerBlockType(name, {
			edit: function (props) {
				return H.edit(panelsFn(props), H.preview(name, props.attributes));
			},
			save: function () {
				return null;
			}
		});
	};

	window.tedxBlocks = H;
})(window.wp);
