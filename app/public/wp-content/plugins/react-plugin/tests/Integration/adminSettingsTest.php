<?php
namespace Tests\Integration;

use lucatume\WPBrowser\TestCase\WPTestCase;

class AdminSettingsTest extends WPTestCase
{
  public function test_settings_registered(): void {
    $this->assertTrue(has_action('init', 'afh_register_settings'));
    
    $response = rest_do_request('/wp/v2/settings');
    $data = $response->get_data();
    $this->assertArrayHasKey('afh_api_key', $data);
}

public function test_admin_menu_added(): void {
    global $submenu;

    afh_add_settings_page();
    $this->assertNotEmpty($submenu['options-general.php']);
    $this->assertNotEmpty(menu_page_url('afh-settings', false));
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