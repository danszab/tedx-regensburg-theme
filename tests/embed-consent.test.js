const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const test = require('node:test');

test('each embed requires explicit consent; withdrawal removes it; no persistent storage', () => {
    function element() {
        return {
            hidden: true, listeners: {}, children: [],
            addEventListener(name, fn) { this.listeners[name] = fn; },
            replaceChildren(...nodes) { this.children = nodes; },
            focus() { this.focused = true; }
        };
    }
    function embed() {
        const accept = element(), revoke = element(), placeholder = element(), content = element();
        placeholder.hidden = false;
        return {
            accept, revoke, placeholder, content,
            querySelector(selector) {
                return { '[data-embed-accept]': accept, '[data-embed-revoke]': revoke,
                    '.tedx-embed-placeholder': placeholder, '.tedx-embed-content': content }[selector];
            },
            getAttribute(name) { return name === 'data-embed-url' ? 'https://www.google.com/maps/embed?pb=test' : 'Map'; }
        };
    }
    const a = embed(), b = embed();
    let created = 0;
    vm.runInNewContext(fs.readFileSync(path.join(__dirname, '..', 'assets', 'js', 'embed-consent.js'), 'utf8'), {
        document: {
            querySelectorAll() { return [a, b]; },
            createElement(tag) { assert.equal(tag, 'iframe'); created++; return element(); }
        }
    });
    assert.equal(created, 0);
    assert.equal(a.accept.hidden, false);
    a.accept.listeners.click();
    assert.equal(created, 1);
    assert.equal(a.content.children[0].referrerPolicy, 'strict-origin-when-cross-origin');
    assert.equal(a.placeholder.hidden, true);
    assert.equal(b.placeholder.hidden, false);
    assert.equal(b.content.children.length, 0);
    assert.equal(a.revoke.focused, true);
    a.revoke.listeners.click();
    assert.equal(a.content.children.length, 0);
    assert.equal(a.placeholder.hidden, false);
    assert.equal(a.revoke.hidden, true);
    assert.equal(a.accept.focused, true);
});
