<?php
session_start();
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u585303710_ejano');
define('DB_PASSWORD', 'Pa@sword123f');
define('DB_DATABASE', 'u585303710_ejano');

// Create connection
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = mysqli_real_escape_string($con, $_POST['username_or_email']);
    $password = $_POST['password'];

    // Check if it's an admin login attempt
    $adminLoginQuery = "SELECT id, name, code FROM emp WHERE name = ? LIMIT 1";
    $adminStmt = mysqli_prepare($con, $adminLoginQuery);

    if ($adminStmt) {
        mysqli_stmt_bind_param($adminStmt, 's', $usernameOrEmail);
        mysqli_stmt_execute($adminStmt);
        $adminResult = mysqli_stmt_get_result($adminStmt);

        if ($adminResult && mysqli_num_rows($adminResult) == 1) {
            $adminRow = mysqli_fetch_assoc($adminResult);

            // Verify password
            if ($password == $adminRow['code']) {
                // Valid credentials for admin, redirect to POS
                $_SESSION['admin_id'] = $adminRow['id'];
                header('Location: pos.html');
                exit();
            }
        } else {
            echo 'Invalid username or password';
        }

        mysqli_stmt_close($adminStmt);
    } else {
        echo 'Error in preparing statement: ' . mysqli_error($con);
    }

    // Check if it's a user login attempt
    $userLoginQuery = "SELECT id, email, password FROM user WHERE email = ? LIMIT 1";
    $userStmt = mysqli_prepare($con, $userLoginQuery);
    mysqli_stmt_bind_param($userStmt, 's', $usernameOrEmail);
    mysqli_stmt_execute($userStmt);
    $userResult = mysqli_stmt_get_result($userStmt);

    if ($userResult && mysqli_num_rows($userResult) == 1) {
        $userRow = mysqli_fetch_assoc($userResult);
        // Verify password
        if (password_verify($password, $userRow['password'])) {
            // Valid credentials for user, redirect to POS
            $_SESSION['user_id'] = $userRow['id'];
            header('Location: index.php');
            exit();
        }
    }

    echo 'Invalid username or password';
}

mysqli_close($con);
?>
