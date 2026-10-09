<?php
get_header();
?>
<main id="main-content">
    <section class="page-hero">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('Rolkat Financial Services', 'rolkat'); ?></p>
            <h1><?php esc_html_e('Latest updates', 'rolkat'); ?></h1>
        </div>
    </section>
    <section class="section">
        <div class="container page-content">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class(); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                    </article>
                <?php endwhile; ?>
                <div class="pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p class="empty-state"><?php esc_html_e('There is no content here yet.', 'rolkat'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
