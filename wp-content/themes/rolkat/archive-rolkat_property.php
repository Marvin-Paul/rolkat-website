<?php
get_header();
?>
<main id="main-content">
    <header class="page-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('Property opportunities', 'rolkat'); ?></p>
            <h1><?php esc_html_e('Explore properties', 'rolkat'); ?></h1>
            <p class="lead"><?php esc_html_e('Browse current listings and contact our team for details or viewing enquiries.', 'rolkat'); ?></p>
        </div>
    </header>
    <section class="section">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content', 'property'); ?>
                    <?php endwhile; ?>
                </div>
                <div class="pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p class="empty-state"><?php esc_html_e('There are no published property listings right now. Contact our team to discuss your requirements.', 'rolkat'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
