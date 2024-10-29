<?php
// Set JSON content type
header('Content-Type: application/json');

// Suppress errors for JSON compatibility
error_reporting(0);
ini_set('display_errors', 0);

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acme_gallery";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Determine action type
$action = $_POST['action'] ?? '';

if ($action == 'insert') {
    // Handle insert action
    $title = $_POST['title'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $year = $_POST['year'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    if ($title && $artist && $year && $image) {
        $targetDir = "assets/img/";
        $targetFile = $targetDir . basename($image);

        // Ensure file upload was successful
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO paintings (title, artist, year, image) VALUES ('$title', '$artist', '$year', '$image')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["success" => true, "message" => "Painting added successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
    }

} elseif ($action == 'update') {
    // Handle update action
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $year = $_POST['year'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    if ($id && $title && $artist && $year) {
        $updateImage = "";
        if ($image) {
            $targetDir = "assets/img/";
            $targetFile = $targetDir . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $updateImage = ", image='$image'";
            } else {
                echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
                exit();
            }
        }

        $sql = "UPDATE paintings SET title='$title', artist='$artist', year='$year' $updateImage WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Painting updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
    }

} elseif ($action == 'delete') {
    // Handle delete action
    $id = $_POST['id'] ?? '';

    if ($id) {
        $sql = "DELETE FROM paintings WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Painting deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Missing painting ID"]);
    }

} elseif ($action == 'fetch') {
    // Handle fetch action
    $sql = "SELECT * FROM paintings";
    $result = $conn->query($sql);
    $paintings = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $paintings[] = $row;
        }
    }
    echo json_encode(["success" => true, "data" => $paintings]);

} else {
    echo json_encode(["success" => false, "message" => "Invalid action"]);
}

// Close connection
$conn->close();
?>
