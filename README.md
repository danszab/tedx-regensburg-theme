# TEDx Regensburg WordPress Theme

A WordPress theme for [TEDx Regensburg](https://tedxregensburg.com), based on a hand-made Figma design. Page sections use native Gutenberg blocks; events, speakers, speaker coaches, and team members are managed in WordPress.

## Key Features

- Responsive dark layout, TEDx brand colors, desktop navigation, and a mobile menu.
- Homepage video hero with separate desktop/mobile videos and still images.
- Twelve custom Gutenberg blocks and starter patterns for the homepage, event pages, and About Us.
- Event, speaker, speaker-coach, and team directories.
- Press-post cards with automatically generated PDF downloads.
- Image gallery with scrolling controls and a click-to-open lightbox.
- Partner and sponsor logos managed directly inside blocks.
- Per-embed consent controls for Google Maps and YouTube, including supported embeds in post content.
- Self-hosted Inter fonts, responsive images, and precompiled Tailwind CSS.

## First Installation

1. **Prepare WordPress and hosting.**
   - Use a maintained WordPress release and a supported PHP version. Theme metadata declares WordPress 6.0+ and PHP 7.4+, but these minimums are not a recommendation to run outdated software.
   - Enable PHP **DOM** and **mbstring**: DOM is used for SVG/iframe processing, and both extensions are needed for press PDFs. Hosting also needs working WordPress image processing.
   - Back up an existing site before switching themes.
2. **Install and activate the theme.**
   - Upload a theme ZIP under **Appearance > Themes > Add New**, or copy the theme into `wp-content/themes`.
   - Include the compiled `assets/css/style.css`, fonts, JavaScript, and `vendor` directory. Node.js is not required on the production server.
   - Activate **TEDx Regensburg**.
3. **Save permalinks.**
   - Open **Settings > Permalinks** and click **Save Changes** to register the custom content URLs.
4. **Set up the homepage.**
   - Create a page and select it as the static homepage under **Settings > Reading**.
   - Insert the **TEDx Homepage Layout** pattern and replace its sample text, images, years, and links.
   - The homepage hero comes from the theme template; it is not a Gutenberg block.
5. **Configure identity, hero, and navigation.**
   - Under **Appearance > Customize > Site Identity**, choose the main logo. An optional mobile logo is also available.
   - Under **TEDx Event Settings > Hero & Navigation**, configure the active event year, navbar ticket button, desktop/mobile WebM and MP4 videos, preview stills, and hero button.
   - Create and assign a menu to **Primary Navigation** under **Appearance > Menus**.
6. **Configure footer and legal pages.**
   - Create an imprint and privacy-policy page. Select the latter under **Settings > Privacy**.
   - Assign a menu to **Footer Legal Menu**. Edit labels, links, and order in **Appearance > Menus**.
   - Configure social links, footer navigation visibility, and the newsletter endpoint under **TEDx Event Settings > Footer & Social Links**. A newsletter endpoint needs an actual configured provider; the theme does not supply a mailing service.
7. **Add your content and check the site.**
   - Create events and directory entries using the guides below.
   - Regenerate thumbnails for existing uploads if the theme's image sizes are missing.
   - Check mobile navigation, links, consent controls, and PDFs before publishing.

## Using the Blocks

Open a page or event in the block editor, click **+**, and search for **TEDx**. Select a block to edit its controls in the sidebar or directly in the canvas. Save/update the page to publish changes.

Many blocks expose a **Section ID** for anchor links. Use distinct IDs when placing multiple copies on one page.

### Starter patterns and page layout

Under the inserter's **Patterns > TEDx** category:

- **TEDx Homepage Layout:** Event, Gallery, About, Speakers, Stats, featured partner, and sponsor grid.
- **TEDx Event Page Layout:** Event, Speakers, Timetable, featured partner, sponsor grid, and Venue.
- **TEDx About Us Layout:** Text Section, Team, and Speaker Coaches.

Patterns are starting points, not finished content. In particular, update sample dates, the fixed speaker year, and placeholder URLs.

On the default page template, a **Full width** block removes the outer rounded article card. This also works for blocks nested inside groups or synced patterns. Page titles, featured images, and other content remain centered; pages without full-width blocks retain the article card.

### TEDx Event (`tedx/event`)

Creates a two-column event pitch and artwork card.

1. Set the eyebrow, title, description, and section ID.
2. Configure ticket and "Show More" labels, URLs, and visibility.
3. Select a linked event to provide fallback destinations for "Show More" and the clickable card.
4. Choose the card image, optional label, clickability, and column order.

Explicit button/card URLs override the linked event's URL. Linking an event does **not** automatically copy its title, description, or artwork into the block.

### TEDx Speakers (`tedx/speakers`)

Displays published speaker entries for a year.

1. Choose a fixed year or **event year** when used on an event.
2. Set the heading and whether to display the year.
3. Optionally enable the event button and configure its destination.
4. Configure or hide the Call-for-Speakers card, including its application link.

The selected year must match the speakers' **Event Year**. Event-year mode reads the current event's metadata; keep the block's fallback year correct too.

### TEDx Speaker Coaches (`tedx/speaker-coaches`)

Displays published speaker coaches. Set the heading and optional year filter; leave the year empty to show all coaches. Use **Show More** to control expandable biographies, together with the individual coach's card setting.

### TEDx Gallery (`tedx/gallery`)

Creates a scrolling, two-row gallery with a lightbox.

1. Click **Edit gallery images** in the block or toolbar.
2. Select, add, remove, or reorder Media Library images.
3. Maintain useful alternative text in the selected images.

The grid loads small responsive thumbnails. Clicking opens a larger responsive image; the browser chooses the appropriate candidate rather than always downloading the original.

### TEDx Press Releases (`tedx/press-releases`)

Displays regular WordPress posts from a selected category.

1. Select the press category.
2. Set the heading, post count (1-24), and excerpt length (10-80 words).
3. Customize the post-link and PDF-download labels.

Newest published posts appear first; password-protected posts are excluded. See **Managing Press Posts** for the publishing workflow.

### TEDx Timetable (`tedx/timetable`)

Creates an event schedule. Set the title and optional eyebrow, edit the time and title of each item, and highlight important entries such as opening, breaks, or closing. Add/remove items as needed and toggle the connecting line. The schedule is edited in the block, not generated from event metadata.

### TEDx About (`tedx/about`)

Creates an image-and-text introduction. Choose the image and its left/right position, set the eyebrow and description, and toggle the logo and action button. Enter the button label and destination. Without an image, a TEDx graphic placeholder is shown.

### TEDx Stats (`tedx/stats`)

Creates statistic cards. Edit each item's value, label, and icon.

- Choose a built-in icon or **Custom SVG**.
- Use **Upload or select SVG icon** to choose a trusted Media Library SVG.
- Replace/remove the upload or switch back to a built-in icon.

Custom icons use a 24px TEDx-green CSS mask. Transparent areas remain transparent; opaque backgrounds become part of the silhouette. The theme supports SVG uploads but does not comprehensively sanitize every uploaded SVG: only upload trusted files.

### TEDx Sponsors (`tedx/sponsors`)

Stores partner/sponsor entries inside the block; there is no separate sponsor directory.

1. Choose **Featured Partner** for one prominent partner or **Logo Grid** for multiple sponsors.
2. Set the section ID, eyebrow, title, and description.
3. Add logos from the Media Library and enter each company name and optional website link.

Logos retain their proportions rather than stretching or cropping. Remove excess whitespace from source artwork if a logo looks too small.

### TEDx Text Section (`tedx/text-section`)

A styled container for standard Gutenberg content. Insert headings, paragraphs, lists, and other supported inner blocks. Set an optional section ID. Use this for longer editorial pages rather than entering long text in a short About block.

### TEDx Team (`tedx/team`)

Displays published entries from the **Team** directory. Set the heading and section ID; manage names, portraits, roles, and links in the directory rather than the block. Ordering uses entry order, then title.

### TEDx Venue (`tedx/venue`)

Displays a venue address, consent-gated map, and directions link.

1. Set the heading, venue name, address, and section ID.
2. Leave embed HTML empty to generate a map from the address, or paste Google's map iframe code.
3. Set the directions URL/button label and visibility.
4. Use **Sync with event** on an event page to read venue metadata.

When synchronizing, a non-empty event venue name enables the metadata overrides; explicit block map HTML and directions URLs take precedence over event values. Custom iframe code is reduced to an approved HTTPS Google Maps URL; scripts and other supplied markup are not executed.

## Managing Events

1. Open **Events > Add New**.
2. Set the title and URL slug.
3. Complete the **Event Year** and location metadata: venue name, address, optional Maps iframe, and directions link.
4. Edit the default block layout: Event, Speakers, Timetable, featured partner, sponsor grid, and Venue.
5. Set the Speakers block to event-year mode, then ensure your speaker entries use the same year.
6. Edit the Event block's pitch, ticket URL, and artwork explicitly. These are block content, not automatically populated from the event record.
7. Publish and add the event to navigation or link it from the homepage Event block.

For a new annual event, also update the homepage's speaker filter and the Customizer's active year/navbar ticket link. Existing page blocks are not automatically rewritten when these settings change.

## Managing Press Posts

1. Create a category under **Posts > Categories**, for example **Press Releases**.
2. Create a normal post with a title, body, featured image, and that category.
3. Add an excerpt if you want specific summary text; otherwise the block derives a shortened summary.
4. Publish the post.
5. Place a **TEDx Press Releases** block on the press page and select the category.

The post link opens the article; **Download PDF** generates an A4 PDF on demand. PDFs contain the title, date, article link, and supported saved text/HTML. They do not reproduce the complete website design, images, videos, dynamic block output, or executable shortcodes.

Deploy `vendor` for Dompdf and enable PHP DOM/mbstring. Missing dependencies produce an explicit error instead of a PDF. The download endpoint accepts published, non-password-protected regular posts; the category controls the block listing, not PDF access authorization.

## Managing Speakers, Speaker Coaches, and Team Members

### Speakers

1. Open **Speakers > Add New**.
2. Use the person's name as the title, add the biography as content, and set a portrait as the featured image.
3. Complete **Topic / Field**, **Talk Language**, **Event Year**, and optional LinkedIn and YouTube URLs.
4. Configure the individual **Show More** card setting and publish.

Year-filtered blocks query the entry's metadata, not its publication date. Speaker pages display the biography and, when provided, a consent-gated YouTube talk. YouTube links can use standard watch, short-link, embed, shorts, or live video URLs.

### Speaker Coaches

Open **Speaker Coaches > Add New**, enter the name and biography, set a featured portrait, and complete the year, LinkedIn URL, and Show More setting. Publish, then check the Speaker Coaches block's year filter.

### Team Members

Open **Team > Add New**, enter the name, set a portrait, and complete the role and optional LinkedIn URL. Publish to include the person in the Team block. Adjust the entry's order in page attributes where available.

Only published entries appear in directory grids. Speakers/coaches use menu order for sorting; the Team grid uses order and title. Set useful portrait alt text in the Media Library and keep years consistent.

## Cookies Management

The theme provides **per-embed click-to-load**, not a global cookie banner or full consent-management platform.

- Maps and YouTube initially show German, translatable disclosures, provider details, and privacy links.
- **Einwilligen und ... laden** loads only the selected embed.
- No provider iframe, remote preview, or provider script is emitted by these components before acceptance.
- YouTube uses `youtube-nocookie.com`, but still requires consent; that domain is not a guarantee of zero cookies or data processing.
- **Inhalt deaktivieren / Einwilligung widerrufen** removes the iframe and restores the placeholder.
- Choices are not saved in cookies, localStorage, or server-side consent logs. Reloading requires consent again.
- Removing an embed cannot recall transmitted data, erase Google's cookies, or cancel every request already in flight.
- Without JavaScript, disclosure text and external links remain available.

Coverage includes the Venue block, speaker talk videos, and Google Maps/YouTube iframes in standard post content: raw HTML, Gutenberg embeds, and `[embed]` shortcode/oEmbed output. Other providers, analytics, and embeds injected later by JavaScript are **not covered**.

After consent, cross-origin iframe requests send the site's origin as the referrer, not the page path/query string. YouTube requires this identification; suppressing it entirely causes player error 153.

### Administrator checklist

1. Select the privacy-policy page under **Settings > Privacy** and keep the imprint/privacy links accessible.
2. Update that policy for actual providers, purposes, legal bases, storage, international transfers, and withdrawal.
3. If a consent plugin exists only for these embeds, remove the redundant layer after testing. If it manages analytics or other services, review those separately; the theme does not synchronize consent with the plugin.
4. Test in a fresh private window: no provider requests before acceptance, independent consent per embed, keyboard operation, withdrawal, and reset on reload.
5. Check older pages as well as newly created blocks, and clear page caches after deployment.

**Legal limitation:** technical blocking is not certification of German legal compliance. Review the disclosures and the method of demonstrating consent against GDPR and section 25 TDDDG with qualified advice. The absence of a theme consent cookie does not mean that every other site feature is cookie-free.

## Technical Details

### Loading Hierarchy

| Resource | Behavior |
|---|---|
| Header logo | Eligible local SVGs are sanitized and inlined, avoiding an image request. Image fallbacks are eager and `fetchpriority="high"`, with viewport-specific preloads registered at head priority 1. |
| Hero still | Matching desktop/mobile poster is preloaded at head priority 2 and displayed eagerly at high priority; uses a generated large image when available. |
| Main font | Latin Inter WOFF2 is preloaded at head priority 3 with CORS; Latin Extended loads as needed. |
| Event artwork | Eager, high-priority image, without its own head preload. |
| Other images | Normal (`auto`) priority. Shared block images retain lazy loading, asynchronous decoding, and responsive candidates. |
| Hero video | `preload="none"`; matching sources are attached after window load. Skipped for reduced-motion or Save-Data. The still stays visible until playback, then switches without a fade. |
| Maps / YouTube | No active iframe until the visitor explicitly consents. |

Head hook order aids early discovery; it is not a guarantee of network scheduling. HTML supports only `high`, `auto`, and `low`, and does not support `fetchpriority` on video.

### Performance and Media

- Inter is self-hosted as Latin and Latin Extended variable WOFF2 subsets (weights 400-900, `font-display: swap`). The OFL license is shipped with the fonts; there are no Google Fonts requests.
- Tailwind styles are compiled into `assets/css/style.css`. The parent root `style.css` contains metadata; a child-theme stylesheet is enqueued when a child theme is active. Blocks may also load their own stylesheets.
- Main, consent, and block view scripts use deferred loading. The gallery's interactive script is front-end-only.
- WordPress emoji detection assets are removed.
- The hero uses a stable `100vh` height and static bottom button padding: 7rem below 768px and 3rem on larger screens. Check actual mobile browser toolbar clearance; these values are not a universal device guarantee.
- Inline logo SVGs must be local, readable, at most 50 KB, and supported by the sanitizer. Unsupported SVGs fall back to images. Footer/About logo copies remain normal-priority images.

Retained generated image sizes:

| Size | Dimensions / use |
|---|---|
| `thumbnail` | Standard 150px thumbnail/admin previews; WordPress setting may vary. |
| `medium` | Standard 300px bounding box; gallery/sponsor previews. |
| `medium_large` | Standard 768px width; responsive candidate. |
| `large` | Standard 1024px bounding box; larger cards, About, portraits, posters, lightbox. |
| `tedx-speaker` | 400 x 400, cropped; speaker/coach cards. |
| `tedx-gallery-thumb` | 480px wide, uncropped; intermediate responsive gallery candidate. |

A height of `0` means unconstrained proportional height, not an empty image. WordPress's settings and the upload's dimensions affect which files are actually generated. The theme no longer registers `post-thumbnail` or `tedx-hero`, and unregisters `1536x1536` and `2048x2048`. High-density screens may therefore select the original.

Regenerate missing sizes for older uploads using a thumbnail tool or `wp media regenerate --yes`. Back up before deleting obsolete sizes: older content may link directly to those files. Original uploads are not compressed or transcoded by the theme. Use optimized WebP/AVIF photos and short WebM/fast-start MP4 videos.

### Hosting and Measurement

- Enable compression and appropriate public caching for public assets. Use long immutable caching only when file URLs change on updates; do not apply it blindly to replaceable uploads.
- Cache anonymous HTML where appropriate, but exclude personalized/account pages. Review cache headers with the host rather than overriding them globally.
- If using WP Fastest Cache, test minification/combination settings and clear its page and asset caches after deployment.
- Avoid unused icon/font plugins and unnecessary external resources.
- Run Lighthouse for mobile and desktop while logged out. Compare multiple runs and inspect LCP, CLS, TBT, and network requests rather than promising a fixed score.

## Developer Details

### Build and Dependencies

Use Node.js/npm for Tailwind and PHP/Composer for the PDF dependency.

```powershell
npm ci
npm run dev
```

`dev` watches CSS/template changes. For deployment:

```powershell
npm run build
composer install --no-dev --prefer-dist --optimize-autoloader
```

Use the committed lockfiles. Composer installation restores the runtime PDF libraries when `vendor` is absent; do not omit them from deployment. Production does not require Node.js or `node_modules`.

### Where to Make Changes

| Area | Files |
|---|---|
| Theme setup, image sizes, asset loading | [functions.php](functions.php) |
| Brand tokens / scanned template paths | [tailwind.config.js](tailwind.config.js) |
| CSS source / compiled output | [assets/css/input.css](assets/css/input.css), [assets/css/style.css](assets/css/style.css) |
| Block metadata, editor controls, PHP output | [blocks/](blocks/) (`block.json`, `index.js`, `render.php`; optional styles/view scripts) |
| Block registration / shared image and card helpers | [inc/blocks.php](inc/blocks.php) |
| Patterns / default event layout | [inc/block-patterns.php](inc/block-patterns.php) |
| Directories and metadata fields | [inc/custom-post-types.php](inc/custom-post-types.php) |
| Customizer controls | [inc/customizer.php](inc/customizer.php), [inc/customizer-additions.php](inc/customizer-additions.php) |
| Logo rendering / hero media / font preload | [inc/template-tags.php](inc/template-tags.php), [inc/hero-media.php](inc/hero-media.php), [inc/performance.php](inc/performance.php) |
| Consent markup, content filters, browser behavior | [inc/embed-consent.php](inc/embed-consent.php), [assets/js/embed-consent.js](assets/js/embed-consent.js) |
| Press PDF generation | [inc/press-release-pdf.php](inc/press-release-pdf.php) |
| Page templates and hero | Root PHP templates and [template-parts/](template-parts/) |

Edit CSS source rather than the minified output, then rebuild. Rebuild after introducing Tailwind classes in PHP/JS too. Block editor scripts are plain JavaScript; there is no separate bundler command. Update directly related asset dependency/version files when needed.

Keep block attributes compatible with saved content. Test both old entries and new ones when changing render code. Use the existing `tedx-regensburg` textdomain for user-facing strings. Keep complex child selectors in CSS rather than HTML class attributes to avoid WordPress text-formatting issues.

### Validation and Deployment

The repository's tests are standalone PHP scripts and Node's built-in test runner:

```powershell
Get-ChildItem tests\*.php | ForEach-Object {
    php $_.FullName
    if ($LASTEXITCODE -ne 0) { throw "PHP test failed: $($_.Name)" }
}
$testFiles = (Get-ChildItem tests\*.test.js).FullName
node --test $testFiles
git diff --check
```

Also run `php -l` on changed PHP files. Tests cover media, consent, layout, gallery/editor behavior, icons, and footer links; they do not replace testing inside WordPress.

Deploy matching PHP, JavaScript, block assets, compiled CSS, fonts, and dependencies together. Clear caches, then check the editor and front end on mobile/desktop, legacy embeds, consent/network behavior, PDFs, and thumbnail selection. Do not deploy `node_modules`, `.git`, or local development artifacts.

## Warning: AI-Generated Theme and Documentation

**This theme was AI-generated/AI-assisted, although its visual design is based on a hand-made Figma design. This README is also AI-generated.**

Neither code nor documentation should be assumed correct, secure, accessible, or legally compliant simply because it exists here. Review changes, verify behavior in the deployed WordPress environment, and obtain appropriate professional review for security, privacy, and legal requirements. The design origin does not guarantee pixel-perfect implementation.
