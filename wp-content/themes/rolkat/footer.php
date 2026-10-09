<?php
if (!defined('ABSPATH')) {
    exit;
}

$phone = get_theme_mod('rolkat_phone', '');
$email = get_theme_mod('rolkat_email', '');
$address = get_theme_mod('rolkat_address', '');
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <span class="brand-mark" aria-hidden="true">R</span>
                    <span>
                        <span class="brand-name">Rolkat Financial</span>
                        <span class="brand-caption"><?php esc_html_e('Serving you better', 'rolkat'); ?></span>
                    </span>
                </a>
                <p><?php esc_html_e('Financial services, property management and real estate — delivered with care and a personal approach.', 'rolkat'); ?></p>
            </div>
            <div>
                <h2><?php esc_html_e('Explore', 'rolkat'); ?></h2>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About us', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('Services', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('rolkat_property')); ?>"><?php esc_html_e('Properties', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('rolkat_team_member')); ?>"><?php esc_html_e('Our team', 'rolkat'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'rolkat'); ?></a></li>
                </ul>
            </div>
            <div>
                <h2><?php esc_html_e('Get in touch', 'rolkat'); ?></h2>
                <ul class="footer-links">
                    <?php if ($phone !== '') : ?>
                        <li><a href="<?php echo esc_url('tel:' . preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></li>
                    <?php endif; ?>
                    <?php if ($email !== '') : ?>
                        <li><a href="<?php echo esc_url('mailto:' . sanitize_email($email)); ?>"><?php echo esc_html($email); ?></a></li>
                    <?php endif; ?>
                    <?php if ($address !== '') : ?>
                        <li><?php echo nl2br(esc_html($address)); ?></li>
                    <?php endif; ?>
                    <?php if ($phone === '' && $email === '' && $address === '') : ?>
                        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Send us a message', 'rolkat'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html(wp_date('Y')); ?> <?php esc_html_e('ROLKAT Financial Services SMC Ltd.', 'rolkat'); ?></p>
            <p><?php esc_html_e('Serving you better.', 'rolkat'); ?></p>
        </div>
    </div>
</footer>
<button type="button" class="back-to-top" id="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'rolkat'); ?>" title="<?php esc_attr_e('Back to top', 'rolkat'); ?>">
    <i data-lucide="arrow-up" style="width:18px;height:18px;"></i>
</button>
<?php wp_footer(); ?>
</body>
</html>
