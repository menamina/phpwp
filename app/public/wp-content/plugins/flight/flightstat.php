<?php 

/**
 * Plugin Name: Flight Status Widget + Board
 * Description: Adds a flight status widget and departure board style list via shortcode
 * Version: 1.0.0
 */

function enqueue_flight() {
      wp_enqueue_script(
        'flightdiv',
        plugin_dir_url(__FILE__) . 'flightjs.js',
        array(),
        true
    );
}

add_action('rest_api_init', function() {
    register_rest_route('flight', '/status', [
        'methods' => 'GET',
        'callback' => 'cachedFlightData',
        'permission_callback' => '__return_true'
    ]);
});


function cachedFlightData(){
    $cache = get_transient("flightData");
    if ($cache){
        return $cache;
    }

    $data = getFlightAPIData();

    set_transient('flightData', $data, 180);

    return $data;
}

function getFlightAPIData(){
    $response = wp_remote_get('https://api.aviationstack.com/v1/flights?access_key=7db0d516b096fa10389900afc2e4e375&limit=20');

    return json_decode(wp_remote_retrieve_body($response), true);
}


function flight_shortcode() {
    enqueue_flight();
    return '<div id="flight-board"></div>';
}

add_shortcode('flightwidget', 'flight_shortcode');