<?php
require __DIR__.'/../../vendor/autoload.php';
use Playwright\Playwright;

$context = Playwright::chromium();
$page = $context->newPage();

$page->goto('http://phpwp.local/wp-admin/');

// Use environment variables for credentials
$username = getenv('WP_TEST_USER') ?: 'admin';
$password = getenv('WP_TEST_PASSWORD') ?: 'password';

// WordPress login form uses 'user_login' and 'user_pass' IDs
$page->locator('#user_login')->fill($username);
$page->locator('#user_pass')->fill($password);
$page->locator('#wp-submit')->click();

// Wait for WordPress dashboard to load
$page->waitForURL('**/wp-admin/**');

// Save the storage state to a file
$context->saveStorageState(__DIR__.'/auth.json');

$context->close();

echo "Authentication state saved successfully to auth.json\n";