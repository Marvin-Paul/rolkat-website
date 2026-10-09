<?php
get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Rolkat Financial Services', 'rolkat'); ?></p>
                <h1><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>
        </header>
        <section class="section">
            <div class="container page-content">
                <?php the_content(); ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
