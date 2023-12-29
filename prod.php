<?php

define('DB_SERVER', "localhost");
define('DB_USERNAME', "u585303710_ejano");
define('DB_PASSWORD', "Pa@sword123f");
define('DB_DATABASE', "u585303710_ejano");

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category_options = '';

$sql_fetch_categories = "SELECT id, name FROM cat";
$result_categories = $conn->query($sql_fetch_categories);

if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $category_options .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
    }
}

// For product submission and displaying product data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_product'])) {
    // Insert product
    $prod_name = $_POST['prod_name'];
    $category_id = $_POST['category_id'];
    $prod_image = $_FILES['prod_image']['name'];
    $prod_price = $_POST['prod_price'];
    $target_dir_prod = "uploads/";
    $target_file_prod = $target_dir_prod . basename($_FILES["prod_image"]["name"]);

    $sql_product = "INSERT INTO prod (name, category_id, image, price) VALUES ('$prod_name', '$category_id', '$prod_image', '$prod_price')";

    if ($conn->query($sql_product) === TRUE) {
        move_uploaded_file($_FILES["prod_image"]["tmp_name"], $target_file_prod);
        echo "New product created successfully";
    } else {
        echo "Error: " . $sql_product . "<br>" . $conn->error;
    }
}

// Delete product if "Delete" link is clicked
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql_delete_product = "DELETE FROM prod WHERE id = $delete_id";

    if ($conn->query($sql_delete_product) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

// Retrieve and display product data
$sql_fetch_products = "SELECT * FROM prod";
$result_products = $conn->query($sql_fetch_products);

?>

<!DOCTYPE html>
<html>
<head>
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
        <h1>Products</h1>
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
    <!-- Product Submission Form -->
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Product Name: <input type="text" name="prod_name"><br>
        Category:
        <select name="category_id">
            <?php echo $category_options; ?>
        </select><br>
        Product Image: <input type="file" name="prod_image"><br>
        Product Price: <input type="text" name="prod_price"><br>
        <input type="submit" name="submit_product" value="Add Product">
    </form>

    <!-- Product Table -->
    <h2>Product Data</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php
        // Display product data
        if ($result_products->num_rows > 0) {
            while ($row = $result_products->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td><img src='uploads/" . $row["image"] . "' width='100'></td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "<td>";
                echo "<a href='edit_product.php?id=" . $row["id"] . "'><img src='edit.png' alt='Edit' style='filter: invert(50%) sepia(100%) saturate(10000%) hue-rotate(180deg); width: 20px; height: 20px;'></a>  ";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No products found</td></tr>";
        }
        ?>
    </table>
</body>


</html>

<?php
// Close the database connection
$conn->close();
?>
