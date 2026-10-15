(function(wp) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var MediaPlaceholder = wp.blockEditor.MediaPlaceholder;
    var BlockControls = wp.blockEditor.BlockControls;
    var ToolbarButton = wp.components.ToolbarButton;
    var __ = wp.i18n.__;

    registerBlockType('tedx/gallery', {
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var images = attributes.images || [];
            var blockProps = wp.blockEditor.useBlockProps({
                style: { padding: '20px', background: '#000000', color: 'white', borderRadius: '12px' }
            });

            var onSelectImages = function(media) {
                var newImages = media.map(function(item) {
                    return {
                        id: item.id,
                        url: item.url,
                        alt: item.alt || ''
                    };
                });
                setAttributes({ images: newImages });
            };

            var imagePicker = function(render) {
                return el(
                    wp.blockEditor.MediaUploadCheck,
                    null,
                    el(wp.blockEditor.MediaUpload, {
                        onSelect: onSelectImages,
                        allowedTypes: ['image'],
                        multiple: true,
                        gallery: true,
                        value: images.map(function(img) { return img.id; }),
                        render: render
                    })
                );
            };

            if (images.length === 0) {
                return el(
                    'div',
                    blockProps,
                    el(MediaPlaceholder, {
                        onSelect: onSelectImages,
                        accept: 'image/*',
                        multiple: true,
                        allowedTypes: ['image'],
                        labels: { title: __('TEDx Gallery: Select Images', 'tedx-regensburg') }
                    })
                );
            }

            var imagePreviews = images.map(function(img) {
                return el('img', { 
                    key: img.id || img.url,
                    src: img.url, 
                    alt: img.alt || '',
                    style: { width: '100px', height: '70px', objectFit: 'cover', borderRadius: '8px', margin: '5px' } 
                });
            });

            return el(
                'div',
                blockProps,
                el(
                    BlockControls,
                    null,
                    el(
                        wp.components.ToolbarGroup,
                        null,
                        imagePicker(function(obj) {
                            return el(ToolbarButton, {
                                onClick: obj.open,
                                icon: 'edit',
                                label: __('Edit gallery images', 'tedx-regensburg')
                            });
                        })
                    )
                ),
                el('h3', { style: { margin: '0 0 10px 0' } }, __('TEDx Gallery Block', 'tedx-regensburg')),
                imagePicker(function(obj) {
                    return el(wp.components.Button, {
                        onClick: obj.open,
                        variant: 'secondary'
                    }, __('Edit gallery images', 'tedx-regensburg'));
                }),
                el('div', { style: { display: 'flex', flexWrap: 'wrap' } }, imagePreviews),
                el('p', { style: { fontSize: '12px', opacity: 0.7 } }, __('Frontend will render the complex 2-row layout with slider.', 'tedx-regensburg'))
            );
        },
        save: function() {
            return null;
        }
    });
})(window.wp);
