<?php

/**
 * Plugin Name: React plugin
 * Description: React php plugin
 * Version: 1.0.0
 */

function enq_plugin(){
    $asset_file = include(plugin_dir_path(__FILE__) . 'build/index.asset.php');
    
    wp_enqueue_script(
        'my-react-plugin',
        plugin_dir_url(__FILE__) . 'build/index.js',
        $asset_file['dependencies'],
        $asset_file['version'],
        true
    );


}

add_action('wp_register_scripts', 'enq_plugin')