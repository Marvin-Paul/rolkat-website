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
$categories = rolkat_service_categories();
$category_icons = array(
    'loans' => 'banknote',
    'property-management' => 'building-2',
    'real-estate' => 'home',
);
$whatsapp_url = rolkat_whatsapp_url();
$phone = rolkat_get_phone();
?>
<main id="main-content">
    <section class="hero hero--split">
        <div class="container hero-split">
            <div class="hero-copy-block reveal">
                <p class="eyebrow"><?php esc_html_e('ROLKAT Financial Services', 'rolkat'); ?></p>
                <h1><?php esc_html_e('Quick loans. Trusted property services. Serving you better.', 'rolkat'); ?></h1>
                <p class="hero-copy"><?php esc_html_e('Fast, flexible and respectful support for boda boda riders, market vendors, small shop owners, landlords and property buyers across Uganda.', 'rolkat'); ?></p>
                <div class="hero-actions">
                    <a class="button" href="<?php echo esc_url(home_url('/contact/?subject=Loans')); ?>"><?php esc_html_e('Get a Quick Loan', 'rolkat'); ?></a>
                    <a class="button button--outline" href="<?php echo esc_url(get_post_type_archive_link('rolkat_property')); ?>"><?php esc_html_e('View Properties', 'rolkat'); ?></a>
                </div>
                <ul class="trust-chips" aria-label="<?php esc_attr_e('Trust highlights', 'rolkat'); ?>">
                    <li><?php esc_html_e('24-hour approval', 'rolkat'); ?></li>
                    <li><?php esc_html_e('Daily or weekly repayment', 'rolkat'); ?></li>
                    <li><?php esc_html_e('Flexible terms', 'rolkat'); ?></li>
                </ul>
            </div>
            <div class="hero-visual reveal reveal-delay-1" aria-hidden="false">
                <div class="hero-collage">
                    <img
                        class="hero-collage__main"
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholders/boda-rider.svg'); ?>"
                        alt="<?php esc_attr_e('Boda boda rider in Kampala representing everyday borrowers ROLKAT serves', 'rolkat'); ?>"
                        width="640"
                        height="480"
                        loading="eager"
                    >
                    <img
                        class="hero-collage__secondary"
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholders/market-vendor.svg'); ?>"
                        alt="<?php esc_attr_e('Market vendor at a stall representing flexible daily lending', 'rolkat'); ?>"
                        width="280"
                        height="200"
                        loading="lazy"
                    >
                    <img
                        class="hero-collage__tertiary"
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholders/modern-house.svg'); ?>"
                        alt="<?php esc_attr_e('Modern house and apartment representing ROLKAT property services', 'rolkat'); ?>"
                        width="240"
                        height="180"
                        loading="lazy"
                    >
                    <div class="hero-glass-card">
                        <strong><?php esc_html_e('Approved in 24 hours', 'rolkat'); ?></strong>
                        <span><?php esc_html_e('Clear terms before you sign', 'rolkat'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('What we do', 'rolkat'); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Three ways we serve you better.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Loans, property management and real estate — one trusted local partner on Entebbe Road.', 'rolkat'); ?></p>
            </div>
            <div class="pillar-grid reveal reveal-delay-1">
                <?php foreach ($categories as $slug => $category) : ?>
                    <article class="pillar-card">
                        <div class="icon-circle" aria-hidden="true">
                            <i data-lucide="<?php echo esc_attr($category_icons[$slug] ?? 'circle'); ?>"></i>
                        </div>
                        <h3><?php echo esc_html($category['label']); ?></h3>
                        <p><?php echo esc_html($category['summary']); ?></p>
                        <a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service') . '#' . $slug); ?>"><?php esc_html_e('Learn more', 'rolkat'); ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section--sand">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Who we serve', 'rolkat'); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Built for everyday Ugandans.', 'rolkat'); ?></h2>
                </div>
            </div>
            <ul class="audience-strip reveal reveal-delay-1">
                <?php foreach (rolkat_audiences() as $audience) : ?>
                    <li><?php echo esc_html($audience); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <section class="section" id="loan-process">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('How a loan works', 'rolkat'); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Four clear steps to funding.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Simple process, respectful service, and repayment that fits how you earn.', 'rolkat'); ?></p>
            </div>
            <ol class="loan-timeline reveal reveal-delay-1">
                <?php foreach (rolkat_loan_steps() as $index => $step) : ?>
                    <li class="loan-timeline__step">
                        <span class="loan-timeline__number"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3><?php echo esc_html($step['title']); ?></h3>
                        <p><?php echo esc_html($step['text']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <section class="section section--sand" id="properties">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Featured properties', 'rolkat'); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Homes, land and rentals worth a look.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Browse current listings and enquire with our team for viewings or details.', 'rolkat'); ?></p>
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
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Why choose ROLKAT', 'rolkat'); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('Trust you can feel.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Practical support, clear communication and a local team that understands how you earn and grow.', 'rolkat'); ?></p>
            </div>
            <div class="trust-grid reveal reveal-delay-1">
                <?php foreach (rolkat_trust_points() as $point) : ?>
                    <article class="trust-card">
                        <div class="icon-circle" aria-hidden="true"><i data-lucide="shield-check"></i></div>
                        <h3><?php echo esc_html($point['title']); ?></h3>
                        <p><?php echo esc_html($point['text']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="licence-badge reveal"><?php esc_html_e('Licensed lender', 'rolkat'); ?> · <span><?php esc_html_e('[licence no. to be supplied]', 'rolkat'); ?></span></p>
        </div>
    </section>

    <section class="section section--sand" aria-label="<?php esc_attr_e('Optional testimonials', 'rolkat'); ?>">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Optional', 'rolkat'); ?></p>
                    <span class="section-title-accent" aria-hidden="true"></span>
                    <h2><?php esc_html_e('What customers say.', 'rolkat'); ?></h2>
                </div>
                <p><?php esc_html_e('Placeholder testimonials — replace with real quotes when ready.', 'rolkat'); ?></p>
            </div>
            <div class="testimonial-grid reveal reveal-delay-1">
                <?php
                $placeholders = array(
                    array('name' => 'Aisha N.', 'role' => 'Market vendor', 'quote' => 'Placeholder: daily repayment that matches my stall sales.'),
                    array('name' => 'Joseph K.', 'role' => 'Boda rider', 'quote' => 'Placeholder: approved quickly when my motorcycle needed repairs.'),
                    array('name' => 'Grace M.', 'role' => 'Landlord', 'quote' => 'Placeholder: clear monthly statements for my rental units.'),
                );
                foreach ($placeholders as $item) :
                    ?>
                    <blockquote class="testimonial-card">
                        <p>“<?php echo esc_html($item['quote']); ?>”</p>
                        <footer>
                            <strong><?php echo esc_html($item['name']); ?></strong>
                            <span><?php echo esc_html($item['role']); ?></span>
                        </footer>
                    </blockquote>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section cta-band">
        <div class="container cta-band-inner reveal">
            <div>
                <p class="eyebrow"><?php esc_html_e('Ready when you are', 'rolkat'); ?></p>
                <h2><?php esc_html_e('Need cash fast? Talk to us today.', 'rolkat'); ?></h2>
                <p><?php esc_html_e('WhatsApp or call — we will respond promptly.', 'rolkat'); ?></p>
            </div>
            <div class="hero-actions">
                <?php if ($whatsapp_url) : ?>
                    <a class="button" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('WhatsApp', 'rolkat'); ?></a>
                <?php endif; ?>
                <a class="button button--outline" href="<?php echo esc_url(rolkat_tel_href($phone)); ?>"><?php esc_html_e('Call', 'rolkat'); ?> <?php echo esc_html($phone); ?></a>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
