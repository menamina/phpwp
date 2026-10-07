<?php
/**
 * Playwright E2E Tests for Wait Times Map
 * Run: vendor/bin/phpunit tests/Playwright/waitTimesPW.php
 */

namespace Tests\Playwright;

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Playwright\Playwright;
use function Playwright\Testing\expect;

class WaitTimesMapTest extends TestCase
{
    private $context;
    private $page;
    private string $baseUrl;
    private int $blockPostId;
    private int $shortcodePostId;

    protected function setUp(): void
    {
        parent::setUp();

        // Load .env file
        if (file_exists(__DIR__ . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        }

        // Get base URL from environment or use default
        $this->baseUrl = $_ENV['WP_TEST_URL'] ?? getenv('WP_TEST_URL');

        // Bootstrap WordPress
        $this->loadWordPress();

        // Create test posts
        $this->blockPostId = $this->createBlockPost('ORD');
        $this->shortcodePostId = $this->createShortcodePost('SEA');

        $this->context = Playwright::chromium(['headless' => true]);
        $this->page = $this->context->newPage();
    }

    protected function tearDown(): void
    {
        $this->context->close();

        // Clean up test posts
        if (isset($this->blockPostId)) {
            wp_delete_post($this->blockPostId, true);
        }
        if (isset($this->shortcodePostId)) {
            wp_delete_post($this->shortcodePostId, true);
        }

        parent::tearDown();
    }

    private function loadWordPress(): void
    {
        // Load WordPress
        $wpLoadPath = __DIR__ . '/../../../../wp-load.php';
        if (file_exists($wpLoadPath)) {
            require_once $wpLoadPath;
        } else {
            throw new \Exception('WordPress not found. Check wp-load.php path.');
        }
    }

    private function createBlockPost(string $airport): int
    {
        $postId = wp_insert_post([
            'post_title' => "Test Wait Times Block - {$airport}",
            'post_content' => "<!-- wp:create-block/wait-times {\"airport\":\"{$airport}\"} /-->",
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        if (is_wp_error($postId)) {
            throw new \Exception('Failed to create test post: ' . $postId->get_error_message());
        }

        return $postId;
    }

    private function createShortcodePost(string $airport): int
    {
        $postId = wp_insert_post([
            'post_title' => "Test Wait Times Shortcode - {$airport}",
            'post_content' => "[wait_times airport=\"{$airport}\"]",
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        if (is_wp_error($postId)) {
            throw new \Exception('Failed to create test post: ' . $postId->get_error_message());
        }

        return $postId;
    }

    public function test_wait_times_map_renders_on_frontend(): void
    {
        $this->page->goto("{$this->baseUrl}/{$this->blockPostId}");

        expect($this->page->locator('.flight-wait-times'))->toBeVisible();
        expect($this->page->locator('[data-airport="ORD"]'))->toBeVisible();

        $markerCount = $this->page->locator('.custom-marker')->count();
        $this->assertGreaterThan(0, $markerCount);
    }

    public function test_wait_times_popup_shows_correct_data(): void
    {
        $this->page->goto("{$this->baseUrl}/{$this->blockPostId}");
        $this->page->waitForSelector('.custom-marker', ['timeout' => 10000]);

        $this->page->click('.custom-marker');

        $popup = $this->page->locator('.leaflet-popup');
        expect($popup)->toContainText('Wait Time:');
        expect($popup)->toContainText('min');
        expect($popup)->toContainText('Airport:');
        expect($popup)->toContainText('Terminal:');
    }

    public function test_shortcode_renders_same_as_block(): void
    {
        $this->page->goto("{$this->baseUrl}/{$this->shortcodePostId}");
        $this->page->waitForSelector('.custom-marker', ['timeout' => 10000]);

        expect($this->page->locator('.flight-wait-times'))->toBeVisible();
        expect($this->page->locator('[data-airport="SEA"]'))->toBeVisible();
        
        $this->page->click('.custom-marker');
        expect($this->page->locator('.leaflet-popup'))->toContainText('Wait Time:');
    }
}
