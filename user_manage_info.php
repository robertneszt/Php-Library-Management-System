<?php

// Include config file
require_once "dbconnection.php";



session_start();

include('header.php');
include('footer.php');

if (!array_key_exists("role", $_SESSION) || !($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user')) {
    echo 'Not logged in to an account! Please login to access this page.<br>';
    echo '<a href="login.php">Login here!</a>';
} else {
    // Processing form data when form is submitted

    // Get user's current info
    $user_id = $_SESSION["id"];



    // Get user's current info
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Define variables and initialize with empty values
        $name = $email = $password = $password1 = $role = $confirm_password = $address = $dob = $phone = "";
        $name_err = $email_err = $password_err = $roll_err = $confirm_password_err = $address_err = $dob_err = $phone_err = "";

        // Validate name
        $input_name = trim($_POST["name"]);
        if (empty($input_name)) {
            $name_err = "Please enter your name.";
        } elseif (!preg_match("/^[a-zA-Z' ]*$/", $input_name)) {
            $name_err = "Name can only contain letters and white space.";
        } else {
            $name = $input_name;
        }


        // Validate email
        $input_email = trim($_POST["email"]);
        $email_query = "SELECT * FROM users WHERE email='$input_email' AND user_id!='$user_id'";
        $email_query_result = mysqli_query($conn, $email_query);
        if (empty($input_email)) {
            $email_err = "Please enter your email address.";
        } elseif (mysqli_num_rows($email_query_result) > 0) {
            $email_err = "Email is already taken.";
        } elseif (!preg_match("/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/", $input_email)) {
            $email_err = "Invalid email format. Email should contain at least 1 letter before and after the @ sign, followed by a dot, and at least 2 letters are required after the dot.";
        } else {
            $email = $input_email;
        }

        // Validate address
        $input_address = trim($_POST["address"]);
        if (empty($input_address)) {
            $address_err = "Please enter your address.";
        } elseif (!preg_match("/^\d+/", $input_address)) {
            $address_err = "Address must start with digits.";
        } else {
            $address = $input_address;
        }

        // Validate DOB
        $input_dob = trim($_POST["dob"]);
        $dob_timestamp = strtotime($input_dob);
        $age = floor((time() - $dob_timestamp) / 31556926);
        if (empty($input_dob)) {
            $dob_err = "Please enter your date of birth.";
        } elseif (floor($age) < 12) {
            $dob_err = "You must be at least 12 years old to register.";
        } else {
            $dob = $input_dob;
        }

        // Validate password
        $input_password = trim($_POST["password"]);
        if (empty($input_password)) {
            $password = $row['password'];
            //$password_err = "Please enter a password.";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?_&]{8,}$/", $input_password)) {
            $password_err = "Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?_&)";
        } else {
            $password1 = $input_password;
        }

        // Validate confirm password
        $input_confirm_password = trim($_POST["confirm_password"]);
        if (empty($input_confirm_password)) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = $input_confirm_password;
            if (empty($password_err) && ($password1 != $confirm_password)) {
                $confirm_password_err = "Passwords do not match.";
            }
        }
        // Validate phone
        $input_phone = trim($_POST["phone"]);
        if (empty($input_phone)) {
            $phone_err = "Please enter your phone number.";
        } elseif (!preg_match("/^\+?[0-9]{11}$/", $input_phone)) {
            $phone_err = "Phone number must start with a plus sign followed by 11 digits.";
        } else {
            $phone = $input_phone;
        }



        // Check input errors before updating in database
        if (empty($name_err) && empty($email_err) && empty($address_err) && empty($password_err) && empty($confirm_password_err)  && empty($dob_err) && empty($phone_err) && empty($role_err)) {

            // Prepare an update statement
            $sql = "UPDATE users SET `name`=?, email=?, `password`=?, `address`=?, birth_date=?, phone=? WHERE user_id=?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                //Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssssi", $param_name, $param_email, $param_password, $param_address, $param_dob, $param_phone, $param_id);

                // Set parameters
                $param_name = $name;
                $param_email = $email;
                $param_address = $address;
                $param_password = password_hash($password1, PASSWORD_DEFAULT);
                $param_dob = $dob;
                $param_phone = $phone;
                $param_role = $role;
                $param_id = $user_id;
            }
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to user profile page
                echo "Succesfully updated User Information.";
                header("location: user_dashboard.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }




?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="style.css" rel="stylesheet" />
        <title>Update user profile</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="user_dashboard.php">Logedin as <?php echo htmlspecialchars($_SESSION["name"]); ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="btn btn-danger mx-lg-3" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-info mx-lg-3" href="bookstore.php">Bookstore</a>
                        </li>
                    </ul>


                </div>
            </div>
        </nav>


        <div class="container my-4">
            <h1>Update user info</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>"><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>"><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo $row['birth_date']; ?>"><br>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $row['phone']; ?>"><br>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password"><br>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password"><br>

                <input type="submit" class="btn-primary" value="Update">
            </form>
        </div>



        <footer>
            Created by Syntax Squad @2023
        </footer>
    </body>

    </html>

<?php
}

?>