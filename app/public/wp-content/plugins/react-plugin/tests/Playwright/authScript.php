<?php
require __DIR__.'/../../vendor/autoload.php';
use Playwright\Playwright;

// Load .env file
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$baseUrl = $_ENV['WP_TEST_URL'] ?? getenv('WP_TEST_URL') ?: 'http://phpwp.local';
$username = $_ENV['WP_TEST_USER'] ?? getenv('WP_TEST_USER') ?: 'admin';
$password = $_ENV['WP_TEST_PASSWORD'] ?? getenv('WP_TEST_PASSWORD') ?: 'password';

echo "Logging in to {$baseUrl} as {$username}...\n";

$context = Playwright::chromium(['headless' => true]);
$page = $context->newPage();

$page->goto("{$baseUrl}/wp-admin/");

// WordPress login form uses 'user_login' and 'user_pass' IDs
$page->locator('#user_login')->fill($username);
$page->locator('#user_pass')->fill($password);
$page->locator('#wp-submit')->click();

// Wait for navigation and check if we're on an admin page
$page->waitForLoadState('networkidle');

// Check current URL
$currentUrl = $page->url();
echo "Current URL after login: {$currentUrl}\n";

// Verify we're actually logged in by checking for admin bar
try {
    $page->waitForSelector('#wpadminbar', ['timeout' => 5000]);
    echo "✓ Successfully logged in (admin bar detected)\n";
} catch (\Exception $e) {
    echo "✗ Login may have failed - admin bar not found\n";

    // Check for error message
    if ($page->locator('#login_error')->count() > 0) {
        $errorText = $page->locator('#login_error')->textContent();
        echo "Error: {$errorText}\n";
    }

    $context->close();
    exit(1);
}

// Save the storage state to a file
$context->saveStorageState(__DIR__.'/auth.json');

$context->close();

echo "✓ Authentication state saved successfully to auth.json\n";