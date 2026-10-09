<?php
get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $category_key = get_post_meta(get_the_ID(), '_rolkat_service_category', true);
        $categories = rolkat_service_categories();
        $category_label = isset($categories[$category_key]) ? $categories[$category_key]['label'] : __('Service', 'rolkat');
        ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php echo esc_html($category_label); ?></p>
                <h1><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>
        </header>
        <section class="section">
            <div class="container page-content">
                <?php the_content(); ?>
                <div class="hero-actions">
                    <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Enquire / Contact us', 'rolkat'); ?></a>
                    <a class="button button--outline" href="<?php echo esc_url(get_post_type_archive_link('rolkat_service')); ?>"><?php esc_html_e('All services', 'rolkat'); ?></a>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
