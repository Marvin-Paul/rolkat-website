<?php
get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $location = get_post_meta(get_the_ID(), '_rolkat_property_location', true);
        $price = get_post_meta(get_the_ID(), '_rolkat_property_price', true);
        $status = get_post_meta(get_the_ID(), '_rolkat_property_status', true);
        $type = get_post_meta(get_the_ID(), '_rolkat_property_type', true);
        ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Property listing', 'rolkat'); ?></p>
                <h1><?php the_title(); ?></h1>
                <div class="property-meta">
                    <?php if ($status !== '') : ?><span><?php echo esc_html($status); ?></span><?php endif; ?>
                    <?php if ($type !== '') : ?><span><?php echo esc_html($type); ?></span><?php endif; ?>
                    <?php if ($location !== '') : ?><span><?php echo esc_html($location); ?></span><?php endif; ?>
                    <?php if ($price !== '') : ?><span><?php echo esc_html($price); ?></span><?php endif; ?>
                </div>
            </div>
        </header>
        <section class="section">
            <div class="container page-content">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
                <?php endif; ?>
                <?php the_content(); ?>
                <div class="hero-actions">
                    <a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Enquire about this property', 'rolkat'); ?></a>
                    <a class="button button--outline" href="<?php echo esc_url(get_post_type_archive_link('rolkat_property')); ?>"><?php esc_html_e('Back to listings', 'rolkat'); ?></a>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
