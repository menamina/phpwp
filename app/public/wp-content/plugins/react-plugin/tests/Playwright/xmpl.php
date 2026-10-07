<?php
use Playwright\Playwright;

$userContext = Playwright::chromium(['headless' => true]); //creates browser + browsercontext
//browser context is an isolated incotnito like session within a browser instance
//each context has its own cookies, local storage, cache + do not share w other contexts
//can create mult contexts from single browser instance 
$browser = $userContext->browser();
$adminContext = $browser->newContext();

// A Page represents a single tab within a BrowserContext
// Most of the essential actions, like navigating, clicking, and typing
// are methods on the Page object.

$userPage = $userContext->newPage();
$adminPage = $adminContext->newPage();

// A Locator is a "recipe" for finding an element on the page. 
// Unlike traditional methods that find an element immediately, 
// a Locator has auto-waiting built-in


// There are two primary strategies for handling authentication in your tests:
// UI Login: Perform a login by interacting with the login form, just as a user would.
// Reusing Authentication State: Log in once, save the session state, and then reuse it across multiple tests. * This is the recommended approach for most scenarios.*



$context->close();