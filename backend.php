<?php

session_start();

// Making Connection To The Database

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Sorry, couldn't connect to the database");

// Login System

if (isset($_POST['logInEmail']) && isset($_POST['logInPassword'])) {
  $logInEmail = $_POST['logInEmail'];
  $logInPassword = $_POST['logInPassword'];
  $logInHashedPwd = md5($logInPassword);

  $process = "SELECT * FROM users WHERE email='$logInEmail' AND password='$logInHashedPwd'";
  $res = mysqli_query($connection, $process);

  if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);

    $_SESSION['role'] = $row['role'];

    $_SESSION['signUpName'] = $row['name'];
    $_SESSION['signUpEmail'] = $row['email'];
    $_SESSION['user_age'] = $row['age'];
    $_SESSION['user_gender'] = $row['gender'];
    $_SESSION['user_nationality'] = $row['country'];
    $_SESSION['user_phone_number'] = $row['phone'];

    $_SESSION['signUpBtn'] = "1";
  } else {
    die("Incorrect Email or Password");
  }
}

?>
