<?php

namespace Tests\Support\Helpers;

class ApiMocker
{
    /**
     * Mock the Aviation API to return flight data
     */
    public static function mockFlightApiWithData(): void
    {
        add_filter('pre_http_request', function($preempt, $args, $url) {
            // Only mock the aviation API
            if (strpos($url, 'aviationstack.com') === false) {
                return $preempt;
            }

            $mockData = [
                'data' => [
                    [
                        'flight' => ['iata' => 'AA100'],
                        'airline' => ['name' => 'American Airlines'],
                        'departure' => [
                            'iata' => 'ORD',
                            'airport' => "O'Hare",
                            'scheduled' => '2024-01-01T10:00:00+00:00'
                        ],
                        'arrival' => [
                            'iata' => 'LAX',
                            'airport' => 'Los Angeles'
                        ],
                        'flight_status' => 'scheduled'
                    ],
                    [
                        'flight' => ['iata' => 'UA200'],
                        'airline' => ['name' => 'United Airlines'],
                        'departure' => [
                            'iata' => 'ORD',
                            'airport' => "O'Hare",
                            'scheduled' => '2024-01-01T11:00:00+00:00'
                        ],
                        'arrival' => [
                            'iata' => 'SFO',
                            'airport' => 'San Francisco'
                        ],
                        'flight_status' => 'active'
                    ]
                ]
            ];

            return [
                'response' => ['code' => 200],
                'body' => json_encode($mockData)
            ];
        }, 10, 3);
    }

    /**
     * Mock the Aviation API to return no flights
     */
    public static function mockFlightApiEmpty(): void
    {
        add_filter('pre_http_request', function($preempt, $args, $url) {
            if (strpos($url, 'aviationstack.com') === false) {
                return $preempt;
            }

            return [
                'response' => ['code' => 200],
                'body' => json_encode(['data' => []])
            ];
        }, 10, 3);
    }

    /**
     * Mock the Aviation API to return an error
     */
    public static function mockFlightApiError(): void
    {
        add_filter('pre_http_request', function($preempt, $args, $url) {
            if (strpos($url, 'aviationstack.com') === false) {
                return $preempt;
            }

            return [
                'response' => ['code' => 500],
                'body' => json_encode(['error' => ['message' => 'API error']])
            ];
        }, 10, 3);
    }

    /**
     * Remove all API mocks
     */
    public static function removeMocks(): void
    {
        remove_all_filters('pre_http_request');
    }
}
