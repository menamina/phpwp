<?php
/**
 * Plugin Name: Flight Tracker
 * Description: Flight tracking plugin
 * Version: 1.0.0
 */

function enqueue_flight_scripts() {
    wp_enqueue_script(
        'flight-tracker',
        plugin_dir_url(__FILE__) . 'js/flight.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_flight_scripts');
