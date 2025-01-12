<?php
// Database connection details
$servername = "localhost"; // Your MySQL server address
$username = "root"; // Your MySQL username
$password = "17111998"; // Your MySQL password
$dbname = "movies"; // Your database name

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search query variable
$searchQuery = "";

// Step 1: Check if the form is submitted
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search_query']; // Get the user input from the form

    // Step 2: Query to fetch the required movie details
    $query = "
        SELECT 
            m.title AS Movie_Title,
            s.starname AS Actor_Name,
            sa.role AS Actor_Role
        FROM 
            movies m
        JOIN 
            starsin sa ON m.title = sa.title
        JOIN 
            stardetails s ON sa.starname = s.starname
        WHERE
            LOWER(m.title) LIKE LOWER(?) -- Case-insensitive search for partial title
        ORDER BY 
            m.title ASC, s.starname ASC;
    ";

    // Prepare and bind the query
    if ($stmt = $conn->prepare($query)) {
        $searchQueryLike = "%$searchQuery%";  // Apply wildcard for search
        $stmt->bind_param("s", $searchQueryLike);  // Bind parameters for movie title

        // Execute the query
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($movie_title, $actor_name, $actor_role);

        // Step 3: Check if results are found
        $result_found = false;

        echo "<h2>Search Results for '$searchQuery'</h2>";
        echo "<table>
                <tr>
                    <th>Movie Title</th>
                    <th>Star Name</th>
                    <th>Role</th>
                </tr>";

        // Display each result row
        while ($stmt->fetch()) {
            $result_found = true;  // If any results are found, set this flag to true
            echo "<tr>
                    <td>$movie_title</td>
                    <td>$actor_name</td>
                    <td>$actor_role</td>
                  </tr>";
        }

        echo "</table>";

        // If no results found
        if (!$result_found) {
            echo "<p>No results found for '$searchQuery'.</p>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // If the query fails, show an error message
        echo "Error preparing the statement: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
