import { createRoot, useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {
	TextControl,
	Button,
	Notice,
	RangeControl,
} from '@wordpress/components';
import {
	QueryClient,
	QueryClientProvider,
	useQuery,
	useMutation,
} from '@tanstack/react-query';

interface Settings {
	afh_api_key: string;
	afh_default_airport: string;
	afh_refresh_interval: number;
}

interface SettingsMutation {
	afh_default_airport: string;
	afh_api_key: string;
	afh_refresh_interval: number;
}

const queryClient = new QueryClient();

function SettingsPage() {
	const [ airport, setAirport ] = useState< string >( '' );
	const [ apiKey, setApiKey ] = useState< string >( '' );
	const [ refreshInterval, setRefreshInterval ] = useState< number >( 60 );

	const { data, isLoading } = useQuery< Settings >( {
		queryKey: [ 'settings' ],
		queryFn: () =>
			apiFetch( { path: '/wp/v2/settings' } ) as Promise< Settings >,
	} );

	useEffect( () => {
		if ( data ) {
			setAirport( data.afh_default_airport || '' );
			setApiKey( data.afh_api_key || '' );
			setRefreshInterval( data.afh_refresh_interval || 60 );
		}
	}, [ data ] );

	const mutation = useMutation< Settings, Error, SettingsMutation >( {
		mutationFn: ( settingsData: SettingsMutation ) =>
			apiFetch( {
				path: '/wp/v2/settings',
				method: 'POST',
				data: settingsData,
			} ) as Promise< Settings >,
		onSuccess: () => {
			queryClient.invalidateQueries( { queryKey: [ 'settings' ] } );
		},
	} );

	const save = () => {
		mutation.mutate( {
			afh_default_airport: airport,
			afh_api_key: apiKey,
			afh_refresh_interval: refreshInterval,
		} );
	};

	if ( isLoading ) {
		return <p>Loading settings...</p>;
	}

	return (
		<>
			<h1>Airport Info Hub Settings</h1>
			{ mutation.isSuccess && (
				<Notice status="success" onRemove={ () => mutation.reset() }>
					Settings saved.
				</Notice>
			) }
			{ mutation.isError && (
				<Notice status="error" onRemove={ () => mutation.reset() }>
					Save failed.
				</Notice>
			) }
			<TextControl
				label="Default Airport Code"
				value={ airport }
				onChange={ setAirport }
				help="3-letter IATA code (e.g., ORD, LAX, JFK)"
			/>
			<TextControl
				label="Aviation API Key"
				value={ apiKey }
				onChange={ setApiKey }
				help="Get your API key from aviationstack.com"
			/>
			<RangeControl
				label="Wait Time Map Refresh Interval (seconds)"
				value={ refreshInterval }
				onChange={ setRefreshInterval }
				min={ 10 }
				max={ 300 }
				help="How often the wait time map should refresh (10-300 seconds)"
			/>
			<Button
				variant="primary"
				onClick={ save }
				isBusy={ mutation.isPending }
				disabled={ mutation.isPending }
			>
				{ mutation.isPending ? 'Saving...' : 'Save Settings' }
			</Button>
		</>
	);
}

const rootElement = document.getElementById( 'afh-settings' );
if ( rootElement ) {
	createRoot( rootElement ).render(
		<QueryClientProvider client={ queryClient }>
			<SettingsPage />
		</QueryClientProvider>
	);
}
