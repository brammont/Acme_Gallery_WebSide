<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acme_gallery";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Prepare SQL query and check for errors
$sql = "SELECT title, artist, year, image FROM paintings";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(["error" => "SQL error: " . $conn->error]);
    exit();
}

$paintings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $paintings[] = $row;
    }
}

echo json_encode($paintings);  // Always output JSON, even if empty
$conn->close();
?>
