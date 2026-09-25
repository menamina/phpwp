// The mapboxConfig variable is provided by wp_localize_script
const map = new mapboxgl.Map({
  accessToken: mapboxConfig.accessToken,
  container: "map",
  center: [-71.06776, 42.35816],
  zoom: 9,
});

let markers = [];

function addMarkers(locations) {
  // Clear existing markers
  markers.forEach((marker) => marker.remove());
  markers = [];

  // Add new markers
  locations.forEach((location) => {
    const marker = new mapboxgl.Marker()
      .setLngLat([location.lng, location.lat])
      .setPopup(new mapboxgl.Popup().setHTML(`<h3>${location.name}</h3>`))
      .addTo(map);
    markers.push(marker);
  });
}

async function fetchLocations() {
  try {
    const response = await fetch("/wp-json/mapbox/v1/locations");
    const locations = await response.json();
    addMarkers(locations);
    console.log("Markers updated:", locations.length);
  } catch (error) {
    console.error("Failed to fetch locations:", error);
  }
}

// Initial load
map.on("load", () => {
  fetchLocations();

  // Refresh every 10 seconds
  setInterval(fetchLocations, 10000);
});
