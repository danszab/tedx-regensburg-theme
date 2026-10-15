document.querySelectorAll('[data-tedx-embed]').forEach(function (embed) {
    var accept = embed.querySelector('[data-embed-accept]');
    var revoke = embed.querySelector('[data-embed-revoke]');
    var placeholder = embed.querySelector('.tedx-embed-placeholder');
    var content = embed.querySelector('.tedx-embed-content');
    if (!accept) { return; }
    accept.hidden = false;
    accept.addEventListener('click', function () {
        var iframe = document.createElement('iframe');
        iframe.title = embed.getAttribute('data-embed-title');
        // YouTube requires a referrer (error 153); send only the origin cross-site.
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.allowFullscreen = true;
        iframe.src = embed.getAttribute('data-embed-url');
        content.replaceChildren(iframe);
        placeholder.hidden = true;
        content.hidden = false;
        revoke.hidden = false;
        revoke.focus();
    });
    revoke.addEventListener('click', function () {
        content.replaceChildren();
        content.hidden = true;
        revoke.hidden = true;
        placeholder.hidden = false;
        accept.focus();
    });
});
