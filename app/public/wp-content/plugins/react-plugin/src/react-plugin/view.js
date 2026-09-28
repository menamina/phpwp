/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

/* eslint-disable no-console */
console.log("Hello World! (from create-block-react-plugin block)");
// /* eslint-enable no-console */

// import apiFetch from "@wordpress/api-fetch";
// import { useQuery } from "@tanstack/react-query";
import { useSelect } from "@wordpress/data";

function FlightBoard() {
	// const [isLoading, setIsLoading] = useState(true);
	// const [waitTimes, setWaitTimes] = useState([]);
	// const [isError, setIsError] = useState(false);

	// useEffect(() => {
	// 	async function waitTimeAPICall() {
	// 		const res = await apiFetch({ path: "wait/times" });
	// 		if (res.success === true) {
	// 			isLoading === true && setIsLoading(false);
	// 			setWaitTimes(res);
	// 			isError === true && setIsError(false);
	// 		} else if (res.success !== true) {
	// 			isLoading === true && setIsLoading(false);
	// 			setWaitTimes([]);
	// 			isError === false && setIsError(true);
	// 		}
	// 	}

	// 	const interval = setInterval(waitTimeAPICall, 60000);

	// 	return () => clearInterval(interval);
	// }, []);

	// const { data, error, isLoading } = useQuery({
	// 	queryKey: ["waitTimes"],
	// 	queryFn: () => apiFetch({ path: "wait/times" }),
	// 	refetchInterval: 60000,
	// });

	const { data, isLoading, error } = useSelect((select) => {
		const { getEntityRecords, isResolving, hasResolutionFailed } =
			select("core");

		return {
			data: getEntityRecords("root", "__unstableBase", { path: "/wait/times" }),
			isLoading: isResolving("getEntityRecords", [
				"root",
				"__unstableBase",
				{ path: "/wait/times" },
			]),
			error: hasResolutionFailed("getEntityRecords", [
				"root",
				"__unstableBase",
				{ path: "/wait/times" },
			]),
		};
	}, []);

	return (
		<>
			{isLoading && <div>loading</div>}
			{error && <div>error msg</div>}
			{!isLoading && !error && (
				<div>
					{data?.data?.length === 0 && <div>no wait times</div>}
					{data?.data?.length !== 0 && (
						<div>
							{data.map((time) => {
								<div>
									<div>{time.checkpoint}</div>
									<div>{time.terminal}</div>
									<div>{time.wait_time}</div>
									<div>{time.status}</div>
								</div>;
							})}
						</div>
					)}
				</div>
			)}
		</>
	);
}
