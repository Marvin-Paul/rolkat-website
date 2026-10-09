<?php
$location = get_post_meta(get_the_ID(), '_rolkat_property_location', true);
$price = get_post_meta(get_the_ID(), '_rolkat_property_price', true);
$status = get_post_meta(get_the_ID(), '_rolkat_property_status', true);
$type = get_post_meta(get_the_ID(), '_rolkat_property_type', true);
?>
<article <?php post_class('property-card'); ?>>
    <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('View property: %s', 'rolkat'), get_the_title())); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium_large', array('alt' => get_the_title())); ?>
        <?php else : ?>
            <div class="property-card-placeholder" aria-hidden="true"></div>
        <?php endif; ?>
    </a>
    <div class="property-card-body">
        <div class="property-card-tags">
            <?php if ($status !== '') : ?><span class="property-card-status"><?php echo esc_html($status); ?></span><?php endif; ?>
            <?php if ($type !== '') : ?><span class="property-card-type"><?php echo esc_html($type); ?></span><?php endif; ?>
        </div>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php if ($location !== '' || $price !== '') : ?>
            <div class="property-meta">
                <?php if ($location !== '') : ?><span><?php echo esc_html($location); ?></span><?php endif; ?>
                <?php if ($price !== '') : ?><strong><?php echo esc_html($price); ?></strong><?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (has_excerpt()) : ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
        <a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e('View listing', 'rolkat'); ?></a>
    </div>
</article>
