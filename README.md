# TEDx Regensburg - WordPress Theme

A modern, standards-compliant, and high-performance WordPress theme for **[TEDx Regensburg](https://tedxregensburg.com)**. Built based on the official Figma design system using **Tailwind CSS**, modular PHP template parts, dynamic Custom Post Types (Events, Speakers, Team Members), and WordPress Customizer integration.

---

## 🎯 Key Features

- **100% Figma Pixel-Perfect Implementation:**
  - **Sticky Blurred Navigation:** Responsive desktop navbar and mobile drawer menu with language indicator (`DE | EN`) and Ticket CTA.
  - **Full-Screen Homepage Hero:** Uploadable background and poster images, animated background pan, and topographic branding.
  - **Event Pitch Section:** Event summary, pill badges, and independently configurable Ticket and "Show More" action buttons, backed by a selectable Event Pitch Card.
  - **About Section & Dedicated "About Us" Page (`page-about.php`):** Clean Figma layout featuring mission statement, core statistics, and team member grid with LinkedIn badges.
  - **Dedicated Event Pages (`single-tedx_event.php`):** Full subpage template for past and upcoming events including dynamic pitch details, speaker lineups filtered by year, and venue location with interactive Google Maps link/embed.
  - **Dynamic Speaker & Team Member Directories:** Custom Post Types with custom meta fields (topic, language, LinkedIn, YouTube talk link), responsive multi-column grids, and clean fallbacks.
  - **"Watch the Talk" YouTube Integration:** Speakers with a YouTube URL automatically display a "Watch the Talk" button on their card.
  - **Interactive Event Cards:** Rounded squircle cards with custom images, smooth hover zoom animations, pill badges, and direct database linking.
  - **Custom Gutenberg Gallery Block (`tedx/gallery`):** Full-width, 2-row image gallery slider for use inside page/post content.
  - **Footer:** Social badges (LinkedIn, Instagram, Facebook), newsletter signup form, and TEDx licensing disclaimer.
- **Zero-Dependency Production Build:** Pre-compiled CSS located at `assets/css/style.css`. Ready to activate immediately out-of-the-box.
- **Developer Ready:** Tailwind CSS CLI workflow available via `package.json` and `tailwind.config.js`.

---

## 📁 File Structure

```text
tedx-regensburg-theme/
├── style.css                 # Theme header & WordPress metadata
├── functions.php             # Enqueue scripts/styles, theme supports, and module loader
├── header.php                # Sticky navbar & mobile navigation drawer
├── footer.php                # Site footer, social icons & newsletter subscription
├── front-page.php            # Homepage template orchestrating all main sections
├── page-about.php            # Custom page template for "About Us" (Team grid & mission)
├── single-tedx_event.php     # Template for single Event pages
├── single-speaker.php        # Template for single Speaker pages
├── archive-speaker.php       # Archive overview for all speakers
├── page.php                  # Default page template (Legal, Privacy, etc.)
├── single.php                # Default blog post template
├── 404.php                   # 404 Error page in TEDx branding
├── inc/
│   ├── custom-post-types.php # CPT registration for Events, Speakers, Team & Meta Boxes
│   ├── customizer.php        # WordPress Customizer controls and panels
│   ├── customizer-additions.php # Hero background/poster image controls
│   ├── customizer-helpers.php# Helper functions for dynamic event dropdown choices
│   ├── template-tags.php     # Helper functions (SVG icons, logos, formatting)
│   ├── gallery-render.php    # Server-side render callback for the Gutenberg gallery block
│   └── nav-walker.php        # Custom Tailwind CSS Nav Walker
├── template-parts/
│   ├── hero.php              # Full-screen hero section with background/poster image
│   ├── event-pitch.php       # Event pitch, descriptions, and action buttons
│   ├── event-card.php        # Reusable Event Card component
│   ├── about.php             # About TEDx snippet for the homepage
│   ├── team.php              # Team member grid component for the About page
│   ├── speakers.php          # Speaker grid & "Your Name Here?" Call-for-Speakers card
│   ├── stats.php             # 3-column stats counter cards
│   ├── location.php          # Venue section with address, image & Google Maps CTA/embed
│   └── content.php           # Default content loop
├── blocks/
│   └── tedx-gallery/         # Custom Gutenberg block: 2-row gallery slider
├── assets/
│   ├── css/
│   │   ├── input.css         # Tailwind source CSS
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
   - **Adding a "Past Events" Dropdown:**
     - Add a top-level menu item named `Past Events` (use `#` as the URL if it should not be clickable itself).
     - Drag each past event link (e.g., `TEDxRegensburg 2025`, `TEDxRegensburg 2024`) slightly to the right, underneath the `Past Events` item, so they become **sub items** in the menu editor.
     - The theme's nav walker automatically renders sub items as a dropdown that opens on hover on desktop, and expands on tap in the mobile drawer — no extra configuration needed.
   - *(Optional)* Under **Appearance → Customize → Site Identity / Hero & Event Pitch**, upload a **Mobile Logo** to show an alternative logo on small screens.

---

## 📖 How to Manage Events & Event Pages

Every event (e.g. "TEDxRegensburg 2026", "Past Event 2025") is stored as an independent database item under the **Events** post type.

### 1. Creating a New Event
1. In the WordPress sidebar, go to **Events → Add New**.
2. **Title & Permalink:** Enter the name of your event (e.g., `TEDxRegensburg 2026`).
3. **Featured Image:** Set a high-resolution featured image or specify a custom card image URL in the settings box.
4. **Fill out the "Event Details" Box:**
   - **Event Year:** e.g., `2026` (Used to automatically filter the speaker lineup on this event's page).
   - **Pitch Title & Subtitle:** e.g., `This Events Title` and `TEDxREGENSBURG | NOV 14 | MARINAFORUM`.
   - **Pitch Description:** Paragraph introducing the theme and vision for this specific event.
   - **Ticket & Show More Buttons:**
     - Check/uncheck **Enable Tickets Button** and **Enable Show More Button** to show or hide them independently.
     - Specify custom URLs for both buttons (e.g., direct ticketing link or custom subpage).
   - **Event Card Settings:**
     - **Card Image URL:** (Optional override if different from the Featured Image).
     - **Date / Badge Label Text:** Text displayed in the pill badge (e.g., `Nov. 14 | TEDxRegensburg`).
     - **Custom Card Link URL:** Optional custom destination.
   - **Location & Venue Settings:**
     - **Venue Name:** e.g., `Marinaforum Regensburg`.
     - **Address Line 1 & Line 2:** e.g., `Johanna-Dachs-Straße 46` / `93055 Regensburg`.
     - **Google Maps URL:** Direct link to Google Maps for navigation.
     - **Google Maps Embed:** Optional embed code/URL to show an interactive map instead of a static image.
     - **Venue Image:** Photo of the event hall / location.
5. Click **Publish**.

### 2. How the Event Page Works (`single-tedx_event.php`)
When viewing the published event link (`/events/your-event-slug/`), WordPress automatically renders a dedicated event page featuring:
1. **Event Pitch & Card:** Populated exclusively with the data, buttons, and card image you defined for this event.
2. **Speaker Lineup:** Automatically queries and displays all speakers assigned to this event's year.
3. **Venue / Location Section:** Shows the venue name, address, photo (or Maps embed), and Google Maps button specific to this event.

---

## 🏠 How to Manage the Homepage (`front-page.php`)

The homepage can be customized via **Appearance → Customize → TEDx Event Settings**.

### 1. Hero Section (Full-Screen Opener)
1. Go to **Appearance → Customize → TEDx Event Settings → Hero & Event Pitch**.
2. Upload a **Hero Background Image** — the blurred, animated full-bleed background behind the hero text.
3. Upload a **Hero Event Poster Image** — the framed poster/visual displayed next to the hero heading.
4. Configure the **Event Year**, hero heading text, navbar ticket button visibility/URL, and "Show More" button URL from the same section.

### 2. Featuring an Event in the Event Pitch Card (Bottom Card)
1. Go to **Appearance → Customize → TEDx Event Settings → Event Pitch Card (Bottom Card)**.
2. Under **Select Event to Feature**, choose the event you want to pull image/details from automatically (overrides the manual fields below).
3. If no event is selected, upload a **Custom Card Image** and set the label/link manually.
4. Use **Show Date / Badge Label** to toggle the pill badge, and **Enable Card Click & Hover Animation** plus **Card Redirect Link URL** to make the card clickable.

### 3. Other Homepage Sections in Customizer
- **About Section:** Title, description text, stage photo, and "Learn More" redirect URL.
- **Speakers Section:** Event year to display, call-for-speakers title/description text, and application URL.
- **Stats Section:** Numbers, labels, and icons for the 3 counter cards.
- **Footer & Social Links:** Social media URLs (LinkedIn, Instagram, Facebook) and newsletter form submission endpoint.

---

## 👥 How to Manage Team Members & About Page

1. Create a WordPress page with the slug `about-us` and select the **About Us** template.
2. In the WordPress sidebar, go to **Team Members → Add New**.
3. **Title:** Enter the member's full name (e.g., `Jane Doe`).
4. **Featured Image:** Upload a square headshot photo.
5. **Team Member Details:**
   - **Role:** e.g., `Lead Organizer` or `Curator`.
   - **LinkedIn URL:** Direct link to their profile (renders the square LinkedIn icon button).
6. **Order:** In the **Page Attributes** box on the right, set the **Order** integer to control grid positioning.
7. Click **Publish**.
8. The **About Us** page template (`page-about.php`) also pulls its "About TED", "About TEDx", and "About TEDxRegensburg" copy from **Appearance → Customize → About Us Page**.

---

## 🎤 How to Manage Speakers

1. In the WordPress sidebar, go to **Speakers → Add New**.
2. **Title:** Speaker's full name.
3. **Content Editor:** Speaker bio and talk description.
4. **Featured Image:** Speaker portrait photo.
5. **Speaker Details:**
   - **Card Display:** Toggle **Truncate bio and display "Show More" button on the homepage** if you want long bios collapsed on the speaker grid.
   - **Topic / Field:** e.g., `Artificial Intelligence & Ethics` (displays in red).
   - **Talk Language:** `EN` or `DE`.
   - **Event Year:** e.g., `2026` (Matches this speaker with the corresponding Event page and homepage speaker filter).
   - **LinkedIn URL:** Link for the "VIEW LINKEDIN" button.
   - **YouTube URL:** Link for the "WATCH THE TALK" button, shown automatically once the talk is filmed and published.
6. Click **Publish**.

---

## 🖼️ How to Add an Image Gallery (Gutenberg Block)

The theme ships a custom **TEDx Gallery** block (`tedx/gallery`) for adding a full-width, 2-row scrolling image gallery to any page or post.

1. Edit a page or post in the block editor and insert the **TEDx Gallery** block (search "TEDx Gallery" in the block inserter, under the *Media* category).
2. Add images to the block via the media uploader.
3. The block automatically renders as a full-bleed, two-row slider on the front end — no additional configuration required.

---

## 💻 Developer Workflow

### Requirements
- Node.js (for the Tailwind CSS CLI build) — no PHP build step is required, all PHP runs directly through WordPress.
- A local WordPress environment (e.g. LocalWP, MAMP, Docker) with the theme linked/copied into `wp-content/themes/`.

### Working with Tailwind CSS
All utility classes are authored in `assets/css/input.css` and compiled to the production stylesheet `assets/css/style.css`, which is the file actually enqueued by `functions.php`. Never edit `assets/css/style.css` directly — changes will be overwritten on the next build.

```bash
cd wp-content/themes/tedx-regensburg-theme

# Install dependencies
npm install

# Start the development watcher (auto-recompiles assets/css/style.css on save)
npm run dev

# Build the minified production CSS (run before committing/deploying)
npm run build
```

### Working with the Gutenberg Gallery Block
The `tedx/gallery` block lives in `blocks/tedx-gallery/`:
- `block.json` — block metadata/attributes.
- `index.js` — editor-side script (registration & edit UI), compiled reference in `index.asset.php`.
- `script.js` / `style.css` — front-end slider behavior and styles.
- `render.php` — server-side render callback (`inc/gallery-render.php` provides supporting helpers).

If you change `index.js`, WordPress loads it directly (no separate JS bundler is configured for blocks), so simply reload the block editor to see changes.

### Theme Structure Conventions
- **Template parts** (`template-parts/*.php`) each render one homepage/section and pull their content from `tedx_mod()` (Customizer) calls defined in `inc/customizer.php` and `inc/customizer-additions.php`.
- **Custom Post Types & meta boxes** (Events, Speakers, Team Members) are all registered in `inc/custom-post-types.php`; add new meta fields there alongside their save/sanitize handlers.
- **Reusable helpers** (icon SVGs, logo markup, formatting) live in `inc/template-tags.php`.
- Keep new Customizer controls grouped under the existing `tedx_event_panel` sections where possible so all site content stays manageable from **Appearance → Customize → TEDx Event Settings**.

### Before Committing
1. Run `npm run build` so `assets/css/style.css` reflects your latest Tailwind changes.
2. Verify permalinks still work for Events/Speakers if you changed CPT rewrite rules (re-save **Settings → Permalinks**).
3. Check the homepage, an Event page, and the About Us page in the browser to confirm no layout regressions.

---

## 📄 License & Attribution

- Licensed under the **GPL-2.0-or-later**.
- *TEDx is an independently organized TED event operated under license from TED Conferences, LLC.*
- Official site: [tedxregensburg.com](https://tedxregensburg.com)

---

> ⚠️ **Note:** This README was AI-generated and may contain inaccuracies. Please verify against the actual codebase.


