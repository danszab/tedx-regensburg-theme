(function(wp) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var ToggleControl = wp.components.ToggleControl;
    var SelectControl = wp.components.SelectControl;
    var Button = wp.components.Button;

    registerBlockType('tedx/timetable-v2', {
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var title = attributes.title || 'Timetable';
            var eyebrow = attributes.eyebrow || 'SCHEDULE';
            var eventDate = attributes.eventDate || '';
            var showProgress = attributes.showProgress !== false;
            var testMode = !!attributes.testMode;
            var previewActiveIndex = typeof attributes.previewActiveIndex === 'number' ? attributes.previewActiveIndex : -1;
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

            // Add new session item
            var addItem = function() {
                var lastItem = items.length > 0 ? items[items.length - 1] : { endTime: '15:00' };
                var startTime = lastItem.endTime || '15:00';
                var newItem = {
                    startTime: startTime,
                    endTime: '15:45',
                    title: 'New Session',
                    description: 'Session overview and discussion details.',
                    hasButton: false,
                    buttonText: 'Details',
                    buttonUrl: '',
                    buttonNewTab: false,
                    buttonVariant: 'outline'
                };
                setAttributes({ items: items.concat([newItem]) });
            };

            // Remove item
            var removeItem = function(index) {
                var newItems = items.filter(function(_, idx) {
                    return idx !== index;
                });
                setAttributes({ items: newItems });
                if (previewActiveIndex === index) {
                    setAttributes({ previewActiveIndex: -1 });
                }
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

            // Toggle active preview for testing live appearance
            var toggleActivePreview = function(index) {
                var newIndex = previewActiveIndex === index ? -1 : index;
                setAttributes({ previewActiveIndex: newIndex });
            };

            var blockProps = useBlockProps ? useBlockProps({ className: 'tedx-timetable-v2-block tedx-timetable-v2-editor' }) : { className: 'tedx-timetable-v2-block tedx-timetable-v2-editor' };

            // Render Card Elements in Editor
            var cardElements = items.map(function(item, index) {
                var isPreviewActive = previewActiveIndex === index;
                var hasButton = !!item.hasButton;
                var btnText = item.buttonText || 'Details';
                var btnVariant = item.buttonVariant || 'outline';

                var cardClasses = 'editor-card-item' + (isPreviewActive ? ' is-preview-active' : '');

                return el(
                    'div',
                    { key: index, className: cardClasses },
                    // Main Single-row Card Flex
                    el(
                        'div',
                        { className: 'editor-card-main' },
                        // Time Range Inputs
                        el(
                            'div',
                            { className: 'editor-time-group', title: 'Start & End Time (24h format)' },
                            el('input', {
                                type: 'text',
                                className: 'editor-time-input',
                                value: item.startTime || '',
                                placeholder: '15:00',
                                onChange: function(e) { updateItem(index, 'startTime', e.target.value); }
                            }),
                            el('span', { style: { color: 'rgba(255,255,255,0.4)', fontWeight: 'bold' } }, '–'),
                            el('input', {
                                type: 'text',
                                className: 'editor-time-input',
                                value: item.endTime || '',
                                placeholder: '15:45',
                                onChange: function(e) { updateItem(index, 'endTime', e.target.value); }
                            })
                        ),
                        // Title & Description Inputs
                        el(
                            'div',
                            { className: 'editor-content-group' },
                            el('input', {
                                type: 'text',
                                className: 'editor-title-input',
                                value: item.title || '',
                                placeholder: 'Session Title (e.g. Keynote, Break, Workshop...)',
                                onChange: function(e) { updateItem(index, 'title', e.target.value); }
                            }),
                            el('input', {
                                type: 'text',
                                className: 'editor-desc-input',
                                value: item.description || '',
                                placeholder: 'Short session description or speaker name...',
                                onChange: function(e) { updateItem(index, 'description', e.target.value); }
                            })
                        ),
                        // Button Live Preview (if button enabled)
                        hasButton ? el(
                            'div',
                            { className: 'tedx-timetable-v2-action', style: { flexShrink: 0 } },
                            el('span', {
                                className: 'tedx-timetable-v2-btn variant-' + btnVariant,
                                title: 'Button preview (' + (item.buttonUrl || 'No URL set') + ')',
                                style: { pointerEvents: 'none' }
                            }, btnText)
                        ) : null,
                        // Card Action Controls
                        el(
                            'div',
                            { className: 'editor-actions-group' },
                            // Add button checkbox
                            el('label', {
                                className: 'action-checkbox' + (hasButton ? ' is-active' : ''),
                                title: 'Toggle action button for this item'
                            },
                                el('input', {
                                    type: 'checkbox',
                                    checked: hasButton,
                                    onChange: function(e) { updateItem(index, 'hasButton', e.target.checked); }
                                }),
                                el('span', null, 'Button')
                            ),
                            // Active preview toggle
                            el('button', {
                                type: 'button',
                                className: 'action-btn preview-active-btn' + (isPreviewActive ? ' is-active' : ''),
                                title: isPreviewActive ? 'Turn off active preview' : 'Preview this session as active (green border)',
                                onClick: function() { toggleActivePreview(index); }
                            }, isPreviewActive ? '● Active' : '○ Preview'),
                            // Move Up
                            index > 0 ? el('button', {
                                type: 'button',
                                className: 'action-btn',
                                title: 'Move Up',
                                onClick: function() { moveItem(index, -1); }
                            }, '↑') : null,
                            // Move Down
                            index < items.length - 1 ? el('button', {
                                type: 'button',
                                className: 'action-btn',
                                title: 'Move Down',
                                onClick: function() { moveItem(index, 1); }
                            }, '↓') : null,
                            // Delete
                            el('button', {
                                type: 'button',
                                className: 'action-btn delete-btn',
                                title: 'Delete Session',
                                onClick: function() { removeItem(index); }
                            }, '✕')
                        )
                    ),
                    // Link Drawer (visible when hasButton is checked)
                    hasButton ? el(
                        'div',
                        { className: 'editor-link-drawer' },
                        el('div', { className: 'drawer-group' },
                            el('label', null, 'Button Text:'),
                            el('input', {
                                type: 'text',
                                value: item.buttonText || '',
                                placeholder: 'Details',
                                style: { width: '120px' },
                                onChange: function(e) { updateItem(index, 'buttonText', e.target.value); }
                            })
                        ),
                        el('div', { className: 'drawer-group', style: { flex: '1 1 200px' } },
                            el('label', null, 'URL:'),
                            el('input', {
                                type: 'url',
                                value: item.buttonUrl || '',
                                placeholder: 'https://...',
                                style: { width: '100%' },
                                onChange: function(e) { updateItem(index, 'buttonUrl', e.target.value); }
                            })
                        ),
                        el('div', { className: 'drawer-group' },
                            el('label', { style: { display: 'inline-flex', alignItems: 'center', gap: '4px', cursor: 'pointer' } },
                                el('input', {
                                    type: 'checkbox',
                                    checked: !!item.buttonNewTab,
                                    onChange: function(e) { updateItem(index, 'buttonNewTab', e.target.checked); }
                                }),
                                'New tab'
                            )
                        ),
                        el('div', { className: 'drawer-group' },
                            el('label', null, 'Variant:'),
                            el('select', {
                                value: btnVariant,
                                onChange: function(e) { updateItem(index, 'buttonVariant', e.target.value); }
                            },
                                el('option', { value: 'outline' }, 'Outline (shadcn)'),
                                el('option', { value: 'secondary' }, 'Secondary'),
                                el('option', { value: 'default' }, 'TEDx Red')
                            )
                        )
                    ) : null
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
                        { title: 'Timetable v2 Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Section Eyebrow',
                            value: eyebrow,
                            placeholder: 'SCHEDULE',
                            help: 'Category header text in TEDx green above the title.',
                            onChange: function(val) { setAttributes({ eyebrow: val }); }
                        }),
                        el(TextControl, {
                            label: 'Section Title',
                            value: title,
                            placeholder: 'Timetable',
                            onChange: function(val) { setAttributes({ title: val }); }
                        }),
                        el(TextControl, {
                            label: 'Event Date (YYYY-MM-DD)',
                            value: eventDate,
                            placeholder: 'e.g. 2026-11-14',
                            help: 'Leave empty to auto-sync with daily clock time. When set, live sync only runs on this calendar date.',
                            onChange: function(val) { setAttributes({ eventDate: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Show Left Vertical Progress Rail',
                            checked: showProgress,
                            help: 'Displays the continuous vertical timeline progress bar on the left.',
                            onChange: function(val) { setAttributes({ showProgress: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Test / Simulation Mode',
                            checked: testMode,
                            help: 'Simulates live time tracking continuously regardless of the event date.',
                            onChange: function(val) { setAttributes({ testMode: val }); }
                        }),
                        el(Button, {
                            variant: 'secondary',
                            style: { marginTop: '10px', width: '100%', justifyContent: 'center' },
                            onClick: addItem
                        }, '+ Add New Session')
                    ),
                    el(
                        PanelBody,
                        { title: 'Sessions & Buttons (' + items.length + ')', initialOpen: false },
                        items.map(function(item, idx) {
                            var itemLabel = (item.startTime || '15:00') + ' - ' + (item.title || 'Session ' + (idx + 1));
                            return el(
                                'div',
                                { key: idx, style: { marginBottom: '16px', paddingBottom: '12px', borderBottom: '1px solid rgba(255,255,255,0.08)' } },
                                el('p', { style: { fontWeight: 600, margin: '0 0 6px 0', fontSize: '13px' } }, itemLabel),
                                el(ToggleControl, {
                                    label: 'Enable Action Button',
                                    checked: !!item.hasButton,
                                    onChange: function(val) { updateItem(idx, 'hasButton', val); }
                                }),
                                item.hasButton ? el(
                                    'div',
                                    { style: { paddingLeft: '8px' } },
                                    el(TextControl, {
                                        label: 'Button Label',
                                        value: item.buttonText || '',
                                        placeholder: 'Details',
                                        onChange: function(val) { updateItem(idx, 'buttonText', val); }
                                    }),
                                    el(TextControl, {
                                        label: 'Button URL',
                                        value: item.buttonUrl || '',
                                        placeholder: 'https://...',
                                        onChange: function(val) { updateItem(idx, 'buttonUrl', val); }
                                    }),
                                    el(ToggleControl, {
                                        label: 'Open in new tab',
                                        checked: !!item.buttonNewTab,
                                        onChange: function(val) { updateItem(idx, 'buttonNewTab', val); }
                                    }),
                                    el(SelectControl, {
                                        label: 'Button Variant',
                                        value: item.buttonVariant || 'outline',
                                        options: [
                                            { label: 'Outline (shadcn)', value: 'outline' },
                                            { label: 'Secondary', value: 'secondary' },
                                            { label: 'TEDx Red', value: 'default' }
                                        ],
                                        onChange: function(val) { updateItem(idx, 'buttonVariant', val); }
                                    })
                                ) : null
                            );
                        })
                    )
                ),
                // Main Editor Visual Canvas
                el(
                    'div',
                    { className: 'tedx-timetable-v2-container' },
                    eyebrow ? el('p', {
                        className: 'text-[13px] font-medium uppercase tracking-[0.65px] text-tedx-green m-0 mb-3',
                        style: { color: '#2ad17e', textTransform: 'uppercase', letterSpacing: '0.65px', fontSize: '13px', margin: '0 0 8px 0' }
                    }, eyebrow) : null,
                    el('input', {
                        type: 'text',
                        className: 'tedx-timetable-v2-title',
                        value: title,
                        placeholder: 'Timetable',
                        style: { fontSize: '42px', fontWeight: '400', letterSpacing: '-1.5px', color: '#fff', margin: '0 0 24px 0', border: 'none', background: 'transparent', width: '100%' },
                        onChange: function(e) { setAttributes({ title: e.target.value }); }
                    }),
                    // Timeline with Left Rail
                    el(
                        'div',
                        { className: 'tedx-timetable-v2-timeline' + (previewActiveIndex >= 0 ? ' is-active-live' : '') },
                        showProgress ? el(
                            'div',
                            {
                                className: 'tedx-timetable-v2-rail',
                                style: { top: 0, bottom: 0 }
                            },
                            el('div', {
                                className: 'tedx-timetable-v2-progress-fill',
                                style: {
                                    height: previewActiveIndex >= 0
                                        ? (items.length > 0 ? (previewActiveIndex / items.length * 100) + '%' : '0%')
                                        : '0%'
                                }
                            }),
                            el('div', {
                                className: 'tedx-timetable-v2-progress-thumb',
                                style: {
                                    top: previewActiveIndex >= 0
                                        ? (items.length > 0 ? (previewActiveIndex / items.length * 100) + '%' : '0%')
                                        : '0%'
                                }
                            })
                        ) : null,
                        // Cards list
                        el(
                            'div',
                            { className: 'tedx-timetable-v2-list' },
                            cardElements
                        )
                    ),
                    // Add Row Button
                    el('button', {
                        type: 'button',
                        className: 'add-item-btn',
                        onClick: addItem
                    }, '+ Add New Session')
                )
            );
        },
        save: function() {
            return null; // Server-side rendered via render.php
        }
    });
})(window.wp);
