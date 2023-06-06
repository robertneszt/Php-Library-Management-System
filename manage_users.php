<?php
// Include config file
require_once "dbconnection.php";
session_start();

include('header.php');


$name = $email = $password = $confirm_password = $address = $dob = $phone = $role = "";
$name_err = $email_err = $password_err = $confirm_password_err = $address_err = $dob_err = $phone_err = $role_err = "";

if (!array_key_exists("role", $_SESSION) || $_SESSION['role'] != 'admin') {
    echo '<script> alert("No admin privilege, please login as admin to access this page"); window.location.href = "login.php"; </script>';
} else {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete']) && array_key_exists("HTTP_REFERER", $_SERVER)) {

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
                echo '<script> alert("User add sucessfully"); window.location.href = "manage_users.php"; </script>';

                //  header("location: manage_users.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Update user
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);

    if (isset($_POST['update'])) {
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $registration_date = $_POST['registration_date'];

        $sql = "UPDATE users SET name='$name', email='$email', address='$address', phone='$phone', registration_date='$registration_date' WHERE user_id='$user_id'";

        if (mysqli_query($conn, $sql)) {
            echo "User updated successfully.";
        } else {
            echo "Error updating user: " . mysqli_error($conn);
        }
    }

    // Delete user
    if (isset($_POST['delete'])) {
        $user_id = $_POST['user_id'];

        $sql = "DELETE FROM users WHERE user_id='$user_id'";

        if (mysqli_query($conn, $sql)) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . mysqli_error($conn);
        }
    }

    $sqlBooks = "SELECT * FROM books";
    $resultBooks = mysqli_query($conn, $sqlBooks);



?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <link href="style.css" rel="stylesheet" />

        <title>Manage Users</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="update_users.php?user_id= <?php echo $_SESSION['id'] ?>  ">Welcome Admin <?php echo htmlspecialchars($_SESSION["name"]); ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                </div>
            </div>
        </nav>
        <div id="accordion" class="my-4">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Add user
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">

                        <h2>Create User</h2>
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

                            <label>Password</label>
                            <input type="password" name="password">
                            <span class="error"><?php echo $password_err; ?></span>

                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password">
                            <span class="error"><?php echo $confirm_password_err; ?></span>
                            <button type="submit" name="create">Create</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            User list
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">

                        <table border="1">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Registration Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                $user_id = $row['user_id'];
                                $name = $row['name'];
                                $email = $row['email'];
                                $address = $row['address'];
                                $phone = $row['phone'];
                                $registration_date = $row['registration_date'];
                                echo "<tr>";
                                echo "<td>$user_id</td>";
                                echo "<td>$name</td>";
                                echo "<td>$email</td>";
                                echo "<td>$address</td>";
                                echo "<td>$phone</td>";
                                echo "<td>$registration_date</td>";
                                echo "<td>";
                                echo "<form class=\"text-center\" method='POST' action=''>";
                                echo "<input type='hidden' name='user_id' value='$user_id'>";
                                echo "<a href='update_users.php?user_id=$user_id'><div class=\"btn btn-warning my-2\">Update</div></a>";
                                echo "<a href='view_users.php?user_id=$user_id'><div class=\"btn btn-info my-2\">View</div></a>";
                                echo "<button type='submit' name='delete'>Delete</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Manage Books
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">

                        <a href='bookstore.php'>
                            <div class="btn btn-warning my-2">Loan a book for user</div>
                        </a>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th> ID</th>
                                    <th>Title</th>
                                    <th>Authors</th>
                                    <th>Publisher</th>
                                    <th>Publish Date</th>
                                    <th>ISBN</th>
                                </tr>
                            </thead>
                            <?php
                            while ($rowBook = mysqli_fetch_assoc($resultBooks)) {
                                $book_id = $rowBook['book_id'];
                                $title = $rowBook['title'];
                                $author = $rowBook['author'];
                                $publisher = $rowBook['publisher'];
                                $date = $rowBook['date_of_publication'];
                                $isbn = $rowBook['isbn'];
                                echo "<tr>";
                                echo "<td>$book_id</td>";
                                echo "<td>$title</td>";
                                echo "<td>$author</td>";
                                echo "<td>$publisher</td>";
                                echo "<td>$date</td>";
                                echo "<td>$isbn</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>


                    </div>
                </div>
            </div>

    </body>
    <footer>Created by Syntax Squad @2023</footer>

    </html>

<?php
}

?>