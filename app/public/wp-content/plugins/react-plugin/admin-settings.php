<?php
/**
 * Admin Settings Page for Airport Info Hub
 *
 * Handles:
 * - Settings registration (API key, default airport, refresh interval) with REST API exposure
 * - Admin menu page creation (Settings → Airport Info Hub)
 * - Settings page UI (renders div for React to mount)
 *
 * Part of react-plugin
 */

// Register settings with show_in_rest so they appear at /wp/v2/settings
function afh_register_settings() {
    register_setting('afh_settings', 'afh_api_key', [
        'type' => 'string',
        'description' => 'API key for aviation data service',
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
        'default' => '',
    ]);

    register_setting('afh_settings', 'afh_default_airport', [
        'type' => 'string',
        'description' => 'Default airport code',
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
        'default' => 'ORD',
    ]);

    register_setting('afh_settings', 'afh_refresh_interval', [
        'type' => 'integer',
        'description' => 'Refresh interval in seconds for wait time map',
        'sanitize_callback' => 'absint',
        'show_in_rest' => true,
        'default' => 60,
    ]);
}
add_action('init', 'afh_register_settings');

function afh_add_settings_page() {
    $hook_suffix = add_options_page(
        'Airport Info Hub Settings',      // Page title
        'Airport Info Hub',               // Menu title
        'manage_options',                 // Capability required
        'afh-settings',                   // Menu slug
        'afh_settings_page_html'          // Callback function
    );

    add_action('admin_enqueue_scripts', function($current_hook) use ($hook_suffix) {
        if ($current_hook !== $hook_suffix) {
            return;
        }

        $asset_file = plugin_dir_path(__FILE__) . 'build/admin-settings/index.asset.php';

        if (file_exists($asset_file)) {
            $asset = include $asset_file;

            wp_enqueue_script(
                'afh-settings',
                plugins_url('build/admin-settings/index.js', __FILE__),
                $asset['dependencies'],
                $asset['version'],
                true
            );

            wp_enqueue_style('wp-components');
        }
    });
}
add_action('admin_menu', 'afh_add_settings_page');


function afh_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <div id="afh-settings"></div>
    </div>
    <?php
}
