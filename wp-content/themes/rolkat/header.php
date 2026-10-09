<?php
if (!defined('ABSPATH')) {
    exit;
}

$home_url = home_url('/');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content', 'rolkat'); ?></a>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?php echo esc_url($home_url); ?>" aria-label="<?php esc_attr_e('ROLKAT home', 'rolkat'); ?>">
            <span class="brand-mark" aria-hidden="true">R</span>
            <span>
                <span class="brand-name">ROLKAT</span>
                <span class="brand-caption"><?php esc_html_e('Serving you better', 'rolkat'); ?></span>
            </span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation">
            <span></span><span></span><span></span>
            <span class="screen-reader-text"><?php esc_html_e('Toggle navigation', 'rolkat'); ?></span>
        </button>
        <nav class="site-nav" id="primary-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'rolkat'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => 'rolkat_fallback_menu',
                'depth' => 1,
            ));
            ?>
            <a class="button button--nav" href="<?php echo esc_url(home_url('/contact/?subject=Loans')); ?>">
                <?php esc_html_e('Get a Quick Loan', 'rolkat'); ?>
            </a>
        </nav>
    </div>
</header>
