# TEDx Regensburg - WordPress Theme

A modern, standards-compliant, and high-performance WordPress theme for **TEDx Regensburg**. Built based on the official Figma design system using **Tailwind CSS**, modular PHP template parts, dynamic Custom Post Types (Events, Speakers, Team Members), and WordPress Customizer integration.

---

## 🎯 Key Features

- **100% Figma Pixel-Perfect Implementation:**
  - **Sticky Blurred Navigation:** Responsive desktop navbar and mobile drawer menu with language indicator (`DE | EN`) and Ticket CTA.
  - **Homepage Hero Section:** Topographic contour lines, background glow, and standalone interactive Hero Event Card.
  - **Event Pitch Section:** Event summary, pill badges, and independently configurable Ticket and "Show More" action buttons.
  - **About Section & Dedicated "About Us" Page (`page-about.php`):** Clean Figma layout (Node `166:1525`) featuring mission statement, core statistics, and team member grid with LinkedIn badges.
  - **Dedicated Event Pages (`single-tedx_event.php`):** Full subpage template for past and upcoming events including dynamic pitch details, speaker lineups filtered by year, and venue location with interactive Google Maps link.
  - **Dynamic Speaker & Team Member Directories:** Custom Post Types with custom meta fields, responsive grids, and clean fallbacks.
  - **Interactive Event Cards (Node `166:1430`):** 48px rounded squircle cards with custom images, smooth hover zoom animations, pill badges, and direct database linking.
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
│   ├── customizer-helpers.php# Helper functions for dynamic event dropdown choices
│   ├── template-tags.php     # Helper functions (SVG icons, logos, formatting)
│   └── nav-walker.php        # Custom Tailwind CSS Nav Walker
├── template-parts/
│   ├── hero.php              # Hero section with heading & Hero Event Card
│   ├── event-pitch.php       # Event pitch, descriptions, and action buttons
│   ├── event-card.php        # Reusable Figma 166:1430 Event Card component
│   ├── about.php             # About TEDx snippet for the homepage
│   ├── team.php              # Team member grid component for the About page
│   ├── speakers.php          # Speaker grid & "Your Name Here?" Call-for-Speakers card
│   ├── stats.php             # 3-column stats counter cards
│   ├── location.php          # Venue section with address & Google Maps CTA
│   └── content.php           # Default content loop
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
   - Without making any changes, scroll to the bottom and click **Save Changes**. *(This registers the `/events/...` URL rewrite rules in WordPress).*
3. **Setup Navigation Menu:**
   - Navigate to **Appearance → Menus**.
   - Create a primary menu and assign it to the **Primary Navigation** location.
   - Example menu items:
     - Home (`/`)
     - 2026 Event (`/events/tedx-regensburg-2026/`)
     - About Us (`/about-us/`)
     - Speakers (`#speakers`)

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
     - **Venue Image URL:** Photo of the event hall / location.
5. Click **Publish**.

### 2. How the Event Page Works (`single-tedx_event.php`)
When viewing the published event link (`/events/your-event-slug/`), WordPress automatically renders a dedicated event page featuring:
1. **Event Pitch & Card:** Populated exclusively with the data, buttons, and card image you defined for this event.
2. **Speaker Lineup:** Automatically queries and displays all speakers assigned to this event's year.
3. **Venue / Location Section:** Shows the venue name, address, photo, and Google Maps button specific to this event.

---

## 🏠 How to Manage the Homepage (`front-page.php`)

The homepage can be customized via **Appearance → Customize → TEDx Event Settings**.

### 1. Featuring an Event in the Hero Card (Top Card)
1. Go to **Appearance → Customize → TEDx Event Settings → Hero Card (Top Visual Card)**.
2. Under **Select Event to Feature**, pick any event from your database (e.g., `TEDxRegensburg 2026`).
   - The Hero Card will automatically load the event's image, date badge text, and link.
3. **Card Display Controls:**
   - Check/uncheck **Show Date / Badge Label** to toggle the top badge on/off.
   - Check/uncheck **Enable Card Click & Hover Animation** to enable/disable clickable behavior and hover zoom effects.
4. *(Optional)* If no event is selected, you can manually upload an image and specify custom text/links.

### 2. Featuring an Event in the Event Pitch Card (Bottom Card)
1. Go to **Appearance → Customize → TEDx Event Settings → Event Pitch Card (Bottom Card)**.
2. Under **Select Event to Feature**, choose the event you want to display in the pitch section on the homepage.
3. Use the checkboxes to toggle the date badge and hover animations as desired.

### 3. Other Homepage Sections in Customizer
- **Hero & Event Settings:** Global theme motto, event year, and default ticket button URLs.
- **About Section:** Title, description text, stage photo, and "Learn More" redirect URL.
- **Speakers Section:** Call-for-speakers title, descriptive text, and application URL.
- **Stats Section:** Numbers and labels for the 3 counter cards.
- **Social & Newsletter:** Social media URLs (LinkedIn, Instagram, Facebook) and form submission endpoint.

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

---

## 🎤 How to Manage Speakers

1. In the WordPress sidebar, go to **Speakers → Add New**.
2. **Title:** Speaker's full name.
3. **Content Editor:** Speaker bio and talk description.
4. **Featured Image:** Speaker portrait photo.
5. **Speaker Details:**
   - **Topic / Field:** e.g., `Artificial Intelligence & Ethics` (displays in red).
   - **Talk Language:** `EN` or `DE`.
   - **Event Year:** e.g., `2026` (Matches this speaker with the corresponding Event page).
   - **LinkedIn URL:** Link for the "View LinkedIn" profile button.
6. Click **Publish**.

---

## 💻 Developer Workflow (Tailwind CSS)

To modify Tailwind CSS styles or rebuild the static stylesheet:

```bash
cd wp-content/themes/tedx-regensburg-theme

# Install dependencies
npm install

# Start development watcher (auto-recompiles assets/css/style.css)
npm run dev

# Build minified production CSS
npm run build
```

---

## 📄 License & Attribution

- Licensed under the **GPL-2.0-or-later**.
- *TEDx is an independently organized TED event operated under license from TED Conferences, LLC.*

