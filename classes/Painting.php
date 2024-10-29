<?php
// classes/Painting.php

class Painting {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($title, $artist, $year, $image) {
        $sql = "INSERT INTO paintings (title, artist, year, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $artist, $year, $image]);
    }

    public function read() {
        $sql = "SELECT * FROM paintings";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $artist, $year, $image) {
        $sql = "UPDATE paintings SET title = ?, artist = ?, year = ?, image = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $artist, $year, $image, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM paintings WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
