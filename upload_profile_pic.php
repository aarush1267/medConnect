<?php

session_start();

// Database Connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Database connection failed");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_pic"])) {
    $user_email = $_POST["email"] ?? null;

    if (!$user_email) {
        echo json_encode(["success" => false, "error" => "Invalid email"]);
        exit;
    }

    // Check if user is a consultant or a regular user
    $stmt = $connection->prepare("SELECT role FROM users WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(["success" => false, "error" => "User not found"]);
        exit;
    }

    $user_role = $row['role']; // 'consultant' or 'user'

    // Set the correct filename prefix based on role
    $file_prefix = ($user_role === "consultant") ? "consultant_" : "user_";

    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file_ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
    $file_name = $file_prefix . md5($user_email . time()) . "." . $file_ext;
    $file_path = $uploadDir . $file_name;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $file_path)) {
        // Save new profile picture path in the database
        $stmt = $connection->prepare("UPDATE users SET profile_pic = ? WHERE email = ?");
        $stmt->bind_param("ss", $file_path, $user_email);
        $stmt->execute();

        echo json_encode(["success" => true, "file_path" => $file_path]);
    } else {
        echo json_encode(["success" => false, "error" => "Upload failed"]);
    }
}

?>
