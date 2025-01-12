<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_title = $_POST['movie_title'];
    $user_id = $_POST['user_id'];
    $review = $_POST['review'];

    // Establish database connection
    $conn = new mysqli('localhost', 'root', '17111998', 'movies');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO reviews (title, user, review) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $movie_title, $user_id, $review);

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
