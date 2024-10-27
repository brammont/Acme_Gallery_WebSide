<?php
// classes/Painting.php
class Painting {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllPaintings() {
        $sql = "SELECT * FROM paintings";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function insertPainting($title, $artist, $year, $image) {
        $sql = "INSERT INTO paintings (title, artist, year, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssis", $title, $artist, $year, $image);
        return $stmt->execute();
    }

    public function updatePainting($id, $title, $artist, $year, $image) {
        $sql = "UPDATE paintings SET title=?, artist=?, year=?, image=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssisi", $title, $artist, $year, $image, $id);
        return $stmt->execute();
    }

    public function deletePainting($id) {
        $sql = "DELETE FROM paintings WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
