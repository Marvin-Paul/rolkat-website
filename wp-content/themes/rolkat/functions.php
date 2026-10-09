<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/defaults.php';

function rolkat_setup()
{
    load_theme_textdomain('rolkat', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('style.css');

    register_nav_menus(array(
        'primary' => __('Primary navigation', 'rolkat'),
        'footer' => __('Footer navigation', 'rolkat'),
    ));
}
add_action('after_setup_theme', 'rolkat_setup');

function rolkat_fallback_menu()
{
    $links = array(
        __('Home', 'rolkat') => home_url('/'),
        __('About Us', 'rolkat') => home_url('/about/'),
        __('Services', 'rolkat') => get_post_type_archive_link('rolkat_service'),
        __('Team', 'rolkat') => get_post_type_archive_link('rolkat_team_member'),
        __('Contact', 'rolkat') => home_url('/contact/'),
    );

    echo '<ul>';
    foreach ($links as $label => $url) {
        if ($url) {
            printf('<li><a href="%1$s">%2$s</a></li>', esc_url($url), esc_html($label));
        }
    }
    echo '</ul>';
}

function rolkat_enqueue_assets()
{
    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'rolkat-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap',
        array(),
        null
    );
    wp_enqueue_style('rolkat-style', get_stylesheet_uri(), array('rolkat-fonts'), $theme_version);
    wp_enqueue_script(
        'rolkat-lucide',
        get_template_directory_uri() . '/assets/js/lucide.min.js',
        array(),
        '1.54.0',
        true
    );
    wp_enqueue_script(
        'rolkat-lottie',
        get_template_directory_uri() . '/assets/js/lottie.min.js',
        array(),
        '5.13.0',
        true
    );
    wp_enqueue_script(
        'rolkat-site',
        get_template_directory_uri() . '/assets/js/site.js',
        array('rolkat-lucide', 'rolkat-lottie'),
        $theme_version,
        true
    );

    wp_localize_script('rolkat-site', 'rolkatTheme', array(
        'isWordPress' => true,
    ));
}
add_action('wp_enqueue_scripts', 'rolkat_enqueue_assets');

