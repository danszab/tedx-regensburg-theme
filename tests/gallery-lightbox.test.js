const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

function element(attributes = {}) {
    return {
        attributes: { ...attributes },
        listeners: {},
        style: {},
        classList: { add() {}, remove() {} },
        addEventListener(event, handler) { this.listeners[event] = handler; },
        getAttribute(name) { return this.attributes[name] || null; },
        removeAttribute(name) { delete this.attributes[name]; delete this[name]; }
    };
}

test('gallery defers large images until opening and clears responsive sources on close', () => {
    const thumbnail = element({
        'data-lightbox-src': '/large.jpg',
        'data-lightbox-srcset': '/large.jpg 1024w, /larger.jpg 2048w'
    });
    thumbnail.src = '/medium.jpg';
    thumbnail.alt = 'Gallery image';
    const external = element();
    external.src = 'https://example.test/external.jpg';
    const image = element();
    const modal = element();
    const close = element();
    const scroll = element();
    const wrapper = {
        querySelector(selector) {
            return {
                '.tedx-gallery-scroll-container': scroll,
                '.tedx-lightbox': modal,
                '.lightbox-img': image,
                '.lightbox-close': close
            }[selector] || null;
        },
        querySelectorAll() { return [thumbnail, external]; }
    };
    const timers = new Map();
    let timerId = 0;
    const document = {
        body: { style: {} },
        addEventListener(event, callback) { assert.equal(event, 'DOMContentLoaded'); callback(); },
        querySelectorAll() { return [wrapper]; }
    };
    vm.runInNewContext(
        fs.readFileSync(path.join(__dirname, '..', 'blocks', 'tedx-gallery', 'script.js'), 'utf8'),
        {
            document,
            setTimeout(callback) { timers.set(++timerId, callback); return timerId; },
            clearTimeout(id) { timers.delete(id); }
        }
    );
    assert.equal(image.src, undefined);
    assert.equal(image.srcset, undefined);

    thumbnail.listeners.click({ preventDefault() {} });
    assert.equal(image.src, '/large.jpg');
    assert.equal(image.srcset, '/large.jpg 1024w, /larger.jpg 2048w');
    assert.equal(image.sizes, '90vw');
    assert.equal(image.alt, 'Gallery image');
    assert.equal(document.body.style.overflow, 'hidden');

    close.listeners.click();
    external.listeners.click({ preventDefault() {} });
    assert.equal(timers.size, 0, 'Reopening cancels pending image cleanup');
    assert.equal(image.src, external.src);
    assert.equal(image.srcset, undefined, 'URL-only image does not reuse the previous responsive source');

    close.listeners.click();
    Array.from(timers.values()).forEach((callback) => callback());
    assert.equal(image.src, undefined);
    assert.equal(image.srcset, undefined);
    assert.equal(image.sizes, undefined);
    assert.equal(document.body.style.overflow, '');
});
