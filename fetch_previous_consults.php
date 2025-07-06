<?php

session_start();
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die("Connection Failed");

if (!isset($_SESSION['signUpBtn'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role']; // 'user' or 'consultant'

// Adjust query based on role
if ($role === 'user') {
    $query = "SELECT c.*, con.name AS consultant_name, con.profile_pic AS consultant_pic
              FROM consultations c
              JOIN users con ON c.consultant_id = con.id
              WHERE c.user_id = $user_id AND c.status = 'completed'
              ORDER BY c.date DESC, c.time DESC";
} else { // If consultant
    $query = "SELECT c.*, u.name AS user_name, u.profile_pic AS user_pic
              FROM consultations c
              JOIN users u ON c.user_id = u.id
              WHERE c.consultant_id = $user_id AND c.status = 'completed'
              ORDER BY c.date DESC, c.time DESC";
}

$result = mysqli_query($connection, $query);
$consultations = [];

while ($row = mysqli_fetch_assoc($result)) {
    $consultations[] = $row;
}

echo json_encode($consultations);
mysqli_close($connection);

?>
