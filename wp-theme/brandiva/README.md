# Brandiva Media — WordPress theme

Bilingual (Arabic RTL default · English) digital-agency theme converted from the static site.
Same markup, CSS, animations (GSAP, ScrollTrigger, Lenis, SplitType) and features — now fully editable.

## Install
1. Upload `brandiva.zip` (Appearance → Themes → Add New → Upload) and **Activate**.
2. On activation the theme installs & activates **Secure Custom Fields**, **Contact Form 7** and **Polylang** from WordPress.org
   (if the server can't, use the admin notice button “Install & activate now”).
3. Go to **Appearance → Demo Import → Import demo content**. This creates both languages (Arabic at `/`, English at `/en/`),
   5 pages per language, all SCF content, 4 CF7 forms, images, menus, Theme Options, and sets the static front page.
4. Settings → Permalinks → Save once (if links 404).

## Where things are edited
| What | Where |
|---|---|
| Logo, favicon, contacts, social, colors, fonts, style, effects, forms mode, SEO, custom code | **Theme Options** (admin menu) |
| Interface texts (menu CTA, footer, labels, 404, form messages, WhatsApp template) per language | **Theme Options → Texts — العربية / English** |
| Home page sections (toggle each section on/off) | Pages → Home → fields |
| Web Design / SEO / Hosting pages | Pages → (page) → *Service page content*: hero, flexible sections (rich text, tabs, steps, flip cards, plans, FAQ…), bottom CTA |
| Contact page | Pages → Contact |
| Forms | **Contact Form 7** (markup uses the theme's `.form/.field` classes) |
| Menus | Appearance → Menus (locations: Primary, Footer pages, Footer services — one set per language) |

## Forms
Delivery mode (Theme Options → Forms): *email + WhatsApp* (default), *email only*, *WhatsApp only* (the original behavior).
Keep the field names `your-name`, `your-phone`, `service`, `your-email`, `your-message` if you edit the CF7 forms.

## Regenerating demo data from the static site
`python3 tools/export_theme_data.py` (repo root) rewrites `demo/demo-data.json`.
CSS: edit `assets/css/src/*.css`, then re-bundle into `assets/css/site.css` (see `tools/bundle_css.py`, point it at this folder).

## Requirements
WordPress 6.2+, PHP 7.4+.
