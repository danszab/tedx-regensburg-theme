(function(wp) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPalette = wp.components.ColorPalette;
    var Button = wp.components.Button;

    var BRAND_COLORS = [
        { name: 'TEDx Red', color: '#eb0028' },
        { name: 'TEDx Green', color: '#2ad17e' },
        { name: 'TEDx Dark', color: '#212121' },
        { name: 'White', color: '#ffffff' },
        { name: 'Muted Gray', color: '#888888' },
        { name: 'Black', color: '#000000' }
    ];

    registerBlockType('tedx/button', {
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var text = attributes.text || 'Click Here';
            var url = attributes.url || '';
            var newTab = !!attributes.newTab;
            var variant = attributes.variant || 'default';
            var customBg = attributes.customBg || '';
            var customTextColor = attributes.customTextColor || '';
            var size = attributes.size || 'md';
            var width = attributes.width || 'auto';
            var alignment = attributes.alignment || 'left';
            var iconId = attributes.iconId || 0;
            var iconUrl = attributes.iconUrl || '';
            var iconPosition = attributes.iconPosition || 'left';

            var blockClass = 'tedx-button-block w-full px-4 md:px-8 lg:px-12';
            if (attributes.paddingTop === 'none') { blockClass += ' pt-0'; }
            else if (attributes.paddingTop === 'compact') { blockClass += ' pt-4 md:pt-6'; }
            else if (attributes.paddingTop === 'large') { blockClass += ' pt-16 md:pt-24'; }
            else { blockClass += ' pt-8 md:pt-12'; }

            if (attributes.paddingBottom === 'none') { blockClass += ' pb-0'; }
            else if (attributes.paddingBottom === 'compact') { blockClass += ' pb-4 md:pb-6'; }
            else if (attributes.paddingBottom === 'large') { blockClass += ' pb-16 md:pb-24'; }
            else { blockClass += ' pb-8 md:pb-12'; }

            if (attributes.hideBorderTop) {
                blockClass += ' border-t-0';
            } else {
                blockClass += ' border-t border-white/5';
            }

            var blockProps = useBlockProps ? useBlockProps({ className: blockClass }) : { className: blockClass };

            // Build dynamic classes
            var btnClasses = [
                'tedx-btn',
                'btn-shadcn-anim',
                'size-' + size,
                'width-' + width,
                'variant-' + variant
            ].join(' ');

            // Build inline styles for custom colors
            var btnStyles = {};
            if (variant === 'custom') {
                if (customBg) {
                    btnStyles.backgroundColor = customBg;
                    btnStyles.borderColor = customBg;
                }
                if (customTextColor) {
                    btnStyles.color = customTextColor;
                }
            }

            // Icon Element Preview
            var iconEl = iconUrl ? el(
                'span',
                { className: 'tedx-btn-icon', ariaHidden: true },
                el('span', {
                    className: 'tedx-btn-icon-mask',
                    style: { '--btn-icon-url': 'url(' + iconUrl + ')' }
                })
            ) : null;

            return el(
                'div',
                blockProps,
                // Sidebar Controls
                el(
                    InspectorControls,
                    null,
                    // Link Settings
                    el(
                        PanelBody,
                        { title: 'Link & Redirect', initialOpen: true },
                        el(TextControl, {
                            label: 'Destination URL',
                            value: url,
                            placeholder: 'https://...',
                            help: 'Leave empty for an action button without redirect.',
                            onChange: function(val) { setAttributes({ url: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Open in new tab',
                            checked: newTab,
                            onChange: function(val) { setAttributes({ newTab: val }); }
                        })
                    ),
                    // Style & Colors
                    el(
                        PanelBody,
                        { title: 'Style & Background', initialOpen: true },
                        el(SelectControl, {
                            label: 'Color Variant',
                            value: variant,
                            options: [
                                { label: 'TEDx Red (Primary)', value: 'default' },
                                { label: 'TEDx Green', value: 'green' },
                                { label: 'Dark Secondary', value: 'secondary' },
                                { label: 'White', value: 'white' },
                                { label: 'Outline', value: 'outline' },
                                { label: 'Custom Color', value: 'custom' }
                            ],
                            onChange: function(val) { setAttributes({ variant: val }); }
                        }),
                        variant === 'custom' ? el(
                            'div',
                            { style: { marginTop: '12px' } },
                            el('p', { style: { fontSize: '12px', fontWeight: 600, marginBottom: '6px' } }, 'Custom Background Color'),
                            el(ColorPalette, {
                                colors: BRAND_COLORS,
                                value: customBg,
                                onChange: function(val) { setAttributes({ customBg: val || '' }); }
                            }),
                            el('p', { style: { fontSize: '12px', fontWeight: 600, marginTop: '12px', marginBottom: '6px' } }, 'Custom Text Color'),
                            el(ColorPalette, {
                                colors: BRAND_COLORS,
                                value: customTextColor,
                                onChange: function(val) { setAttributes({ customTextColor: val || '' }); }
                            })
                        ) : null
                    ),
                    // Size & Width Layout
                    el(
                        PanelBody,
                        { title: 'Size & Width Layout', initialOpen: true },
                        el(SelectControl, {
                            label: 'Size (shadcn)',
                            value: size,
                            options: [
                                { label: 'Small (h-8, 32px)', value: 'sm' },
                                { label: 'Medium / Default (h-10, 40px)', value: 'md' },
                                { label: 'Large (h-12, 48px)', value: 'lg' }
                            ],
                            onChange: function(val) { setAttributes({ size: val }); }
                        }),
                        el(SelectControl, {
                            label: 'Width Preset',
                            value: width,
                            options: [
                                { label: 'Auto (Hug Content)', value: 'auto' },
                                { label: 'Small Fixed (140px)', value: 'sm' },
                                { label: 'Medium Fixed (200px)', value: 'md' },
                                { label: 'Large Fixed (280px)', value: 'lg' },
                                { label: 'Full Width (100%)', value: 'full' }
                            ],
                            onChange: function(val) { setAttributes({ width: val }); }
                        }),
                        el(SelectControl, {
                            label: 'Container Alignment',
                            value: alignment,
                            options: [
                                { label: 'Left', value: 'left' },
                                { label: 'Center', value: 'center' },
                                { label: 'Right', value: 'right' }
                            ],
                            onChange: function(val) { setAttributes({ alignment: val }); }
                        })
                    ),
                    // Icon Settings (Custom SVG)
                    el(
                        PanelBody,
                        { title: 'Icon (Optional SVG)', initialOpen: false },
                        el('p', { style: { fontSize: '12px', color: '#888', marginBottom: '10px' } },
                            'Select an SVG icon from the Media Library. If none is selected, the button is text-only.'
                        ),
                        el(
                            MediaUploadCheck,
                            null,
                            el(MediaUpload, {
                                onSelect: function(media) {
                                    setAttributes({ iconId: media.id, iconUrl: media.url });
                                },
                                allowedTypes: ['image'],
                                value: iconId,
                                render: function(obj) {
                                    return el(Button, {
                                        variant: iconUrl ? 'secondary' : 'primary',
                                        style: { width: '100%', justifyContent: 'center' },
                                        onClick: obj.open
                                    }, iconUrl ? 'Replace SVG Icon' : 'Upload / Select SVG Icon');
                                }
                            })
                        ),
                        iconUrl ? el(
                            'div',
                            { style: { marginTop: '12px' } },
                            el(SelectControl, {
                                label: 'Icon Position',
                                value: iconPosition,
                                options: [
                                    { label: 'Left (Before text)', value: 'left' },
                                    { label: 'Right (After text)', value: 'right' }
                                ],
                                onChange: function(val) { setAttributes({ iconPosition: val }); }
                            }),
                            el(Button, {
                                variant: 'link',
                                isDestructive: true,
                                style: { marginTop: '6px' },
                                onClick: function() { setAttributes({ iconId: 0, iconUrl: '' }); }
                            }, 'Remove Icon (Text-Only)')
                        ) : null
                    )
                ),
                // Editor Visual Canvas (WYSIWYG)
                el(
                    'div',
                    { className: 'tedx-button-container max-w-figma mx-auto w-full' },
                    el(
                        'div',
                        { className: 'tedx-button-wrap align-' + alignment },
                        el(
                            'div',
                            {
                                className: btnClasses,
                                style: btnStyles,
                                title: url ? 'Target: ' + url : 'No URL set'
                            },
                            iconEl && iconPosition === 'left' ? iconEl : null,
                            el('input', {
                                type: 'text',
                                className: 'tedx-button-editor-input',
                                value: text,
                                placeholder: 'Button text...',
                                onChange: function(e) { setAttributes({ text: e.target.value }); }
                            }),
                            iconEl && iconPosition === 'right' ? iconEl : null
                        )
                    )
                )
            );
        },
        save: function() {
            return null; // Server-side rendered via render.php
        }
    });
})(window.wp);
