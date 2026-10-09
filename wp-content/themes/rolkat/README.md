# ROLKAT WordPress theme

This is a custom WordPress theme for ROLKAT Financial Services SMC Ltd. It uses PHP templates and a small vanilla JavaScript file for the mobile navigation and loan repayment estimate.

## Install

1. Copy the `rolkat` folder into `wp-content/themes/` in a WordPress installation.
2. In the WordPress dashboard, activate **Rolkat Financial Services** under **Appearance → Themes**.
3. Create pages with the slugs `about` and `contact`. Assign the **Contact** page template to the Contact page. Set a homepage under **Settings → Reading**; the front page template will be used automatically.
4. Add service entries under **Services**, property listings under **Properties**, and team profiles under **Team**. Use each post's excerpt for the card summary or role, and featured images for property and team cards. Property listings also have optional location, display price, and status fields.
5. Set **Appearance → Menus → Primary navigation** to the site's main menu.
6. Edit the homepage headline and introduction under **Appearance → Customize → ROLKAT homepage**. Enter the public phone, email and address under **ROLKAT contact details**. Set the WordPress site email under **Settings → General** so contact enquiries have a valid recipient.

The contact form sends enquiries to the WordPress site email using `wp_mail()`. Configure a reliable mail transport with the hosting provider or a mail plugin before launch. The theme provides page titles and a default meta description; an SEO plugin can replace the description output. The loan calculator is an indicative monthly repayment estimate only and does not include fees or other charges.

## Local development

The workspace does not currently include a WordPress installation or PHP runtime. To preview the theme, install WordPress locally and activate it using the steps above.
