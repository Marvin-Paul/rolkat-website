<?php
get_header();
$properties = new WP_Query(array(
    'post_type' => 'rolkat_property',
    'posts_per_page' => 3,
    'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
    'meta_query' => array(
        array(
            'key' => '_rolkat_property_status',
            'value' => 'Available',
        ),
    ),
));
if (!$properties->have_posts()) {
    $properties = new WP_Query(array(
        'post_type' => 'rolkat_property',
        'posts_per_page' => 3,
        'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
    ));
}
$hero_title = get_theme_mod('rolkat_hero_title', __('Serving you better.', 'rolkat'));
$hero_text = get_theme_mod(
    'rolkat_hero_text',
    __('Fast, fair loans and professional property services for everyday Ugandans and small businesses — from Entebbe Road in Zana.', 'rolkat')
);
$categories = rolkat_service_categories();
$category_animations = array(
    'loans' => 'finance-growth.json',
    'property-management' => 'property-care.json',
    'real-estate' => 'verified-location.json',
);
?>
<main id="main-content">
    <section class="hero">
        <div class="container hero-inner">
            <p class="brand-lockup"><?php esc_html_e('Rolkat Financial Services', 'rolkat'); ?></p>
            <p class="eyebrow"><?php esc_html_e('Financial services · Property management · Real estate', 'rolkat'); ?></p>
            <h1><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-copy"><?php echo esc_html($hero_text); ?></p>
            <div class="hero-actions">
                <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact us', 'rolkat'); ?></a>
                <a class="button button--outline" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('Explore services', 'rolkat'); ?></a>
            </div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('What we do', 'rolkat'); ?></p>
                    <h2><?php esc_html_e('Three connected service lines.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Whether you need quick credit, trusted property care or help buying and selling land, ROLKAT is built to serve you better.', 'rolkat'); ?></p>
            </div>
            <div class="pillar-grid reveal reveal-delay-1">
                <?php foreach ($categories as $slug => $category) : ?>
                    <article class="pillar-card">
                        <?php if (isset($category_animations[$slug])) : ?>
                            <div
                                class="lottie-visual"
                                data-lottie-src="<?php echo esc_url(get_template_directory_uri() . '/assets/lottie/' . $category_animations[$slug]); ?>"
                                aria-hidden="true"
                            ></div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($category['label']); ?></h3>
                        <p><?php echo esc_html($category['summary']); ?></p>
                        <p class="pillar-audience"><strong><?php esc_html_e('For:', 'rolkat'); ?></strong> <?php echo esc_html($category['audience']); ?></p>
                        <a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service') . '#' . $slug); ?>"><?php esc_html_e('How to get started', 'rolkat'); ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section--sand">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Why ROLKAT', 'rolkat'); ?></p>
                    <h2><?php esc_html_e('Reasons to choose us.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Practical support, clear communication and a local team that understands how you earn and grow.', 'rolkat'); ?></p>
            </div>
            <div class="trust-grid reveal reveal-delay-1">
                <?php foreach (rolkat_trust_points() as $point) : ?>
                    <article class="trust-card">
                        <h3><?php echo esc_html($point['title']); ?></h3>
                        <p><?php echo esc_html($point['text']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Property opportunities', 'rolkat'); ?></p>
                    <h2><?php esc_html_e('Featured listings.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Browse current properties and enquire with our team for viewings or details.', 'rolkat'); ?></p>
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

    <section class="section section--sand">
        <div class="container split">
            <div class="split-panel reveal">
                <div class="split-panel-inner">
                    <p class="eyebrow"><?php esc_html_e('Rolkat Financial Services SMC Ltd', 'rolkat'); ?></p>
                    <h3><?php esc_html_e('Serving you better is more than a promise.', 'rolkat'); ?></h3>
                    <p><?php esc_html_e('It is the care we bring to every conversation, decision and relationship.', 'rolkat'); ?></p>
                </div>
            </div>
            <div class="split-copy reveal reveal-delay-2">
                <p class="eyebrow"><?php esc_html_e('About us', 'rolkat'); ?></p>
                <h2><?php esc_html_e('Built for everyday Ugandans and small businesses.', 'rolkat'); ?></h2>
                <p><?php esc_html_e('ROLKAT combines lending, property management and real estate so customers who need money and customers who own or want property can work with one trusted partner.', 'rolkat'); ?></p>
                <ul class="check-list">
                    <li><?php esc_html_e('Speed, honesty and transparency', 'rolkat'); ?></li>
                    <li><?php esc_html_e('Fair, respectful service', 'rolkat'); ?></li>
                    <li><?php esc_html_e('Discipline and accountability', 'rolkat'); ?></li>
                </ul>
                <a class="button" href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('Get to know us', 'rolkat'); ?></a>
            </div>
        </div>
    </section>

    <section class="section cta-band">
        <div class="container cta-band-inner reveal">
            <div>
                <p class="eyebrow"><?php esc_html_e('Ready when you are', 'rolkat'); ?></p>
                <h2><?php esc_html_e('Talk to ROLKAT today.', 'rolkat'); ?></h2>
                <p><?php esc_html_e('Call, WhatsApp or send a message — we will respond promptly.', 'rolkat'); ?></p>
            </div>
            <div class="hero-actions">
                <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact us', 'rolkat'); ?></a>
                <a class="button button--outline" href="<?php echo esc_url(rolkat_tel_href(rolkat_get_phone())); ?>"><?php echo esc_html(rolkat_get_phone()); ?></a>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
