# ROLKAT Financial Services — Design System

Elementor / WordPress page-builder handoff for **ROLKAT Financial Services SMC Ltd**.

Tagline: **Serving you better**

Audience: boda boda riders, market vendors, small shop owners, landlords, property buyers. Warm fintech — not a cold bank, not a cheap template.

---

## Stitch project

- **Project:** ROLKAT Financial Services Website  
- **Project ID:** `745059847409413669`  
- **Design system asset:** `assets/11778199278105098212`  
- Open in [Google Stitch](https://stitch.withgoogle.com/) with the logged-in account that owns the project.

Screenshots mirror: `designs/stitch/`

### Screen inventory

| Page | Desktop | Tablet | Mobile (375) |
|------|---------|--------|--------------|
| Home | `1b16843843f8429ea1ec580c9dd507d9` (revised) | `e0a7b85547a14fa7b2f07cb8a67f3626` | `2939c45efe104df0b05edf010fd04841` (revised) |
| About | `b24b815687a444788df18cac512f569e` | — | `f80587dffa4d41c598d4d42bc9d7a7e5` |
| Services | `f10a9efdc4da4dbe8eb624c873401f8f` | `86e54b759cb14fc4a5fa1c3c8bd8efe6` | `e826b6eebd3445899ae96f443bf48df5` |
| Team | `45180ffac448466c9f8f006630cc82ac` | — | `9c90f9eadbf54fe48476ec12ddd00417` |
| Contact | `f9d7e54cfde44fc98f2401e9912f09b5` | `ec3a00c8a7a0476eadeb5c37773a2683` | `baf748eac0174324af8c6090c4027cae` |
| Components | `8296c047514840c0a02284bea976cdf3` | — | — |

Resource name format: `projects/745059847409413669/screens/{id}`

---

## Brand tokens

### Colour

| Token | Hex | Use |
|-------|-----|-----|
| Navy (primary) | `#0B1F3A` | Header wordmark, headings, footer, secondary outlines |
| Deep blue | `#122A4A` | Hero / footer gradients |
| Accent | `#F04E23` | Primary CTAs, icon circles, eyebrows, underline accents |
| Accent dark | `#D9431C` | Button hover |
| Warm white | `#FAFAF7` | Page background |
| Soft grey-blue | `#EEF2F7` | Alternating sections |
| Slate | `#475569` | Body text |
| Success | `#16A34A` | Available badge |
| Sold | `#64748B` | Sold badge |
| Rented | `#2563EB` | Rented badge |
| WhatsApp | `#25D366` | Floating chat button |
| White | `#FFFFFF` | Cards |

### Typography

| Role | Font | Weight | Notes |
|------|------|--------|-------|
| Headings | Plus Jakarta Sans | 700–800 | Tight tracking `-0.02em` |
| Body | Inter | 400–600 | Min 16px, line-height ~1.7 |
| Eyebrow | Inter / Plus Jakarta | 700 | Uppercase, orange `#F04E23`, ~11–12px, wide tracking |

Google Fonts URL:

```
https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap
```

### Shape & elevation

- Card / button radius: **16–20px** (Elementor: Border Radius 16–20)
- Soft shadow: `0 8px 24px rgba(11, 31, 58, 0.08)`
- Hover lift: translateY `-4px` + `0 14px 32px rgba(11, 31, 58, 0.14)`
- Thin orange heading underline: 48×3px pill under section titles

---

## Component library (Elementor rebuild)

### Buttons

1. **Primary** — fill `#F04E23`, text white, radius 14px, padding 12×24, shadow orange soft. Label examples: `Get a Quick Loan`, `WhatsApp`, `Enquire`.
2. **Secondary / outline** — transparent, 1px `#0B1F3A`, text navy. On navy backgrounds: white outline + white text.
3. **Ghost link** — navy text + orange arrow (`→` or Lucide `arrow-right`). Used for “Learn more”.

### Navigation

- Sticky white header, soft bottom border / blur on scroll.
- Round navy **R** mark + **ROLKAT** wordmark.
- Links: Home · About Us · Services · Team · Contact.
- Right CTA: orange **Get a Quick Loan**.
- Mobile: hamburger → full-height slide-in panel; no app bottom nav.
- Floating WhatsApp (bottom-right) → `https://wa.me/256787165366`.
- Mobile click-to-call bar → `tel:+256787165366`.

### Section heading pattern

1. Orange eyebrow label  
2. 48px orange underline accent  
3. Bold navy H2  
4. Muted slate subtitle (optional)

### Cards

- White fill, 16–20px radius, soft shadow.
- Service / trust cards: Lucide line icon in orange circle (48px) → title → one-line body → Learn more.
- Property cards: photo, title, UGX price, location, status badge, Enquire.
- Team cards: photo, name, job title, short bio (hover/tap reveal on interactive builds).

### Status badges (pill)

| Status | Background | Text |
|--------|------------|------|
| Available | `rgba(22,163,74,.12)` | `#16A34A` |
| Sold | `rgba(100,116,139,.14)` | `#64748B` |
| Rented | `rgba(37,99,235,.12)` | `#2563EB` |

### Forms

- Labels above fields, 16px inputs, 12–14px radius, slate borders.
- Subject dropdown: Loans / Property Management / Real Estate / Other.
- Inline validation + honeypot spam field.
- Success state: green notice “Thank you…”.
- Primary submit: full-width orange on mobile.

### Footer

- Navy → deep blue gradient.
- Logo, tagline, quick links, contact, social (Facebook, Instagram, LinkedIn), hours, Privacy Policy.
- Address: Hanora Plaza, Zana, Entebbe Road, opposite Be Energies.  
- Email: katongolejames122@gmail.com  

---

## Page map (content checklist)

### 1. Home

Hero split (headline *Quick loans. Trusted property services. Serving you better.*), CTAs, trust chips, collage + glass card “Approved in 24 hours” → 3 service cards → Who we serve strip → 4-step loan timeline → Featured properties → Why ROLKAT (Fast / Fair / Flexible / Respectful) + licence placeholder → optional testimonials → CTA band *Need cash fast?*

### 2. About

Story + image → Mission / Vision → 6 value cards → Reg / UMRA placeholders → Map + visit card.

### 3. Services

Anchor tabs → Loans (5 products, responsible lending note, **Contact us for terms** — no rates) → Property Management checklist + 4-step onboarding → Real Estate + safe buying checklist + listings link/filters.

### 4. Team

Responsive card grid; placeholder members OK.

### 5. Contact

Form left / info + map right; large WhatsApp & Call buttons.

---

## Motion (keep light)

- Fade-up on scroll (~200–400ms).
- Card hover lift.
- Optional counter animation on trust stats.
- Target: under 3s mobile load — compress images, defer non-critical JS.

## Accessibility

- AA contrast (orange on white for large CTAs; navy text on `#FAFAF7`).
- Visible `:focus-visible` ring (accent).
- Alt text on every image.
- Tap targets ≥ 44px.

## Theme CSS variables

Implemented in `wp-content/themes/rolkat/style.css` as:

```css
--navy: #0B1F3A;
--accent: #F04E23;
--bg: #FAFAF7;
--sand: #EEF2F7;
--muted: #475569;
--success: #16A34A;
--heading: "Plus Jakarta Sans", …;
--sans: Inter, …;
```
