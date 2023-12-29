
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

        /* Form Styles */
        form {
            margin-top: 15px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 3px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 0 auto;
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
        <h1>Categories</h1>
    </header>
    <nav>
        <a href="cat.php">Category</a>
        <a href="prod.php">Products</a>
        <a href="emp.php">Employee</a>
        <a href="sales.php">Sales</a>
        <a href="index.html">Logout</a>
    </nav>

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

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $name = $_POST['name']; // Assuming the form has a field with the name 'name'
    // Handling image upload
    $image = $_FILES['image']['name']; // Assuming the form has a file input field with the name 'image'
    $target_dir = "uploads/"; // Directory to store uploaded images
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Insert data into the database
    $sql = "INSERT INTO cat (name, image) VALUES ('$name', '$image')";

    if ($conn->query($sql) === TRUE) {
        // If the data is inserted successfully, move the uploaded image to the target directory
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieving category data
$sql_fetch_categories = "SELECT * FROM cat";
$result_categories = $conn->query($sql_fetch_categories);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Management</title>
</head>
<body>

    <!-- Category Add Form -->
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Category Name: <input type="text" name="name"><br>
        Category Image: <input type="file" name="image"><br>
        <input type="submit" value="Submit">
    </form>

    <h2>Category Data</h2>
    <!-- Category Table -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result_categories->num_rows > 0) {
            while ($row = $result_categories->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td><img src='uploads/" . $row["image"] . "' width='100'></td>";
                echo "<td>";
                echo "<a href='edit_category.php?id=" . $row["id"] . "'><img src='edit.png' alt='Edit' style='filter: invert(50%) sepia(100%) saturate(10000%) hue-rotate(180deg); width: 20px; height: 20px;'></a>  ";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No categories found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>