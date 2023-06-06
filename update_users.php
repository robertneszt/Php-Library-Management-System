<?php


// Include config file

require_once "dbconnection.php";
session_start();

include('header.php');
include('footer.php');
// Define variables and initialize with empty values
$name = $email = $address = $dob = $phone = $role = "";
$name_err = $email_err = $address_err = $dob_err = $phone_err = $role_err = "";

if (!array_key_exists("role", $_SESSION) || $_SESSION['role'] != 'admin') {
    echo 'No admin privilege, please login as admin to access this page.<br>';
    echo '<a href="login.php">Login here!</a>';
} else {
       $user_id = (array_key_exists('user_id', $_GET)) ? $_GET['user_id'] : $_POST['user_id'];

    if (!empty($user_id) || is_numeric($user_id)) {
        $autofill_query =   "SELECT * FROM users WHERE user_id='$user_id'";
        $autofill_query_result = mysqli_query($conn, $autofill_query);
        $row = mysqli_fetch_array($autofill_query_result);
        $name = $row['name'];
        $email = $row['email'];
        $address = $row['address'];
        $phone = $row['phone'];
        $role = $row['role'];
        // $registration_date['registration_date'];
        $birth_date = $row['birth_date'];
    }


    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
        $input_dob = trim($_POST["birth_date"]);
        $dob_timestamp = strtotime($input_dob);
        $age = floor((time() - $dob_timestamp) / 31556926);
        if (empty($input_dob)) {
            $dob_err = "Please enter your date of birth.";
        } elseif (floor($age) < 12) {
            $dob_err = "You must be at least 12 years old to register.";
        } else {
            $dob = $input_dob;
        }


        // // Validate password
        // $input_password = trim($_POST["password"]);
        // if (empty($input_password)) {
        //     $password_err = "Please enter a password.";
        // } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?_&]{8,}$/", $input_password)) {
        //     $password_err = "Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?_&)";
        // } else {
        //     $password = $input_password;
        // }

        // // Validate confirm password
        // $input_confirm_password = trim($_POST["confirm_password"]);
        // if (empty($input_confirm_password)) {
        //     $confirm_password_err = "Please confirm password.";
        // } else {
        //     $confirm_password = $input_confirm_password;
        //     if (empty($password_err) && ($password != $confirm_password)) {
        //         $confirm_password_err = "Passwords do not match.";
        //     }
        // }

        // Validate phone
        $input_phone = trim($_POST["phone"]);
        if (empty($input_phone)) {
            $phone_err = "Please enter your phone number.";
        } elseif (!preg_match("/^\+?[0-9]{11}$/", $input_phone)) {
            $phone_err = "Phone number must start with a plus sign followed by 11 digits.";
        } else {
            $phone = $input_phone;
        }

        // Validate role
        $input_role = trim($_POST["role"]);
        if (empty($input_role)) {
            $role_err = "Please select a role.";
        } else {
            $role = $input_role;
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
                // $param_password = password_hash($password, PASSWORD_DEFAULT);
                $param_dob = $dob;
                $param_phone = $phone;
                $param_role = $role;
                $param_id = $user_id;
            }
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to user profile page
                echo "Succesfully updated User Information.";
                // header("location: user_dashboard.php");
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
        <title>Update User</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <header>
            <h1><a href="home.html">Library Management System</a></h1>
            <nav>
                <a href="logout.php">Log Out</a>
            </nav>
        </header>
        <div class="container">
            <main>

                <h2>Update User</h2>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php echo (array_key_exists('user_id', $_GET)) ? '?user_id=' . $_GET['user_id'] : ''; ?>" method="post">

                    <!-- <label>User ID:</label>
        <input type="number" name="user_id" value="<?php echo $user_id ?>"><br>
         -->
                   
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo $name; ?>">
                    <span class="error"><?php echo $name_err; ?></span>

                    <label>Email</label>
                    <input type="text" name="email" value="<?php echo $email; ?>">
                    <span class="error"><?php echo $email_err; ?></span>

                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo $address; ?>">
                    <span class="error"><?php echo $address_err; ?></span>

                    <label>Date of Birth</label>
                    <input type="date" name="birth_date" value="<?php echo $birth_date; ?>">
                    <span class="error"><?php echo $dob_err; ?></span>

                    <label>Phone</label>
                    <input type="tel" name="phone" value="<?php echo $phone; ?>">
                    <span class="error"><?php echo $phone_err; ?></span>

                    <label>Role</label>
                    <select name="role">
                        <option value="">Select Role</option>
                        <option value="user" <?php if ($role == 'user') {
                                                    echo ' selected';
                                                } ?>>User</option>
                        <option value="admin" <?php if ($role == 'admin') {
                                                    echo ' selected';
                                                } ?>>Admin</option>
                    </select>
                    <span class="error"><?php echo $role_err; ?></span>

                    <button type="submit" name="update">Update</button>
                </form>

                <br>
                <a href="manage_users.php">Back to Manage Users</a>
            </main>
        </div>
        <footer>
            Created by Syntax Squad @2023
        </footer>
    </body>

    </html>

<?php
}

?>