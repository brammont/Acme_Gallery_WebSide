<?php
// Connect to database
$conn = new PDO("mysql:host=localhost;dbname=acme_arts", 'root', '');

// Get search input and sort criteria from the form
$search_title = isset($_POST['search_title']) ? $_POST['search_title'] : '';
$sort_criteria = isset($_POST['sort_criteria']) ? $_POST['sort_criteria'] : 'title';

// Construct SQL query for searching and sorting
$query = "SELECT * FROM paintings WHERE title LIKE :search_title ORDER BY $sort_criteria ASC";
$stmt = $conn->prepare($query);

// Bind the search parameter
$search_param = '%' . $search_title . '%';
$stmt->bindParam(':search_title', $search_param);

// Execute the query
$stmt->execute();
$results = $stmt->fetchAll();

// Check if results are found
if ($results) {
    foreach ($results as $painting) {
        echo "<li class='list-group-item'>";
        echo "<strong>Title:</strong> {$painting['title']}<br>";
        echo "<strong>Artist:</strong> {$painting['artist']}<br>";
        echo "<strong>Year:</strong> {$painting['year']}<br>";
        echo "<strong>Style:</strong> {$painting['style']}<br>";
        echo "</li>";
    }
} else {
    echo "<li class='list-group-item'>No paintings found matching your search.</li>";
}
?>
