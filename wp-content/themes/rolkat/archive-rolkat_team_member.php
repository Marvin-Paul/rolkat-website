<?php
get_header();
?>
<main id="main-content">
    <header class="page-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('The people behind the promise', 'rolkat'); ?></p>
            <h1><?php esc_html_e('Meet our team', 'rolkat'); ?></h1>
            <p class="lead"><?php esc_html_e('Get to know the people here to listen, guide and support your next step.', 'rolkat'); ?></p>
        </div>
    </header>
    <section class="section">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content', 'team'); ?>
                    <?php endwhile; ?>
                </div>
                <div class="pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p class="empty-state"><?php esc_html_e('Our team profiles are coming soon. Please contact us to get in touch.', 'rolkat'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
