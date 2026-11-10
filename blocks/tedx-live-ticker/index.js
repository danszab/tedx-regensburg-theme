/**
 * WordPress Block Editor Script for TEDx Live Event Ticker
 */
(function (wp) {
    'use strict';

    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var InspectorControls = wp.blockEditor.InspectorControls || wp.editor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;

    registerBlockType('tedx/live-ticker', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var eyebrowLive = attributes.eyebrowLive || 'LIVE';
            var eyebrowNext = attributes.eyebrowNext || 'UP NEXT';
            var eyebrowFinished = attributes.eyebrowFinished || 'FINISHED';
            var jumpToTimetable = attributes.jumpToTimetable !== false;
            var eventDate = attributes.eventDate || '';
            var testMode = !!attributes.testMode;

            var items = attributes.items || [];
            var previewItem = items.length > 0 ? items[0] : {
                title: 'Opening Session & Introduction',
                startTime: '14:30',
                endTime: '15:00'
            };

            var blockProps = useBlockProps ? useBlockProps({
                className: 'tedx-live-ticker-block tedx-live-ticker-editor'
            }) : {
                className: 'tedx-live-ticker-block tedx-live-ticker-editor'
            };

            return el(
                'div',
                blockProps,
                // Inspector Controls
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Ticker Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Live Label',
                            value: eyebrowLive,
                            onChange: function (val) { setAttributes({ eyebrowLive: val }); }
                        }),
                        el(TextControl, {
                            label: 'Up Next Label',
                            value: eyebrowNext,
                            onChange: function (val) { setAttributes({ eyebrowNext: val }); }
                        }),
                        el(TextControl, {
                            label: 'Finished Label',
                            value: eyebrowFinished,
                            onChange: function (val) { setAttributes({ eyebrowFinished: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Jump to Timetable on Click',
                            help: 'Smooth-scrolls to the Timetable block on page when clicked.',
                            checked: jumpToTimetable,
                            onChange: function (val) { setAttributes({ jumpToTimetable: val }); }
                        }),
                        el(TextControl, {
                            label: 'Event Date (YYYY-MM-DD)',
                            help: 'Leave empty to inherit automatically from the Timetable v2 block on page.',
                            value: eventDate,
                            onChange: function (val) { setAttributes({ eventDate: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Test Mode (Always Simulate Active Event)',
                            checked: testMode,
                            onChange: function (val) { setAttributes({ testMode: val }); }
                        })
                    )
                ),

                // Editor Visual Canvas (Static Clickable Pill preview)
                el(
                    'div',
                    { className: 'tedx-live-ticker-editor-preview' },
                    el(
                        'div',
                        { className: 'tedx-live-ticker-pill' },
                        el(
                            'span',
                            { className: 'tedx-live-ticker-badge badge-live' },
                            el('span', { className: 'tedx-live-ticker-dot' }),
                            el('span', { className: 'tedx-live-ticker-badge-text' }, eyebrowLive)
                        ),
                        el(
                            'span',
                            { className: 'tedx-live-ticker-title' },
                            previewItem.title
                        ),
                        el(
                            'span',
                            { className: 'tedx-live-ticker-time' },
                            previewItem.startTime + (previewItem.endTime ? ' – ' + previewItem.endTime : '')
                        ),
                        jumpToTimetable && el(
                            'span',
                            { className: 'tedx-live-ticker-arrow' },
                            el(
                                'svg',
                                { width: 12, height: 12, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 2.5 },
                                el('path', { d: 'M6 9l6 6 6-6' })
                            )
                        )
                    ),
                    el(
                        'div',
                        { className: 'tedx-live-ticker-editor-notice' },
                        'Floating pill will automatically dock at the top of the viewport on the frontend and sync with the current time / Timetable v2.'
                    )
                )
            );
        },

        save: function () {
            // Rendered dynamically via render.php
            return null;
        }
    });
})(window.wp);
