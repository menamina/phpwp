<?php
/**
 * Admin E2E Tests for Wait Times Plugin
 * Tests admin functionality requiring authentication
 *
 * Prerequisites:
 * 1. Run: php tests/Playwright/authScript.php (to generate auth.json)
 * 2. Run: vendor/bin/phpunit tests/Playwright/AdminWaitTimesTest.php
 */

namespace App\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/adminLoggedIn.php';

use function Playwright\Testing\expect;

class AdminWaitTimesTest extends AuthenticatedTestCase
{
    private string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp(); // This loads the auth.json automatically

        // Load .env file
        if (file_exists(__DIR__ . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        }

        $this->baseUrl = $_ENV['WP_TEST_URL'] ?? 'http://phpwp.local';
    }

    public function test_admin_can_access_dashboard(): void
    {
        $this->page->goto("{$this->baseUrl}/wp-admin/");

        expect($this->page->locator('#wpadminbar'))->toBeVisible();
        expect($this->page->locator('#dashboard-widgets'))->toBeVisible();
    }

    public function test_admin_can_create_post_with_wait_times_block(): void
    {

        $this->page->goto("{$this->baseUrl}/wp-admin/post-new.php");

        $this->page->waitForSelector('.edit-post-visual-editor', ['timeout' => 10000]);

        $this->page->click('.block-editor-inserter__toggle');

        $this->page->fill('.block-editor-inserter__search-input', 'wait times');

        $this->page->click('button:has-text("Wait Times")');

        expect($this->page->locator('[data-type="create-block/wait-times"]'))->toBeVisible();


        echo "✓ Admin can insert wait times block\n";
    }

    public function test_admin_can_access_plugin_settings(): void
    {
        
        $this->page->goto("{$this->baseUrl}/wp-admin/options-general.php");

        expect($this->page->locator('#wpadminbar'))->toBeVisible();

        echo "✓ Admin can access settings\n";
    }

    public function test_admin_stays_logged_in_across_pages(): void
    {
    
        $adminPages = [
            '/wp-admin/',
            '/wp-admin/edit.php',
            '/wp-admin/plugins.php',
        ];

        foreach ($adminPages as $page) {
            $this->page->goto("{$this->baseUrl}{$page}");

            expect($this->page->locator('#wpadminbar'))->toBeVisible();
        }

        echo "✓ Authentication persists across admin pages\n";
    }
}
