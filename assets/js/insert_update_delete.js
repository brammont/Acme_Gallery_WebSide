const apiUrl = 'includes/manage_paintings.php'; // Set the API endpoint for CRUD operations

// Function to load paintings from the database
function loadPaintings() {
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ action: 'fetch' })
    })
    .then(response => response.text())
    .then(text => {
        console.log("Raw response from loadPaintings:", text);
        let data;
        try {
            data = JSON.parse(text); // Attempt to parse JSON
        } catch (error) {
            console.error("Parsing error in loadPaintings:", error);
            return;
        }
        if (data.success) {
            displayPaintings(data.data);
        } else {
            console.error("Error fetching paintings:", data.message);
        }
    })
    .catch(error => console.error("Error loading paintings:", error));
}

// Function to display paintings in the table
function displayPaintings(paintings) {
    const paintingList = document.getElementById('paintingList');
    paintingList.innerHTML = '';

    paintings.forEach(painting => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${painting.title}</td>
            <td>${painting.artist}</td>
            <td>${painting.year}</td>
            <td>${painting.price}</td>
            <td>
                <button onclick="editPainting(${painting.id})">Edit</button>
                <button onclick="deletePainting(${painting.id})">Delete</button>
            </td>
        `;
        paintingList.appendChild(row);
    });
}

// Helper function to collect data from the form and call addPainting
function submitPainting() {
    const title = document.getElementById('title').value;
    const artist = document.getElementById('artist').value;
    const year = document.getElementById('year').value;
    const image = document.getElementById('image').files[0]; // Get file input

    if (!title || !artist || !year) {
        console.error("All fields are required.");
        return;
    }

    // Create a FormData object and append all fields including the file
    const paintingData = new FormData();
    paintingData.append('action', 'insert');
    paintingData.append('title', title);
    paintingData.append('artist', artist);
    paintingData.append('year', year);
    if (image) {
        paintingData.append('image', image);
    }

    // Pass FormData to addPainting
    addPainting(paintingData);
}

function addPainting(paintingData) {
    fetch(apiUrl, {
        method: 'POST',
        body: paintingData
    })
    .then(response => response.text())
    .then(text => {
        console.log("Raw response from addPainting:", text); // Log the response to see what was returned

        // Attempt to parse JSON
        let data;
        try {
            data = JSON.parse(text);
        } catch (error) {
            console.error("Parsing error in addPainting:", error);
            console.error("Server response was:", text); // Log what the response actually was
            return;
        }

        if (data.success) {
            loadPaintings(); // Refresh painting list
        } else {
            console.error("Error inserting painting:", data.message);
        }
    })
    .catch(error => console.error("Error in addPainting:", error));
}


