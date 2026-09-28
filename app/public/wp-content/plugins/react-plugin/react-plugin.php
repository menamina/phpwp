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

// function get_cached_flights($airport, $limit = 100){
//     $cacheKey = "flightData_{$airport}_{$limit}";

//     $cache = get_transient($cacheKey);
//     if ($cache){
//         return $cache;
//     }
  
//     $data = getFlightAPIData($airport, $limit);

//     set_transient($cacheKey, $data, 180);

//     return $data;
// }

// function getFlightAPIData($airport, $limit){
//     $api_key = '7db0d516b096fa10389900afc2e4e375';

//     $url = "https://api.aviationstack.com/v1/flights?access_key={$api_key}&limit={$limit}";

//     $response = wp_remote_get($url);
//     $all_data = json_decode(wp_remote_retrieve_body($response), true);


//     // if (!empty($airport) && isset($all_data['data'])) {

//     //     $filtered = [];
//     //     foreach ($all_data['data'] as $flight) {
//     //         $departure_iata = $flight['departure']['iata'] ?? 'NONE';

//     //         if (isset($flight['departure']['iata']) &&
//     //             strtoupper($flight['departure']['iata']) === strtoupper($airport)) {
//     //             $filtered[] = $flight;
//     //         }
//     //     }

//     //     $all_data['data'] = $filtered;
//     // }

//     return $all_data;
// }
