const titles = ["Flight", "Airline", "From", "To", "Scheduled", "Status"];

function loadFlight() {
  const flightBoard = document.getElementById("flight-board");

  const table = document.createElement("table");
  flightBoard.append(table);

  const tableHead = document.createElement("thead");
  table.append(tableHead);

  const tableRow = document.createElement("tr");
  tableHead.append(tableRow);

  const tableBody = document.createElement("tbody");
  tableBody.id = "bodyForFlights";
  table.append(tableBody);

  titles.forEach((title, index) => {
    const thisTitlesHead = document.createElement("th");

    thisTitlesHead.textContent = title;
    thisTitlesHead.id = `header-${index}`;
    tableRow.append(thisTitlesHead);
  });

  getFlightStats();
}

async function getFlightStats() {
  const response = await fetch("/wp-json/flight/status");
  const data = await response.json();

  const body4Flights = document.getElementById("bodyForFlights");
  body4Flights.innerHTML = "";

  const rows = data.data
    .map(
      (flight, index) => `
    <tr id="${index}">
      <td>${flight.flight.iata}</td>
      <td>${flight.airline.name}</td>
      <td>${flight.departure.iata}</td>
      <td>${flight.arrival.iata}</td>
      <td>${flight.departure.scheduled}</td>
      <td>${flight.flight_status}</td>
    </tr>
  `,
    )
    .join("");

  body4Flights.innerHTML = rows;
}

document.addEventListener("DOMContentLoaded", loadFlight);
