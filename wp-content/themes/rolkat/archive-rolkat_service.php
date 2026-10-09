<?php
get_header();
?>
<main id="main-content">
    <header class="page-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('How we can help', 'rolkat'); ?></p>
            <h1><?php esc_html_e('Our services', 'rolkat'); ?></h1>
            <p class="lead"><?php esc_html_e('Explore financial and property services designed to help you make your next move with confidence.', 'rolkat'); ?></p>
        </div>
    </header>
    <section class="section">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content', 'service'); ?>
                    <?php endwhile; ?>
                </div>
                <div class="pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <div class="card-grid">
                    <?php foreach (rolkat_default_services() as $service) : ?>
                        <article class="service-card">
                            <span class="service-card-number"><?php echo esc_html($service['number']); ?></span>
                            <h2><?php echo esc_html($service['title']); ?></h2>
                            <p><?php echo esc_html($service['description']); ?></p>
                            <a class="text-link" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Ask us about this service', 'rolkat'); ?></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
