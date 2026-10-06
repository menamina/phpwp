<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

class AdminSettingsCest
{
    public function test_settings_page_renders_form(FunctionalTester $I): void
    {
        // Login as admin
        $I->loginAsAdmin();

        // Visit the settings page
        $I->amOnAdminPage('options-general.php?page=afh-settings');

        // Check page title
        $I->see('Airport Info Hub Settings', 'h1');

        // Check form elements exist
        $I->seeElement('form[action="options.php"]');
        $I->seeElement('input[name="afh_api_key"]');
        $I->seeElement('input[name="afh_default_airport"]');

        // Check submit button
        $I->seeElement('input[type="submit"]');
        $I->see('Save Changes', 'input[type="submit"]');
    }

    public function test_settings_can_be_saved(FunctionalTester $I): void
    {
        // Login as admin
        $I->loginAsAdmin();

        // Visit the settings page
        $I->amOnAdminPage('options-general.php?page=afh-settings');

        // Fill in the form
        $I->fillField('afh_api_key', 'test_api_key_12345');
        $I->fillField('afh_default_airport', 'LAX');

        // Submit the form
        $I->click('Save Changes');

        // Should see success message
        $I->see('Settings saved', '.notice-success');

        // Verify values were saved in database
        $I->seeOptionInDatabase('afh_api_key', 'test_api_key_12345');
        $I->seeOptionInDatabase('afh_default_airport', 'LAX');
    }

    public function test_settings_page_requires_admin_privileges(FunctionalTester $I): void
    {
        // Create a subscriber user (non-admin)
        $subscriberId = $I->haveUserInDatabase('subscriber_user', 'subscriber');

        // Login as subscriber
        $I->loginAs('subscriber_user', 'subscriber_user');

        // Try to visit settings page
        $I->amOnAdminPage('options-general.php?page=afh-settings');

        // Should not have access
        $I->see('Sorry, you are not allowed to access this page');
    }

    public function test_settings_page_displays_current_values(FunctionalTester $I): void
    {
        // Set some options in database
        $I->haveOptionInDatabase('afh_api_key', 'existing_key_123');
        $I->haveOptionInDatabase('afh_default_airport', 'ORD');

        // Login as admin
        $I->loginAsAdmin();

        // Visit the settings page
        $I->amOnAdminPage('options-general.php?page=afh-settings');

        // Check that current values are displayed in form
        $I->seeInField('afh_api_key', 'existing_key_123');
        $I->seeInField('afh_default_airport', 'ORD');
    }

    public function test_settings_appear_in_admin_menu(FunctionalTester $I): void
    {
        // Login as admin
        $I->loginAsAdmin();

        // Visit admin dashboard
        $I->amOnAdminPage('/');

        // Go to Settings menu
        $I->amOnAdminPage('options-general.php');

        // Check that our settings page link exists
        $I->see('Airport Info Hub');
        $I->seeLink('Airport Info Hub');
        $I->click('Airport Info Hub');

        // Should be on our settings page
        $I->seeInCurrentUrl('page=afh-settings');
        $I->see('Airport Info Hub Settings', 'h1');
    }
}
