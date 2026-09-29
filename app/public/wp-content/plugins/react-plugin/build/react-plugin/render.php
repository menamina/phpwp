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

// 1: Read attributes
$airport = $attributes['airport'] ?? '';
$limit = $attributes['limit'] ?? 10;

// 2: Validate attributes
if (empty($airport)){
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<p>Please enter an airport in the block settings</p>
	</div>
	<?php
	return;
}

// 3: Check if flight plugin is active
if (!function_exists('get_cached_flights')) {
    ?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
        <p>Flight plugin function not found. Debug: <?php
            echo function_exists('cachedFlightData') ? 'cachedFlightData exists' : 'cachedFlightData missing';
            echo ' | ';
            echo function_exists('getFlightAPIData') ? 'getFlightAPIData exists' : 'getFlightAPIData missing';
        ?></p>
    </div>
    <?php
    return;
}

// 4: Get flight data
$flights = get_cached_flights($airport, $limit);

// DEBUG: Show API response
echo '<pre>';
print_r($flights);
echo '</pre>';

// 5: Display data


?>

<div <?php echo get_block_wrapper_attributes([
  'data-airport' => $airport,
  'data-limit'   => $limit,
]); ?>>
    <h3>Flights from <?php echo esc_html($airport); ?></h3>
    
    <?php if (empty($flights['data'])): ?>
        <p>No flights found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Flight</th>
                    <th>Airline</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights['data'] as $flight): ?>
                    <tr>
                        <td><?php echo esc_html($flight['flight']['iata']); ?></td>
                        <td><?php echo esc_html($flight['airline']['name']); ?></td>
                        <td><?php echo esc_html($flight['departure']['iata']); ?></td>
                        <td><?php echo esc_html($flight['arrival']['iata']); ?></td>
                        <td><?php echo esc_html($flight['flight_status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

