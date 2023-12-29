<?php
// Establish a connection to your database
define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the employee ID is set in the URL
if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];

    // Delete the employee from the database
    $sql_delete_employee = "DELETE FROM emp WHERE id = $delete_id";

    if ($conn->query($sql_delete_employee) === TRUE) {
        // Delete the associated image file
        $sql_fetch_image = "SELECT image FROM emp WHERE id = $delete_id";
        $result_image = $conn->query($sql_fetch_image);

        if ($result_image->num_rows > 0) {
            $row = $result_image->fetch_assoc();
            $image_path = "uploads/" . $row["image"];

            // Check if the file exists before attempting to delete it
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        echo "Employee deleted successfully";

    } else {
        echo "Error deleting employee: " . $conn->error;
    }
} else {
    echo "Employee ID not provided";
}

// Close the database connection
$conn->close();

echo '<script>window.location.href = document.referrer;</script>';

?>
