<article <?php post_class('service-card'); ?>>
    <span class="service-card-number"><?php esc_html_e('Service', 'rolkat'); ?></span>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p><?php echo esc_html(has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 24)); ?></p>
    <a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Learn more', 'rolkat'); ?></a>
</article>
