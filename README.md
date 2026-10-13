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
- **Developer Ready:** Tailwind CSS CLI workflow available via `package.json` and `tailwind.config.js`.

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
