<?php

session_start();


if (!array_key_exists("role", $_SESSION) || !($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user')) {

  echo '<script> alert("Please login to access this page."); window.location.href = "login.php"; </script>';

  exit;
}

$books_isbn = array();

$sucess = $error = "";
$count = "";

if (filter_input(INPUT_POST, 'add_to_cart')) {
  if (isset($_SESSION['shopping_cart'])) {

    $count = count($_SESSION['shopping_cart']);



    $books_isbn = array_column($_SESSION['shopping_cart'], 'ISBNC');
    $books_title = array_column($_SESSION['shopping_cart'], 'titles');

    if (!in_array(filter_input(INPUT_POST, 'isbn'), $books_isbn, true) || !in_array(filter_input(INPUT_POST, 'title'), $books_title, true)) {
      $_SESSION['shopping_cart'][$count] = array(

        'index' =>  filter_input(INPUT_POST, 'index'),
        'titles' => filter_input(INPUT_POST, 'title'),
        'ISBNC' => filter_input(INPUT_POST, 'isbn'),
        'Authors' => filter_input(INPUT_POST, 'authors'),
        'publisher' => filter_input(INPUT_POST, 'publisher'),
        'Publish date' => filter_input(INPUT_POST, 'date')
      );
    } else {
      $error = "The book is already in cart";
    }
  } else {


    $_SESSION['shopping_cart'][0] = array(
      'index' =>  filter_input(INPUT_POST, 'index'),
      'titles' => filter_input(INPUT_POST, 'title'),
      'ISBNC' => filter_input(INPUT_POST, 'isbn'),
      'Authors' => filter_input(INPUT_POST, 'authors'),
      'publisher' => filter_input(INPUT_POST, 'publisher'),
      'Publish date' => filter_input(INPUT_POST, 'date')
    );
  }
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
  <title>Library</title>
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
        </ul>

        <form action="cart.php" class="d-flex mx-2">
          <button class="btn btn-outline-dark" type="submit">
            <i class="bi-cart-fill me-1"></i>
            Checkout <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
              <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </svg>

          </button>
        </form>

        <div class="d-flex">
          <form action="" id="myForm" class="row gx-3 gy-2 align-items-center">
            <div class="col-md-3">
              <label class="visually-hidden" for="search">Name</label>
              <input type="text" class="form-control" id="search" style="width:300px !important;" name="search" placeholder="Search Books by Title, ISBN, Author">
            </div>
          </form>
          <div class=" col-auto mx-3  ">
            <button type="submit" class="btn btn-success" id="serachbtn">Search</button>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <header class="bg-dark py-5">
  <div class="container px-4 px-lg-5 my-5">
    <div class="row align-items-center">
      <div class="col-2">
        <img src="images/logo.png" alt="Logo" width="150">
      </div>
      <div class="col-10">
        <div class="text-center text-white">
          <h1 class="display-4 fw-bolder">Welcome to the library</h1>
        </div>
      </div>
    </div>
  </div>
</header>




  <div class="container px-4 px-lg-5 mt-5">

    <div id="output" class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">

      <!-- all results populate here -->

    </div>


    <div id="outputError">
      <h2 class="text-danger"> <?php echo $error; ?> </h2>
    </div>
  </div>


  <div class="text-center">
    <div class="container px-4 px-lg-5 mt-5">

      <div class="table-responsive">
        <table class="table">
          <tr class="font-weight-bold text-center">
            <th colspan="5">
              <h3> Books in Cart</h3>
            </th>
          </tr>
          <tr>
            <th width="50%">Title</th>
            <th width="25%">ISBN</th>
            <th width="25%">Authors</th>

          </tr>
          <?php
          if (!empty($_SESSION['shopping_cart'])) :

            foreach ($_SESSION['shopping_cart'] as $key => $book) :
          ?>
              <tr>
                <td><?php echo $book['titles'] ?></td>
                <td><?php echo $book['ISBNC'] ?></td>
                <td><?php echo $book['Authors'] ?></td>
              </tr>
            <?php
            endforeach;
            ?>

          <?php
          endif;
          ?>

        </table>
      </div>
    </div>
  </div>



  <script src="app.js"></script>

</body>

</html>