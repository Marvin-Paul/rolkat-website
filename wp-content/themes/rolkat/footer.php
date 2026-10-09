<?php
if (!defined('ABSPATH')) {
    exit;
}

$phone = rolkat_get_phone();
$email = rolkat_get_email();
$address = rolkat_get_address();
$hours = rolkat_get_hours();
$whatsapp_url = rolkat_whatsapp_url();
$facebook = get_theme_mod('rolkat_facebook', '');
$instagram = get_theme_mod('rolkat_instagram', '');
$linkedin = get_theme_mod('rolkat_linkedin', '');
$privacy = get_page_by_path('privacy-policy');
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <span class="brand-mark" aria-hidden="true">R</span>
                    <span>
                        <span class="brand-name" style="color:#fff;">ROLKAT</span>
                        <span class="brand-caption"><?php esc_html_e('Serving you better', 'rolkat'); ?></span>
                    </span>
                </a>
                <p><?php esc_html_e('Financial services, property management and real estate — delivered with care and a personal approach.', 'rolkat'); ?></p>
                <?php if ($facebook || $instagram || $linkedin) : ?>
                    <ul class="social-links">
                        <?php if ($facebook) : ?><li><a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Facebook', 'rolkat'); ?></a></li><?php endif; ?>
                        <?php if ($instagram) : ?><li><a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Instagram', 'rolkat'); ?></a></li><?php endif; ?>
                        <?php if ($linkedin) : ?><li><a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('LinkedIn', 'rolkat'); ?></a></li><?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div>
                <h2><?php esc_html_e('Explore', 'rolkat'); ?></h2>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About us', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('Services', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('rolkat_property')); ?>"><?php esc_html_e('Properties', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('rolkat_team_member')); ?>"><?php esc_html_e('Our team', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'rolkat'); ?></a></li>
                    <?php if ($privacy) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($privacy)); ?>"><?php esc_html_e('Privacy policy', 'rolkat'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <h2><?php esc_html_e('Get in touch', 'rolkat'); ?></h2>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(rolkat_tel_href($phone)); ?>"><?php echo esc_html($phone); ?></a></li>
                    <li><a href="<?php echo esc_url('mailto:' . sanitize_email($email)); ?>"><?php echo esc_html($email); ?></a></li>
                    <li><?php echo nl2br(esc_html($address)); ?></li>
                    <li><?php echo esc_html($hours); ?></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html(wp_date('Y')); ?> <?php esc_html_e('ROLKAT Financial Services SMC Ltd.', 'rolkat'); ?></p>
            <p><?php esc_html_e('Serving you better.', 'rolkat'); ?></p>
        </div>
    </div>
</footer>
<?php if ($whatsapp_url) : ?>
    <a class="whatsapp-float" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Chat on WhatsApp +256 787 165 366', 'rolkat'); ?>">
        <span aria-hidden="true">WA</span>
        <span class="screen-reader-text"><?php esc_html_e('Chat on WhatsApp', 'rolkat'); ?></span>
    </a>
<?php endif; ?>
<div class="mobile-call-bar">
    <a href="<?php echo esc_url(rolkat_tel_href($phone)); ?>"><?php esc_html_e('Call', 'rolkat'); ?> <?php echo esc_html($phone); ?></a>
</div>
<button type="button" class="back-to-top" id="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'rolkat'); ?>" title="<?php esc_attr_e('Back to top', 'rolkat'); ?>">
    ↑
</button>
<?php wp_footer(); ?>
</body>
</html>
