<?php 

/**
 * Plugin Name: Mapbox Integration
 * Description: Adds Mapbox maps to your WordPress site
 * Version: 1.0.0
 */


function enqueue_mapbox(){
    // CSS
    wp_enqueue_style(
        'mapbox-gl-css',
        'https://api.mapbox.com/mapbox-gl-js/v3.30.0/mapbox-gl.css',
        array(),
        '3.30.0'
    );
    
    // SCRIPTS 

    // Mapbox GL JS actual library
    wp_enqueue_script(
        'mapbox-gl-js',
        'https://api.mapbox.com/mapbox-gl-js/v3.30.0/mapbox-gl.js',
        array(), // dependencies and loads first
        '3.30.0',
        true
    );

    // my custom code to render
    wp_enqueue_script(
        'my-mapbox-init',
        plugin_dir_url(__FILE__) . '/js/mapbox-init.js',
        array('mapbox-gl-js'), // Depends on mapbox-gl-js ^^ says dont load 
        // me until this script in the array loads first 
        '1.0.0',
        true
    );

    // passes key and attaches to ^^
     wp_localize_script(
        'my-mapbox-init',
        'mapboxConfig',
        array(
            'accessToken' => 'YOUR_MAPBOX_TOKEN_HERE'
        )
    );
}

// Shortcode to display the map
function mapbox_map_shortcode() {
    // Load scripts ONLY when shortcode is used
    enqueue_mapbox();

    return '<div id="map" style="position: absolute; top: 0; bottom: 0; width: 100%;"></div>';
}
add_shortcode('mapbox_map', 'mapbox_map_shortcode');

add_action('rest_api_init', 'registerMapboxRest');

function registerMapboxRest() {
    register_rest_route(
        'mapbox/v1',
        '/locations',
        array(
            'methods' => 'GET',
            'callback' => 'get_mapbox_locations',
            'permission_callback' => '__return_true'
        )
    );
}

function get_mapbox_locations() {
    $locations = array(
        array('name' => 'Boston Common', 'lng' => -71.0655, 'lat' => 42.3551),
        array('name' => 'Fenway Park', 'lng' => -71.0972, 'lat' => 42.3467),
        array('name' => 'MIT', 'lng' => -71.0942, 'lat' => 42.3601),
        array('name' => 'Harvard', 'lng' => -71.1167, 'lat' => 42.3770)
    );
    
    return rest_ensure_response($locations);
}
