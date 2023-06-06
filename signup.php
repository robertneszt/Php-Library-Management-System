<?php

// Include config file
require_once "dbconnection.php";

include('header.php');
include('footer.php');

// Define variables and initialize with empty values
$name = $email = $password = $confirm_password = $address = $dob = $phone = $role = "";
$name_err = $email_err = $password_err = $confirm_password_err = $address_err = $dob_err = $phone_err = $role_err = "";

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
    $email_query = "SELECT * FROM users WHERE email='$input_email'";
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

    // Validate password
    $input_password = trim($_POST["password"]);
    if (empty($input_password)) {
        $password_err = "Please enter a password.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?_&]{8,}$/", $input_password)) {
        $password_err = "Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?_&)";
    } else {
        $password = $input_password;
    }

    // Validate confirm password
    $input_confirm_password = trim($_POST["confirm_password"]);
    if (empty($input_confirm_password)) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = $input_confirm_password;
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
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

    // Validate phone
    $input_phone = trim($_POST["phone"]);
    if (empty($input_phone)) {
        $phone_err = "Please enter your phone number.";
    } elseif (!preg_match("/^\+?[0-9]{11}$/", $input_phone)) {
        $phone_err = "Phone number must start with a plus sign followed by 11 digits.";
    } else {
        $phone = $input_phone;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($address_err) && empty($dob_err) && empty($phone_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password, address, birth_date, phone, role, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            //Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_name, $param_email, $param_password, $param_address, $param_dob, $param_phone, $param_role, $param_date);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_address = $address;
            $param_dob = $dob;
            $param_phone = $phone;
            $param_role = "user";
            $param_date = date("Y-m-d");
        }

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to login page
            echo '<script> alert("User Signed up, please login to explore"); window.location.href = "login.php"; </script>';
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <main>
            <h2>Sign Up</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                <input type="date" name="dob" value="<?php echo $dob; ?>">
                <span class="error"><?php echo $dob_err; ?></span>

                <label>Phone</label>
                <input type="tel" name="phone" value="<?php echo $phone; ?>">
                <span class="error"><?php echo $phone_err; ?></span>

                <label>Password</label>
                <input type="password" name="password">
                <span class="error"><?php echo $password_err; ?></span>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
                <span class="error"><?php echo $confirm_password_err; ?></span>

                <input type="submit" value="Sign me up">
            </form>
        </main>
    </div>
    <footer>
        Created by Syntax Squad @2023
    </footer>
</body>

</html>