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

// Initialize variables
$edit_id = $_GET['id'];
$edit_employee = null;

// Fetch the employee data to be edited
$sql_fetch_employee = "SELECT * FROM emp WHERE id = $edit_id";
$result_edit_employee = $conn->query($sql_fetch_employee);

if ($result_edit_employee->num_rows > 0) {
    $edit_employee = $result_edit_employee->fetch_assoc();
} else {
    echo "Employee not found";
    exit;
}

// Handling form submission for employee update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $employee_name = $_POST['employee_name'];
    $employee_code = $_POST['employee_code'];
    $employee_email = $_POST['employee_email'];
    $employee_image = $_FILES['employee_image']['name'];

    // Handling image upload
    if ($_FILES['employee_image']['name']) {
        $target_dir_employee = "uploads/";
        $target_file_employee = $target_dir_employee . basename($_FILES["employee_image"]["name"]);
    } else {
        // If no new image is uploaded, keep the existing image
        $employee_image = $edit_employee['image'];
    }

    // Update data in the database
    $sql_update_employee = "UPDATE emp SET name='$employee_name', code='$employee_code', email='$employee_email', image='$employee_image' WHERE id=$edit_id";

    if ($conn->query($sql_update_employee) === TRUE) {
        // If the data is updated successfully, move the uploaded image to the target directory
        if ($_FILES['employee_image']['name']) {
            move_uploaded_file($_FILES["employee_image"]["tmp_name"], $target_file_employee);
        }
        echo "Employee updated successfully";
        header("Location: emp.php");

    } else {
        echo "Error updating employee: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>

    <style>
        /* Basic CSS styling for layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px 0;
        }

        nav {
            background-color: #444;
            text-align: center;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            padding: 15px 20px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #555;
        }

        section {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Responsive design for smaller screens */
        @media screen and (max-width: 600px) {
            nav a {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }

        /* Form Styles */
        form {
            margin-top: 15px;
            background-color: #ffffff;
            padding: 20px;
            /* Increased padding for better visualization */
            border-radius: 3px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            /* Adjust the width as needed */
            margin: 0 auto;
            /* Centering the form horizontally */
        }

        input[type="text"],
        input[type="file"] {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
            width: 100%;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Employee</h1>
    </header>
    <nav>
        <a href="cat.php">Category</a>
        <a href="prod.php">Products</a>
        <a href="emp.php">Employee</a>
        <a href="sales.php">Sales</a>
        <a href="index.html">Logout</a>
    </nav>
</head>
<body>

    <!-- Employee Update Form -->
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $edit_id; ?>">
        Employee Name: <input type="text" name="employee_name" value="<?php echo $edit_employee['name']; ?>"><br>
        Employee Code: <input type="text" name="employee_code" value="<?php echo $edit_employee['code']; ?>"><br>
        Employee Email: <input type="text" name="employee_email" value="<?php echo $edit_employee['email']; ?>"><br>
        Employee Image: <input type="file" name="employee_image"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
