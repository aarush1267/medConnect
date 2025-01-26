<?php

session_start();

// Making Connection To The Database
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Sorry, couldn't connect to the database");

if (isset($_POST['role'])) {
  // Retrieve the selected role from the query parameter
  $selectedRole = $_POST['role'];
  $roleEmail = $_SESSION['signUpEmail'];

  // Update the role in the database
  $update = "UPDATE users SET role = '$selectedRole' WHERE email = '$roleEmail'";
  if (mysqli_query($connection, $update)) {
    // Store the selected role in session
    $_SESSION['role'] = $_POST['role'];

    // Redirect the user to the appropriate interface based on the role
    if ($selectedRole === 'user') {
      header("Location: user_index.php");
      exit();
    } elseif ($selectedRole === 'consultant') {
      header("Location: consultant_index.php");
      exit();
    }
  } else {
    echo "Error updating role: " . mysqli_error($connection);
  }
} else {
  // If the role is not selected, redirect the user to the role selection page
  header("Location: role.php");
  exit();
}

?>
