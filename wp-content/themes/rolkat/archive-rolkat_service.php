<?php
get_header();
$grouped = rolkat_services_by_category();
$categories = rolkat_service_categories();
?>
<main id="main-content">
    <header class="page-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('How we can help', 'rolkat'); ?></p>
            <h1><?php esc_html_e('Our services', 'rolkat'); ?></h1>
            <p class="lead"><?php esc_html_e('Loans, property management and real estate — clear offerings for the people we serve, with a simple path to get started.', 'rolkat'); ?></p>
        </div>
    </header>

    <?php foreach ($categories as $slug => $category) : ?>
        <section class="section <?php echo $slug === 'property-management' ? 'section--sand' : ''; ?>" id="<?php echo esc_attr($slug); ?>">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><?php echo esc_html($category['label']); ?></p>
                        <h2><?php echo esc_html($category['summary']); ?></h2>
                    </div>
                    <div>
                        <p><strong><?php esc_html_e('Who it is for:', 'rolkat'); ?></strong> <?php echo esc_html($category['audience']); ?></p>
                        <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Enquire about this service', 'rolkat'); ?></a>
                    </div>
                </div>
                <div class="card-grid">
                    <?php if (!empty($grouped[$slug])) : ?>
                        <?php foreach ($grouped[$slug] as $service) : ?>
                            <article class="service-card">
                                <h3><?php echo esc_html($service['title']); ?></h3>
                                <p><?php echo esc_html($service['description']); ?></p>
                                <a class="text-link" href="<?php echo esc_url($service['permalink']); ?>"><?php esc_html_e('Learn more', 'rolkat'); ?></a>
                            </article>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="empty-state"><?php esc_html_e('Details for this service line are being prepared. Contact us for help.', 'rolkat'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
</main>
<?php
get_footer();
