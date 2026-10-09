# ROLKAT WordPress theme

Custom WordPress theme for **ROLKAT Financial Services SMC Ltd**, built to the Phase 1 SRS: public pages, property listings, team, contact form with spam protection, and admin-editable content.

## What is included

| Area | Delivery |
|---|---|
| Pages | Home, About, Services (3 lines), Team, Contact, Privacy |
| Content types | Services, Properties, Team, Enquiries (private) |
| Contact | Name, email, phone, subject, message · honeypot · email + saved enquiry · CSV export |
| Properties | Photo, title, price, location, status (Available / Sold / Rented), type filters |
| Contact extras | Click-to-call, WhatsApp float, working hours, Google Maps embed, social links |
| Brand defaults | Company profile phone, email, address and tagline |

## Install on WordPress

1. Copy the `rolkat` folder into `wp-content/themes/`.
2. Activate **Rolkat Financial Services** under **Appearance → Themes**.
3. On activation the theme creates **About**, **Contact**, **Privacy Policy** and a **Home** page (if missing). Assign **Settings → Reading → Your homepage displays** to the Home page.
4. Set **Appearance → Menus → Primary navigation** (or use the built-in fallback menu).
5. Edit contact details under **Appearance → Customize → ROLKAT contact details** and social URLs under **ROLKAT social links**.
6. Add **Services** (set business line), **Properties** (status + type), and **Team** (job title).
7. Configure SMTP/`wp_mail` so contact enquiries deliver reliably.
8. Install an SEO plugin if you need advanced sitemap/Search Console controls (basic meta descriptions are built in).
9. Recommended plugins: security/firewall, caching, backup.

## Local preview (without WordPress)

This workspace includes a Node preview for design and admin training before hosting is ready:

```bash
npm install
npm run dev
```

- Public site: http://127.0.0.1:5173  
- Admin: http://127.0.0.1:5173/admin (default `admin` / `rolkat2026`)  
- Content is stored in `data/site-data.json`

Change the admin password in the admin **Site Settings** tab before any shared demo.

## Admin handover checklist

- [ ] Strong passwords for all WordPress users  
- [ ] SSL / HTTPS enforced on hosting  
- [ ] Weekly backups confirmed  
- [ ] Contact form test (email + Enquiries list)  
- [ ] WhatsApp and phone links tested on mobile  
- [ ] Sample properties / team / services published or cleared  
- [ ] Privacy policy reviewed with client  

## Phase 2 (out of scope here)

Online loan applications with uploads, client portal, payments / mobile money, advanced search, multi-language.
