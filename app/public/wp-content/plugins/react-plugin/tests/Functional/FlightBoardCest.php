<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;
use Tests\Support\Helpers\ApiMocker;

class FlightBoardCest
{
    public function test_flight_board_renders_table_with_data(FunctionalTester $I): void
    {
        // Mock API to return flight data
        ApiMocker::mockFlightApiWithData();

        // Set up API key
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        // Create a post with the Flight Board block
        $postId = $I->havePostInDatabase([
            'post_title' => 'Flight Board Test Page',
            'post_content' => '<!-- wp:react-plugin/flight-board {"airport":"ORD","limit":100} /-->',
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        // Visit the page
        $I->amOnPage("/{$postId}");

        // Check that the Flight Board container is rendered
        $I->seeElement('.flight-board-container');

        // Check for table structure
        $I->seeElement('table.flight-board');
        $I->seeElement('table.flight-board thead');
        $I->seeElement('table.flight-board tbody');

        // Check for table headers
        $I->see('Flight', 'th');
        $I->see('Airline', 'th');
        $I->see('From', 'th');
        $I->see('To', 'th');
        $I->see('Departure', 'th');
        $I->see('Status', 'th');

        // Verify actual flight data appears
        $I->see('AA100', '.flight-board');
        $I->see('American Airlines', '.flight-board');

        ApiMocker::removeMocks();
    }

    public function test_flight_board_shows_error_without_api_key(FunctionalTester $I): void
    {
        // Make sure no API key is set
        $I->dontHaveOptionInDatabase('afh_api_key');

        // Create a post with the Flight Board block
        $postId = $I->havePostInDatabase([
            'post_title' => 'Flight Board Error Test',
            'post_content' => '<!-- wp:react-plugin/flight-board {"airport":"LAX"} /-->',
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        // Visit the page
        $I->amOnPage("/{$postId}");

        // Check that error message is displayed
        $I->seeElement('.flight-board-error');
        $I->see('API key not configured', '.flight-board-error');

        // Table should not be rendered
        $I->dontSeeElement('table.flight-board');
    }

    public function test_flight_board_shows_no_flights_message(FunctionalTester $I): void
    {
        // Mock API to return empty data
        ApiMocker::mockFlightApiEmpty();

        // Set up API key
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        // Create a post with the Flight Board block
        $postId = $I->havePostInDatabase([
            'post_title' => 'Flight Board Empty Test',
            'post_content' => '<!-- wp:react-plugin/flight-board {"airport":"ORD"} /-->',
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        // Visit the page
        $I->amOnPage("/{$postId}");

        // Check for container - this should ALWAYS exist
        $I->seeElement('.flight-board-container');

        // Should show "No flights found" message
        $I->see('No flights found', '.flight-board-container');

        // Table should not be rendered
        $I->dontSeeElement('table.flight-board');

        ApiMocker::removeMocks();
    }

    public function test_flight_board_respects_custom_airport(FunctionalTester $I): void
    {
        // Set up API key
        $I->haveOptionInDatabase('afh_api_key', 'test_key_123');

        // Create a post with custom airport attribute
        $postId = $I->havePostInDatabase([
            'post_title' => 'Flight Board Custom Airport',
            'post_content' => '<!-- wp:react-plugin/flight-board {"airport":"SEA","limit":50} /-->',
            'post_status' => 'publish',
            'post_type' => 'post'
        ]);

        // Visit the page
        $I->amOnPage("/{$postId}");

        // Check that the block renders
        $I->seeElement('.flight-board-container');
    }
}
