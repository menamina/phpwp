<?php

namespace Tests\EndToEnd;

use Tests\Support\EndToEndTester;

class WaitTimesMapCest
{
    public function _before(EndToEndTester $I): void
    {
        // Ensure plugin is activated before each test
        $I->loginAsAdmin();
        $I->amOnPluginsPage();

        if (!$I->seePluginActivated('react-plugin')) {
            $I->activatePlugin('react-plugin');
        }
    }

    public function test_wait_times_map_renders_on_frontend(EndToEndTester $I): void
    {
        // Set up API key
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        // Create post with Wait Times block
        $postId = $I->havePostInDatabase([
            'post_content' => '<!-- wp:create-block/wait-times {"airport":"ORD"} /-->',
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        $I->amOnPage("/{$postId}");

        // Wait for React to render the map
        $I->waitForElement('.custom-marker', 10);

        // Verify map container exists
        $I->seeElement('.flight-wait-times');
        $I->seeElement('[data-airport="ORD"]');

        // Verify markers are visible (data loaded)
        $I->seeElement('.custom-marker');
    }

    public function test_wait_times_popup_shows_correct_data(EndToEndTester $I): void
    {
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        $postId = $I->havePostInDatabase([
            'post_content' => '<!-- wp:create-block/wait-times {"airport":"LAX"} /-->',
            'post_status' => 'publish'
        ]);

        $I->amOnPage("/{$postId}");

        // Wait for markers to appear
        $I->waitForElement('.custom-marker', 10);

        // Click a marker
        $I->click('.custom-marker');

        // Wait for popup to appear
        $I->waitForElement('.leaflet-popup', 5);

        // Verify popup content has wait time data
        $I->see('Wait Time:', '.leaflet-popup');
        $I->see('min', '.leaflet-popup');
        $I->see('Airport:', '.leaflet-popup');
        $I->see('Terminal:', '.leaflet-popup');
    }

    public function test_wait_times_handles_different_airports(EndToEndTester $I): void
    {
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        // Test ORD airport
        $postId1 = $I->havePostInDatabase([
            'post_content' => '<!-- wp:create-block/wait-times {"airport":"ORD"} /-->',
            'post_status' => 'publish'
        ]);

        $I->amOnPage("/{$postId1}");
        $I->waitForElement('.custom-marker', 10);
        $I->seeElement('[data-airport="ORD"]');

        // Test LAX airport
        $postId2 = $I->havePostInDatabase([
            'post_content' => '<!-- wp:create-block/wait-times {"airport":"LAX"} /-->',
            'post_status' => 'publish'
        ]);

        $I->amOnPage("/{$postId2}");
        $I->waitForElement('.custom-marker', 10);
        $I->seeElement('[data-airport="LAX"]');
    }

    public function test_wait_times_shows_error_without_api_key(EndToEndTester $I): void
    {
        // Remove API key
        $I->dontHaveOptionInDatabase('afh_api_key');

        $postId = $I->havePostInDatabase([
            'post_content' => '<!-- wp:create-block/wait-times {"airport":"ORD"} /-->',
            'post_status' => 'publish'
        ]);

        $I->amOnPage("/{$postId}");

        // Should show error message
        $I->waitForElement('.flight-wait-times', 5);
        $I->see('Wait times unavailable');
    }

    public function test_shortcode_renders_same_as_block(EndToEndTester $I): void
    {
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        // Create post with shortcode
        $postId = $I->havePostInDatabase([
            'post_content' => '[wait_times airport="SEA"]',
            'post_status' => 'publish'
        ]);

        $I->amOnPage("/{$postId}");

        // Verify shortcode renders map
        $I->waitForElement('.custom-marker', 10);
        $I->seeElement('.flight-wait-times');
        $I->seeElement('[data-airport="SEA"]');

        // Click marker to verify interactivity
        $I->click('.custom-marker');
        $I->waitForElement('.leaflet-popup', 5);
        $I->see('Wait Time:', '.leaflet-popup');
    }
}
