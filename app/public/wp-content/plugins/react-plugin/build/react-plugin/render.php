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

// Get airport attribute (defaults to ORD)
$airport = $attributes['airport'] ?? 'ORD';

// Output the placeholder div that view.js will mount the React map to
?>
<div <?php echo get_block_wrapper_attributes([
  'class' => 'flight-wait-times',
  'data-airport' => esc_attr($airport),
]); ?>></div>

