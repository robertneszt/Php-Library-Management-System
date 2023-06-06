<?php
// Check if user is logged in
session_start();

include('header.php');
require_once "dbconnection.php";
if (!array_key_exists("role", $_SESSION) || !($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user')) {
    echo 'Not logged in to an account! Please login to access this page.<br>';
    echo '<a href="login.php">Login here!</a>';
} else {

    // Get user's current info
    $user_id = $_SESSION["id"];

    // Include config file
    require_once "dbconnection.php";

    // Get user's current info
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="style.css" rel="stylesheet" />
        <title>User Account</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="user_dashboard.php">Logged in <?php echo htmlspecialchars($_SESSION["role"]); ?> <?php echo htmlspecialchars($_SESSION["name"]); ?></a>
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
            <h1>User info</h1>
            <form action="" method="">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $row['name']; ?> " disabled><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" disabled><br>

                <!-- <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>"><br> -->

                <!-- <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo $row['birth_date']; ?>" disabled><br> -->

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $row['phone']; ?>" disabled><br>


                <a class="text-decoration-none" href="user_manage_info.php">
                    <div class="btn btn-warning">Want to update info? click here </div>
                </a>

            </form>
        </div>

        <div class="text-center">
            <div class="container px-4 px-lg-5 mt-5">

                <div class="table-responsive">
                    <table class="table">
                        <tr class="font-weight-bold text-center">
                            <th colspan="5">
                                <h3> Loan History</h3>
                            </th>
                        </tr>
                        <tr>
                            <th width="50%">Title</th>
                            <th width="25%">ISBN</th>
                            <th width="25%">Authors</th>

                        </tr>
                        <?php


                        if ($conn) {
                            $sql2 = "SELECT * from loanhistory WHERE user_id = '" . $_SESSION["id"] . "' ";
                            $result2 = mysqli_query($conn, $sql2);
                            if ($result2) {
                                $x = 0;

                                while ($row2 = mysqli_fetch_assoc($result2)) {



                                    echo ("
                                                <tr>
                                                    <td>" . $row2["book_title"] . "</td>
                                                    <td>" . $row2["isbn"] . "</td>
                                                    <td>" . $row2["authors"] . "</td>
                                                    </tr>
                                                "
                                    );
                                }
                            } else {
                                echo "Database connection failed";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
<footer>Created By Robert Neszt, Ruiqing Zhu, Nadezhda Mikhaylova, and Shubhparteek Singh @2023 </footer>

    </body>

    </html>

<?php
}

?>