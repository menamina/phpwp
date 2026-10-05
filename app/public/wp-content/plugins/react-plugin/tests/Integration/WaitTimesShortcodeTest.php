<?php
namespace Tests\Integration;

use lucatume\WPBrowser\TestCase\WPTestCase;

class WaitTimesShortcodeTest extends WPTestCase
{
    public function test_shortcode_is_registered(): void {
        $this->assertTrue(shortcode_exists('wait_times'));
    }

    public function test_renders_with_custom_airport(): void {
        $html = do_shortcode('[wait_times airport="LAX"]');

        $this->assertStringContainsString('data-airport="LAX"', $html);
        $this->assertStringContainsString('class="flight-wait-times"', $html);
    }

    public function test_uses_default_airport_when_no_attribute(): void {
        $html = do_shortcode('[wait_times]');

        $this->assertStringContainsString('data-airport="ORD"', $html);
        $this->assertStringContainsString('class="flight-wait-times"', $html);
    }

    public function test_enqueues_scripts_and_styles(): void {
        do_shortcode('[wait_times]');

        $this->assertTrue(wp_script_is('react-plugin-wait-times', 'enqueued'));
        $this->assertTrue(wp_style_is('react-plugin-wait-times', 'enqueued'));
    }

}
