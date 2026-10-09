<?php
/*
Template Name: Contact
*/
get_header();
$phone = get_theme_mod('rolkat_phone', '');
$email = get_theme_mod('rolkat_email', '');
$address = get_theme_mod('rolkat_address', '');
$contact_status = isset($_GET['contact_status']) ? sanitize_key(wp_unslash($_GET['contact_status'])) : '';
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('We are ready to listen', 'rolkat'); ?></p>
                <h1><?php the_title(); ?></h1>
                <p class="lead"><?php esc_html_e('Tell us what you are working towards. Our team will be in touch.', 'rolkat'); ?></p>
            </div>
        </header>
        <section class="section">
            <div class="container contact-layout">
                <div>
                    <div class="page-content">
                        <?php the_content(); ?>
                    </div>
                    <div class="contact-details">
                        <?php if ($phone !== '') : ?>
                            <div class="contact-detail">
                                <strong><?php esc_html_e('Call us', 'rolkat'); ?></strong>
                                <a href="<?php echo esc_url('tel:' . preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($email !== '') : ?>
                            <div class="contact-detail">
                                <strong><?php esc_html_e('Email', 'rolkat'); ?></strong>
                                <a href="<?php echo esc_url('mailto:' . sanitize_email($email)); ?>"><?php echo esc_html($email); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($address !== '') : ?>
                            <div class="contact-detail">
                                <strong><?php esc_html_e('Visit us', 'rolkat'); ?></strong>
                                <span><?php echo nl2br(esc_html($address)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php if ($contact_status === 'sent') : ?>
                        <p class="notice" role="status"><?php esc_html_e('Thank you for contacting us. Your message has been sent.', 'rolkat'); ?></p>
                    <?php elseif ($contact_status === 'invalid') : ?>
                        <p class="notice notice--error" role="alert"><?php esc_html_e('Please check the details and try again.', 'rolkat'); ?></p>
                    <?php elseif ($contact_status === 'error') : ?>
                        <p class="notice notice--error" role="alert"><?php esc_html_e('We could not send your message right now. Please try again later.', 'rolkat'); ?></p>
                    <?php endif; ?>
                    <input type="hidden" name="action" value="rolkat_contact">
                    <?php wp_nonce_field('rolkat_contact', 'rolkat_contact_nonce'); ?>
                    <div class="hp-field" aria-hidden="true">
                        <label for="company-website"><?php esc_html_e('Leave this field empty', 'rolkat'); ?></label>
                        <input id="company-website" name="company_website" type="text" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="contact-name"><?php esc_html_e('Your name', 'rolkat'); ?></label>
                        <input id="contact-name" name="contact_name" type="text" autocomplete="name" maxlength="120" required>
                    </div>
                    <div class="field">
                        <label for="contact-email"><?php esc_html_e('Email address', 'rolkat'); ?></label>
                        <input id="contact-email" name="contact_email" type="email" autocomplete="email" maxlength="254" required>
                    </div>
                    <div class="field">
                        <label for="contact-service"><?php esc_html_e('What can we help with?', 'rolkat'); ?></label>
                        <select id="contact-service" name="contact_service">
                            <option value=""><?php esc_html_e('Choose a service (optional)', 'rolkat'); ?></option>
                            <option value="Loans"><?php esc_html_e('Loans', 'rolkat'); ?></option>
                            <option value="Property management"><?php esc_html_e('Property management', 'rolkat'); ?></option>
                            <option value="Real estate"><?php esc_html_e('Real estate', 'rolkat'); ?></option>
                            <option value="Other"><?php esc_html_e('Other', 'rolkat'); ?></option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="contact-message"><?php esc_html_e('Your message', 'rolkat'); ?></label>
                        <textarea id="contact-message" name="contact_message" maxlength="10000" required></textarea>
                    </div>
                    <button class="button" type="submit"><?php esc_html_e('Send message', 'rolkat'); ?></button>
                </form>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
