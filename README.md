# TEDx Regensburg - WordPress Theme

Ein modernes, standardkonformes und hochperformantes WordPress-Theme für **TEDx Regensburg**, entwickelt basierend auf dem offiziellen Figma-Design mit **Tailwind CSS**, modularen PHP-Template-Parts und dynamischen WordPress-Template-Tags.

---

## 🎯 Features

- **100% Figma-konform:** Präzise Umsetzung aller Sektionen (`Homepage Main` Node ID `166:2126`):
  - Sticky / Blurred Header mit dynamischem Menü, Sprachwechsler (`DE | EN`) und Tickets-CTA
  - Hero-Sektion mit topographischen Kurven, Leucht-Effekt und Jahresmotto-Karte
  - Event-Pitch-Sektion mit Datums- & Location-Badge (`TEDxREGENSBURG | NOV 14 | MARINAFORUM`) und Action-Buttons
  - About TEDx-Sektion mit Stage-Foto, Branding und Info-Text
  - 2026 Speakers-Sektion mit dynamischem Custom Post Type Loop & "Your Name Here?" Call-for-Speakers-Karte
  - Stats-Sektion mit 3 dynamischen Counter-Karten (Gäste, Talks, Ideen)
  - Footer mit Social-Media-Badges (LinkedIn, Instagram, Facebook), Newsletter-Anmeldung und TEDx-Lizenzhinweis
- **Zero-Dependency Setup:** Das Theme ist sofort nach Aktivierung in WordPress einsatzbereit (kompilierte `assets/css/style.css` liegt bereits bei).
- **Entwickler-Workflow:** Tailwind CSS CLI Integration (`npm run dev`, `npm run build`) über `tailwind.config.js` und `assets/css/input.css`.
- **WordPress Standards:** Volle Unterstützung für `wp_nav_menu()`, `the_custom_logo()`, `the_post_thumbnail()`, `Customizer`, `WP_Query`, HTML5 und Übersetzung (`tedx-regensburg` Text-Domain).

---

## 📁 Ordner- & Dateistruktur

```text
tedx-regensburg-theme/
├── style.css                 # Theme-Header & Metadaten
├── functions.php             # Enqueueing, Theme-Supports & Modul-Loader
├── header.php                # Sticky Navigation & Mobile Drawer Menu
├── footer.php                # Footer mit Social Links & Newsletter
├── front-page.php            # Haupt-Landingpage (orchestiriert alle Sektionen)
├── index.php                 # Standard-Fallback & Blog-Archiv
├── page.php                  # Template für Standard-Seiten (z. B. Impressum, Datenschutz)
├── single.php                # Standard Single Post Template
├── single-speaker.php        # Detail-Ansicht für Speaker
├── archive-speaker.php       # Archiv-Übersicht aller Speaker
├── 404.php                   # 404-Fehlerseite im TEDx-Stil
├── inc/
│   ├── custom-post-types.php # CPT 'speaker' mit Meta-Feldern (Topic, Sprache, LinkedIn, Jahr)
│   ├── customizer.php        # Customizer Panel: TEDx Event Settings
│   ├── template-tags.php     # Helper: Vektor-Logo, Inline-SVGs, Language-Switcher
│   └── nav-walker.php        # Tailwind CSS Nav Walker für WordPress-Menüs
├── template-parts/
│   ├── hero.php              # Hero-Sektion
│   ├── event-pitch.php       # Event-Pitch & Motto
│   ├── about.php             # Über TEDx Regensburg
│   ├── speakers.php          # Speaker-Grid & CFS-Card
│   ├── stats.php             # Counter Squircle Cards
│   └── content.php           # Post/Page Content Loop
├── assets/
│   ├── css/
│   │   ├── input.css         # Tailwind Quell-CSS
│   │   └── style.css         # Kompiliertes, produktionsreifes Tailwind CSS
│   └── js/
│       └── main.js           # Menü-Toggle, Smooth Scroll, Newsletter-Feedback
├── tailwind.config.js        # Tailwind Konfiguration & TEDx Farbtokens
├── package.json              # NPM Skripte für Tailwind
└── README.md                 # Dokumentation
```

---

## 🚀 Installation & Aktivierung

### 1. In WordPress installieren
1. Kopiere den Ordner `tedx-regensburg-theme` in dein WordPress-Theme-Verzeichnis:
   `wp-content/themes/tedx-regensburg-theme`
   *(oder erstelle eine `.zip`-Datei des Ordners und lade sie unter **Design → Themes → Theme hinzufügen → Theme hochladen** hoch)*.
2. Gehe im WordPress-Adminbereich auf **Design → Themes** und klicke bei **TEDx Regensburg** auf **Aktivieren**.

### 2. Menü einrichten
1. Gehe zu **Design → Menüs**.
2. Erstelle ein Menü (z. B. "Hauptmenü") mit Links zu den Sektionen:
   - `#hero` (2026 Event)
   - `#event-pitch` (Past Talks)
   - `#about` (About Us)
   - `#speakers` (Speaker Information)
3. Weise das Menü der Position **Primary Navigation** zu.

### 3. Inhalte im Customizer anpassen
Unter **Design → Customizer → TEDx Event Settings** kannst du alle dynamischen Elemente flexibel bearbeiten:
- **Hero & Event Pitch:** Event-Jahr, Theme-Titel, Pitch-Text, Ticket-Shop URL & Button-Text
- **About Section:** Titel, Mission-Text, Foto, "Learn More" Link
- **Speakers Section:** Call-for-Speakers Titel, Text und Bewerbungs-Link
- **Stats Section:** Werte und Beschriftungen für die 3 Counter-Karten
- **Footer & Social Links:** LinkedIn, Instagram, Facebook URLs und Newsletter-Endpoint

### 4. Speaker anlegen
1. Klicke im WordPress-Menü auf **Speakers → Add New**.
2. Gib den Namen des Speakers als Titel ein.
3. Füge die Bio/Beschreibung in das Textfeld ein.
4. Lade unter **Beitragsbild (Featured Image)** ein quadratisches Foto hoch.
5. Fülle die **Speaker Details** aus:
   - **Topic / Field:** z. B. `Content Creation` oder `AI & Ethics` (erscheint in Rot)
   - **Talk Language:** `EN` oder `DE`
   - **LinkedIn URL:** Profil-Link für den "VIEW LINKEDIN"-Button
6. Klicke auf **Veröffentlichen**.

---

## 💻 Entwickler-Workflow (Optional)

Falls du neue Tailwind-Klassen oder Styles hinzufügen möchtest:

```bash
cd wp-content/themes/tedx-regensburg-theme
npm install
npm run dev    # Für kontinuierliche CSS-Kompilierung während der Entwicklung
npm run build  # Für optimierte, minifizierte Produktions-CSS
```

---

## 📄 Lizenz
GPL-2.0-or-later. TEDx ist eine eingetragene Marke der TED Conferences, LLC.
