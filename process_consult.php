<?php
session_start();
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";
$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Connection Failed");

if (!isset($_SESSION['signUpBtn'])) {
    echo "Unauthorized action!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['consultation_id'], $_POST['action'])) {
    $consultation_id = intval($_POST['consultation_id']);
    $action = $_POST['action'];

    if ($action === "accept") {
        $query = "UPDATE consultations SET status='accepted' WHERE id=$consultation_id";
    } elseif ($action === "reject" && isset($_POST['rejection_reason'])) {
        $rejection_reason = mysqli_real_escape_string($connection, $_POST['rejection_reason']);
        $query = "UPDATE consultations SET status='rejected', rejection_reason='$rejection_reason' WHERE id=$consultation_id";
    } else {
        echo "Invalid action!";
        exit;
    }

    if (mysqli_query($connection, $query)) {
        echo "Consultation $action successfully!";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>
