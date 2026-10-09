<?php
get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <?php $role = get_post_meta(get_the_ID(), '_rolkat_team_role', true); ?>
        <header class="page-hero">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Our team', 'rolkat'); ?></p>
                <h1><?php the_title(); ?></h1>
                <?php if ($role !== '') : ?>
                    <p class="lead"><?php echo esc_html($role); ?></p>
                <?php endif; ?>
            </div>
        </header>
        <section class="section">
            <div class="container page-content team-single">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
                <?php endif; ?>
                <?php the_content(); ?>
                <p><a class="button" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact our team', 'rolkat'); ?></a></p>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
