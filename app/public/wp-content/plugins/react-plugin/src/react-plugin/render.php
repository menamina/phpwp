<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Get airport attribute (defaults to site-wide setting, then ORD)
$airport = sanitize_text_field( $attributes['airport'] ?? get_option('afh_default_airport', 'ORD') );
$airport = strtoupper( $airport );

// Validate airport code format
if ( ! preg_match('/^[A-Z]{3,4}$/', $airport) ) {
    $airport = get_option('afh_default_airport', 'ORD');
}

// Output the placeholder div that view.js will mount the React map to
?>
<div class="flight-wait-times" data-airport="<?php echo esc_attr($airport); ?>"></div>

