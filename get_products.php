<?php

header('Content-Type: text/html; charset=utf-8');
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

// Retrieve category_id from the request
$category_id = $_GET['category_id'];

// Fetch products based on category_id from the database, including the image path
$sql = "SELECT id, name, price, image FROM prod WHERE category_id = $category_id";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => $row['image'] // Assuming the image path is stored in the 'image' column
        );
    }
}

// Close the database connection
$conn->close();

// Return products as JSON response
header('Content-Type: application/json');
echo json_encode($products);
?>
