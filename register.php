<?php
define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$response = array(); // Initialize response array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO user (`email`, `password`) VALUES ('$email', '$password')";

    if (mysqli_query($con, $sql)) {
        // Registration successful
        $response['success'] = true;
        $response['message'] = "Registration successful!";
        header("Location: https://siaappdevfinal.brunswicksteel-admin.com/SECRET/EJANO/index.html?fbclid=IwAR0FTDpF7SZpNIUF1amoq8Q-DaYR32_Kwc7XXL9fDdFgDAFMDDmyXAUUros");
        exit(); // Ensure no other code runs after redirection
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

mysqli_close($con);

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
