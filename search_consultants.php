<?php
// Force display of errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Manually connect to the database
$servername = "localhost";  // Change if needed
$username = "root";
$password = "root";
$database = "medconnect";

$conn = new mysqli($servername, $username, $password, $database);

// Stop if database connection fails
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check if query is received
if (!isset($_POST['query']) || empty($_POST['query'])) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "No search query received"]);
    exit;
}

$search = "%{$_POST['query']}%"; // Wildcard for partial matching

$stmt = $conn->prepare("SELECT id, name, services FROM users WHERE role = 'consultant' AND (name LIKE ? OR services LIKE ?)");
if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $search, $search);
if (!$stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Query execution failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$consultants = [];

while ($row = $result->fetch_assoc()) {
    $consultants[] = $row;
}

// Ensure correct JSON format
header('Content-Type: application/json');
echo json_encode($consultants);
exit;
?>
