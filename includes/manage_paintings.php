<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include 'fetch_paintings.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acme_gallery";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$action = $_POST['action'] ?? '';

if ($action == 'insertPainnting') {
    $title = $_POST['title'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $year = $_POST['year'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    if ($title && $artist && $year && $image) {
        $targetDir = "assets/img/";
        $targetFile = $targetDir . basename($image);

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
} elseif ($action == 'updatePainting') {
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
} elseif ($action == 'deletePainting') {
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
} else {
    echo json_encode(["success" => false, "message" => "Invalid action"]);
}

$conn->close();
?>
