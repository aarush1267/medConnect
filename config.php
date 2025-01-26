<?php

session_start();
$_SESSION['signUpName'] = $_POST['signUpName'];
$_SESSION['signUpEmail'] = $_POST['signUpEmail'];
$_SESSION['signUpPassword'] = $_POST['signUpPassword'];

// initialising the variables

$name = $_POST['signUpName'];
$email = $_POST['signUpEmail'];
$password = $_POST['signUpPassword'];

$errors = array();
$result;

// making the database connection

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Sorry, couldn't connect to the database");

// check database for existing email and prevent SQL injection

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($connection, $sql);

if(mysqli_num_rows($result) > 0) {
  echo "Email is already registered";
} else {
  $hashed_password = md5($password); // this will encrypt the password
  $sql = "INSERT INTO users (name, email, password, date_joined) VALUES ('$name', '$email', '$hashed_password', CURDATE())";

// register the user if no error

if(count($errors) == 0) {
  $sql = "INSERT INTO users (name, email, password, date_joined) VALUES ('$name', '$email', '$hashed_password', CURDATE())";
  $stmt = mysqli_stmt_init($connection);
}

if (mysqli_query($connection, $sql)) {
  $_SESSION["signUpBtn"] = "1";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($connection);
}
}


?>
