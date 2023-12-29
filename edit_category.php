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
$edit_category = null;

// Fetch the category data to be edited
$sql_fetch_category = "SELECT * FROM cat WHERE id = $edit_id";
$result_edit_category = $conn->query($sql_fetch_category);

if ($result_edit_category->num_rows > 0) {
    $edit_category = $result_edit_category->fetch_assoc();
} else {
    echo "Category not found";
    exit;
}

// Handling form submission for category update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $name = $_POST['name']; // Assuming the form has a field with the name 'name'

    // Handling image upload
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name']; // Assuming the form has a file input field with the name 'image'
        $target_dir = "uploads/"; // Directory to store uploaded images
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
    } else {
        // If no new image is uploaded, keep the existing image
        $image = $edit_category['image'];
    }

    // Update data in the database
    $sql_update_category = "UPDATE cat SET name='$name', image='$image' WHERE id=$edit_id";

    if ($conn->query($sql_update_category) === TRUE) {
        // If the data is updated successfully, move the uploaded image to the target directory
        if ($_FILES['image']['name']) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
        echo "Category updated successfully";
        header("Location: cat.php");
    } else {
        echo "Error updating category: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
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
        <h1>Edit Categories</h1>
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
    <!-- Category Update Form -->
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $edit_id; ?>">
        Category Name: <input type="text" name="name" value="<?php echo $edit_category['name']; ?>"><br>
        Category Image: <input type="file" name="image"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
