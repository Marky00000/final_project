<?php
define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling employee form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_employee'])) {
    $employee_name = $_POST['employee_name'];
    $employee_code = $_POST['employee_code'];
    $employee_email = $_POST['employee_email'];
    $employee_image = $_FILES['employee_image']['name'];
    $target_dir_employee = "uploads/";
    $target_file_employee = $target_dir_employee . basename($_FILES["employee_image"]["name"]);

    $sql_employee = "INSERT INTO emp (name, code, email, image) VALUES ('$employee_name', '$employee_code', '$employee_email', '$employee_image')";

    if (move_uploaded_file($_FILES["employee_image"]["tmp_name"], $target_file_employee)) {
        if ($conn->query($sql_employee) === TRUE) {
            echo "New employee added successfully";
        } else {
            echo "Error: " . $sql_employee . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading file";
    }
}

$sql_fetch_employees = "SELECT * FROM emp";
$result_employees = $conn->query($sql_fetch_employees);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        /* Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
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

        /* Animation */
        .fade-in {
            animation: fadeInAnimation 1s ease-in-out forwards;
            opacity: 0;
        }

        @keyframes fadeInAnimation {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
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
    <!-- Employee Add Form -->
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Employee Name: <input type="text" name="employee_name"><br>
        Employee Code: <input type="text" name="employee_code"><br>
        Employee Email: <input type="text" name="employee_email"><br>
        Employee Image: <input type="file" name="employee_image"><br>
        <input type="submit" name="submit_employee" value="Add Employee">
    </form>

    <h2>Employee Data</h2>
    <!-- Employee Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Email</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result_employees->num_rows > 0) {
            while ($row = $result_employees->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["code"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td><img src='uploads/" . $row["image"] . "' width='100'></td>";
                echo "<td>";
                echo "<a href='edit_employee.php?id=" . $row["id"] . "'><img src='edit.png' alt='Edit' style='filter: invert(50%) sepia(100%) saturate(10000%) hue-rotate(180deg); width: 20px; height: 20px;'></a>  ";
    echo "<a href='delete_employee.php?id=" . $row["id"] . "'><img src='bin.png' alt='Delete' style='filter: invert(50%) sepia(100%) saturate(10000%) hue-rotate(0deg); width: 20px; height: 20px;'></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No employees found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
