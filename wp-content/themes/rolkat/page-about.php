<?php
/*
Template Name: About
*/
get_header();
$values = array(
    array('title' => __('Speed', 'rolkat'), 'text' => __('Quick decisions so you can keep earning.', 'rolkat')),
    array('title' => __('Honesty', 'rolkat'), 'text' => __('Clear conversations and transparent terms.', 'rolkat')),
    array('title' => __('Fairness', 'rolkat'), 'text' => __('Pricing and collection that respect your dignity.', 'rolkat')),
    array('title' => __('Respect', 'rolkat'), 'text' => __('Warm service for every customer who walks in.', 'rolkat')),
    array('title' => __('Discipline', 'rolkat'), 'text' => __('Reliable processes behind every loan and listing.', 'rolkat')),
    array('title' => __('Accountability', 'rolkat'), 'text' => __('We own our commitments to clients and partners.', 'rolkat')),
);
$map = rolkat_get_map_embed();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Who we are', 'rolkat'); ?></p>
                <span class="section-title-accent" aria-hidden="true"></span>
                <h1><?php the_title(); ?></h1>
                <p class="lead"><?php esc_html_e('A Ugandan company delivering financial services, property management and real estate with one promise: serving you better.', 'rolkat'); ?></p>
            </div>
        </header>

        <section class="section">
            <div class="container about-intro split">
                <div class="about-story-media reveal">
                    <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholders/market-vendor.svg'); ?>"
                        alt="<?php esc_attr_e('ROLKAT team serving customers along Entebbe Road in Zana', 'rolkat'); ?>"
                        width="560"
                        height="400"
                        loading="lazy"
                    >
                </div>
                <div class="page-content reveal reveal-delay-1">
                    <?php if (get_the_content()) : ?>
                        <?php the_content(); ?>
                    <?php else : ?>
                        <p><?php esc_html_e('Rolkat Financial Services SMC Ltd is based at Hanora Plaza in Zana, along Entebbe Road. We serve ordinary working people and small enterprises — including boda boda cyclists, small retailers and market vendors — with fast, flexible credit, and we support property owners, buyers and tenants with professional management and real estate advice.', 'rolkat'); ?></p>
                    <?php endif; ?>
                    <div class="mvv-grid" style="margin-top:28px;">
                        <article class="mvv-card">
                            <h3><?php esc_html_e('Mission', 'rolkat'); ?></h3>
                            <p><?php esc_html_e('To serve customers better by giving fast, fair and flexible access to credit, and professional management and advice on property.', 'rolkat'); ?></p>
                        </article>
                        <article class="mvv-card">
                            <h3><?php esc_html_e('Vision', 'rolkat'); ?></h3>
                            <p><?php esc_html_e('To be the most trusted provider of financial, property and real estate services for everyday Ugandans and small businesses.', 'rolkat'); ?></p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section--sand">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('Our values', 'rolkat'); ?></p>
                        <span class="section-title-accent" aria-hidden="true"></span>
                        <h2><?php esc_html_e('What guides every conversation.', 'rolkat'); ?></h2>
                    </div>
                </div>
                <div class="values-grid">
                    <?php foreach ($values as $value) : ?>
                        <article class="value-card">
                            <div class="icon-circle" aria-hidden="true"><i data-lucide="heart-handshake"></i></div>
                            <h3><?php echo esc_html($value['title']); ?></h3>
                            <p><?php echo esc_html($value['text']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('Compliance', 'rolkat'); ?></p>
                        <span class="section-title-accent" aria-hidden="true"></span>
                        <h2><?php esc_html_e('Registration and licence.', 'rolkat'); ?></h2>
                    </div>
                </div>
                <div class="licence-panel">
                    <p><strong><?php esc_html_e('Company registration no.', 'rolkat'); ?></strong> <?php esc_html_e('[to be supplied]', 'rolkat'); ?></p>
                    <p><strong><?php esc_html_e('UMRA licence no.', 'rolkat'); ?></strong> <?php esc_html_e('[to be supplied]', 'rolkat'); ?></p>
                    <p><?php esc_html_e('Licensed lender badge placeholder — replace with official numbers before launch.', 'rolkat'); ?></p>
                </div>
            </div>
        </section>

        <section class="section section--sand">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('Visit us', 'rolkat'); ?></p>
                        <span class="section-title-accent" aria-hidden="true"></span>
                        <h2><?php esc_html_e('Hanora Plaza, Zana.', 'rolkat'); ?></h2>
                    </div>
                    <p><?php echo nl2br(esc_html(rolkat_get_address())); ?></p>
                </div>
                <?php if ($map !== '') : ?>
                    <div class="map-embed">
                        <iframe
                            title="<?php esc_attr_e('ROLKAT office location map at Hanora Plaza, Zana', 'rolkat'); ?>"
                            src="<?php echo esc_url($map); ?>"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
