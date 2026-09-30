<?php
/**
 * Admin Settings Page for Wait Times Plugin
 * 
 * Handles:
 * - Settings registration (API key, default airport) with REST API exposure
 * - Admin menu page creation (Settings → Wait Times)
 * - Settings page UI (renders div for React to mount)
 * 
 * Part of react-plugin
 */

// Register settings with show_in_rest so they appear at /wp/v2/settings
function wait_times_register_settings() {
    register_setting('wait_times_settings', 'wait_times_api_key', [
        'type' => 'string',
        'description' => 'API key for wait times service',
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
        'default' => '',
    ]);

    register_setting('wait_times_settings', 'wait_times_default_airport', [
        'type' => 'string',
        'description' => 'Default airport code',
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
        'default' => 'ORD',
    ]);
}
add_action('init', 'wait_times_register_settings');

function wait_times_add_settings_page() {
    $hook_suffix = add_options_page(
        'Wait Times Settings',           // Page title
        'Wait Times',                     // Menu title
        'manage_options',                 // Capability required
        'wait-times-settings',            // Menu slug
        'wait_times_settings_page_html'   // Callback function
    );

    add_action('admin_enqueue_scripts', function($current_hook) use ($hook_suffix) {
        if ($current_hook !== $hook_suffix) {
            return;
        }

        $asset_file = plugin_dir_path(__FILE__) . 'build/admin-settings/index.asset.php';

        if (file_exists($asset_file)) {
            $asset = include $asset_file;

            wp_enqueue_script(
                'wait-times-settings',
                plugins_url('build/admin-settings/index.js', __FILE__),
                $asset['dependencies'],
                $asset['version'],
                true
            );

            wp_enqueue_style('wp-components');
        }
    });
}
add_action('admin_menu', 'wait_times_add_settings_page');


function wait_times_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <div id="wait-times-settings"></div>
    </div>
    <?php
}
