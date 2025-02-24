<?php
// Force display of errors for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Manually connect to the database
$servername = "localhost";
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

// Prepare query to fetch consultant details
$stmt = $conn->prepare("SELECT id, name, services, profile_pic, age, gender, hospital
                        FROM users
                        WHERE role = 'consultant'
                        AND (name LIKE ? OR services LIKE ?)");

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
    // Ensure a default profile picture if none exists
    $row['profile_pic'] = !empty($row['profile_pic']) ? $row['profile_pic'] : 'medconnect_images/blank_profile_pic.png';

    // Convert services to a readable format
    if (!empty($row['services'])) {
        $formatted_services = array_map(function ($service) {
            return ucwords(str_replace("_", " ", $service)); // Converts "mental_health_screen" → "Mental Health Screen"
        }, explode(", ", $row['services']));
        $row['services'] = implode(" • ", $formatted_services); // Join services with a bullet point
    } else {
        $row['services'] = "Not specified";
    }

    // Handle missing age, gender, or hospital values
    $row['age'] = !empty($row['age']) ? $row['age'] : "Unknown";
    $row['gender'] = !empty($row['gender']) ? $row['gender'] : "Unknown";
    $row['hospital'] = !empty($row['hospital']) ? $row['hospital'] : "Not listed";

    $consultants[] = $row;
}

// Ensure correct JSON format
header('Content-Type: application/json');
echo json_encode($consultants);
exit;
?>
