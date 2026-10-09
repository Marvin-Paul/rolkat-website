<?php
get_header();
$current_status = isset($_GET['property_status']) ? sanitize_text_field(wp_unslash($_GET['property_status'])) : '';
$current_type = isset($_GET['property_type']) ? sanitize_text_field(wp_unslash($_GET['property_type'])) : '';
$archive_url = get_post_type_archive_link('rolkat_property');
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
            <form class="listing-filters" method="get" action="<?php echo esc_url($archive_url); ?>">
                <div class="field">
                    <label for="property-status"><?php esc_html_e('Status', 'rolkat'); ?></label>
                    <select id="property-status" name="property_status">
                        <option value=""><?php esc_html_e('All statuses', 'rolkat'); ?></option>
                        <?php foreach (rolkat_property_statuses() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($current_status, $value); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="property-type"><?php esc_html_e('Type', 'rolkat'); ?></label>
                    <select id="property-type" name="property_type">
                        <option value=""><?php esc_html_e('All types', 'rolkat'); ?></option>
                        <?php foreach (rolkat_property_types() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($current_type, $value); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="button" type="submit"><?php esc_html_e('Filter', 'rolkat'); ?></button>
                <a class="text-link" href="<?php echo esc_url($archive_url); ?>"><?php esc_html_e('Clear', 'rolkat'); ?></a>
            </form>

            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content', 'property'); ?>
                    <?php endwhile; ?>
                </div>
                <div class="pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p class="empty-state"><?php esc_html_e('No listings match these filters. Contact our team to discuss your requirements.', 'rolkat'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
