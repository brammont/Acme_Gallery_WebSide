<?php
header('Content-Type: application/json');
include 'db_connect.php';
include '../classes/Painting.php';

$db = new Database();
$painting = new Painting($db->connect());

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['title'])) {
    $title = $data['title'];
    $result = $painting->searchByTitle($title);

    if ($result) {
        echo json_encode(['success' => true, 'painting' => $result]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Painting not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Title not provided']);
}
?>
