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
                    let listItem = document.createElement("tr");
                    listItem.innerHTML = `
                    <tr>
                    <td>${painting.title}</td>
                     <td>${painting.artist}</td>
                     <td>${painting.year}</td>
                     <td><img src='assets/img/${painting.image}' alt='${painting.title}' width='50'></td>
                     </tr>`;
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
                let listItem = document.createElement("tr");
                listItem.innerHTML = `
                <tr>
                    <td>${painting.title}</td>
                     <td>${painting.artist}</td>
                     <td>${painting.year}</td>
                     <td><img src='assets/img/${painting.image}' alt='${painting.title}' width='50'></td>
                     </tr>`;
                paintingList.appendChild(listItem);
            });
        })
        .catch(error => console.error("Error fetching or sorting paintings:", error));
}
function fetchAndDisplayData() {
    const searchTitle = document.getElementById("searchTitle").value.trim();
    const searchArtist = document.getElementById("searchArtist").value.trim();
    const searchYear = document.getElementById("searchYear").value.trim();

    const searchCriteria = {
        title: searchTitle,
        artist: searchArtist,
        year: searchYear ? Number(searchYear) : null
    };

    fetch("includes/fetch_paintings.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(searchCriteria)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        const paintingList = document.getElementById("paintingList");
        paintingList.innerHTML = ""; // Clear existing list

        if (data.length === 0) {
            paintingList.innerHTML = `<tr><td colspan="4">No paintings found matching the search criteria.</td></tr>`;
        } else {
            data.forEach(painting => {
                let listItem = document.createElement("tr");
                listItem.innerHTML = `
                <tr>
                    <td>${painting.title}</td>
                     <td>${painting.artist}</td>
                     <td>${painting.year}</td>
                     <td><img src='assets/img/${painting.image}' alt='${painting.title}' width='50'></td>
                    </tr>`;
                paintingList.appendChild(listItem);
            });
        }
    })
    .catch(error => console.error("Error fetching paintings:", error));
}

// Function painting listing
document.addEventListener('DOMContentLoaded', function() {
    fetch('controllers/PaintingController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'getAllPaintings' }) // Use JSON.stringify for JSON format
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .finally(data => {
        const tableBody = document.querySelector('#paintingList tbody');
        data.paintings.forEach(painting => { // Ensure you access 'data.paintings' 
            let listItem = document.createElement("tr");
                    listItem.innerHTML = `
                    <tr>
                    <td>${painting.title}</td>
                     <td>${painting.artist}</td>
                     <td>${painting.year}</td>
                     <td><img src='assets/img/${painting.image}' alt='${painting.title}' width='50'></td>
                     </tr>`;
            tableBody.appendChild(listItem);
        });
    })
});

// Function Manage Painting 
function fetchPaintings() {
    $.ajax({
        url: 'includes/fetch_paintings.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const paintingList = $('#paintingList2');
            paintingList.empty();
            data.forEach(painting => {
                paintingList.append(`
            "<tr>
                "<td>${painting.title}</td>";
                 "<td>${painting.artist}</td>";
                 "<td>${painting.year}</td>";
                 "<td><img src='assets/img/${painting.image}' alt='${painting.title}' width='50'></td>";
                 "<td><button class='btn btn-info' onclick='editPainting({${painting.id}}, \"{${painting.title}}\", \"{${painting.artist}}\", {${painting.year}})'>Edit</button></td>";
            "</tr>`);
            });
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}


window.onload = () => {
    fetchPaintings();

}
