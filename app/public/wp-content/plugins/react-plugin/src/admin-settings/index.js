import { createRoot, useState, useEffect } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { TextControl, Button, Notice } from "@wordpress/components";
import {
	QueryClient,
	QueryClientProvider,
	useQuery,
	useMutation,
} from "@tanstack/react-query";

const queryClient = new QueryClient();

function SettingsPage() {
	const [airport, setAirport] = useState("");
	const [apiKey, setApiKey] = useState("");

	const { data, isLoading } = useQuery({
		queryKey: ["settings"],
		queryFn: () => apiFetch({ path: "/wp/v2/settings" }),
	});

	useEffect(() => {
		if (data) {
			console.log("Fetched settings:", data);
			console.log("Airport:", data.wait_times_default_airport);
			console.log("API Key:", data.wait_times_api_key);
			setAirport(data.wait_times_default_airport || "");
			setApiKey(data.wait_times_api_key || "");
		}
	}, [data]);

	const mutation = useMutation({
		mutationFn: (data) =>
			apiFetch({
				path: "/wp/v2/settings",
				method: "POST",
				data,
			}),
		onSuccess: () => {
			queryClient.invalidateQueries({ queryKey: ["settings"] });
		},
	});

	const save = () => {
		mutation.mutate({
			wait_times_default_airport: airport,
			wait_times_api_key: apiKey,
		});
	};

	if (isLoading) {
		return <p>Loading settings...</p>;
	}

	return (
		<>
			<h1>Wait Times Settings</h1>
			{mutation.isSuccess && (
				<Notice status="success" onRemove={() => mutation.reset()}>
					Settings saved.
				</Notice>
			)}
			{mutation.isError && (
				<Notice status="error" onRemove={() => mutation.reset()}>
					Save failed.
				</Notice>
			)}
			<TextControl
				label="Default airport"
				value={airport}
				onChange={setAirport}
			/>
			<TextControl label="API key" value={apiKey} onChange={setApiKey} />
			<Button
				variant="primary"
				onClick={save}
				isBusy={mutation.isPending}
				disabled={mutation.isPending}
			>
				{mutation.isPending ? "Saving..." : "Save"}
			</Button>
		</>
	);
}

createRoot(document.getElementById("wait-times-settings")).render(
	<QueryClientProvider client={queryClient}>
		<SettingsPage />
	</QueryClientProvider>,
);
