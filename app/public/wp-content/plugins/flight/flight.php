<?php
/**
 * Plugin Name: Flight Status Widget + Board
 * Description: Adds a flight status widget and departure board style list via shortcode
 * Version: 1.0.1
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Flush rewrite rules on plugin activation
register_activation_hook( __FILE__, function() {
    flush_rewrite_rules();
});

add_action('rest_api_init', function() {
    register_rest_route('flight', '/status', [
        'methods' => 'GET',
        'callback' => 'cachedFlightData',
        'permission_callback' => '__return_true',
        'args' => [
            'airport' => [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            'limit' => [
                'default' => 10,
                'sanitize_callback' => 'absint'
            ]
        ]
    ]);
});



function cachedFlightData($request){
   $airport = strtoupper( sanitize_text_field( $request['airport'] ?? '' ) );
   $limit = absint( $request['limit'] ?? 10 );

    return get_cached_flights($airport, $limit);
}


function get_cached_flights($airport, $limit = 10){
    $cacheKey = "flightData_{$airport}_{$limit}";

    $cache = get_transient($cacheKey);
    if ($cache){
        return $cache;
    }

    $data = getFlightAPIData($airport, $limit);

    set_transient($cacheKey, $data, 180);

    return $data;
}

function getFlightAPIData($airport, $limit){
    $api_key = defined('AVIATIONSTACK_API_KEY') ? AVIATIONSTACK_API_KEY : '';

    $url = "https://api.aviationstack.com/v1/flights?access_key={$api_key}&limit={$limit}";

    $response = wp_remote_get($url);
    $all_data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($airport) && isset($all_data['data'])) {
        $filtered = [];
        foreach ($all_data['data'] as $flight) {
            if (isset($flight['departure']['iata']) &&
                strtoupper($flight['departure']['iata']) === strtoupper($airport)) {
                $filtered[] = $flight;
            }
        }
        $all_data['data'] = $filtered;
    }

    return $all_data;
}

add_action('rest_api_init', function() {
    register_rest_route('wait', '/times', [
        'methods' => 'GET',
        'callback' => 'waitTimes',
        'permission_callback' => '__return_true',
    ]);
});


function waitTimes($request){
    $airport = $request->get_param('airport') ?: 'ORD';

    $checkpoints = [
        [
            'checkpoint' => 'Security Checkpoint A',
            'terminal' => 'Terminal 1',
            'airport' => 'ORD',
            'wait_time' => rand(5, 25),
            'status' => 'Normal',
            'last_updated' => current_time('mysql'),
            'lat' => 41.9786,
            'lng' => -87.9047
        ],
        [
            'checkpoint' => 'Security Checkpoint B',
            'terminal' => 'Terminal 1',
            'airport' => 'LAX',
            'wait_time' => rand(10, 30),
            'status' => 'Busy',
            'last_updated' => current_time('mysql'),
            'lat' => 33.9416,
            'lng' => -118.4085
        ],
        [
            'checkpoint' => 'Security Checkpoint C',
            'terminal' => 'Terminal 2',
            'airport' => '123',
            'wait_time' => rand(5, 20),
            'status' => 'Normal',
            'last_updated' => current_time('mysql'),
            'lat' => 41.9796,
            'lng' => -87.9057
        ],
        [
            'checkpoint' => 'Customs',
            'terminal' => 'International',
            'airport' => 'ORD',
            'wait_time' => rand(15, 45),
            'status' => 'Very Busy',
            'last_updated' => current_time('mysql'),
            'lat' => 41.9776,
            'lng' => -87.9037
        ],
        [
            'checkpoint' => 'Immigration',
            'terminal' => 'International',
            'airport' => '123',
            'wait_time' => rand(20, 50),
            'status' => 'Very Busy',
            'last_updated' => current_time('mysql'),
            'lat' => 41.9806,
            'lng' => -87.9067
        ],
        [
            'checkpoint' => 'Baggage Claim 1',
            'terminal' => 'Terminal 1',
            'airport' => 'ORD',
            'wait_time' => rand(5, 15),
            'status' => 'Fast',
            'last_updated' => current_time('mysql'),
            'lat' => 41.9766,
            'lng' => -87.9027
        ]
    ];

    $reqCheckPoints = [];

    foreach ($checkpoints as $point){
        if($point['airport'] === $airport){
            $reqCheckPoints[] = $point;
        }

    }

    return [
        'success' => true,
        'data' => $reqCheckPoints,
        'airport' => $airport,
        'timestamp' => current_time('mysql')
    ];
}

