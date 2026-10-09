<?php
get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Our services', 'rolkat'); ?></p>
                <h1><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>
        </header>
        <section class="section">
            <div class="container page-content">
                <?php the_content(); ?>
                <p><a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Ask us about this service', 'rolkat'); ?></a></p>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
