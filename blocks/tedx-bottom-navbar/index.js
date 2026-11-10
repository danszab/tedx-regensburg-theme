(function (wp) {
    'use strict';

    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var InspectorControls = wp.blockEditor.InspectorControls || wp.editor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var MediaUpload = wp.blockEditor.MediaUpload || wp.editor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck || wp.editor.MediaUploadCheck;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;
    var Button = wp.components.Button;

    registerBlockType('tedx/bottom-navbar', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var buttons = attributes.buttons || [];

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
                var updated = buttons.concat([{
                    label: 'New Link',
                    url: '#',
                    newTab: false,
                    customIconId: 0,
                    customIconUrl: ''
                }]);
                setAttributes({ buttons: updated });
            };

            var removeButton = function (idx) {
                var updated = buttons.filter(function (_, i) { return i !== idx; });
                setAttributes({ buttons: updated });
            };

            return el(
                'div',
                blockProps,
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Bottom Bar Buttons', initialOpen: true },
                        buttons.map(function (btn, idx) {
                            return el(
                                'div',
                                { key: idx, style: { marginBottom: '16px', padding: '12px', background: '#1c1c1c', borderRadius: '8px' } },
                                el('strong', { style: { color: '#fff', display: 'block', marginBottom: '8px' } }, (idx + 1) + '. ' + (btn.label || 'Button')),
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
                                el(
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
                                el(Button, {
                                    variant: 'destructive',
                                    size: 'small',
                                    onClick: function () { removeButton(idx); }
                                }, 'Delete Button')
                            );
                        }),
                        el(Button, {
                            variant: 'primary',
                            onClick: addButton
                        }, 'Add Navigation Button')
                    )
                ),
                el(
                    'div',
                    { className: 'tedx-bottom-navbar-container', style: { borderTop: '1px solid rgba(255,255,255,0.1)' } },
                    buttons.map(function (b, i) {
                        return el(
                            'div',
                            { key: i, className: 'tedx-bottom-nav-item' },
                            el('span', { className: 'tedx-bottom-nav-icon-box' },
                                b.customIconUrl ? el('span', {
                                    className: 'tedx-bottom-nav-custom-icon',
                                    style: { WebkitMaskImage: 'url(' + b.customIconUrl + ')', maskImage: 'url(' + b.customIconUrl + ')' }
                                }) : el('span', null, '●')
                            ),
                            el('span', { className: 'tedx-bottom-nav-label' }, b.label)
                        );
                    })
                )
            );
        },

        save: function () {
            return null;
        }
    });
})(window.wp);
