<?php
/*
Plugin Name: Google Reviews
Description: A WordPress plugin to manage and display Google Reviews.
Version: 1.0
Author: Kimberly Pash
*/

// Include Timber
if (!class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber plugin is not activated. Please activate it to use Twig templates.</p></div>';
    });
    return;
}

// Add a menu item in the WordPress dashboard
function google_reviews_menu()
{
    add_menu_page(
        'Google Reviews',
        'Google Reviews',
        'manage_options',
        'google_reviews',
        'google_reviews_page',
        'dashicons-google' 
    );

    // Enqueue styles for the admin page
    add_action('admin_enqueue_scripts', 'google_reviews_admin_styles');

    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Save the selected style option
        update_option('google_reviews_style', sanitize_text_field($_POST['google_reviews_style']));

        // Add a success notice
        add_action('admin_notices', 'google_reviews_display_notice');
    }
}

// Callback function to display the success notice with preview link
function google_reviews_display_notice()
{
    // Get the ID of the "Google Reviews" page
    $google_reviews_page_id = get_page_by_title('Google Reviews');

    // Check if the page exists
    if ($google_reviews_page_id) {
        $google_reviews_page_link = get_permalink($google_reviews_page_id->ID);
        ?>
        <div class="notice notice-success is-dismissible">
            <h4 class="uk-margin-remove g6-padding g6-color-pinterest" style="text-transform: capitalize;">
                <?php _e('Settings saved! ', 'google-reviews'); ?>
                <br />
                <a href="<?php echo esc_url($google_reviews_page_link); ?>" target="_blank">
                    <?php _e('Preview Google Reviews Page', 'google-reviews'); ?>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                </a>
            </h4>
        </div>
        <?php
    } else {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Error: Google Reviews page not found.', 'google-reviews'); ?></p>
            <p>Please deactivate and then re-activate the plug-in again to create a new page.</p>
        </div>
        <?php
    }
}

// Enqueue styles for the admin page
function google_reviews_admin_styles()
{
    // Enqueue WordPress core styles
    wp_enqueue_style('wp-admin');

    // // UIkit styles
    // $theme_css_folder = get_template_directory_uri() . '/less/uikit/less/uikit.theme.less'; 
    // wp_enqueue_style('google-reviews-admin', $theme_css_folder);

    // // Enqueue theme base LESS
    // wp_enqueue_style('google-reviews-admin-additional-styles', get_stylesheet_directory_uri() . '/style.less');

    // // Enqueue UIkit JavaScript from the parent theme
    // $parent_theme_js_folder = get_template_directory_uri() . '/less/uikit/js/uikit.min.js';
    // wp_enqueue_script('google-reviews-admin-script', $parent_theme_js_folder, array('jquery'), null, true); 
}

// Callback function for the menu page
function google_reviews_page()
{
    // Get Timber context
    $context = Timber::get_context();

    // Add styles and current_style to the context
    $context['styles'] = ['default', 'style1', 'style2', 'style3'];
    $context['current_style'] = get_option('google_reviews_style', 'default');

    // Get the ID of the Google Reviews page
    $google_reviews_page_id = get_page_by_title('Google Reviews');

    // Check if the page exists
    if ($google_reviews_page_id) {
        // Get the permalink of the page
        $context['google_reviews_page_link'] = get_permalink($google_reviews_page_id->ID);
    }

    // Render Twig template
    Timber::render('views/google-reviews-page.twig', $context);
}


// Shortcode function
function display_google_reviews_shortcode($atts)
{
    // Retrieve the current style option
    $current_style = get_option('google_reviews_style', 'default');

    // Call the appropriate display function based on the selected style
    switch ($current_style) {
        case 'style1':
            return display_google_reviews_style1();
        case 'style2':
            return display_google_reviews_style2();
        case 'style3':
            return display_google_reviews_style3();
        default:
            return display_google_reviews_default();
    }
}

// Register the shortcode
add_shortcode('google_reviews', 'display_google_reviews_shortcode');

// Display functions for each style
function display_google_reviews_default()
{
    // Get Timber context
    $context = Timber::get_context();

    // Render Twig template
    Timber::render('views/default.twig', $context);
}

function display_google_reviews_style1()
{
    // Get Timber context
    $context = Timber::get_context();

    // Render Twig template
    Timber::render('views/style1.twig', $context);
}

function display_google_reviews_style2()
{
    // Get Timber context
    $context = Timber::get_context();

    // Render Twig template
    Timber::render('views/style2.twig', $context);
}

function display_google_reviews_style3()
{
    // Get Timber context
    $context = Timber::get_context();

    // Render Twig template
    Timber::render('views/style3.twig', $context);
}

// Hook to add the menu item
add_action('admin_menu', 'google_reviews_menu');
