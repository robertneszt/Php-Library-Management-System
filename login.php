<?php

// Start session
session_start();
include('header.php');

// Include config file
require_once "dbconnection.php";

// Define variables and initialize with empty values
$email = $password = $role = "";
$email_err = $password_err = $role_err = "";

// Validate email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please select a role.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Check input errors before processing the form
    if (empty($email_err) && empty($password_err) && empty($role_err)) {
        // Prepare a select statement
        $sql = "SELECT user_id, name, email, role, password FROM users WHERE email = ? AND role = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_role);

            // Set parameters
            $param_email = $email;
            $param_role = $role;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email and role exist in the database
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $name, $email, $role, $hashed_password);

                    // Fetch values
                    mysqli_stmt_fetch($stmt);

                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        session_start();

                        // Set session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["name"] = $name;
                        $_SESSION["email"] = $email;
                        $_SESSION["role"] = $role;

                        // Redirect to appropriate dashboard based on user role
                        if ($role == "admin") {
                            header("location: manage_users.php");
                        } elseif ($role == "user") {
                            header("location: bookstore.php");
                        }
                    } else {
                        // Display an error message if password is invalid
                        $password_err = "Invalid email or password.";
                    }
                } else {
                    // Display an error message if email or role is invalid
                    $password_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Library Management System - Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
</head>

<body>

    <div class="container">
        <main>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1>Login</h1>
                <label for="email">Email address:</label>
                <input type="text" name="email" id="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $email_err; ?></span>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="<?php echo $email; ?>">
                <span class="error"><?php echo $password_err; ?></span>
                <label>Login as:</label>
                <select name="role">
                    <option value="">Select a Role</option>
                    <option value="admin" <?php if ($role == 'admin') {
                                                echo ' selected';
                                            } ?>>Admin</option>
                    <option value="user" <?php if ($role == 'user') {
                                                echo ' selected';
                                            } ?>>User</option>
                </select>
                <span class="error"><?php echo $role_err; ?></span>
                <input type="submit" value="Log In">
            </form>
        </main>
    </div>
    <footer>
    Created By Robert Neszt, Ruiqing Zhu, Nadezhda Mikhaylova, and Shubhparteek Singh @2023
    </footer>
</body>

</html>