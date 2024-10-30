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
    $title = $_POST['title'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $year = $_POST['year'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    if ($title && $artist && $year && $image) {
        $targetDir = "assets/img/";
        $targetFile = $targetDir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $sql = $conn->prepare("INSERT INTO paintings (title, artist, year, image) VALUES (?, ?, ?, ?)");
            $sql->bind_param("ssis", $title, $artist, $year, $image);
            
            if ($sql->execute()) {
                $message = "Pintura añadida correctamente";
            } else {
                $message = "Error en la base de datos: " . $conn->error;
            }
        } else {
            $message = "Error al mover el archivo cargado.";
        }
    } else {
        $message = "Por favor, completa todos los campos.";
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
                    <a class="nav-link" href="search_and_sort.html">Search and Sort</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_paintings.php">Painting Management</a>
                </li>
            </ul>
        </div>
    </nav>
<main>
    <div class= "container">
        <h2>Gestión de Pinturas</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="form-group">
            <form action="manage_paintings.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="artist">Artista</label>
                    <input type="text" class="form-control" id="artist" name="artist" required>
                </div>
                <div class="form-group">
                    <label for="year">Año</label>
                    <input type="number" class="form-control" id="year" name="year" required>
                </div>
                <div class="form-group">
                    <label for="image">Imagen</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Pintura</button>
                <button type="Update" class="btn btn-primary">Update Pintura</button>
                <button type="delete" class="btn btn-primary">Delete Pintura</button>
            </form>
            <div class="results mt-5">
                <h2 class="my-4">Pictures List</h2>
                <table class="table table-responsive-lg" id="searchResultsTable">
                    <thead>
                        
                    <tbody id="paintingList">
                        <!-- Data will be dynamically injected here -->
                    </tbody>
                </table>
            </div>
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
</body>
</html>