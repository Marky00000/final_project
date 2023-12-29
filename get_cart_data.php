<?php
// Connect to your MySQL database (modify these settings accordingly)
define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Fetch data from cart_table
$sql = "SELECT * FROM cart_table";
$result = mysqli_query($conn, $sql);

$cartData = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cartData[] = $row;
        }
    } else {
        echo json_encode(['message' => 'Cart is empty']); // Return a message indicating an empty cart
    }
} else {
    echo json_encode(['error' => 'Query error: ' . mysqli_error($conn)]); // Return error message if query fails
}

mysqli_close($conn);

// Return the cart data as JSON if available
if (!empty($cartData)) {
    echo json_encode($cartData);
}
?>
