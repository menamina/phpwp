<?php
namespace App\Tests;

use Playwright\Testing\PlaywrightTestCase;

// admin first

abstract class AuthenticatedTestCase extends PlaywrightTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load the saved authentication state before each test
        $this->context->loadStorageState(__DIR__.'/auth.json');
    }
}
