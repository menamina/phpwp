<?php
namespace Tests\Integration;

use lucatume\WPBrowser\TestCase\WPTestCase;

class FlightApiTest extends WPTestCase
{
    public function test_cachedFlights_with_custom_params(): void {
        update_option('afh_api_key', 'test_key_123');

        $result = get_cached_flights('SEA', 50);

        $this->assertIsArray($result);

        $cache_key = 'flightData_SEA_50';
        $cached = get_transient($cache_key);
        $this->assertNotFalse($cached, 'Data should be cached in transient');

        $this->assertEquals($result, $cached);
    }

    public function test_cachedFlights_uses_defaults(): void {
        update_option('afh_api_key', 'test_key_123');
        update_option('afh_default_airport', 'LAX');

        $result = get_cached_flights();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
    }

    public function test_cachedFlights_sanitizes_airport_code(): void {
        update_option('afh_api_key', 'test_key_123');

        $result = get_cached_flights('lax', 50);

        $cache_key = 'flightData_LAX_50';
        $cached = get_transient($cache_key);
        $this->assertNotFalse($cached, 'Cache should use uppercase airport code');

        update_option('afh_default_airport', 'ORD');
        $result = get_cached_flights('INVALID123!@#', 50);

        $cache_key_fallback = 'flightData_ORD_50';
        $cached_fallback = get_transient($cache_key_fallback);
        $this->assertNotFalse($cached_fallback, 'Should fall back to default airport for invalid input');
    }

    public function test_cachedFlights_validates_limit(): void {
        update_option('afh_api_key', 'test_key_123');

        $result = get_cached_flights('ORD', -10);
        $cache_key = 'flightData_ORD_100';
        $cached = get_transient($cache_key);
        $this->assertNotFalse($cached, 'Negative limit should fall back to 100');

        $result = get_cached_flights('ORD', 9999);
        $this->assertNotFalse($cached, 'Excessive limit should fall back to 100');
    }

    public function test_flightAPIData_returns_error_without_api_key(): void {
        delete_option('afh_api_key');

        $result = getFlightAPIData('ORD', 100);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('API key not configured', $result['error']);
        $this->assertEmpty($result['data']);
    }

}
