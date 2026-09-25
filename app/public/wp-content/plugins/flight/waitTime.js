async function loadWaitTimes() {
  const waitTimes = await waitTimesApi();

  if (waitTimes) {
    displayWaitTimes(waitTimes);
  }
}

async function waitTimesApi() {
  const response = await fetch("/wp-json/waitTime/status");
  const data = await response.json();
  return data;
}

function displayWaitTimes(times) {
  const waitDiv = document.getElementById("wait");

  const list = times
    .map(
      (airport) => `
    <div style="margin: 10px; padding: 10px; border: 1px solid #ccc;">
        <h3>${airport.airport} (${airport.iata})</h3>
        <p>${airport.terminal}</p>
        <p><strong>Wait Time: ${airport.wait_time_minutes} minutes</strong></p>
    </div>
  `
    )
    .join("");

  waitDiv.innerHTML = list;
}

document.addEventListener("DOMContentLoaded", loadWaitTimes);
