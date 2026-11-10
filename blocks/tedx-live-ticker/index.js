/**
 * WordPress Block Editor Script for TEDx Live Event Ticker
 */
(function (wp) {
    'use strict';

    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var InspectorControls = wp.blockEditor.InspectorControls || wp.editor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var RichText = wp.blockEditor.RichText || wp.editor.RichText;
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

                // Editor Visual Canvas (Clickable & Editable Pill preview)
                el(
                    'div',
                    { className: 'tedx-live-ticker-editor-preview' },
                    el(
                        'div',
                        { style: { display: 'flex', alignItems: 'center', justifyContent: 'space-between', width: '100%', marginBottom: '8px', borderBottom: '1px solid rgba(255,255,255,0.08)', paddingBottom: '8px' } },
                        el('strong', { style: { color: '#2ad17e', fontSize: '13px' } }, '⚡ TEDx Live Event Ticker (Floating Top Pill)'),
                        el('span', { style: { fontSize: '11px', color: 'rgba(255,255,255,0.45)' } }, testMode ? 'Simulation Active' : 'Live Synced')
                    ),
                    el(
                        'div',
                        { className: 'tedx-live-ticker-pill', style: { pointerEvents: 'auto', cursor: 'pointer' } },
                        el(
                            'span',
                            { className: 'tedx-live-ticker-badge badge-live' },
                            el('span', { className: 'tedx-live-ticker-dot' }),
                            el(RichText, {
                                tagName: 'span',
                                className: 'tedx-live-ticker-badge-text',
                                value: eyebrowLive,
                                onChange: function (val) { setAttributes({ eyebrowLive: val }); },
                                placeholder: 'LIVE',
                                allowedFormats: []
                            })
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
                        { style: { display: 'flex', flexWrap: 'wrap', gap: '16px', alignItems: 'center', justifyContent: 'center', marginTop: '12px', width: '100%' } },
                        el(ToggleControl, {
                            label: 'Jump to Timetable on click',
                            checked: jumpToTimetable,
                            onChange: function (val) { setAttributes({ jumpToTimetable: val }); }
                        }),
                        el(ToggleControl, {
                            label: 'Test Mode (preview live event state)',
                            checked: testMode,
                            onChange: function (val) { setAttributes({ testMode: val }); }
                        })
                    ),
                    el(
                        'div',
                        { className: 'tedx-live-ticker-editor-notice' },
                        'On frontend, floating pill automatically docks at the top of the viewport under the navbar and synchronizes with Timetable v2 sessions.'
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
