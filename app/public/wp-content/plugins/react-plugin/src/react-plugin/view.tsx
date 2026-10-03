import { createRoot } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {
	QueryClient,
	QueryClientProvider,
	useQuery,
} from '@tanstack/react-query';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

interface Checkpoint {
	checkpoint: string;
	terminal: string;
	airport: string;
	wait_time: number;
	status: string;
	last_updated: string;
	lat: number;
	lng: number;
}

interface WaitTimesResponse {
	success: boolean;
	data: Checkpoint[];
	airport: string;
	timestamp: string;
}

interface AFHSettings {
	refreshInterval: number;
	defaultAirport: string;
}

declare global {
	interface Window {
		afhSettings?: AFHSettings;
	}
}

// Fix default marker icon issue with webpack
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions( {
	iconRetinaUrl:
		'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
	iconUrl:
		'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
	shadowUrl:
		'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
} );

// Function to get marker color based on wait time
function getMarkerColor( waitTime: number ): string {
	if ( waitTime < 10 ) {
		return 'green';
	}
	if ( waitTime < 20 ) {
		return 'orange';
	}
	return 'red';
}

// Create custom colored marker icon
function createColoredIcon( color: string ): L.DivIcon {
	return L.divIcon( {
		className: 'custom-marker',
		html: `<div style="background-color: ${ color }; width: 25px; height: 41px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
		iconSize: [ 25, 41 ],
		iconAnchor: [ 12, 41 ],
		popupAnchor: [ 1, -34 ],
	} );
}

interface FlightBoardProps {
	airport: string;
}

function FlightBoard( { airport }: FlightBoardProps ) {
	const refreshInterval = window.afhSettings?.refreshInterval || 60000;

	const { data, error, isLoading } = useQuery< WaitTimesResponse >( {
		queryKey: [ 'waitTimes', airport ],
		queryFn: () =>
			apiFetch( {
				path: `/wait/times?airport=${ airport }`,
			} ) as Promise< WaitTimesResponse >,
		refetchInterval: refreshInterval,
	} );

	if ( error ) {
		return <p>Wait times unavailable.</p>;
	}
	if ( isLoading ) {
		return <p>Loading wait times...</p>;
	}

	const checkpoints = data?.data || [];

	if ( checkpoints.length === 0 ) {
		return <p>No wait times available for { airport }</p>;
	}

	// Calculate map center based on average of all checkpoint positions
	const center = [
		checkpoints.reduce( ( sum, cp ) => sum + cp.lat, 0 ) /
			checkpoints.length,
		checkpoints.reduce( ( sum, cp ) => sum + cp.lng, 0 ) /
			checkpoints.length,
	];

	return (
		<div style={ { height: '400px', width: '100%' } }>
			<MapContainer
				center={ center }
				zoom={ 13 }
				scrollWheelZoom={ false }
				style={ { height: '100%', width: '100%' } }
			>
				<TileLayer
					attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
					url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
				/>
				{ checkpoints.map( ( point, index ) => {
					const coords = [ point.lat, point.lng ];
					const color = getMarkerColor( point.wait_time );
					return (
						<Marker
							key={ index }
							position={ coords }
							icon={ createColoredIcon( color ) }
						>
							<Popup>
								<div>
									<h3>{ point.checkpoint }</h3>
									<p>
										<strong>Airport:</strong>{ ' ' }
										{ point.airport }
									</p>
									<p>
										<strong>Terminal:</strong>{ ' ' }
										{ point.terminal }
									</p>
									<p>
										<strong>Wait Time:</strong>{ ' ' }
										{ point.wait_time } min
									</p>
									<p>
										<strong>Status:</strong>{ ' ' }
										{ point.status }
									</p>
								</div>
							</Popup>
						</Marker>
					);
				} ) }
			</MapContainer>
		</div>
	);
}

// React Island: Mount FlightBoard to placeholder divs created by shortcode
document.addEventListener( 'DOMContentLoaded', () => {
	const containers = document.querySelectorAll( '.flight-wait-times' );

	containers.forEach( ( container ) => {
		const airport = container.dataset.airport || 'ORD';
		const queryClient = new QueryClient();
		const root = createRoot( container );

		root.render(
			<QueryClientProvider client={ queryClient }>
				<FlightBoard airport={ airport } />
			</QueryClientProvider>
		);
	} );
} );
