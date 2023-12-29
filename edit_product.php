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
$edit_product = null;

// Fetch the product data to be edited
$sql_fetch_product = "SELECT * FROM prod WHERE id = $edit_id";
$result_edit_product = $conn->query($sql_fetch_product);

if ($result_edit_product->num_rows > 0) {
    $edit_product = $result_edit_product->fetch_assoc();
} else {
    echo "Product not found";
    exit;
}

// Fetch and display category options
$sql_fetch_categories = "SELECT id, name FROM cat";
$result_categories = $conn->query($sql_fetch_categories);
$category_options = '';

while ($row = $result_categories->fetch_assoc()) {
    $selected = ($row['id'] == $edit_product['category_id']) ? 'selected' : '';
    $category_options .= "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
}

// Handling form submission for product update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $prod_name = $_POST['prod_name'];
    $category_id = $_POST['category_id'];
    $prod_image = $_FILES['prod_image']['name'];
    $prod_price = $_POST['prod_price'];

    // Handling image upload
    if ($_FILES['prod_image']['name']) {
        $target_dir_prod = "uploads/";
        $target_file_prod = $target_dir_prod . basename($_FILES["prod_image"]["name"]);
    } else {
        // If no new image is uploaded, keep the existing image
        $prod_image = $edit_product['image'];
    }

    // Update data in the database
    $sql_update_product = "UPDATE prod SET name='$prod_name', category_id='$category_id', image='$prod_image', price='$prod_price' WHERE id=$edit_id";

    if ($conn->query($sql_update_product) === TRUE) {
        // If the data is updated successfully, move the uploaded image to the target directory
        if ($_FILES['prod_image']['name']) {
            move_uploaded_file($_FILES["prod_image"]["tmp_name"], $target_file_prod);
        }
        echo "Product updated successfully";
        header("Location: prod.php");

    } else {
        echo "Error updating product: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

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
        input[type="file"],
        select {
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

            form {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Product</h1>
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

    <!-- Product Update Form -->
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $edit_id; ?>">
        Product Name: <input type="text" name="prod_name" value="<?php echo $edit_product['name']; ?>"><br>
        Category:
        <select name="category_id">
            <?php echo $category_options; ?>
        </select><br>
        Product Image: <input type="file" name="prod_image"><br>
        Product Price: <input type="text" name="prod_price" value="<?php echo $edit_product['price']; ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
