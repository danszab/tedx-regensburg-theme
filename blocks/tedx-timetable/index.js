(function(wp) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;
    var Button = wp.components.Button;

    registerBlockType('tedx/timetable', {
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var title = attributes.title || 'Timetable';
            var eyebrow = attributes.eyebrow || '';
            var showLine = attributes.showLine !== false;
            var items = attributes.items || [];

            // Helper to update a field of a specific item
            var updateItem = function(index, field, value) {
                var newItems = items.map(function(item, idx) {
                    if (idx === index) {
                        var updated = Object.assign({}, item);
                        updated[field] = value;
                        return updated;
                    }
                    return item;
                });
                setAttributes({ items: newItems });
            };

            // Toggle highlight state (green dot vs white dot)
            var toggleHighlight = function(index) {
                var newItems = items.map(function(item, idx) {
                    if (idx === index) {
                        return Object.assign({}, item, { isHighlight: !item.isHighlight });
                    }
                    return item;
                });
                setAttributes({ items: newItems });
            };

            // Add new item to the timetable
            var addItem = function() {
                var lastItem = items.length > 0 ? items[items.length - 1] : { time: '15:00' };
                var newItem = {
                    time: lastItem.time || '15:00',
                    title: 'New Session',
                    isHighlight: false
                };
                setAttributes({ items: items.concat([newItem]) });
            };

            // Remove item
            var removeItem = function(index) {
                var newItems = items.filter(function(_, idx) {
                    return idx !== index;
                });
                setAttributes({ items: newItems });
            };

            // Reorder item up or down
            var moveItem = function(index, direction) {
                var targetIdx = index + direction;
                if (targetIdx < 0 || targetIdx >= items.length) return;
                var newItems = items.slice();
                var temp = newItems[index];
                newItems[index] = newItems[targetIdx];
                newItems[targetIdx] = temp;
                setAttributes({ items: newItems });
            };

            var blockProps = useBlockProps ? useBlockProps({ className: 'tedx-timetable-block tedx-timetable-editor' }) : { className: 'tedx-timetable-block tedx-timetable-editor' };

            // Render Rows
            var rowElements = items.map(function(item, index) {
                var isHighlight = !!item.isHighlight;
                var rowClass = 'tedx-timetable-row ' + (isHighlight ? 'is-highlight' : 'is-standard');

                return el(
                    'div',
                    { key: index, className: rowClass },
                    // Dot (clickable to toggle highlight)
                    el('div', {
                        className: 'tedx-timetable-dot ' + (isHighlight ? 'is-highlight' : 'is-standard'),
                        title: 'Click to toggle Green/White highlight',
                        style: { cursor: 'pointer' },
                        onClick: function() { toggleHighlight(index); }
                    }),
                    // Time input
                    el('div', { className: 'tedx-timetable-time', style: { width: '100px' } },
                        el('input', {
                            type: 'text',
                            value: item.time || '',
                            placeholder: '15:00',
                            onChange: function(e) { updateItem(index, 'time', e.target.value); }
                        })
                    ),
                    // Title input
                    el('div', { className: 'tedx-timetable-text' },
                        el('input', {
                            type: 'text',
                            value: item.title || '',
                            placeholder: 'Session Title (e.g. Start, Break, Keynote...)',
                            onChange: function(e) { updateItem(index, 'title', e.target.value); }
                        })
                    ),
                    // Actions (Highlight toggle, move up/down, delete)
                    el('div', { className: 'item-actions' },
                        el('button', {
                            type: 'button',
                            className: 'action-btn',
                            title: isHighlight ? 'Set as standard (white)' : 'Set as highlight (green)',
                            onClick: function() { toggleHighlight(index); }
                        }, isHighlight ? '★ Green' : '☆ White'),
                        index > 0 ? el('button', {
                            type: 'button',
                            className: 'action-btn',
                            title: 'Move Up',
                            onClick: function() { moveItem(index, -1); }
                        }, '↑') : null,
                        index < items.length - 1 ? el('button', {
                            type: 'button',
                            className: 'action-btn',
                            title: 'Move Down',
                            onClick: function() { moveItem(index, 1); }
                        }, '↓') : null,
                        el('button', {
                            type: 'button',
                            className: 'action-btn delete-btn',
                            title: 'Delete Item',
                            onClick: function() { removeItem(index); }
                        }, '✕')
                    )
                );
            });

            return el(
                'div',
                blockProps,
                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Timetable Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Section Eyebrow (Optional)',
                            value: eyebrow,
                            placeholder: 'e.g. SCHEDULE or PROGRAM',
                            help: 'Category header text in TEDx green above the title.',
                            onChange: function(val) { setAttributes({ eyebrow: val }); }
                        }),
                        el(TextControl, {
                            label: 'Section Title',
                            value: title,
                            placeholder: 'Timetable',
                            onChange: function(val) { setAttributes({ title: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Show Connecting Line',
                            checked: showLine,
                            help: 'Shows the vertical line connecting the timetable dots.',
                            onChange: function(val) { setAttributes({ showLine: val }); }
                        }),
                        el(Button, {
                            variant: 'secondary',
                            style: { marginTop: '10px', width: '100%', justifyContent: 'center' },
                            onClick: addItem
                        }, '+ Add New Row')
                    )
                ),
                // Main Editor Canvas
                el(
                    'div',
                    { className: 'tedx-timetable-container' },
                    // Eyebrow label if set
                    eyebrow ? el('p', {
                        className: 'text-[13px] font-medium uppercase tracking-[0.65px] text-tedx-green m-0 mb-3',
                        style: { color: '#2ad17e', textTransform: 'uppercase', letterSpacing: '0.65px', fontSize: '13px', margin: '0 0 8px 0' }
                    }, eyebrow) : null,
                    // Title input
                    el('input', {
                        type: 'text',
                        className: 'tedx-timetable-title',
                        value: title,
                        placeholder: 'Timetable',
                        style: { fontSize: '48px', fontWeight: '400', letterSpacing: '-2px', color: '#fff', margin: '0 0 24px 0', border: 'none', background: 'transparent', width: '100%' },
                        onChange: function(e) { setAttributes({ title: e.target.value }); }
                    }),
                    // Timetable List
                    el(
                        'div',
                        { className: 'tedx-timetable-list' },
                        showLine ? el('div', { className: 'tedx-timetable-line' }) : null,
                        rowElements
                    ),
                    // Add Row Button
                    el('button', {
                        type: 'button',
                        className: 'add-item-btn',
                        onClick: addItem
                    }, '+ Add Timetable Item')
                )
            );
        },
        save: function() {
            // Rendered dynamically on the server via render.php
            return null;
        }
    });
})(window.wp);
