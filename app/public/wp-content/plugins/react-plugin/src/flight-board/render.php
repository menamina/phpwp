<?php
/**
 * Server-side render for Flight Board block
 * Fetches flight data from cached API and outputs HTML table
 */

$airport = $attributes['airport'] ?? get_option('afh_default_airport', 'ORD');
$limit = $attributes['limit'] ?? 100;

// Fetch cached flight data
$flight_data = get_cached_flights($airport, $limit);

?>
<div <?php echo get_block_wrapper_attributes(['class' => 'flight-board-container']); ?>>
	<?php if (isset($flight_data['error'])): ?>
		<p class="flight-board-error"><?php echo esc_html($flight_data['error']); ?></p>
	<?php elseif (empty($flight_data['data'])): ?>
		<p class="flight-board-empty">No flights found for <?php echo esc_html($airport); ?></p>
	<?php else: ?>
		<table class="flight-board">
			<thead>
				<tr>
					<th>Flight</th>
					<th>Airline</th>
					<th>From</th>
					<th>To</th>
					<th>Departure</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($flight_data['data'] as $flight): ?>
					<tr>
						<td><?php echo esc_html($flight['flight']['iata'] ?? 'N/A'); ?></td>
						<td><?php echo esc_html($flight['airline']['name'] ?? 'N/A'); ?></td>
						<td><?php echo esc_html($flight['departure']['iata'] ?? 'N/A'); ?></td>
						<td><?php echo esc_html($flight['arrival']['iata'] ?? 'N/A'); ?></td>
						<td><?php echo esc_html($flight['departure']['scheduled'] ?? 'N/A'); ?></td>
						<td><?php echo esc_html($flight['flight_status'] ?? 'N/A'); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
