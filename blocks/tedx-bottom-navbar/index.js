(function (wp) {
    'use strict';

    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var InspectorControls = wp.blockEditor.InspectorControls || wp.editor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var RichText = wp.blockEditor.RichText || wp.editor.RichText;
    var MediaUpload = wp.blockEditor.MediaUpload || wp.editor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck || wp.editor.MediaUploadCheck;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;
    var Button = wp.components.Button;
    var useState = wp.element.useState;

    registerBlockType('tedx/bottom-navbar', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var buttons = attributes.buttons || [];
            var showIcons = attributes.showIcons !== false;

            var stateHook = useState(0);
            var selectedIdx = stateHook[0];
            var setSelectedIdx = stateHook[1];

            if (selectedIdx >= buttons.length && buttons.length > 0) {
                selectedIdx = buttons.length - 1;
            }

            var blockProps = useBlockProps ? useBlockProps({
                className: 'tedx-bottom-navbar-root tedx-bottom-navbar-editor-wrap'
            }) : {
                className: 'tedx-bottom-navbar-root tedx-bottom-navbar-editor-wrap'
            };

            var updateButton = function (idx, field, value) {
                var updated = buttons.map(function (b, i) {
                    if (i === idx) {
                        var copy = Object.assign({}, b);
                        copy[field] = value;
                        return copy;
                    }
                    return b;
                });
                setAttributes({ buttons: updated });
            };

            var addButton = function () {
                var newBtn = {
                    label: 'New Link',
                    url: '#',
                    newTab: false,
                    customIconId: 0,
                    customIconUrl: ''
                };
                var updated = buttons.concat([newBtn]);
                setAttributes({ buttons: updated });
                setSelectedIdx(updated.length - 1);
            };

            var removeButton = function (idx) {
                var updated = buttons.filter(function (_, i) { return i !== idx; });
                setAttributes({ buttons: updated });
                if (selectedIdx >= updated.length) {
                    setSelectedIdx(Math.max(0, updated.length - 1));
                }
            };

            var removeAllIcons = function () {
                var updated = buttons.map(function (b) {
                    var copy = Object.assign({}, b);
                    copy.customIconId = 0;
                    copy.customIconUrl = '';
                    return copy;
                });
                setAttributes({
                    showIcons: false,
                    buttons: updated
                });
            };

            var moveButton = function (idx, direction) {
                var targetIdx = idx + direction;
                if (targetIdx < 0 || targetIdx >= buttons.length) return;
                var updated = buttons.slice();
                var temp = updated[idx];
                updated[idx] = updated[targetIdx];
                updated[targetIdx] = temp;
                setAttributes({ buttons: updated });
                setSelectedIdx(targetIdx);
            };

            var activeBtn = buttons[selectedIdx] || null;

            return el(
                'div',
                blockProps,
                // Sidebar Inspector Controls
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Navbar Settings & Icons', initialOpen: true },
                        el(ToggleControl, {
                            label: 'Show Icons',
                            help: showIcons ? 'Icons are displayed above button labels.' : 'Icons are removed (clean text-only navbar).',
                            checked: showIcons,
                            onChange: function (val) { setAttributes({ showIcons: val }); }
                        }),
                        el(Button, {
                            variant: 'secondary',
                            isDestructive: true,
                            size: 'small',
                            style: { width: '100%', justifyContent: 'center', marginTop: '6px' },
                            onClick: removeAllIcons
                        }, '🚫 Remove All Icons')
                    ),
                    el(
                        PanelBody,
                        { title: 'Bottom Bar Buttons (' + buttons.length + ')', initialOpen: true },
                        buttons.map(function (btn, idx) {
                            var isSelected = (idx === selectedIdx);
                            return el(
                                'div',
                                {
                                    key: idx,
                                    style: {
                                        marginBottom: '16px',
                                        padding: '12px',
                                        background: isSelected ? '#222' : '#181818',
                                        border: isSelected ? '1px solid #2ad17e' : '1px solid rgba(255,255,255,0.08)',
                                        borderRadius: '8px'
                                    }
                                },
                                el(
                                    'div',
                                    { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px' } },
                                    el('strong', { style: { color: '#fff' } }, (idx + 1) + '. ' + (btn.label || 'Button')),
                                    el(
                                        'div',
                                        { style: { display: 'flex', gap: '4px' } },
                                        el(
                                            Button,
                                            {
                                                size: 'small',
                                                variant: isSelected ? 'primary' : 'secondary',
                                                onClick: function () { setSelectedIdx(idx); }
                                            },
                                            isSelected ? 'Editing' : 'Select'
                                        ),
                                        el(
                                            Button,
                                            {
                                                variant: 'destructive',
                                                isDestructive: true,
                                                size: 'small',
                                                onClick: function () { removeButton(idx); }
                                            },
                                            'Remove'
                                        )
                                    )
                                ),
                                el(TextControl, {
                                    label: 'Label',
                                    value: btn.label || '',
                                    onChange: function (val) { updateButton(idx, 'label', val); }
                                }),
                                el(TextControl, {
                                    label: 'Link URL or Anchor (#section-id)',
                                    value: btn.url || '',
                                    onChange: function (val) { updateButton(idx, 'url', val); }
                                }),
                                el(ToggleControl, {
                                    label: 'Open external link in new tab',
                                    checked: !!btn.newTab,
                                    onChange: function (val) { updateButton(idx, 'newTab', val); }
                                }),
                                showIcons && el(
                                    'div',
                                    { style: { marginTop: '8px', marginBottom: '8px' } },
                                    el(
                                        MediaUploadCheck,
                                        null,
                                        el(MediaUpload, {
                                            allowedTypes: ['image'],
                                            value: btn.customIconId || 0,
                                            onSelect: function (media) {
                                                updateButton(idx, 'customIconId', media.id);
                                                updateButton(idx, 'customIconUrl', media.url);
                                            },
                                            render: function (picker) {
                                                return el(Button, {
                                                    variant: 'secondary',
                                                    size: 'small',
                                                    onClick: picker.open
                                                }, btn.customIconUrl ? 'Replace Custom SVG' : 'Upload / Select Custom SVG');
                                            }
                                        })
                                    ),
                                    btn.customIconUrl && el(Button, {
                                        variant: 'link',
                                        isDestructive: true,
                                        size: 'small',
                                        onClick: function () {
                                            updateButton(idx, 'customIconId', 0);
                                            updateButton(idx, 'customIconUrl', '');
                                        }
                                    }, 'Remove SVG')
                                ),
                                el(
                                    'div',
                                    { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: '8px' } },
                                    el(
                                        'div',
                                        { style: { display: 'flex', gap: '6px' } },
                                        el(Button, {
                                            size: 'small',
                                            disabled: idx === 0,
                                            onClick: function () { moveButton(idx, -1); }
                                        }, '←'),
                                        el(Button, {
                                            size: 'small',
                                            disabled: idx === buttons.length - 1,
                                            onClick: function () { moveButton(idx, 1); }
                                        }, '→')
                                    ),
                                    el(Button, {
                                        variant: 'destructive',
                                        isDestructive: true,
                                        size: 'small',
                                        onClick: function () { removeButton(idx); }
                                    }, '🗑️ Remove Item')
                                )
                            );
                        }),
                        el(Button, {
                            variant: 'primary',
                            onClick: addButton,
                            style: { width: '100%', justifyContent: 'center' }
                        }, '➕ Add Navigation Button')
                    )
                ),

                // Editor In-Canvas Visual Bar
                el(
                    'div',
                    { className: 'tedx-bottom-navbar-editor-header' },
                    el('span', { className: 'tedx-bottom-navbar-editor-title' }, '📱 Fixed Bottom Navbar (Click any button to edit)'),
                    el(
                        'div',
                        { style: { display: 'flex', gap: '8px', alignItems: 'center' } },
                        el(Button, {
                            variant: showIcons ? 'secondary' : 'primary',
                            size: 'small',
                            onClick: function () { setAttributes({ showIcons: !showIcons }); }
                        }, showIcons ? '🚫 Hide Icons' : '✦ Show Icons'),
                        el(Button, {
                            variant: 'secondary',
                            size: 'small',
                            onClick: addButton
                        }, '➕ Add Button')
                    )
                ),

                el(
                    'div',
                    { className: 'tedx-bottom-navbar-container' + (showIcons ? '' : ' tedx-no-icons'), style: { borderTop: '1px solid rgba(255,255,255,0.1)' } },
                    buttons.length === 0 ? el(
                        'div',
                        { style: { width: '100%', padding: '24px', textAlign: 'center', color: 'rgba(255,255,255,0.6)', fontSize: '13px' } },
                        'No navigation buttons. Click "➕ Add Button" above to add an item.'
                    ) : buttons.map(function (b, i) {
                        var isSelected = (i === selectedIdx);
                        return el(
                            'div',
                            {
                                key: i,
                                className: 'tedx-bottom-nav-item' + (isSelected ? ' is-selected' : '') + (showIcons ? '' : ' is-no-icon'),
                                onClick: function () { setSelectedIdx(i); }
                            },
                            // Direct Canvas Item Remove Button
                            el('button', {
                                type: 'button',
                                className: 'tedx-bottom-nav-item-remove-btn',
                                title: 'Remove item "' + (b.label || ('Button #' + (i + 1))) + '"',
                                'aria-label': 'Remove item',
                                onClick: function (e) {
                                    e.stopPropagation();
                                    removeButton(i);
                                }
                            }, '✕'),
                            showIcons && el('span', { className: 'tedx-bottom-nav-icon-box' },
                                b.customIconUrl ? el('span', {
                                    className: 'tedx-bottom-nav-custom-icon',
                                    style: { WebkitMaskImage: 'url(' + b.customIconUrl + ')', maskImage: 'url(' + b.customIconUrl + ')' }
                                }) : el('span', { style: { fontSize: '18px', lineHeight: 1 } }, '✦')
                            ),
                            el(RichText, {
                                tagName: 'span',
                                className: 'tedx-bottom-nav-label',
                                value: b.label || '',
                                onChange: function (val) { updateButton(i, 'label', val); },
                                placeholder: 'Label',
                                allowedFormats: []
                            })
                        );
                    })
                ),

                // Selected Button Quick Settings Card right in the canvas
                activeBtn && el(
                    'div',
                    { className: 'tedx-bottom-navbar-editor-inline-card' },
                    el(
                        'div',
                        { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' } },
                        el('strong', { style: { color: '#2ad17e', fontSize: '13px' } }, 'Editing Button #' + (selectedIdx + 1) + ': ' + (activeBtn.label || 'Untitled')),
                        el(
                            'div',
                            { style: { display: 'flex', gap: '6px' } },
                            el(Button, {
                                size: 'small',
                                disabled: selectedIdx === 0,
                                onClick: function () { moveButton(selectedIdx, -1); }
                            }, '← Move Left'),
                            el(Button, {
                                size: 'small',
                                disabled: selectedIdx === buttons.length - 1,
                                onClick: function () { moveButton(selectedIdx, 1); }
                            }, 'Move Right →'),
                            el(Button, {
                                variant: 'destructive',
                                isDestructive: true,
                                size: 'small',
                                onClick: function () { removeButton(selectedIdx); }
                            }, '🗑️ Remove Item')
                        )
                    ),
                    el(
                        'div',
                        { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' } },
                        el(TextControl, {
                            label: 'Button Label',
                            value: activeBtn.label || '',
                            onChange: function (val) { updateButton(selectedIdx, 'label', val); }
                        }),
                        el(TextControl, {
                            label: 'Link URL or Anchor (#section-id)',
                            value: activeBtn.url || '',
                            help: 'E.g. #timetable or #speakers to smooth-scroll',
                            onChange: function (val) { updateButton(selectedIdx, 'url', val); }
                        })
                    ),
                    el(
                        'div',
                        { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: '8px', flexWrap: 'wrap', gap: '8px' } },
                        showIcons ? el(
                            'div',
                            { style: { display: 'flex', alignItems: 'center', gap: '8px' } },
                            el(
                                MediaUploadCheck,
                                null,
                                el(MediaUpload, {
                                    allowedTypes: ['image'],
                                    value: activeBtn.customIconId || 0,
                                    onSelect: function (media) {
                                        updateButton(selectedIdx, 'customIconId', media.id);
                                        updateButton(selectedIdx, 'customIconUrl', media.url);
                                    },
                                    render: function (picker) {
                                        return el(Button, {
                                            variant: 'secondary',
                                            size: 'small',
                                            onClick: picker.open
                                        }, activeBtn.customIconUrl ? 'Replace Custom SVG Icon' : 'Upload / Select Custom SVG Icon');
                                    }
                                })
                            ),
                            activeBtn.customIconUrl && el(Button, {
                                variant: 'link',
                                isDestructive: true,
                                size: 'small',
                                onClick: function () {
                                    updateButton(selectedIdx, 'customIconId', 0);
                                    updateButton(selectedIdx, 'customIconUrl', '');
                                }
                            }, 'Remove Icon')
                        ) : el('span', { style: { fontSize: '12px', color: 'rgba(255,255,255,0.5)' } }, 'Icons are disabled (text-only mode).'),
                        el(ToggleControl, {
                            label: 'Open in new tab',
                            checked: !!activeBtn.newTab,
                            onChange: function (val) { updateButton(selectedIdx, 'newTab', val); }
                        })
                    )
                )
            );
        },

        save: function () {
            return null;
        }
    });
})(window.wp);
