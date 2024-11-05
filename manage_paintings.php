<?php
// painting_management.php

// Configuración y manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acme_gallery";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesamiento del formulario
$message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form input values
        $title = $_POST['title'] ?? '';
        $artist = $_POST['artist'] ?? '';
        $year = $_POST['year'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $painting_id = $_POST['painting_id'] ?? ''; // Painting ID for update/delete operations

        // Check if the 'add' button was pressed and required fields are filled
        if (isset($_POST['add']) && $title && $artist && $year && $image) {
            // Directory to store the uploaded image
            $targetDir = "assets/img/";
            $targetFile = $targetDir . basename($image);

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Prepare and execute the INSERT query
                $sql = $conn->prepare("INSERT INTO paintings (title, artist, year, image) VALUES (?, ?, ?, ?)");
                $sql->bind_param("ssis", $title, $artist, $year, $image);

                // Check if the INSERT was successful
                if ($sql->execute()) {
                    $message = "Painting added successfully.";
                } else {
                    $message = "Database error: " . $conn->error;
                }
            } else {
                $message = "Error moving the uploaded file.";
            }
        }  elseif (isset($_POST['update'])) {
            // Logic to update painting
            if ($painting_id && $title && $artist && $year) {
                $sql = $conn->prepare("UPDATE paintings SET title=?, artist=?, year=? WHERE id=?");
                $sql->bind_param("ssii", $title, $artist, $year, $painting_id);

                if ($sql->execute()) {
                    $message = "Pintura actualizada correctamente.";
                } else {
                    $message = "Error en la base de datos: " . $conn->error;
                }
            } else {
                $message = "Por favor, completa todos los campos.";
            }
        } elseif (isset($_POST['delete'])) {
            // Logic to delete painting
            if ($painting_id && $title && $artist && $year ) {
                $sql = $conn->prepare("DELETE FROM paintings WHERE id=?");
                $sql->bind_param("i", $painting_id);

                if ($sql->execute()) {
                    $message = "Pintura eliminada correctamente.";
                } else {
                    $message = "Error en la base de datos: " . $conn->error;
                }
            } else {
                $message = "Selecciona una pintura para eliminar.";
            }
        }
    }

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painting Management - Acme Arts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/scripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .painting-card {
            display: flex;
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .painting-image {
            width: 150px;
            height: auto;
            margin-right: 15px;
        }
        .painting-details {
            flex: 1;
        }
        .painting-details h5 {
            margin: 0;
            font-weight: bold;
        }
    </style>

</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="Index.html">Acme Arts</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="painting_listing.html">Painting Listing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search_paintings.html">Search Paintings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_paintings.php">Painting Management</a>
                </li>
            </ul>
        </div>
</nav>
<main>
<div class="container">
        <h2>Painting Management</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form action="manage_paintings.php" method="post" enctype="multipart/form-data">
            <!-- Hidden field to store painting ID for update/delete -->
            <input type="hidden" id="painting_id" name="painting_id">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="artist">Artist</label>
                <input type="text" class="form-control" id="artist" name="artist" required>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" class="form-control" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <button type="submit" name="add" class="btn btn-success">Add Painting</button>
            <button type="submit" name="update" class="btn btn-success">Update Painting</button>
            <button type="submit" name="delete" class="btn btn-success">Delete Painting</button>
        </form>

        <div class="results mt-5">
            <h2 class="my-4">Lista de Pinturas</h2>
            <table class="table table-responsive-lg" id="paintingTable">
                <tbody id="paintingList2">
                </tbody>
            </table>
        </div>
    </div>
</main>
<footer class="bg-dark text-white text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Sudakaz IT Services</h5>
                <p>
                    Your trusted partner in IT services. We provide comprehensive solutions tailored to meet the needs of modern businesses.
                </p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Quick Links</h5>
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="index.html" class="text-white">Home</a>
                    </li>
                    <li>
                        <a href="contact.html" class="text-white">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Follow Us</h5>
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="https://www.facebook.com/" target="_blank" class="text-white">Facebook</a>
                    </li>
                    <li>
                        <a href="https://x.com/?lang=en" target="_blank" class="text-white">Twitter</a>
                    </li>
                    <li>
                        <a href="https://au.linkedin.com/" target="_blank" class="text-white">LinkedIn</a>
                    </li>
                    <li>
                        <a href="https://github.com/" target="_blank" class="text-white">GitHub</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3 bg-secondary">
        © 2024 Sudakaz IT Services. All rights reserved.
    </div>
</footer>
<script>
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
                <div class="painting-card">
                    <img src='assets/img/${painting.image}' alt='${painting.title}' class="painting-image">
                    <div class="painting-details">
                        <h5>${painting.title}</h5>
                        <p><strong>Finished:</strong> ${painting.year}</p>
                        <p><strong>Paint Media:</strong> ${painting.media}</p>
                        <p><strong>Artist Name:</strong> ${painting.artist}</p>
                        <p><strong>Style:</strong> ${painting.style}</p>
                        <button class="btn btn-success" onclick='editPainting("${painting.id}", "${painting.title}", "${painting.artist}", "${painting.year}")'>Edit</button>
                    </div>
                </div>`);
            });
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
}


// Function to edit painting
function editPainting(id, title, artist, year) {
    document.getElementById('painting_id').value = id;
    document.getElementById('title').value = title;
    document.getElementById('artist').value = artist;
    document.getElementById('year').value = year;
}

fetchPaintings(); // Fetch paintings on page load
</script>
</body>
</html>