<?php
namespace Tests\Integration;

use lucatume\WPBrowser\TestCase\WPTestCase;

class WaitTimesApiTest extends WPTestCase
{
    public function test_rest_route_is_registered(): void {
        $routes = rest_get_server()->get_routes();
        $this->assertArrayHasKey('/wait/times', $routes);
    }

    public function test_response_structure_and_fields(): void {
        $request = new \WP_REST_Request('GET', '/wait/times');
        $request->set_param('airport', 'ORD');

        $response = rest_do_request($request);

        $this->assertEquals(200, $response->get_status());

        $data = $response->get_data();

        $this->assertTrue($data['success']);
        $this->assertEquals('ORD', $data['airport']);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertNotEmpty($data['timestamp']);
        $this->assertIsArray($data['data']);
        $this->assertNotEmpty($data['data']);

        $checkpoint = $data['data'][0];
        $this->assertArrayHasKey('checkpoint', $checkpoint);
        $this->assertArrayHasKey('terminal', $checkpoint);
        $this->assertArrayHasKey('wait_time', $checkpoint);
        $this->assertArrayHasKey('status', $checkpoint);
        $this->assertArrayHasKey('lat', $checkpoint);
        $this->assertArrayHasKey('lng', $checkpoint);
    }

    public function test_filters_by_airport_code(): void {
        $request = new \WP_REST_Request('GET', '/wait/times');
        $response = rest_do_request($request);
        $data = $response->get_data();
        $this->assertEquals('ORD', $data['airport']);

        $request = new \WP_REST_Request('GET', '/wait/times');
        $request->set_param('airport', 'ORD');
        $response = rest_do_request($request);
        $data = $response->get_data();

        foreach ($data['data'] as $checkpoint) {
            $this->assertEquals('ORD', $checkpoint['airport']);
        }
        
        $request = new \WP_REST_Request('GET', '/wait/times');
        $request->set_param('airport', 'LAX');
        $response = rest_do_request($request);
        $data = $response->get_data();

        $this->assertCount(1, $data['data']);
        $this->assertEquals('LAX', $data['data'][0]['airport']);
    }

    public function test_empty_results_for_unknown_airport(): void {
        $request = new \WP_REST_Request('GET', '/wait/times');
        $request->set_param('airport', 'UNKNOWN');

        $response = rest_do_request($request);
        $data = $response->get_data();

        $this->assertTrue($data['success']);
        $this->assertEmpty($data['data']);
    }

}