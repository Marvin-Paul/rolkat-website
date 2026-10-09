<?php
get_header();
$services = new WP_Query(array(
    'post_type' => 'rolkat_service',
    'posts_per_page' => 3,
    'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
));
$properties = new WP_Query(array(
    'post_type' => 'rolkat_property',
    'posts_per_page' => 3,
    'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
));
$hero_title = get_theme_mod('rolkat_hero_title', __('A better way to move forward.', 'rolkat'));
$hero_text = get_theme_mod('rolkat_hero_text', __('Thoughtful financial solutions and property services, shaped around your goals and built on relationships you can trust.', 'rolkat'));
?>
<main id="main-content">
    <section class="hero">
        <div class="container hero-inner">
            <p class="eyebrow"><?php esc_html_e('Financial services · Property · Real estate', 'rolkat'); ?></p>
            <h1><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-copy"><?php echo esc_html($hero_text); ?></p>
            <div class="hero-actions">
                <a class="button" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('Explore our services', 'rolkat'); ?></a>
                <a class="button button--outline" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Talk to our team', 'rolkat'); ?></a>
            </div>
            <div class="hero-note"><?php esc_html_e('Serving you better', 'rolkat'); ?></div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><?php esc_html_e('What we do', 'rolkat'); ?></p>
                    <h2><?php esc_html_e('Comprehensive financial, property and real estate solutions.', 'rolkat'); ?></h2>
                </div>
                <div class="services-heading-side">
                    <p><?php esc_html_e('From fast and flexible credit with 24-hour approval to dedicated property care and safe land acquisitions, explore all our services below.', 'rolkat'); ?></p>
                    <div class="marquee-controls" aria-label="<?php esc_attr_e('Services carousel controls', 'rolkat'); ?>">
                        <span class="marquee-hint"><i data-lucide="info" style="width:13px;height:13px;"></i> <?php esc_html_e('Hover to pause', 'rolkat'); ?></span>
                        <button type="button" class="marquee-btn" id="marquee-toggle-btn" aria-label="<?php esc_attr_e('Pause moving cards', 'rolkat'); ?>" title="<?php esc_attr_e('Pause / Play animation', 'rolkat'); ?>">
                            <i data-lucide="pause" id="marquee-toggle-icon" style="width:14px;height:14px;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="services-marquee-wrapper" id="services-marquee" role="region" aria-label="<?php esc_attr_e('Continuous Services Carousel', 'rolkat'); ?>">
            <div class="services-marquee-track" id="services-track">
                <div class="services-marquee-group" id="services-group-1">
                    <?php if ($services->have_posts()) : ?>
                        <?php while ($services->have_posts()) : $services->the_post(); ?>
                            <?php get_template_part('template-parts/content', 'service'); ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <?php foreach (rolkat_default_services() as $service_default) : ?>
                            <article class="service-card">
                                <span class="service-card-number"><?php echo esc_html($service_default['number']); ?></span>
                                <h3><?php echo esc_html($service_default['title']); ?></h3>
                                <p><?php echo esc_html($service_default['description']); ?></p>
                                <a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('Learn more', 'rolkat'); ?></a>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="services-marquee-group" id="services-group-2" aria-hidden="true">
                    <?php if ($services->have_posts()) : ?>
                        <?php while ($services->have_posts()) : $services->the_post(); ?>
                            <?php get_template_part('template-parts/content', 'service'); ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <?php foreach (rolkat_default_services() as $service_default) : ?>
                            <article class="service-card">
                                <span class="service-card-number"><?php echo esc_html($service_default['number']); ?></span>
                                <h3><?php echo esc_html($service_default['title']); ?></h3>
                                <p><?php echo esc_html($service_default['description']); ?></p>
                                <a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('Learn more', 'rolkat'); ?></a>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--sand">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Property opportunities', 'rolkat'); ?></p>
                    <h2><?php esc_html_e('Find a place for what comes next.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Explore current listings and get in touch with our team to learn more.', 'rolkat'); ?></p>
            </div>
            <?php if ($properties->have_posts()) : ?>
                <div class="card-grid reveal reveal-delay-1">
                    <?php while ($properties->have_posts()) : $properties->the_post(); ?>
                        <?php get_template_part('template-parts/content', 'property'); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <p><a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('rolkat_property')); ?>"><?php esc_html_e('View all properties', 'rolkat'); ?></a></p>
            <?php else : ?>
                <p class="empty-state"><?php esc_html_e('New listings are on the way. Contact us to discuss what you are looking for.', 'rolkat'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <div class="container split">
            <div class="split-panel reveal">
                <div class="split-panel-inner">
                    <p class="eyebrow"><?php esc_html_e('Rolkat Financial Services SMC Ltd', 'rolkat'); ?></p>
                    <h3><?php esc_html_e('Serving you better is more than a promise.', 'rolkat'); ?></h3>
                    <p><?php esc_html_e('It is the care we bring to every conversation, decision and relationship.', 'rolkat'); ?></p>
                </div>
            </div>
            <div class="split-copy reveal reveal-delay-2">
                <p class="eyebrow"><?php esc_html_e('A little about us', 'rolkat'); ?></p>
                <h2><?php esc_html_e('Good decisions start with a good conversation.', 'rolkat'); ?></h2>
                <p><?php esc_html_e('ROLKAT brings financial services, property management and real estate together under one roof. We take time to understand what matters to you, then help you find a practical way forward.', 'rolkat'); ?></p>
                <ul class="check-list">
                    <li><?php esc_html_e('Personal support grounded in your goals', 'rolkat'); ?></li>
                    <li><?php esc_html_e('Clear guidance through every step', 'rolkat'); ?></li>
                    <li><?php esc_html_e('A connected view of finance and property', 'rolkat'); ?></li>
                </ul>
                <a class="button" href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('Get to know us', 'rolkat'); ?></a>
            </div>
        </div>
    </section>

    <section class="section" id="loan-calculator">
        <div class="container calculator reveal">
            <div>
                <p class="eyebrow"><?php esc_html_e('Plan with confidence', 'rolkat'); ?></p>
                <h2><?php esc_html_e('A clearer picture of your loan.', 'rolkat'); ?></h2>
                <p class="calculator-copy"><?php esc_html_e('Example inputs only. Your actual rate and repayment may differ; this estimate is not a loan offer or final quotation and excludes fees.', 'rolkat'); ?></p>
                <a class="button button--outline" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Discuss your options', 'rolkat'); ?></a>
            </div>
            <form class="calculator-fields" data-loan-calculator>
                <div class="field">
                    <label for="loan-amount"><?php esc_html_e('Loan amount (UGX)', 'rolkat'); ?></label>
                    <input id="loan-amount" name="amount" type="number" min="100000" max="10000000000" step="100000" value="5000000" required>
                </div>
                <div class="field">
                    <label for="loan-rate"><?php esc_html_e('Annual interest rate (%)', 'rolkat'); ?></label>
                    <input id="loan-rate" name="rate" type="number" min="0" max="100" step="0.1" value="18" required>
                </div>
                <div class="field">
                    <label for="loan-term"><?php esc_html_e('Repayment term (years)', 'rolkat'); ?></label>
                    <input id="loan-term" name="term" type="number" min="1" max="30" step="1" value="3" required>
                </div>
                <div class="calculator-result" aria-live="polite" aria-atomic="true">
                    <span><?php esc_html_e('Estimated monthly repayment', 'rolkat'); ?></span>
                    <strong data-loan-result>—</strong>
                </div>
            </form>
        </div>
    </section>
</main>
<?php
get_footer();
