<?php
/**
 * Plugin Name:       React Plugin
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       react-plugin
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include admin settings page
require_once plugin_dir_path( __FILE__ ) . 'admin-settings.php';

/**
 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
 * based on the registered block metadata. Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function create_block_react_plugin_block_init() {
	wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
}
add_action( 'init', 'create_block_react_plugin_block_init' );

// Register and enqueue the React view script for shortcode usage
function react_plugin_register_wait_times_script() {
    $asset_file = plugin_dir_path( __FILE__ ) . 'build/react-plugin/view.asset.php';

    if ( file_exists( $asset_file ) ) {
        $asset = include $asset_file;

        wp_register_script(
            'react-plugin-wait-times',
            plugins_url( 'build/react-plugin/view.js', __FILE__ ),
            $asset['dependencies'],
            $asset['version'],
            true
        );

        // Pass settings to JavaScript
        wp_localize_script(
            'react-plugin-wait-times',
            'afhSettings',
            [
                'refreshInterval' => get_option('afh_refresh_interval', 60) * 1000, // Convert to milliseconds
                'defaultAirport' => get_option('afh_default_airport', 'ORD')
            ]
        );

        // Register the CSS that includes Leaflet styles
        wp_register_style(
            'react-plugin-wait-times',
            plugins_url( 'build/react-plugin/view.css', __FILE__ ),
            [],
            $asset['version']
        );
    }
}
add_action( 'wp_enqueue_scripts', 'react_plugin_register_wait_times_script' );

function react_plugin_wait_times_shortcode( $atts ) {
    $atts = shortcode_atts( [ 'airport' => 'ORD' ], $atts );

    $airport = sanitize_text_field( $atts['airport'] );
    $airport = strtoupper( $airport );

    wp_enqueue_script( 'react-plugin-wait-times' );
    wp_enqueue_style( 'react-plugin-wait-times' );

    return '<div class="flight-wait-times" data-airport="'
        . esc_attr( $airport ) . '"></div>';
}

add_shortcode( 'wait_times', 'react_plugin_wait_times_shortcode' );

add_action('rest_api_init', function() {
    register_rest_route('wait', '/times', [
        'methods' => 'GET',
        'callback' => 'react_plugin_wait_times_api',
        'permission_callback' => '__return_true',
        'args' => [
            'airport' => [
                'required' => false,
                'default' => 'ORD',
                'sanitize_callback' => function($value) {
                    return strtoupper( sanitize_text_field( $value ) );
                },
                'validate_callback' => function($value) {
                    return preg_match('/^[A-Z]{3,4}$/i', $value);
                }
            ]
        ]
    ]);
});










function react_plugin_wait_times_api($request) {
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

    foreach ($checkpoints as $point) {
        if ($point['airport'] === $airport) {
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

function get_cached_flights($airport, $limit = 100){

    $airport = sanitize_text_field( $airport );
    $airport = strtoupper( $airport );

    $limit = absint( $limit );
    if ( $limit < 1 || $limit > 100 ) {
        $limit = 100;
    }

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
    $api_key = get_option('afh_api_key', '');

    if (empty($api_key)) {
        return [
            'error' => 'API key not configured. Please add your API key in Settings → Airport Info Hub.',
            'data' => []
        ];
    }

    $url = "https://api.aviationstack.com/v1/flights?access_key={$api_key}&limit={$limit}";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return [
            'error' => 'Failed to fetch flight data: ' . $response->get_error_message(),
            'data' => []
        ];
    }

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
