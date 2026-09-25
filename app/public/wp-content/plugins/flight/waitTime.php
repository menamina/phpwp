<?php 

/**
 * Plugin Name: Flight Wait Time Tracker
 * Description: Adds a flight wait time tracker
 * Version: 1.0.0
 */

function enqueue_waittime(){
     wp_enqueue_script(
        'waittime-script',
        plugin_dir_url(__FILE__) . 'waitTime.js',
        array(),
        true
    );


}

 add_action('rest_api_init', function(){
        register_rest_route('waitTime', '/status', [
        'methods' => 'GET',
        'callback' => 'getCachedWaitTimes',
        'permission_callback' => '__return_true'
        ]);
    });

function getCachedWaitTimes() {
    $cached = get_transient("waitTimes");
    if ($cached !== false){
        return $cached;
    }

    $data = getWaitTime();

    set_transient("waitTimes", $data, 180);

    return $data;
}

function getWaitTime(){

    return [
      [
      "airport" => "LAX",
      "iata" => "LAX",
      "terminal" => "Terminal 1",
      "wait_time_minutes" => 18,
      "last_updated" => "2026-09-24T10:30:00Z",
      "lat" => 33.9416,
      "lng" => -118.4085
      ],
      [
      "airport" => "JFK",
      "iata" => "JFK",
      "terminal" => "Terminal 4",
      "wait_time_minutes" => 5,
      "lat" => 40.6413,
      "lng" => -73.7781
      ]
    ];

}


function waittime_shortcode() {
    enqueue_waittime();
    return '<div id="wait"></div>';
}

add_shortcode('waitTimeTracker', 'waittime_shortcode');