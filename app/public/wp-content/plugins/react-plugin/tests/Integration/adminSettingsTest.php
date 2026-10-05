<?php
namespace Tests\Integration;

use lucatume\WPBrowser\TestCase\WPTestCase;

class AdminSettingsTest extends WPTestCase
{
  public function test_settings_registered(): void {
    // has_action returns priority (10), not true
    $this->assertNotFalse(has_action('init', 'afh_register_settings'));

    // Login as admin to access /wp/v2/settings
    $admin = $this->factory()->user->create(['role' => 'administrator']);
    wp_set_current_user($admin);

    $request = new \WP_REST_Request('GET', '/wp/v2/settings');
    $response = rest_do_request($request);

    $this->assertEquals(200, $response->get_status());

    $data = $response->get_data();
    $this->assertArrayHasKey('afh_api_key', $data);
}

public function test_admin_menu_added(): void {
    global $submenu;

    // Create admin user - add_options_page requires manage_options capability
    $admin = $this->factory()->user->create(['role' => 'administrator']);
    wp_set_current_user($admin);

    afh_add_settings_page();

    // Check specifically for our page slug
    $found = false;
    if (isset($submenu['options-general.php'])) {
        foreach ($submenu['options-general.php'] as $item) {
            if ($item[2] === 'afh-settings') {
                $found = true;
                break;
            }
        }
    }

    $this->assertTrue($found, 'Settings page "afh-settings" not found in submenu');
}

public function test_settings_page_html_for_admin(): void {
    // Create admin user and log in
    $admin = $this->factory()->user->create(['role' => 'administrator']);
    wp_set_current_user($admin);

    // Capture HTML output
    ob_start();
    afh_settings_page_html();
    $output = ob_get_clean();

    // Check output contains React mount point
    $this->assertStringContainsString('<div id="afh-settings"></div>', $output);
    $this->assertStringContainsString('class="wrap"', $output);
}

public function test_settings_page_html_blocks_non_admin(): void {
    // Create regular subscriber (not admin)
    $subscriber = $this->factory()->user->create(['role' => 'subscriber']);
    wp_set_current_user($subscriber);

    // Should output nothing for non-admins
    ob_start();
    afh_settings_page_html();
    $output = ob_get_clean();

    $this->assertEmpty($output);
}

}