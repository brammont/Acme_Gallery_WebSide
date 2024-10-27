document.addEventListener("DOMContentLoaded", function () {
    // Fetch paintings data from the correct endpoint
    fetch("includes/fetch_paintings.php")
        .then(response => response.text())
        .then(data => {
            console.log("Raw response:", data);  // Log the response for inspection

            if (!data || data.trim() === "") {
                console.error("Error: Received empty response from the server.");
                return;
            }

            try {
                const paintings = JSON.parse(data);  // Attempt to parse JSON if response is not empty
                const paintingList = document.getElementById("paintingList");

                if (!paintingList) {
                    console.error("Error: Element with ID 'paintingList' not found.");
                    return;
                }

                paintingList.innerHTML = "";  // Clear existing content

                // Sort and display paintings by year and artist
                paintings.sort((a, b) => a.year - b.year || a.artist.localeCompare(b.artist)).forEach(painting => {
                    let listItem = document.createElement("div");
                    listItem.innerHTML = `
                        <h3>${painting.title}</h3>
                        <p><strong>Artist:</strong> ${painting.artist}</p>
                        <p><strong>Year:</strong> ${painting.year}</p>
                        <img src="assets/img/${painting.image}" alt="${painting.title}" />
                    `;
                    paintingList.appendChild(listItem);
                });
            } catch (error) {
                console.error("JSON parse error:", error, "\nResponse data:", data);
            }
        })
        .catch(error => console.error("Error fetching paintings:", error));
});
// Function to sort the table based on selected option
function sortTable() {
    const sortBy = document.getElementById("sort").value;  // Get selected sort option
    fetch("includes/fetch_paintings.php")
        .then(response => response.json())
        .then(data => {
            // Sort data based on selected option
            if (sortBy === "artist") {
                data.sort((a, b) => a.artist.localeCompare(b.artist));
            } else if (sortBy === "year") {
                data.sort((a, b) => a.year - b.year);
            }

            // Render sorted data in the table
            const paintingList = document.getElementById("paintingList");
            paintingList.innerHTML = "";  // Clear existing entries

            data.forEach(painting => {
                let listItem = document.createElement("div");
                listItem.innerHTML = `
                    <h3>${painting.title}</h3>
                    <p><strong>Artist:</strong> ${painting.artist}</p>
                    <p><strong>Year:</strong> ${painting.year}</p>
                    <img src="assets/img/${painting.image}" alt="${painting.title}" />
                `;
                paintingList.appendChild(listItem);
            });
        })
        .catch(error => console.error("Error fetching or sorting paintings:", error));
}
function fetchAndDisplayData() {
    const searchTitle = document.getElementById("searchTitle").value.trim().toLowerCase();
    const sortBy = document.getElementById("sort").value;

    fetch("includes/fetch_paintings.php")
        .then(response => response.json())
        .then(data => {
            // Filter data by search title
            const filteredData = data.filter(painting => {
                return painting.title.toLowerCase().includes(searchTitle);
            });

            // Sort data
            if (sortBy === "artist") {
                filteredData.sort((a, b) => a.artist.localeCompare(b.artist));
            } else if (sortBy === "year") {
                filteredData.sort((a, b) => a.year - b.year);
            } else if (sortBy === "title") {
                filteredData.sort((a, b) => a.title.localeCompare(b.title));
            }

            // Display data in the table
            const paintingList = document.getElementById("paintingList");
            paintingList.innerHTML = "";  // Clear previous results

            filteredData.forEach(painting => {
                let listItem = document.createElement("tr");
                listItem.innerHTML = `
                    <td>${painting.title}</td>
                    <td>${painting.artist}</td>
                    <td>${painting.style || "N/A"}</td>
                    <td>${painting.year}</td>
                    <td>
                        <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#img${painting.title.replace(/\s/g, '')}" aria-expanded="false" aria-controls="img${painting.title.replace(/\s/g, '')}">
                            Toggle Image
                        </button>
                        <div class="collapse" id="img${painting.title.replace(/\s/g, '')}">
                            <img src="assets/img/${painting.image}" class="img-thumbnail" style="height: 50%; width: 50%" alt="${painting.title}">
                        </div>
                    </td>
                `;
                paintingList.appendChild(listItem);
            });
        })
        .catch(error => console.error("Error fetching paintings:", error));
}