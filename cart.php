<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page

if (!array_key_exists("role", $_SESSION) || !($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user')) {
    echo '<script> alert("Please login to access this page."); window.location.href = "login.php"; </script>';
    exit;
}

// Include config file
require_once "dbconnection.php";
$error = "";
$sucess = $message = "";
$table_data = "";
if (filter_input(INPUT_GET, 'action') == 'delete') {

    foreach ($_SESSION['shopping_cart'] as $key => $book) {
        if ($book['ISBNC'] == filter_input(INPUT_GET, 'id')) {
            unset($_SESSION['shopping_cart'][$key]);
        }
    }
    $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'user') {
    $message = count($_SESSION['shopping_cart']);
    $bookCount = count($_SESSION['shopping_cart']);
    if ($bookCount <= 3) {

        $query2 = $query = "";
        foreach ($_SESSION['shopping_cart'] as $key => $book) {

            $book['titles'];
            $book['ISBNC'];
            $book['Authors'];
            $book['publisher'];
            $book['Publish date'];

            $table_data .= '
                   <tr>
                       <td>' . $book['titles'] . '</td>
                       <td>' . $book['ISBNC'] . '</td>
                       <td>' . $book['Authors'] . '</td>
                       <td>' . $book['publisher'] . '</td>
                       <td>' . $book['Publish date'] . '</td>
                   </tr>';

            $query2 = "SELECT `isbn` from loanhistory where isbn = '" . $book['ISBNC'] . "'";


            $result2 = mysqli_query($conn, $query2);
            $num_rows = mysqli_num_rows($result2);

            if ($num_rows > 0) {
                $error .= "Book '" . $book['titles'] . "' is alraedy loaned to another user, please choose another book \n";
            } else {
                $query = "INSERT INTO loanhistory (user_id, user_name, book_title, isbn, authors, publisher, publish_date) VALUES ('" . $_SESSION["id"] . "', '" . $_SESSION["name"] . "', '" . $book['titles'] . "', '" . $book['ISBNC'] . "', '" . $book['Authors'] . "', '" . $book['publisher'] . "', '" . $book['Publish date'] . "');";
                $queryBook = "INSERT INTO books(`title`, `author`, `publisher`, `date_of_publication`, `isbn`) VALUES ('" . $book['titles'] . "','" . $book['Authors'] . "','" . $book['publisher'] . "','" . $book['Publish date'] . "','" . $book['ISBNC'] . "')";
                if ($conn) {



                    $resultbook = mysqli_query($conn, $queryBook);
                    $result = mysqli_query($conn, $query);
                    if ($result) {

                        $sucess .= " Order submitted successfully for '" . $book['titles'] . "' \n";
                    } else {
                        $error = " failed to insert";
                    }
                } else {
                    $error = "Database connection failed";
                }
            }


            unset($_SESSION['shopping_cart']);
        }
    } else {
        $error = "Please remove a book form cart as maximum borrow limit is 3";
    }
}

// admin

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'admin' && !empty($_POST["manualId"])) {
    $message = count($_SESSION['shopping_cart']);
    $bookCount = count($_SESSION['shopping_cart']);
    if ($bookCount <= 3) {

        $manualId = "";
        $query2 = $query = "";
        foreach ($_SESSION['shopping_cart'] as $key => $book) {

            $book['titles'];
            $book['ISBNC'];
            $book['Authors'];
            $book['publisher'];
            $book['Publish date'];

            $table_data .= '
                       <tr>
                           <td>' . $book['titles'] . '</td>
                           <td>' . $book['ISBNC'] . '</td>
                           <td>' . $book['Authors'] . '</td>
                           <td>' . $book['publisher'] . '</td>
                           <td>' . $book['Publish date'] . '</td>
                       </tr>';

            $query2 = "SELECT `isbn` from loanhistory where isbn = '" . $book['ISBNC'] . "'";


            $result2 = mysqli_query($conn, $query2);
            $num_rows = mysqli_num_rows($result2);

            if ($num_rows > 0) {
                $error .= "Book '" . $book['titles'] . "' is alraedy loaned to another user, please choose another book \n";
            } else {

                $manualId = trim($_POST["manualId"]);

                $queryName = "SELECT name from users Where user_id= $manualId";

                $resultName = mysqli_query($conn, $queryName);
                if ($resultName) {
                    $row = mysqli_fetch_assoc($resultName);
                    $manualName = $row['name'];
                }

                $query = "INSERT INTO loanhistory (user_id, user_name, book_title, isbn, authors, publisher, publish_date) VALUES ('$manualId', '$manualName', '" . $book['titles'] . "', '" . $book['ISBNC'] . "', '" . $book['Authors'] . "', '" . $book['publisher'] . "', '" . $book['Publish date'] . "');";
                $queryBook = "INSERT INTO books(`title`, `author`, `publisher`, `date_of_publication`, `isbn`) VALUES ('" . $book['titles'] . "','" . $book['Authors'] . "','" . $book['publisher'] . "','" . $book['Publish date'] . "','" . $book['ISBNC'] . "')";
                if ($conn) {



                    $resultbook = mysqli_query($conn, $queryBook);
                    $result = mysqli_query($conn, $query);
                    if ($result) {

                        $sucess .= " Order submitted successfully for '" . $book['titles'] . "' \n";
                    } else {
                        $error = " failed to insert";
                    }
                } else {
                    $error = "Database connection failed";
                }
            }


            unset($_SESSION['shopping_cart']);
        }
    } else {
        $error = "Please remove a book form cart as maximum borrow limit is 3";
    }
} elseif ($_SESSION['role'] == 'admin' && empty($_POST["manualId"])) {
    $error = "Please enter user ID";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet" />
    <title>Library</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="user_dashboard.php">Logined <?php echo htmlspecialchars($_SESSION["role"]); ?> <?php echo htmlspecialchars($_SESSION["name"]); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="btn btn-danger mx-lg-3" href="logout.php">Logout</a>
                    </li>
                </ul>

                <div class="d-flex">
                    <form action="bookstore.php" id="" class="row gx-3 gy-2 align-items-center">
                        <div class=" col-auto">
                            <button type="submit" class="btn btn-success" id="serachbtn">Return to books</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </nav>

    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Welcome to the library</h1>

            </div>
        </div>
    </header>



    <div class="container px-4 px-lg-5 mt-5">

        <div class="table-responsive">

            <table class="table">
                <tr class="font-weight-bold text-center">
                    <th colspan="5">
                        <h3> Books in cart</h3>
                    </th>
                </tr>
                <tr>
                    <th width="30%">Title</th>
                    <th width="20%">ISBN</th>
                    <th width="15%">Authors</th>
                    <th width="15%">Publisher</th>
                    <th width="20%">Publish Date</th>
                    <th></th>
                </tr>
                <?php
                if (!empty($_SESSION['shopping_cart'])) :

                    foreach ($_SESSION['shopping_cart'] as $key => $book) :
                ?>
                        <tr>
                            <td><?php echo $book['titles'] ?></td>
                            <td><?php echo $book['ISBNC'] ?></td>
                            <td><?php echo $book['Authors'] ?></td>
                            <td><?php echo $book['publisher'] ?></td>
                            <td><?php echo $book['Publish date'] ?></td>
                            <td>
                                <a class="text-decoration-none" href="cart.php?action=delete&id=<?php echo $book['ISBNC'] ?>">
                                    <div class="btn-danger">Remove</div>
                                </a>
                            </td>

                        </tr>
                    <?php
                    endforeach;
                    ?>

                    <tr class="text-center">
                        <td colspan="5">
                            <?php
                            if (isset($_SESSION['shopping_cart'])) :
                                if (count($_SESSION['shopping_cart']) > 0 && ($_SESSION['role'] == 'user')) :
                            ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <input type="submit" name="" class="btn btn-primary btn-lg btn-block" value="CHECKOUT!">
                                    </form>
                            <?php
                                endif;
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="5">
                            <?php
                            if (isset($_SESSION['shopping_cart'])) :
                                if (count($_SESSION['shopping_cart']) > 0 && ($_SESSION['role'] == 'admin')) :
                            ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <lable for="manualId">Enter user id</lable>
                                        <input type="text" name="manualId" id="manualId">
                                        <input type="submit" name="" class="btn btn-primary btn-lg btn-block" value="CHECKOUT!">
                                    </form>
                            <?php
                                endif;
                            endif;
                            ?>
                        </td>
                    </tr>

                <?php
                endif;
                ?>

            </table>

        </div>
        <div id="cartError" class="text-justified">
            <h2 class="text-danger"> <?php echo nl2br($error); ?> </h2>

            <h2 class="text-success"> <?php echo nl2br($sucess); ?> </h2>

        </div>
    </div>
    <div>

    </div>

</body>

</html>