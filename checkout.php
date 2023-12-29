<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $cartData = json_decode(file_get_contents('php://input'), true);

    // Connect to your MySQL database (modify these settings accordingly)
  define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Check connection
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute SQL statements to insert cart data
    $stmt = $conn->prepare("INSERT INTO cart_table (product_id, image, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('issi', $productId, $image, $price, $quantity);

    foreach ($cartData as $item) {
        $productId = $item['id'];
        $image = $item['image'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        $stmt->execute();
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Send a response back to the client
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Cart data processed successfully']);
} else {
    // If the request method is not POST, return an error
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>
