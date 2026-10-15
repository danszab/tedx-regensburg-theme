# TEDx Regensburg - WordPress Theme

A modern, standards-compliant, and high-performance WordPress theme for **[TEDx Regensburg](https://tedxregensburg.com)**. Built based on the official Figma design system using **Tailwind CSS**, native **Gutenberg blocks**, dynamic Custom Post Types (Events, Speakers, Speaker Coaches, Team Members), and WordPress block patterns.

---

## 🎯 Key Features

- **100% Figma Pixel-Perfect Implementation:**
  - **Sticky Blurred Navigation:** Responsive desktop navbar and mobile drawer menu with language indicator (`DE | EN`) and Ticket CTA.
  - **Full-Screen Homepage Video Hero:** Looping background video with mobile & desktop video sources, safe-area button placement, and smooth scroll anchor.
  - **Modular Gutenberg Blocks (`tedx/*`):**
    - `tedx/event`: Event pitch description, action buttons (Tickets, Show More), and theme graphic card with pill badge.
    - `tedx/speakers`: Dynamic speaker lineup queried by event year with Call-for-Speakers card and event button.
    - `tedx/speaker-coaches`: Speaker coach lineup with optional year filtering and bio truncation toggles.
    - `tedx/gallery`: Full-bleed, 2-row image gallery slider with smooth scrolling and lightbox modal.
    - `tedx/press-releases`: Category-filtered post cards with featured images, truncated excerpts, post links, and automatically generated PDF downloads.
    - `tedx/timetable`: Visual schedule and timeline block with highlight dots.
    - `tedx/about`: Stylized stage photo/fallback graphic with mission text and learn more CTA.
    - `tedx/stats`: Squircle counter cards with customizable icons (built-in vectors or custom uploads) and numbers.
    - `tedx/sponsors`: Featured partner or multi-column logo grid with individual logo uploads and links directly in the block editor (no database required).
    - `tedx/text-section`: Container block for rich content and headings styled with the exact Figma About Us typography.
    - `tedx/team`: Automatic 6-column team member grid querying the team directory.
    - `tedx/venue`: Venue location map with customizable coordinates/embed, address, and Google Maps directions link.
  - **Ready-to-Use Block Patterns:** Pre-packaged patterns for Homepage, Event Page, and About Us page layouts.
  - **Dynamic Responsive Layouts:** Max width 1000px on standard content containers. 2-column layouts smoothly collapse into 2 rows on mobile/tablet screens. The Gallery slider intentionally overflows to full-bleed.
- **Zero-Dependency Production Build:** Pre-compiled CSS located at `assets/css/style.css`.
- **Bundled PDF Generation:** Dompdf is included in `vendor/`; PDF downloads require PHP 7.4+ with the DOM and mbstring extensions.
- **Developer Ready:** Tailwind CSS CLI workflow available via `package.json` and `tailwind.config.js`.

---

## Media Loading Priorities

- **Header logo:** local SVG logos (up to 50 KB, without `<style>` or entity declarations) are sanitized and inlined in the header, so the LCP logo needs no network request. Other logos fall back to an eager `fetchpriority="high"` image with viewport-specific head preloads emitted before the hero poster preloads. Footer and About-block copies remain cacheable normal-priority images.
- **Hero still:** responsive, high-priority image preloads and an eager `fetchpriority="high"` image.
- **Hero video:** `preload="none"` with `data-src` sources; a small inline script attaches the matching mobile/desktop source after the window `load` event, then shows the video over the still immediately once playing, without a fade. Skipped for `prefers-reduced-motion` and Save-Data; without JavaScript, the still remains. Browsers do not support `fetchpriority` on `<video>`.
- **Event card image:** `loading="eager"` and `fetchpriority="high"`, without a head preload.
- **Other images:** normal (`auto`) fetch priority, retaining existing lazy loading and responsive image sizes. WordPress loading optimizations do not automatically promote unrelated images to high priority.

HTML only provides `high`, `auto`, and `low` fetch priorities, not separate "top" or "very high" levels. Earlier logo and hero preloads aid discovery; actual network scheduling remains browser-controlled.

Run the standalone regression checks with `php tests/media-priorities.php`, `php tests/hero-media.php`, and `php tests/block-images.php`.

## Performance

The theme itself:

- **Fonts:** Inter is self-hosted (`assets/fonts/`, variable 400–900, Latin + Latin Extended subsets, SIL OFL) with `font-display: swap`; the Latin subset is preloaded. No Google Fonts requests or preconnects.
- **CSS:** a single compiled stylesheet (`assets/css/style.css`); the root `style.css` holds only theme metadata and is enqueued only for child themes. Rebuild with `npm run build` after changing `assets/css/input.css` or template classes.
- **JavaScript:** `main.js` and block view scripts (gallery lightbox) load with `defer`; the gallery script is front-end only. WordPress emoji detection script and styles are removed.

WP-admin / server checklist (not controlled by the theme):

1. **Plugins:** deactivate *JVM Rich Text Icons* (its render-blocking Font Awesome CSS is unused by the theme), or limit it to pages that use icons.
2. **WP Fastest Cache:** enable Minify HTML, Minify CSS, Combine CSS (one bundle), Gzip, and Browser Caching; clear all caches after each theme deployment.
3. **Server/CDN:** enable Brotli or gzip; serve versioned theme assets and `wp-content/uploads` with `Cache-Control: public, max-age=31536000, immutable` (not `private`); do not send `no-store` for anonymous HTML so the page cache and back/forward cache work.
4. **Media:** keep hero videos short and compressed (WebM + fast-start MP4, ideally under 1 MB on mobile); upload WebP/AVIF stills.
5. **Verify:** rerun Lighthouse (mobile + desktop) and check the LCP element in DevTools → Performance.

---

## 📁 File Structure

```text
tedx-regensburg-theme/
├── style.css                 # Theme header & WordPress metadata
├── functions.php             # Enqueue scripts/styles, theme supports, and module loader
├── header.php                # Sticky navbar & mobile navigation drawer
├── footer.php                # Site footer, social icons & newsletter subscription
├── front-page.php            # Homepage template (video hero + dynamic content)
├── page-about.php            # About Us page template (renders block content)
├── single-tedx_event.php     # Event page template (renders block content)
├── single-speaker.php        # Template for single Speaker pages
├── single-speaker_coach.php  # Template for single Speaker Coach detail pages
├── archive-speaker.php       # Archive overview for all speakers
├── page.php                  # Default page template (Legal, Privacy, etc.)
├── single.php                # Default blog post template
├── 404.php                   # 404 Error page in TEDx branding
├── inc/
│   ├── blocks.php            # Block registration, category, editor assets & shared helpers
│   ├── block-patterns.php    # Block patterns for Homepage, Event Page, and About Us
│   ├── custom-post-types.php # CPT registration for Events, Speakers, Coaches, Team
│   ├── customizer.php        # Customizer controls (Navbar, Hero video, Social, Location)
│   ├── customizer-additions.php # Video hero settings
│   ├── template-tags.php     # Helper functions (SVG icons, logos, formatting)
│   └── nav-walker.php        # Custom Tailwind CSS Nav Walker
├── blocks/
│   ├── shared/               # Shared ES5 editor helpers
│   ├── tedx-event/           # Event pitch & card block
│   ├── tedx-speakers/        # Dynamic speakers block
│   ├── tedx-speaker-coaches/ # Speaker coaches block
│   ├── tedx-gallery/         # Full-width 2-row gallery block
│   ├── tedx-timetable/       # Timetable / schedule block
│   ├── tedx-about/           # About teaser block
│   ├── tedx-stats/           # Counter stats block
│   ├── tedx-sponsors/        # Sponsors & partner logos block
│   ├── tedx-text-section/    # Rich text section with About Us typography
│   ├── tedx-team/            # Team members grid block
│   └── tedx-venue/           # Venue map and address block
├── template-parts/
│   ├── hero-video.php        # Looping full-screen video hero
│   └── content.php           # Default content loop
├── assets/
│   ├── css/
│   │   ├── input.css         # Tailwind source CSS with .tedx-prose rules
│   │   └── style.css         # Compiled production CSS
│   └── js/
│       └── main.js           # Menu toggles, mobile drawer, smooth scroll
├── tailwind.config.js        # Tailwind configuration and TEDx brand tokens
├── package.json              # Build scripts
└── README.md                 # Theme documentation & guide
```

---

## 🚀 Installation & First-Time Setup

1. **Upload & Activate Theme:**
   - Copy the `tedx-regensburg-theme` directory into `/wp-content/themes/` (or upload as `.zip` in **Appearance → Themes → Add New**).
   - Click **Activate** under **TEDx Regensburg**.
2. **Flush Permalinks (Crucial Step for Event URLs):**
   - Go to **Settings → Permalinks** in the WordPress admin panel.
   - Without making any changes, scroll to the bottom and click **Save Changes**. *(This registers the `/events/...` and `/speakers/...` URL rewrite rules in WordPress).*
3. **Setup Navigation Menu:**
   - Navigate to **Appearance → Menus**.
   - Create a primary menu and assign it to the **Primary Navigation** location.
   - Example menu items:
     - Home (`/`)
     - 2026 Event (`/events/tedx-regensburg-2026/`)
     - About Us (`/about-us/`)
     - Speakers (`#speakers`)
4. **Configure Header & Video Hero:**
   - Navigate to **Appearance → Customize → TEDx Event Settings → Hero & Navigation**.
   - Upload desktop and mobile WebM / MP4 video files and poster images for the homepage hero.

---

## 🧱 Using the TEDx Gutenberg Blocks & Patterns

When editing pages or events in the WordPress Block Editor, click the **+** (Add block) button and scroll to the **TEDx** category, or search for `TEDx`.

### Available Block Patterns
To quickly assemble a full page:
1. In the editor, open the block inserter and click the **Patterns** tab.
2. Select the **TEDx** category:
   - **TEDx Homepage Layout:** Inserts Event Pitch → Gallery → About → Speakers → Stats → Partner → Sponsors Grid.
   - **TEDx Event Page Layout:** Inserts Event Pitch → Speakers (linked to event year) → Timetable → Partner → Sponsors Grid.
   - **TEDx About Us Layout:** Inserts Rich Text Section → Team Grid → Speaker Coaches.

### Managing Sponsors & Partners in Blocks
Sponsors are no longer stored in a separate database post type. Instead, simply insert a **TEDx Sponsors** block:
- Set the layout to **Featured Partner** for a single large sponsor logo, or **Logo Grid** for a 3-column sponsor list.
- Click **+ Add Sponsor Logos** to select images from your Media Library.
- Set the company name and optional website link for each logo in the sidebar inspector.
- Logos fit proportionally inside a full-width, 60px-high area in grid cards (40px for the featured partner), with identical sizing for linked and unlinked images. Images are not stretched or cropped. Transparent padding or whitespace inside the uploaded image remains part of the image; trim the source artwork if its visible logo is still too small.

### Custom Stats Icons
In a **TEDx Stats** block, set an item's **Icon** to **Custom SVG**, then use **Upload or select SVG icon** to choose a Media Library SVG. You can replace or remove it, or switch back to a built-in icon. Custom icons display at 24px and automatically use TEDx green (`#2ad17e`) through a CSS alpha mask, preserving transparent areas without modifying or inlining the SVG. Use trusted SVG files with transparent backgrounds; an opaque background becomes part of the icon silhouette.

### Managing Footer Links
Footer navigation uses WordPress's built-in menu editor; no code changes are needed:
1. Open **Appearance → Menus**, create a menu (for example, **Footer Links**), and assign it to **Footer Legal Menu** under **Menu Settings → Display location**.
2. Add existing pages or use **Custom Links** to enter a URL and link title (for example, **Impress** or **Legal**).
3. Expand an item and edit its **Navigation Label** to change the displayed title. Use **Remove** to delete a link, or drag items to reorder them, then click **Save Menu**.

Under **Appearance → Customize → TEDx Event Settings → Footer & Social Links**, use **Show footer navigation links** to hide or show the entire link area. This control also links to the menu editor. Existing assigned footer menus are preserved. If no menu is assigned, the existing Imprint and Privacy defaults remain; turn off the checkbox to remove all links without showing those defaults.

### Editing Gallery Images
Select a **TEDx Gallery** block and click **Edit gallery images** in the block itself or its toolbar. The media dialog opens with the current images selected, allowing you to add, remove, or reorder them. Update the gallery and save the page to keep your changes. Existing galleries use the same controls when reopening the page in the editor.

### Image Loading
Large block headings share the Speakers year typography: Helvetica Neue/Inter, regular weight (400), 48px below 640px and 60px from 640px, line-height 1, and -0.03em letter spacing. This covers event, gallery, team, venue, timetable, sponsors/partner, press releases, speaker coaches, and the text section's main heading. Small eyebrow labels, card titles, logos, and text-section subheadings keep their distinct styles.

Gallery thumbnails and sponsor logos use WordPress's generated **medium** image size with responsive `srcset`/`sizes`, intrinsic dimensions, lazy loading, and asynchronous decoding. Gallery uploads also get an uncropped 480px-wide **tedx-gallery-thumb** size, so high-density phones pick it instead of the 768px version; after deploying, run a thumbnail regeneration plugin (e.g. *Regenerate Thumbnails*) once so existing uploads receive it. The About block image uses the **large** size with `srcset`, a `(min-width: 1024px) 600px, 100vw` hint, and lazy loading. Speaker and speaker-coach portraits retain the generated **tedx-speaker** size with a `122px` display-size hint, so browsers do not select oversized images for the small cards.

Gallery lightbox sources are only assigned when an image is opened, using the **large** size and responsive candidates for sharper screens. The gallery layout, sponsor links, and saved image selections are unchanged. URL-only Media Library images are resolved to attachments when possible; external images and SVGs without generated sizes retain their original URLs.

The theme retains **thumbnail**, **medium**, **medium_large**, **large**, **tedx-speaker**, and **tedx-gallery-thumb**. The unused **post-thumbnail** and **tedx-hero** sizes are no longer registered; WordPress's **1536x1536** and **2048x2048** sizes are explicitly unregistered to reduce upload processing and storage. High-resolution displays may select the original instead of those larger derivatives.

WordPress must already have generated the smaller files. If older uploads are missing image sizes (especially **tedx-speaker** and **tedx-gallery-thumb**, registered by this theme), regenerate their thumbnails using a WordPress thumbnail-regeneration tool or WP-CLI (`wp media regenerate --yes`). Unregistering sizes does not delete existing files; back up uploads before using a regeneration tool's option to remove obsolete sizes, since older page content may link to those files directly. Original uploads are not modified or compressed by the theme.

Gallery and speaker preview requests are explicitly low priority as well as lazy-loaded; the browser decides when nearby lazy images should load. WordPress generates resized/re-encoded raster images during upload, not each page view. Missing sizes can still fall back to the full source, and SVGs do not have raster previews. For further compression, use an image optimization plugin to generate optimized JPEG/WebP/AVIF derivatives and regenerate existing uploads; the server's GD/Imagick support determines available output formats.

The homepage preloads only the matching mobile/desktop hero poster at high priority, using its generated **large** version when available. Poster images load eagerly. A single video selects media-qualified mobile or desktop sources (attached after page load) instead of downloading two CSS-hidden videos. Videos are not transcoded by WordPress; use optimized WebM/MP4 files, with MP4 fast-start enabled. Browser scheduling and autoplay restrictions still apply.

### Managing Press Releases
1. Create a category under **Posts → Categories** (for example, **Press Releases**).
2. Publish regular WordPress posts in that category, adding a featured image and optionally a custom excerpt.
3. Insert the **TEDx Press Releases** block on a page and select the category in its sidebar.
4. Adjust the heading, number of posts (1–24), excerpt length (10–80 words), and button labels as needed.

On the default page template, block alignment controls the page layout. Set any block to **Full width** to remove the outer rounded article card and let full-width sections span the page. This applies to all block types, including blocks inside groups and synced patterns. The page title, featured image, and non-full-width content remain centered within 1000px. Pages with only **None** alignment keep the standard article card; the press block has no special exception. The homepage keeps its existing full-width layout.

The press grid uses its containing block's width for spacing, so it can also be placed inside narrower editor groups without a viewport-sized left gutter.

The newest published posts appear first. Password-protected posts are excluded. Cards use the speaker-card dark background and 32px corners, with up to two rows in a horizontally scrollable grid aligned to the gallery's 1000px content area. Keyboard users can focus and scroll the grid. A branded placeholder appears when a post has no featured image.

**Show more** opens the post. **Download PDF** downloads an A4 document with the title, date, full saved article text and basic formatting, and source link; it is not a print-dialog shortcut. PDFs intentionally omit images, embeds, and executable/dynamic blocks or shortcodes, and never fetch remote resources. Both logged-in and anonymous visitors can download published, non-password-protected posts; private and unpublished posts are rejected.

Deploy `vendor/` together with the theme (including the bundled dependency licenses). No Composer command is required on the web server. For dependency maintenance, use `composer install --no-dev --prefer-dist --optimize-autoloader` with the committed lockfile, or `composer update --no-dev` to deliberately update it. Missing PDF dependencies/extensions and generation failures return explicit errors instead of broken downloads.

PDF links use a normal request; the server's `Content-Disposition` header supplies the PDF filename. This avoids forcing HTML errors into downloads named after `admin-post.php` and allows dependency errors to be read in the browser. On browser-based WordPress Playground installations, the PHP runtime must also provide DOM and mbstring.
