<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acme_gallery";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fetch search criteria from the JSON body
$data = json_decode(file_get_contents("php://input"), true);

$conditions = [];
$params = [];

// Prepare conditions based on search criteria
if (!empty($data['title'])) {
    $conditions[] = 'title LIKE ?';
    $params[] = '%' . $conn->real_escape_string($data['title']) . '%';
}

if (!empty($data['artist'])) {
    $conditions[] = 'artist LIKE ?';
    $params[] = '%' . $conn->real_escape_string($data['artist']) . '%';
}

if (!empty($data['year'])) {
    $conditions[] = 'year = ?';
    $params[] = (int)$data['year'];
}

// Build SQL query
$sql = "SELECT id, title, artist, year, image FROM paintings";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($sql);

// Bind parameters if conditions exist
if (!empty($params)) {
    // Create a string for the types of the parameters
    $types = str_repeat('s', count($params)); // Assuming all parameters are strings
    if (isset($data['year']) && !empty($data['year'])) {
        // If year is an integer, we need to bind it as 'i'
        $types = str_replace('s', 'i', $types); // Change string type to integer for the year
    }
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$paintings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $paintings[] = $row;
    }
}

echo json_encode($paintings);
$conn->close();

// Manage Paintings

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
 