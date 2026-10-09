<?php
get_header();
$grouped = rolkat_services_by_category();
$categories = rolkat_service_categories();
$loan_meta = array(
    'Micro Loans' => array('who' => __('Daily earners and boda boda riders', 'rolkat'), 'repay' => __('Daily or weekly', 'rolkat')),
    'Small Business Loans' => array('who' => __('Retail shops and SMEs', 'rolkat'), 'repay' => __('Flexible', 'rolkat')),
    'Group Loans' => array('who' => __('Trader groups and associations', 'rolkat'), 'repay' => __('Weekly', 'rolkat')),
    'Emergency Loans' => array('who' => __('Urgent cash needs', 'rolkat'), 'repay' => __('Flexible', 'rolkat')),
    'Market Vendor Loans' => array('who' => __('Market stallholders', 'rolkat'), 'repay' => __('Daily', 'rolkat')),
);
$pm_checklist = array(
    __('Tenant screening', 'rolkat'),
    __('Rent collection', 'rolkat'),
    __('Maintenance coordination', 'rolkat'),
    __('Property inspections', 'rolkat'),
    __('Monthly owner statements', 'rolkat'),
);
$pm_steps = array(
    __('Share property details', 'rolkat'),
    __('Agree service scope', 'rolkat'),
    __('Onboard tenants & systems', 'rolkat'),
    __('Receive monthly reports', 'rolkat'),
);
$safe_buy = array(
    __('Title search', 'rolkat'),
    __('Independent lawyer review', 'rolkat'),
    __('Traceable payments only', 'rolkat'),
);
?>
<main id="main-content">
    <header class="page-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('How we can help', 'rolkat'); ?></p>
            <span class="section-title-accent" aria-hidden="true"></span>
            <h1><?php esc_html_e('Our services', 'rolkat'); ?></h1>
            <p class="lead"><?php esc_html_e('Loans, property management and real estate — clear offerings with a simple path to get started.', 'rolkat'); ?></p>
            <nav class="service-anchor-tabs" aria-label="<?php esc_attr_e('Service sections', 'rolkat'); ?>">
                <?php foreach ($categories as $slug => $category) : ?>
                    <a href="#<?php echo esc_attr($slug); ?>"><?php echo esc_html($category['label']); ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <section class="section" id="loans">
        <div class="container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><?php echo esc_html($categories['loans']['label']); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Fast, fair credit for how you earn.', 'rolkat'); ?></h2>
                </div>
                <div>
                    <p><strong><?php esc_html_e('Who it is for:', 'rolkat'); ?></strong> <?php echo esc_html($categories['loans']['audience']); ?></p>
                    <a class="button" href="<?php echo esc_url(home_url('/contact/?subject=Loans')); ?>"><?php esc_html_e('Enquire', 'rolkat'); ?></a>
                </div>
            </div>
            <div class="card-grid">
                <?php if (!empty($grouped['loans'])) : ?>
                    <?php foreach ($grouped['loans'] as $service) : ?>
                        <?php $meta = $loan_meta[$service['title']] ?? null; ?>
                        <article class="service-card">
                            <div class="icon-circle" aria-hidden="true"><i data-lucide="banknote"></i></div>
                            <h3><?php echo esc_html($service['title']); ?></h3>
                            <p><?php echo esc_html($service['description']); ?></p>
                            <?php if ($meta) : ?>
                                <p><strong><?php esc_html_e('Who:', 'rolkat'); ?></strong> <?php echo esc_html($meta['who']); ?></p>
                                <p><strong><?php esc_html_e('Repayment:', 'rolkat'); ?></strong> <?php echo esc_html($meta['repay']); ?></p>
                            <?php endif; ?>
                            <p><em><?php esc_html_e('Contact us for terms', 'rolkat'); ?></em></p>
                            <a class="text-link" href="<?php echo esc_url(home_url('/contact/?subject=Loans')); ?>"><?php esc_html_e('Enquire', 'rolkat'); ?></a>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="empty-state"><?php esc_html_e('Loan product details are being prepared. Contact us for help.', 'rolkat'); ?></p>
                <?php endif; ?>
            </div>
            <p class="responsible-note" style="margin-top:24px;">
                <strong><?php esc_html_e('Responsible lending:', 'rolkat'); ?></strong>
                <?php esc_html_e('We show the written total cost before you sign. We do not publish interest rates online — contact us for terms that fit your situation.', 'rolkat'); ?>
            </p>
        </div>
    </section>

    <section class="section section--sand" id="property-management">
        <div class="container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><?php echo esc_html($categories['property-management']['label']); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Professional care for landlords.', 'rolkat'); ?></h2>
                </div>
                <div>
                    <p><strong><?php esc_html_e('Who it is for:', 'rolkat'); ?></strong> <?php echo esc_html($categories['property-management']['audience']); ?></p>
                    <a class="button" href="<?php echo esc_url(home_url('/contact/?subject=Property%20management')); ?>"><?php esc_html_e('Enquire', 'rolkat'); ?></a>
                </div>
            </div>
            <div class="checklist-card">
                <h3><?php esc_html_e('Service checklist', 'rolkat'); ?></h3>
                <ul class="check-list">
                    <?php foreach ($pm_checklist as $item) : ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <ol class="loan-timeline" style="margin-top:28px;">
                <?php foreach ($pm_steps as $index => $step) : ?>
                    <li class="loan-timeline__step">
                        <span class="loan-timeline__number"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3><?php echo esc_html($step); ?></h3>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <section class="section" id="real-estate">
        <div class="container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><?php echo esc_html($categories['real-estate']['label']); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Buying, selling, renting and land.', 'rolkat'); ?></h2>
                </div>
                <div>
                    <p><strong><?php esc_html_e('Who it is for:', 'rolkat'); ?></strong> <?php echo esc_html($categories['real-estate']['audience']); ?></p>
                    <a class="button" href="<?php echo esc_url(get_post_type_archive_link('rolkat_property')); ?>"><?php esc_html_e('View listings', 'rolkat'); ?></a>
                </div>
            </div>
            <div class="checklist-card">
                <h3><?php esc_html_e('Safe property buying checklist', 'rolkat'); ?></h3>
                <ul class="check-list">
                    <?php foreach ($safe_buy as $item) : ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <p style="margin-top:24px;">
                <a class="button button--outline" href="<?php echo esc_url(home_url('/contact/?subject=Real%20estate')); ?>"><?php esc_html_e('Enquire about a property', 'rolkat'); ?></a>
            </p>
        </div>
    </section>
</main>
<?php
get_footer();
