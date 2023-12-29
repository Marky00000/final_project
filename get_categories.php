<?php
// Establish a connection to your database
define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from the database including images
$sql = "SELECT id, name, image FROM cat";
$result = $conn->query($sql);

$categories = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'image' => $row['image'] // Assuming the column name is 'image' for category images
        );
    }
}

// Close the database connection
$conn->close();

// Return categories as JSON response
header('Content-Type: application/json');
echo json_encode($categories);
?>
