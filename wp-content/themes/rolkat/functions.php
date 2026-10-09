<?php

if (!defined('ABSPATH')) {
    exit;
}

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
    ));
}
add_action('after_setup_theme', 'rolkat_setup');

function rolkat_fallback_menu()
{
    $links = array(
        __('Home', 'rolkat') => home_url('/'),
        __('About us', 'rolkat') => home_url('/about/'),
        __('Services', 'rolkat') => get_post_type_archive_link('rolkat_service'),
        __('Properties', 'rolkat') => get_post_type_archive_link('rolkat_property'),
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

    wp_enqueue_style('rolkat-style', get_stylesheet_uri(), array(), $theme_version);
    wp_enqueue_script(
        'rolkat-site',
        get_template_directory_uri() . '/assets/js/site.js',
        array(),
        $theme_version,
        true
    );
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
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
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
}
add_action('init', 'rolkat_register_content_types');

function rolkat_default_services()
{
    return array(
        array(
            'number' => '01',
            'title' => __('Loans', 'rolkat'),
            'description' => __('Explore financing options designed around your needs, plans and circumstances.', 'rolkat'),
        ),
        array(
            'number' => '02',
            'title' => __('Property management', 'rolkat'),
            'description' => __('Reliable support to help property owners care for and manage their investments.', 'rolkat'),
        ),
        array(
            'number' => '03',
            'title' => __('Real estate', 'rolkat'),
            'description' => __('Personal guidance for property decisions, from exploring opportunities to taking the next step.', 'rolkat'),
        ),
    );
}

function rolkat_register_property_meta_box()
{
    add_meta_box(
        'rolkat-property-details',
        __('Property details', 'rolkat'),
        'rolkat_render_property_meta_box',
        'rolkat_property',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_rolkat_property', 'rolkat_register_property_meta_box');

function rolkat_render_property_meta_box($post)
{
    wp_nonce_field('rolkat_save_property_details', 'rolkat_property_details_nonce');
    $location = get_post_meta($post->ID, '_rolkat_property_location', true);
    $price = get_post_meta($post->ID, '_rolkat_property_price', true);
    $status = get_post_meta($post->ID, '_rolkat_property_status', true);
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
            <?php
            $statuses = array(
                '' => __('Select a status', 'rolkat'),
                'Available' => __('Available', 'rolkat'),
                'Under offer' => __('Under offer', 'rolkat'),
                'No longer available' => __('No longer available', 'rolkat'),
            );
            foreach ($statuses as $value => $label) :
                ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($status, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
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

    $fields = array(
        '_rolkat_property_location' => isset($_POST['rolkat_property_location'])
            ? sanitize_text_field(wp_unslash($_POST['rolkat_property_location']))
            : '',
        '_rolkat_property_price' => isset($_POST['rolkat_property_price'])
            ? sanitize_text_field(wp_unslash($_POST['rolkat_property_price']))
            : '',
    );

    $status = isset($_POST['rolkat_property_status'])
        ? sanitize_text_field(wp_unslash($_POST['rolkat_property_status']))
        : '';
    $allowed_statuses = array('', 'Available', 'Under offer', 'No longer available');
    $fields['_rolkat_property_status'] = in_array($status, $allowed_statuses, true) ? $status : '';

    foreach ($fields as $meta_key => $value) {
        if ($value === '') {
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $value);
        }
    }
}
add_action('save_post_rolkat_property', 'rolkat_save_property_details');

function rolkat_flush_rewrite_rules()
{
    rolkat_register_content_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'rolkat_flush_rewrite_rules');

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

    $homepage_settings = array(
        'rolkat_hero_title' => array(
            'label' => __('Homepage headline', 'rolkat'),
            'default' => __('A better way to move forward.', 'rolkat'),
            'type' => 'text',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'rolkat_hero_text' => array(
            'label' => __('Homepage introduction', 'rolkat'),
            'default' => __('Thoughtful financial solutions and property services, shaped around your goals and built on relationships you can trust.', 'rolkat'),
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

    $settings = array(
        'rolkat_phone' => array(
            'label' => __('Phone number', 'rolkat'),
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'rolkat_email' => array(
            'label' => __('Contact email', 'rolkat'),
            'sanitize_callback' => 'sanitize_email',
        ),
        'rolkat_address' => array(
            'label' => __('Office address', 'rolkat'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ),
    );

    foreach ($settings as $setting_name => $setting) {
        $wp_customize->add_setting($setting_name, array(
            'default' => '',
            'sanitize_callback' => $setting['sanitize_callback'],
            'transport' => 'refresh',
        ));
        $wp_customize->add_control($setting_name, array(
            'label' => $setting['label'],
            'section' => 'rolkat_contact',
            'type' => $setting_name === 'rolkat_address' ? 'textarea' : 'text',
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
    $service = isset($_POST['contact_service']) ? sanitize_text_field(wp_unslash($_POST['contact_service'])) : '';
    $message = isset($_POST['contact_message']) ? sanitize_textarea_field(wp_unslash($_POST['contact_message'])) : '';

    if ($name === '' || !is_email($email) || $message === '' || strlen($message) > 10000) {
        rolkat_contact_redirect('invalid');
    }

    $recipient = sanitize_email(get_option('admin_email'));
    if (!is_email($recipient)) {
        rolkat_contact_redirect('error');
    }

    $subject = sprintf(__('Website enquiry from %s', 'rolkat'), $name);
    $body = sprintf(
        "Name: %s\nEmail: %s\nService: %s\n\n%s",
        $name,
        $email,
        $service !== '' ? $service : __('Not specified', 'rolkat'),
        $message
    );
    $headers = array('Reply-To: ' . $email);

    rolkat_contact_redirect(wp_mail($recipient, $subject, $body, $headers) ? 'sent' : 'error');
}
add_action('admin_post_nopriv_rolkat_contact', 'rolkat_handle_contact_form');
add_action('admin_post_rolkat_contact', 'rolkat_handle_contact_form');