(function(wp) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var MediaPlaceholder = wp.blockEditor.MediaPlaceholder;
    var BlockControls = wp.blockEditor.BlockControls;
    var ToolbarButton = wp.components.ToolbarButton;

    registerBlockType('tedx/gallery', {
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var images = attributes.images || [];

            var onSelectImages = function(media) {
                var newImages = media.map(function(item) {
                    return {
                        id: item.id,
                        url: item.url,
                        alt: item.alt
                    };
                });
                setAttributes({ images: newImages });
            };

            if (images.length === 0) {
                return el(
                    'div',
                    { className: props.className },
                    el(MediaPlaceholder, {
                        onSelect: onSelectImages,
                        accept: 'image/*',
                        multiple: true,
                        allowedTypes: ['image'],
                        labels: { title: 'TEDx Gallery: Select Images' }
                    })
                );
            }

            var imagePreviews = images.map(function(img) {
                return el('img', { 
                    src: img.url, 
                    style: { width: '100px', height: '70px', objectFit: 'cover', borderRadius: '8px', margin: '5px' } 
                });
            });

            return el(
                'div',
                { className: props.className, style: { padding: '20px', background: '#000000', color: 'white', borderRadius: '12px' } },
                el(
                    BlockControls,
                    null,
                    el(
                        wp.blockEditor.MediaUploadCheck,
                        null,
                        el(wp.blockEditor.MediaUpload, {
                            onSelect: onSelectImages,
                            allowedTypes: ['image'],
                            multiple: true,
                            gallery: true,
                            value: images.map(function(img) { return img.id; }),
                            render: function(obj) {
                                return el(ToolbarButton, {
                                    onClick: obj.open,
                                    icon: 'edit',
                                    title: 'Edit Gallery Images'
                                });
                            }
                        })
                    )
                ),
                el('h3', { style: { margin: '0 0 10px 0' } }, 'TEDx Gallery Block'),
                el('div', { style: { display: 'flex', flexWrap: 'wrap' } }, imagePreviews),
                el('p', { style: { fontSize: '12px', opacity: 0.7 } }, 'Frontend will render the complex 2-row layout with slider.')
            );
        }
    });
})(window.wp);
