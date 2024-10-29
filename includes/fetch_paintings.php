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

$sql = "SELECT id, title, artist, year, image FROM paintings";
$result = $conn->query($sql);

$paintings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $paintings[] = $row;
    }
}

echo json_encode($paintings);
$conn->close();
?>
