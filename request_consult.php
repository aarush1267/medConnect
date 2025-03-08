<?php
session_start();

// Database Connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";
$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Connection Failed");

// Ensure User is Logged In
if (!isset($_SESSION['signUpBtn'])) {
    echo "You must be logged in to request a consultation.";
    exit;
}

// Fetch Data from Form
$user_id = $_SESSION['id'];
$consultant_id = intval($_POST['consultant_id']);
$symptoms = mysqli_real_escape_string($connection, $_POST['symptoms']);
$details = mysqli_real_escape_string($connection, $_POST['details']);
$date = $_POST['date'];
$time = $_POST['time'];
$notes = isset($_POST['notes']) ? mysqli_real_escape_string($connection, $_POST['notes']) : null;
$status = 'pending';
$medical_docs = null;

// Handle Medical Document Upload
if (!empty($_FILES['medical_docs']['name'])) {
    $targetDir = "uploads/medical_docs/";
    $fileName = basename($_FILES["medical_docs"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Ensure directory exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Move file
    if (move_uploaded_file($_FILES["medical_docs"]["tmp_name"], $targetFilePath)) {
        $medical_docs = $targetFilePath;
    } else {
        echo "Error uploading medical document.";
        exit;
    }
}

// Insert into Database
$query = "INSERT INTO consultations (user_id, consultant_id, symptoms, details, date, time, notes, medical_docs, status)
          VALUES ('$user_id', '$consultant_id', '$symptoms', '$details', '$date', '$time', '$notes', '$medical_docs', '$status')";

if (mysqli_query($connection, $query)) {
    echo "Consultation request submitted successfully!";
} else {
    echo "Error: " . mysqli_error($connection);
}

// Close Connection
mysqli_close($connection);
?>
