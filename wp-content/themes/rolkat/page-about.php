<?php
/*
Template Name: About
*/
get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Who we are', 'rolkat'); ?></p>
                <h1><?php the_title(); ?></h1>
                <p class="lead"><?php esc_html_e('A Ugandan company delivering financial services, property management and real estate with one promise: serving you better.', 'rolkat'); ?></p>
            </div>
        </header>

        <section class="section">
            <div class="container about-intro">
                <div class="page-content">
                    <?php if (get_the_content()) : ?>
                        <?php the_content(); ?>
                    <?php else : ?>
                        <p><?php esc_html_e('Rolkat Financial Services SMC Ltd is based at Hanora Plaza in Zana, along Entebbe Road. We serve ordinary working people and small enterprises — including boda boda cyclists, small retailers and market vendors — with fast, flexible credit, and we support property owners, buyers and tenants with professional management and real estate advice.', 'rolkat'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="section section--sand">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('Direction', 'rolkat'); ?></p>
                        <h2><?php esc_html_e('Mission, vision and values.', 'rolkat'); ?></h2>
                    </div>
                </div>
                <div class="mvv-grid">
                    <article class="mvv-card">
                        <h3><?php esc_html_e('Vision', 'rolkat'); ?></h3>
                        <p><?php esc_html_e('To be the most trusted provider of financial, property and real estate services for everyday Ugandans and small businesses.', 'rolkat'); ?></p>
                    </article>
                    <article class="mvv-card">
                        <h3><?php esc_html_e('Mission', 'rolkat'); ?></h3>
                        <p><?php esc_html_e('To serve customers better by giving fast, fair and flexible access to credit, and professional management and advice on property.', 'rolkat'); ?></p>
                    </article>
                    <article class="mvv-card">
                        <h3><?php esc_html_e('Values', 'rolkat'); ?></h3>
                        <p><?php esc_html_e('Speed, honesty and transparency, fairness, respect for the customer, discipline, and accountability.', 'rolkat'); ?></p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('How we help', 'rolkat'); ?></p>
                        <h2><?php esc_html_e('Our three business lines.', 'rolkat'); ?></h2>
                    </div>
                </div>
                <div class="pillar-grid">
                    <?php foreach (rolkat_service_categories() as $slug => $category) : ?>
                        <article class="pillar-card">
                            <h3><?php echo esc_html($category['label']); ?></h3>
                            <p><?php echo esc_html($category['summary']); ?></p>
                            <a class="button button--outline" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Enquire', 'rolkat'); ?></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
