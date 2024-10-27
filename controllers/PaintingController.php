<?php
// controllers/PaintingController.php
include '../includes/db_connect.php';
include '../classes/Painting.php';

$painting = new Painting($conn);

$action = $_POST['action'];

if ($action == 'getAllPaintings') {
    $result = $painting->getAllPaintings();
    $paintings = array();
    while ($row = $result->fetch_assoc()) {
        $paintings[] = $row;
    }
    echo json_encode($paintings);
}

if ($action == 'insertPainting') {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $year = $_POST['year'];
    $image = $_POST['image'];
    $result = $painting->insertPainting($title, $artist, $year, $image);
    echo json_encode($result);
}

if ($action == 'updatePainting') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $year = $_POST['year'];
    $image = $_POST['image'];
    $result = $painting->updatePainting($id, $title, $artist, $year, $image);
    echo json_encode($result);
}

if ($action == 'deletePainting') {
    $id = $_POST['id'];
    $result = $painting->deletePainting($id);
    echo json_encode($result);
}
?>
