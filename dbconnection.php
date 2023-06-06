<?php

define('DB_SERVER', "localhost:3307");
define('DB_USERNAME', "root");
define('DB_PASSWORD', "");
define('DB_NAME', 'library_management_system');
 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
