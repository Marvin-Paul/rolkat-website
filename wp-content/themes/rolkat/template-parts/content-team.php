<article <?php post_class('team-card'); ?>>
    <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Read about %s', 'rolkat'), get_the_title())); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium_large'); ?>
        <?php else : ?>
            <div class="team-card-placeholder" aria-hidden="true"></div>
        <?php endif; ?>
    </a>
    <div class="team-card-body">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php if (has_excerpt()) : ?>
            <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php endif; ?>
    </div>
</article>
