<?php

namespace Tests\EndToEnd;

use Tests\Support\EndToEndTester;

class ActivationCest
{
    public function test_it_deactivates_activates_correctly(EndToEndTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnPluginsPage();

        $I->seePluginActivated('react-plugin');

        $I->deactivatePlugin('react-plugin');

        $I->seePluginDeactivated('react-plugin');

        $I->activatePlugin('react-plugin');

        $I->seePluginActivated('react-plugin');
    }
}
