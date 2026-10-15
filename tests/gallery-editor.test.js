const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const source = fs.readFileSync(path.join(__dirname, '..', 'blocks', 'tedx-gallery', 'index.js'), 'utf8');

function loadEditor() {
    let definition;
    let hookCalls = 0;
    const wp = {
        element: {
            createElement: (type, props, ...children) => ({ type, props, children })
        },
        blocks: {
            registerBlockType: (name, settings) => {
                assert.equal(name, 'tedx/gallery');
                definition = settings;
            }
        },
        blockEditor: {
            useBlockProps: (props) => {
                hookCalls++;
                return { ...props, ref: 'block-ref', 'data-block': 'gallery-id', tabIndex: 0 };
            },
            MediaPlaceholder: 'MediaPlaceholder',
            BlockControls: 'BlockControls',
            MediaUploadCheck: 'MediaUploadCheck',
            MediaUpload: 'MediaUpload'
        },
        components: {
            ToolbarButton: 'ToolbarButton',
            ToolbarGroup: 'ToolbarGroup',
            Button: 'Button'
        },
        i18n: { __: (text) => text }
    };
    vm.runInNewContext(source, { window: { wp } });
    return { get definition() { return definition; }, get hookCalls() { return hookCalls; } };
}

function findAll(node, type) {
    if (!node || typeof node !== 'object') return [];
    if (Array.isArray(node)) return node.flatMap((child) => findAll(child, type));
    return (node.type === type ? [node] : []).concat(findAll(node.children, type));
}

test('empty and populated galleries attach the API v3 block props to the editor root', () => {
    const editor = loadEditor();
    for (const images of [[], [{ id: 1, url: '/one.jpg', alt: 'One' }]]) {
        const tree = editor.definition.edit({ attributes: { images }, setAttributes() {} });
        assert.equal(tree.props.ref, 'block-ref');
        assert.equal(tree.props['data-block'], 'gallery-id');
        assert.equal(tree.props.tabIndex, 0);
    }
    assert.equal(editor.hookCalls, 2);
    assert.equal(editor.definition.save(), null);
});

test('new selections persist and reopened galleries offer toolbar and visible editing controls', () => {
    const editor = loadEditor();
    let attributes = { images: [] };
    const setAttributes = (updates) => { attributes = { ...attributes, ...updates }; };
    const empty = editor.definition.edit({ attributes, setAttributes });
    const placeholder = findAll(empty, 'MediaPlaceholder')[0];
    placeholder.props.onSelect([
        { id: 1, url: '/one.jpg', alt: 'One', unrelated: 'not saved' },
        { id: 2, url: '/two.jpg' }
    ]);
    attributes = JSON.parse(JSON.stringify(attributes));
    assert.deepEqual(attributes.images, [
        { id: 1, url: '/one.jpg', alt: 'One' },
        { id: 2, url: '/two.jpg', alt: '' }
    ]);
    const reopened = editor.definition.edit({ attributes, setAttributes });
    const pickers = findAll(reopened, 'MediaUpload');
    assert.equal(pickers.length, 2);
    assert.equal(findAll(reopened, 'ToolbarGroup').length, 1);
    assert.equal(findAll(reopened, 'MediaUploadCheck').length, 2);

    pickers.forEach((picker, index) => {
        assert.deepEqual(Array.from(picker.props.value), [1, 2]);
        assert.equal(picker.props.gallery, true);
        assert.equal(picker.props.multiple, true);
        let opened = false;
        const button = picker.props.render({ open: () => { opened = true; } });
        assert.equal(button.type, index === 0 ? 'ToolbarButton' : 'Button');
        button.props.onClick();
        assert.equal(opened, true);
    });

    pickers[1].props.onSelect([
        { id: 2, url: '/two.jpg', alt: 'Updated' },
        { id: 3, url: '/three.jpg', alt: 'Three' }
    ]);
    attributes = JSON.parse(JSON.stringify(attributes));
    const updated = editor.definition.edit({ attributes, setAttributes });
    const previews = findAll(updated, 'img');
    assert.deepEqual(previews.map((image) => image.props.src), ['/two.jpg', '/three.jpg']);
    assert.deepEqual(previews.map((image) => image.props.alt), ['Updated', 'Three']);
    assert.deepEqual(previews.map((image) => image.props.key), [2, 3]);

    findAll(updated, 'MediaUpload')[0].props.onSelect([]);
    const cleared = editor.definition.edit({ attributes, setAttributes });
    assert.equal(findAll(cleared, 'MediaPlaceholder').length, 1);
});
