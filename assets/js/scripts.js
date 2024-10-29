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
                        <div  id="img${painting.title.replace(/\s/g, '')}">
                            <img src="assets/img/${painting.image}" class="img-fluid style="height: 50%; width: 50%" alt="${painting.title}">
                        </div>
                    </td>
                `;
                paintingList.appendChild(listItem);
            });
        })
        .catch(error => console.error("Error fetching paintings:", error));
}

// Function painting listing
document.addEventListener('DOMContentLoaded', function() {
    fetch('controllers/PaintingController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=getAllPaintings'
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#paintingsTable tbody');
        data.forEach(painting => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${painting.title}</td>
                <td>${painting.artist}</td>
                <td>${painting.year}</td>
                <td><img src="assets/img/${painting.image} class="img-flued"" alt="${painting.title} " ></td>
            `;
            tableBody.appendChild(row);
        });
    });
});

// Function Manage Painting 
function fetchPaintings() {
    $.ajax({
        url: 'includes/fetch_paintings.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const paintingList = $('#paintingList');
            paintingList.empty();
            data.forEach(painting => {
                paintingList.append(`
                    <tr>
                        <td>${painting.title}</td>
                        <td>${painting.artist}</td>
                        <td>${painting.year}</td>
                        <td><img src="assets/img/${painting.image}" alt="${painting.title}" width="100"></td>
                        <td>
                            <button class="btn btn-info" onclick="editPainting(${painting.id})">Edit</button>
                        </td>
                    </tr>
                `);
            });
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}

function addPainting() {
    const formData = new FormData();
    formData.append('action', 'insert');
    formData.append('title', $('#title').val());
    formData.append('artist', $('#artist').val());
    formData.append('year', $('#year').val());
    formData.append('image', $('#image')[0].files[0]);

    $.ajax({
        url: 'includes/manage_paintings.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.message);
            fetchPaintings();
            $('#paintingForm')[0].reset();
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}

function editPainting(id) {
    $.ajax({
        url: 'includes/fetch_paintings.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const painting = data.find(p => p.id === id);
            if (painting) {
                $('#paintingId').val(painting.id);
                $('#title').val(painting.title);
                $('#artist').val(painting.artist);
                $('#year').val(painting.year);
            }
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}

function updatePainting() {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', $('#paintingId').val());
    formData.append('title', $('#title').val());
    formData.append('artist', $('#artist').val());
    formData.append('year', $('#year').val());
    formData.append('image', $('#image')[0].files[0]);

    $.ajax({
        url: 'includes/manage_paintings.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.message);
            fetchPaintings();
            $('#paintingForm')[0].reset();
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}

function deletePainting() {
    const id = $('#paintingId').val();
    if (id) {
        $.ajax({
            url: 'includes/manage_paintings.php',
            method: 'POST',
            data: { action: 'delete', id: id },
            success: function(response) {
                alert(response.message);
                fetchPaintings();
                $('#paintingForm')[0].reset();
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    } else {
        alert('Please select a painting to delete.');
    }
}

$(document).ready(function() {
    fetchPaintings();
});

