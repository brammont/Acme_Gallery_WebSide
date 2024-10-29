// JavaScript code to interact with PHP and handle CRUD operations
// Fetch and display paintings on page load
document.addEventListener("DOMContentLoaded", fetchPaintings);

// Function to fetch paintings from the server
function fetchPaintings() {
    fetch("includes/manage_paintings.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "action=fetch"
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            paintings = data.data;
            renderPaintings();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error fetching paintings:', error));
}

// Function to add a new painting
function submitPainting() {
    const formData = new FormData();
    formData.append("action", "insert");
    formData.append("title", document.getElementById("title").value);
    formData.append("artist", document.getElementById("artist").value);
    formData.append("year", document.getElementById("year").value);
    formData.append("image", document.getElementById("image").files[0]);

    fetch("includes/manage_paintings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchPaintings(); // Refresh paintings
            clearForm();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error adding painting:', error));
}

// Function to update an existing painting
function updatePainting() {
    const formData = new FormData();
    formData.append("action", "update");
    formData.append("id", document.getElementById("paintingId").value);
    formData.append("title", document.getElementById("title").value);
    formData.append("artist", document.getElementById("artist").value);
    formData.append("year", document.getElementById("year").value);
    
    const imageFile = document.getElementById("image").files[0];
    if (imageFile) {
        formData.append("image", imageFile);
    }

    fetch("includes/manage_paintings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchPaintings(); // Refresh paintings
            clearForm();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error updating painting:', error));
}

// Function to delete a painting
function deletePainting() {
    const id = document.getElementById("paintingId").value;
    if (!id) {
        alert("Please select a painting to delete.");
        return;
    }

    const formData = new FormData();
    formData.append("action", "delete");
    formData.append("id", id);

    fetch("includes/manage_paintings.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchPaintings(); // Refresh paintings
            clearForm();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error deleting painting:', error));
}

// Function to display the paintings in the table
function renderPaintings() {
    const paintingList = document.getElementById("paintingList");
    paintingList.innerHTML = ""; // Clear current display

    paintings.forEach(painting => {
        const row = document.createElement("tr");

        row.innerHTML = `

            <td>${painting.title}</td>
            <td>${painting.artist}</td>
            <td>${painting.year}</td>
            <td><img src="assets/img/${painting.image}" alt="${painting.title}" height="50"></td>
            <td>
                <button class="btn btn-sm btn-success" onclick="editPainting(${painting.id})">Edit</button>
            </td>
        `;
        paintingList.appendChild(row);
    });
}

// Function to populate form for editing
function editPainting(id) {
    const painting = paintings.find(p => p.id === id);
    if (painting) {
        document.getElementById("paintingId").value = painting.id;
        document.getElementById("title").value = painting.title;
        document.getElementById("artist").value = painting.artist;
        document.getElementById("year").value = painting.year;
        document.getElementById("image").value = ""; // Clear the file input
    }
}

// Function to clear the form fields
function clearForm() {
    document.getElementById("paintingId").value = "";
    document.getElementById("title").value = "";
    document.getElementById("artist").value = "";
    document.getElementById("year").value = "";
    document.getElementById("image").value = "";
}