function rolkat_register_content_types()
{
    register_post_type('rolkat_service', array(
        'labels' => array(
            'name' => __('Services', 'rolkat'),
            'singular_name' => __('Service', 'rolkat'),
            'add_new_item' => __('Add a service', 'rolkat'),
            'edit_item' => __('Edit service', 'rolkat'),
            'menu_name' => __('Services', 'rolkat'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-chart-area',
        'rewrite' => array('slug' => 'services'),
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
    ));

    register_post_type('rolkat_team_member', array(
        'labels' => array(
            'name' => __('Team', 'rolkat'),
            'singular_name' => __('Team member', 'rolkat'),
            'add_new_item' => __('Add a team member', 'rolkat'),
            'edit_item' => __('Edit team member', 'rolkat'),
            'menu_name' => __('Team', 'rolkat'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-groups',
        'rewrite' => array('slug' => 'team'),
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
    ));

    register_post_type('rolkat_property', array(
        'labels' => array(
            'name' => __('Properties', 'rolkat'),
            'singular_name' => __('Property', 'rolkat'),
            'add_new_item' => __('Add a property listing', 'rolkat'),
            'edit_item' => __('Edit property listing', 'rolkat'),
            'menu_name' => __('Properties', 'rolkat'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-admin-home',
        'rewrite' => array('slug' => 'properties'),
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
    ));

    register_post_type('rolkat_enquiry', array(
        'labels' => array(
            'name' => __('Enquiries', 'rolkat'),
            'singular_name' => __('Enquiry', 'rolkat'),
            'menu_name' => __('Enquiries', 'rolkat'),
            'edit_item' => __('View enquiry', 'rolkat'),
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => array('title'),
    ));
}
add_action('init', 'rolkat_register_content_types');

function rolkat_register_meta_boxes()
{
    add_meta_box(
        'rolkat-property-details',
        __('Property details', 'rolkat'),
        'rolkat_render_property_meta_box',
        'rolkat_property',
        'normal',
        'high'
    );

    add_meta_box(
        'rolkat-service-details',
        __('Service category', 'rolkat'),
        'rolkat_render_service_meta_box',
        'rolkat_service',
        'side',
        'default'
    );

    add_meta_box(
        'rolkat-team-details',
        __('Team details', 'rolkat'),
        'rolkat_render_team_meta_box',
        'rolkat_team_member',
        'side',
        'default'
    );

    add_meta_box(
        'rolkat-enquiry-details',
        __('Enquiry details', 'rolkat'),
        'rolkat_render_enquiry_meta_box',
        'rolkat_enquiry',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'rolkat_register_meta_boxes');

function rolkat_render_property_meta_box($post)
{
    wp_nonce_field('rolkat_save_property_details', 'rolkat_property_details_nonce');
    $location = get_post_meta($post->ID, '_rolkat_property_location', true);
    $price = get_post_meta($post->ID, '_rolkat_property_price', true);
    $status = get_post_meta($post->ID, '_rolkat_property_status', true);
    $type = get_post_meta($post->ID, '_rolkat_property_type', true);
    ?>
    <p>
        <label for="rolkat-property-location"><?php esc_html_e('Location', 'rolkat'); ?></label><br>
        <input class="widefat" id="rolkat-property-location" name="rolkat_property_location" type="text" value="<?php echo esc_attr($location); ?>">
    </p>
    <p>
        <label for="rolkat-property-price"><?php esc_html_e('Display price (optional)', 'rolkat'); ?></label><br>
        <input class="widefat" id="rolkat-property-price" name="rolkat_property_price" type="text" value="<?php echo esc_attr($price); ?>">
    </p>
    <p>
        <label for="rolkat-property-status"><?php esc_html_e('Listing status', 'rolkat'); ?></label><br>
        <select id="rolkat-property-status" name="rolkat_property_status">
            <option value=""><?php esc_html_e('Select a status', 'rolkat'); ?></option>
            <?php foreach (rolkat_property_statuses() as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($status, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="rolkat-property-type"><?php esc_html_e('Listing type', 'rolkat'); ?></label><br>
        <select id="rolkat-property-type" name="rolkat_property_type">
            <option value=""><?php esc_html_e('Select a type', 'rolkat'); ?></option>
            <?php foreach (rolkat_property_types() as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($type, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

function rolkat_render_service_meta_box($post)
{
    wp_nonce_field('rolkat_save_service_details', 'rolkat_service_details_nonce');
    $category = get_post_meta($post->ID, '_rolkat_service_category', true);
    ?>
    <p>
        <label for="rolkat-service-category"><?php esc_html_e('Business line', 'rolkat'); ?></label><br>
        <select class="widefat" id="rolkat-service-category" name="rolkat_service_category">
            <option value=""><?php esc_html_e('Select a category', 'rolkat'); ?></option>
            <?php foreach (rolkat_service_categories() as $value => $meta) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($category, $value); ?>><?php echo esc_html($meta['label']); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

function rolkat_render_team_meta_box($post)
{
    wp_nonce_field('rolkat_save_team_details', 'rolkat_team_details_nonce');
    $role = get_post_meta($post->ID, '_rolkat_team_role', true);
    ?>
    <p>
        <label for="rolkat-team-role"><?php esc_html_e('Job title', 'rolkat'); ?></label><br>
        <input class="widefat" id="rolkat-team-role" name="rolkat_team_role" type="text" value="<?php echo esc_attr($role); ?>">
    </p>
    <?php
}

function rolkat_render_enquiry_meta_box($post)
{
    $fields = array(
        'name' => get_post_meta($post->ID, '_rolkat_enquiry_name', true),
        'email' => get_post_meta($post->ID, '_rolkat_enquiry_email', true),
        'phone' => get_post_meta($post->ID, '_rolkat_enquiry_phone', true),
        'subject' => get_post_meta($post->ID, '_rolkat_enquiry_subject', true),
        'message' => get_post_meta($post->ID, '_rolkat_enquiry_message', true),
    );
    ?>
    <table class="form-table">
        <tr><th><?php esc_html_e('Name', 'rolkat'); ?></th><td><?php echo esc_html($fields['name']); ?></td></tr>
        <tr><th><?php esc_html_e('Email', 'rolkat'); ?></th><td><a href="mailto:<?php echo esc_attr($fields['email']); ?>"><?php echo esc_html($fields['email']); ?></a></td></tr>
        <tr><th><?php esc_html_e('Phone', 'rolkat'); ?></th><td><?php echo esc_html($fields['phone']); ?></td></tr>
        <tr><th><?php esc_html_e('Subject', 'rolkat'); ?></th><td><?php echo esc_html($fields['subject']); ?></td></tr>
        <tr><th><?php esc_html_e('Message', 'rolkat'); ?></th><td><?php echo nl2br(esc_html($fields['message'])); ?></td></tr>
    </table>
    <?php
}

function rolkat_save_property_details($post_id)
{
    if (
        !isset($_POST['rolkat_property_details_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rolkat_property_details_nonce'])), 'rolkat_save_property_details')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $location = isset($_POST['rolkat_property_location']) ? sanitize_text_field(wp_unslash($_POST['rolkat_property_location'])) : '';
    $price = isset($_POST['rolkat_property_price']) ? sanitize_text_field(wp_unslash($_POST['rolkat_property_price'])) : '';
    $status = isset($_POST['rolkat_property_status']) ? sanitize_text_field(wp_unslash($_POST['rolkat_property_status'])) : '';
    $type = isset($_POST['rolkat_property_type']) ? sanitize_text_field(wp_unslash($_POST['rolkat_property_type'])) : '';

    $allowed_statuses = array_merge(array(''), array_keys(rolkat_property_statuses()));
    $allowed_types = array_merge(array(''), array_keys(rolkat_property_types()));
    if (!in_array($status, $allowed_statuses, true)) {
        $status = '';
    }
    if (!in_array($type, $allowed_types, true)) {
        $type = '';
    }

    $fields = array(
        '_rolkat_property_location' => $location,
        '_rolkat_property_price' => $price,
        '_rolkat_property_status' => $status,
        '_rolkat_property_type' => $type,
    );

    foreach ($fields as $meta_key => $value) {
        if ($value === '') {
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $value);
        }
    }
}
add_action('save_post_rolkat_property', 'rolkat_save_property_details');

function rolkat_save_service_details($post_id)
{
    if (
        !isset($_POST['rolkat_service_details_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rolkat_service_details_nonce'])), 'rolkat_save_service_details')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $category = isset($_POST['rolkat_service_category']) ? sanitize_text_field(wp_unslash($_POST['rolkat_service_category'])) : '';
    if (!array_key_exists($category, rolkat_service_categories()) && $category !== '') {
        $category = '';
    }

    if ($category === '') {
        delete_post_meta($post_id, '_rolkat_service_category');
    } else {
        update_post_meta($post_id, '_rolkat_service_category', $category);
    }
}
add_action('save_post_rolkat_service', 'rolkat_save_service_details');

function rolkat_save_team_details($post_id)
{
    if (
        !isset($_POST['rolkat_team_details_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rolkat_team_details_nonce'])), 'rolkat_save_team_details')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $role = isset($_POST['rolkat_team_role']) ? sanitize_text_field(wp_unslash($_POST['rolkat_team_role'])) : '';
    if ($role === '') {
        delete_post_meta($post_id, '_rolkat_team_role');
    } else {
        update_post_meta($post_id, '_rolkat_team_role', $role);
    }
}
add_action('save_post_rolkat_team_member', 'rolkat_save_team_details');

function rolkat_flush_rewrite_rules()
{
    rolkat_register_content_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'rolkat_flush_rewrite_rules');

function rolkat_seed_pages()
{
    $pages = array(
        'about' => array(
            'title' => __('About Us', 'rolkat'),
            'content' => '',
            'template' => 'page-about.php',
        ),
        'contact' => array(
            'title' => __('Contact', 'rolkat'),
            'content' => '',
            'template' => 'page-contact.php',
        ),
        'privacy-policy' => array(
            'title' => __('Privacy Policy', 'rolkat'),
            'content' => __('ROLKAT Financial Services SMC Ltd collects personal information submitted through this website (such as name, email, phone and message details) only to respond to enquiries and manage related services. We handle personal data in line with Uganda\'s Data Protection and Privacy Act, 2019. We do not sell your information. Contact us to request access, correction or deletion of your data.', 'rolkat'),
            'template' => '',
        ),
    );

    foreach ($pages as $slug => $page) {
        $existing = get_page_by_path($slug);
        if ($existing) {
            continue;
        }

        $page_id = wp_insert_post(array(
            'post_title' => $page['title'],
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => $page['content'],
        ));

        if (!is_wp_error($page_id) && $page['template'] !== '') {
            update_post_meta($page_id, '_wp_page_template', $page['template']);
        }
    }

    $front = get_option('page_on_front');
    if (!$front) {
        $home = get_page_by_path('home');
        if (!$home) {
            $home_id = wp_insert_post(array(
                'post_title' => __('Home', 'rolkat'),
                'post_name' => 'home',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            if (!is_wp_error($home_id)) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $home_id);
            }
        }
    }
}
add_action('after_switch_theme', 'rolkat_seed_pages');

function rolkat_customize_register($wp_customize)
{
    $wp_customize->add_section('rolkat_homepage', array(
        'title' => __('ROLKAT homepage', 'rolkat'),
        'priority' => 199,
    ));
    $wp_customize->add_section('rolkat_contact', array(
        'title' => __('ROLKAT contact details', 'rolkat'),
        'priority' => 200,
    ));
    $wp_customize->add_section('rolkat_social', array(
        'title' => __('ROLKAT social links', 'rolkat'),
        'priority' => 201,
    ));

    $homepage_settings = array(
        'rolkat_hero_title' => array(
            'label' => __('Homepage headline', 'rolkat'),
            'default' => __('Serving you better.', 'rolkat'),
            'type' => 'text',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'rolkat_hero_text' => array(
            'label' => __('Homepage introduction', 'rolkat'),
            'default' => __('Fast, fair loans and professional property services for everyday Ugandans and small businesses — from Entebbe Road in Zana.', 'rolkat'),
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_textarea_field',
        ),
    );

    foreach ($homepage_settings as $setting_name => $setting) {
        $wp_customize->add_setting($setting_name, array(
            'default' => $setting['default'],
            'sanitize_callback' => $setting['sanitize_callback'],
            'transport' => 'refresh',
        ));
        $wp_customize->add_control($setting_name, array(
            'label' => $setting['label'],
            'section' => 'rolkat_homepage',
            'type' => $setting['type'],
        ));
    }

    $contact_settings = array(
        'rolkat_phone' => array(
            'label' => __('Phone number', 'rolkat'),
            'default' => rolkat_default_phone(),
            'type' => 'text',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'rolkat_email' => array(
            'label' => __('Contact email', 'rolkat'),
            'default' => rolkat_default_email(),
            'type' => 'email',
            'sanitize_callback' => 'sanitize_email',
        ),
        'rolkat_whatsapp' => array(
            'label' => __('WhatsApp number', 'rolkat'),
            'default' => rolkat_default_whatsapp(),
            'type' => 'text',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'rolkat_hours' => array(
            'label' => __('Working hours', 'rolkat'),
            'default' => rolkat_default_hours(),
            'type' => 'text',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'rolkat_address' => array(
            'label' => __('Office address', 'rolkat'),
            'default' => rolkat_default_address(),
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_textarea_field',
        ),
        'rolkat_map_embed' => array(
            'label' => __('Google Maps embed URL', 'rolkat'),
            'default' => rolkat_default_map_embed(),
            'type' => 'url',
            'sanitize_callback' => 'esc_url_raw',
        ),
    );

    foreach ($contact_settings as $setting_name => $setting) {
        $wp_customize->add_setting($setting_name, array(
            'default' => $setting['default'],
            'sanitize_callback' => $setting['sanitize_callback'],
            'transport' => 'refresh',
        ));
        $wp_customize->add_control($setting_name, array(
            'label' => $setting['label'],
            'section' => 'rolkat_contact',
            'type' => $setting['type'],
        ));
    }

    $social_settings = array(
        'rolkat_facebook' => __('Facebook URL', 'rolkat'),
        'rolkat_instagram' => __('Instagram URL', 'rolkat'),
        'rolkat_linkedin' => __('LinkedIn URL', 'rolkat'),
    );

    foreach ($social_settings as $setting_name => $label) {
        $wp_customize->add_setting($setting_name, array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control($setting_name, array(
            'label' => $label,
            'section' => 'rolkat_social',
            'type' => 'url',
        ));
    }
}
add_action('customize_register', 'rolkat_customize_register');

function rolkat_meta_description()
{
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || class_exists('The_SEO_Framework\Load')) {
        return;
    }

    if (is_singular()) {
        $post = get_queried_object();
        $description = has_excerpt($post) ? get_the_excerpt($post) : $post->post_content;
        $description = wp_trim_words(wp_strip_all_tags(strip_shortcodes($description)), 30, '');
    } elseif (is_post_type_archive('rolkat_property')) {
        $description = __('Browse property listings from ROLKAT Financial Services.', 'rolkat');
    } elseif (is_post_type_archive('rolkat_service')) {
        $description = __('Explore loans, property management and real estate services from ROLKAT.', 'rolkat');
    } elseif (is_post_type_archive('rolkat_team_member')) {
        $description = __('Meet the team at ROLKAT Financial Services SMC Ltd.', 'rolkat');
    } else {
        $description = __('Financial services, property management and real estate from ROLKAT Financial Services SMC Ltd. Serving you better.', 'rolkat');
    }

    if ($description !== '') {
        printf("<meta name=\"description\" content=\"%s\">\n", esc_attr($description));
    }
}
add_action('wp_head', 'rolkat_meta_description', 2);

function rolkat_contact_redirect($status)
{
    $contact_page = get_page_by_path('contact');
    $redirect_url = $contact_page ? get_permalink($contact_page) : home_url('/');

    wp_safe_redirect(add_query_arg('contact_status', $status, $redirect_url));
    exit;
}

function rolkat_handle_contact_form()
{
    if (
        !isset($_POST['rolkat_contact_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rolkat_contact_nonce'])), 'rolkat_contact')
    ) {
        rolkat_contact_redirect('invalid');
    }

    if (!empty($_POST['company_website'])) {
        rolkat_contact_redirect('sent');
    }

    $name = isset($_POST['contact_name']) ? sanitize_text_field(wp_unslash($_POST['contact_name'])) : '';
    $email = isset($_POST['contact_email']) ? sanitize_email(wp_unslash($_POST['contact_email'])) : '';
    $phone = isset($_POST['contact_phone']) ? sanitize_text_field(wp_unslash($_POST['contact_phone'])) : '';
    $subject = isset($_POST['contact_subject']) ? sanitize_text_field(wp_unslash($_POST['contact_subject'])) : '';
    $message = isset($_POST['contact_message']) ? sanitize_textarea_field(wp_unslash($_POST['contact_message'])) : '';

    if ($name === '' || !is_email($email) || $subject === '' || $message === '' || strlen($message) > 10000) {
        rolkat_contact_redirect('invalid');
    }

    $enquiry_id = wp_insert_post(array(
        'post_type' => 'rolkat_enquiry',
        'post_status' => 'private',
        'post_title' => sprintf('%s — %s', $name, $subject),
    ));

    if (!is_wp_error($enquiry_id)) {
        update_post_meta($enquiry_id, '_rolkat_enquiry_name', $name);
        update_post_meta($enquiry_id, '_rolkat_enquiry_email', $email);
        update_post_meta($enquiry_id, '_rolkat_enquiry_phone', $phone);
        update_post_meta($enquiry_id, '_rolkat_enquiry_subject', $subject);
        update_post_meta($enquiry_id, '_rolkat_enquiry_message', $message);
    }

    $recipient = sanitize_email(get_option('admin_email'));
    $custom_email = sanitize_email(rolkat_get_email());
    if (is_email($custom_email)) {
        $recipient = $custom_email;
    }

    if (!is_email($recipient)) {
        rolkat_contact_redirect('error');
    }

    $mail_subject = sprintf(__('Website enquiry: %s', 'rolkat'), $subject);
    $body = sprintf(
        "Name: %s\nEmail: %s\nPhone: %s\nSubject: %s\n\n%s",
        $name,
        $email,
        $phone !== '' ? $phone : __('Not provided', 'rolkat'),
        $subject,
        $message
    );
    $headers = array('Reply-To: ' . $email);

    rolkat_contact_redirect(wp_mail($recipient, $mail_subject, $body, $headers) ? 'sent' : 'error');
}
add_action('admin_post_nopriv_rolkat_contact', 'rolkat_handle_contact_form');
add_action('admin_post_rolkat_contact', 'rolkat_handle_contact_form');

function rolkat_filter_property_query($query)
{
    if (is_admin() || !$query->is_main_query() || !is_post_type_archive('rolkat_property')) {
        return;
    }

    $meta_query = array();

    if (!empty($_GET['property_status'])) {
        $status = sanitize_text_field(wp_unslash($_GET['property_status']));
        if (array_key_exists($status, rolkat_property_statuses())) {
            $meta_query[] = array(
                'key' => '_rolkat_property_status',
                'value' => $status,
            );
        }
    }

    if (!empty($_GET['property_type'])) {
        $type = sanitize_text_field(wp_unslash($_GET['property_type']));
        if (array_key_exists($type, rolkat_property_types())) {
            $meta_query[] = array(
                'key' => '_rolkat_property_type',
                'value' => $type,
            );
        }
    }

    if ($meta_query) {
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'rolkat_filter_property_query');

function rolkat_enquiry_columns($columns)
{
    return array(
        'cb' => $columns['cb'],
        'title' => __('Enquiry', 'rolkat'),
        'enquiry_email' => __('Email', 'rolkat'),
        'enquiry_phone' => __('Phone', 'rolkat'),
        'enquiry_subject' => __('Subject', 'rolkat'),
        'date' => __('Date', 'rolkat'),
    );
}
add_filter('manage_rolkat_enquiry_posts_columns', 'rolkat_enquiry_columns');

function rolkat_enquiry_column_content($column, $post_id)
{
    if ($column === 'enquiry_email') {
        echo esc_html(get_post_meta($post_id, '_rolkat_enquiry_email', true));
    }
    if ($column === 'enquiry_phone') {
        echo esc_html(get_post_meta($post_id, '_rolkat_enquiry_phone', true));
    }
    if ($column === 'enquiry_subject') {
        echo esc_html(get_post_meta($post_id, '_rolkat_enquiry_subject', true));
    }
}
add_action('manage_rolkat_enquiry_posts_custom_column', 'rolkat_enquiry_column_content', 10, 2);

function rolkat_export_enquiries_csv()
{
    if (!current_user_can('edit_posts') || !isset($_GET['rolkat_export_enquiries'])) {
        return;
    }

    check_admin_referer('rolkat_export_enquiries');

    $enquiries = get_posts(array(
        'post_type' => 'rolkat_enquiry',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=rolkat-enquiries.csv');

    $out = fopen('php://output', 'w');
    fputcsv($out, array('Date', 'Name', 'Email', 'Phone', 'Subject', 'Message'));

    foreach ($enquiries as $enquiry) {
        fputcsv($out, array(
            get_the_date('c', $enquiry),
            get_post_meta($enquiry->ID, '_rolkat_enquiry_name', true),
            get_post_meta($enquiry->ID, '_rolkat_enquiry_email', true),
            get_post_meta($enquiry->ID, '_rolkat_enquiry_phone', true),
            get_post_meta($enquiry->ID, '_rolkat_enquiry_subject', true),
            get_post_meta($enquiry->ID, '_rolkat_enquiry_message', true),
        ));
    }

    fclose($out);
    exit;
}
add_action('admin_init', 'rolkat_export_enquiries_csv');

function rolkat_enquiry_admin_notice()
{
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'rolkat_enquiry') {
        return;
    }

    $url = wp_nonce_url(admin_url('edit.php?post_type=rolkat_enquiry&rolkat_export_enquiries=1'), 'rolkat_export_enquiries');
    printf(
        '<div class="notice notice-info"><p><a class="button button-secondary" href="%s">%s</a></p></div>',
        esc_url($url),
        esc_html__('Export enquiries to CSV', 'rolkat')
    );
}
add_action('admin_notices', 'rolkat_enquiry_admin_notice');

function rolkat_services_by_category()
{
    $grouped = array(
        'loans' => array(),
        'property-management' => array(),
        'real-estate' => array(),
    );

    $query = new WP_Query(array(
        'post_type' => 'rolkat_service',
        'posts_per_page' => -1,
        'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $category = get_post_meta(get_the_ID(), '_rolkat_service_category', true);
            if (!isset($grouped[$category])) {
                $category = 'loans';
            }
            $grouped[$category][] = array(
                'title' => get_the_title(),
                'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 28),
                'permalink' => get_permalink(),
            );
        }
        wp_reset_postdata();
        return $grouped;
    }

    foreach (rolkat_default_services() as $service) {
        $grouped[$service['category']][] = array(
            'title' => $service['title'],
            'description' => $service['description'],
            'permalink' => home_url('/contact/'),
        );
    }

    return $grouped;
}
