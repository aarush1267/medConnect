<?php
session_start();

// DB Connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";
$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die("Connection Failed");

// Auth Check
if (!isset($_SESSION['signUpBtn'])) {
    header("Location:login.php");
    exit;
}
if ($_SESSION['role'] !== 'user') {
    header("Location:consultant_index.php");
    exit;
}

// Consultation ID
$consultation_id = isset($_GET['consultation_id']) ? intval($_GET['consultation_id']) : 0;
if ($consultation_id === 0) {
    echo "Invalid consultation.";
    exit;
}

// Fetch consultant info for this consultation
$query = "SELECT u.id, u.name, u.age, u.gender, u.country, u.phone, u.profile_pic
          FROM consultations c
          JOIN users u ON c.consultant_id = u.id
          WHERE c.id = $consultation_id AND c.user_id = {$_SESSION['id']}";

$result = mysqli_query($connection, $query);
$consultant = mysqli_fetch_assoc($result);

if (!$consultant) {
    echo "Consultation not found.";
    exit;
}

$profilePic = !empty($consultant['profile_pic']) ? $consultant['profile_pic'] : 'medconnect_images/blank_profile_pic.png';

$user_id = $_SESSION['id'];

// Fetch the consultation data
$stmt = $connection->prepare("SELECT * FROM consultations WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $consultation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$consultation = $result->fetch_assoc();

// Chat System PHP

if (isset($_GET['fetchMessages']) && isset($_GET['consultation_id'])) {
  $consultation_id = intval($_GET['consultation_id']);
  $user_id = $_SESSION['id'];

  // Fetch messages with sender profile pic
  $query = "SELECT cc.*, u.profile_pic
            FROM consultation_chat cc
            JOIN users u ON cc.sender_id = u.id
            WHERE cc.consultation_id = ?
            ORDER BY cc.sent_at ASC";

  $stmt = $connection->prepare($query);
  $stmt->bind_param("i", $consultation_id);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($msg = $result->fetch_assoc()) {
    $isSender = $msg['sender_id'] == $user_id;
    $alignment = $isSender ? 'flex-end' : 'flex-start';
    $bubble = $isSender ? 'sent' : 'received';
    $pic = !empty($msg['profile_pic']) ? $msg['profile_pic'] : 'medconnect_images/blank_profile_pic.png';

    echo "<div style='display: flex; justify-content: $alignment; gap: 10px; align-items: flex-end;'>
            " . (!$isSender ? "<img src='$pic' alt='Profile' style='width: 35px; height: 35px; border-radius: 50%; object-fit: cover;'>" : "") . "
            <div class='chat-message $bubble'>
              <p>" . htmlspecialchars($msg['message'], ENT_QUOTES) . "</p>
              <span class='timestamp'>" . date("Y-m-d H:i", strtotime($msg['sent_at'])) . "</span>
            </div>
            " . ($isSender ? "<img src='$pic' alt='Profile' style='width: 35px; height: 35px; border-radius: 50%; object-fit: cover;'>" : "") . "
          </div>";
  }
  exit;
}

// Handle new message POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
  $msg = $_POST['message'];
  $sender_id = intval($_POST['sender_id']);
  $receiver_id = intval($_POST['receiver_id']);
  $consult_id = intval($_POST['consultation_id']);

  $stmt = $connection->prepare("INSERT INTO consultation_chat (consultation_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iiis", $consult_id, $sender_id, $receiver_id, $msg);
  $stmt->execute();

  // header("Location: user_window.php?consultation_id=$consult_id&section=chat");
  exit;
}

// Prescription System PHP

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_prescriptions_pdf'])) {
    require('fpdf.php');

    $consultation_id = intval($_GET['consultation_id']);
    $query = "SELECT * FROM consultation_prescriptions WHERE consultation_id = ? ORDER BY issued_at DESC";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $consultation_id);
    $stmt->execute();
    $prescriptions_result = $stmt->get_result();

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'MedConnect - Prescription',0,1,'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',12);

    if ($prescriptions_result->num_rows > 0) {
        while ($prescription = $prescriptions_result->fetch_assoc()) {
            $pdf->MultiCell(0,8,"Issue: " . $prescription['description']);
            $pdf->MultiCell(0,8,"Medication: " . $prescription['medication']);
            $pdf->MultiCell(0,8,"Dosage: " . $prescription['dosage']);
            if (!empty($prescription['instructions'])) {
                $pdf->MultiCell(0,8,"Instructions: " . $prescription['instructions']);
            }
            if (!empty($prescription['tests'])) {
                $pdf->MultiCell(0,8,"Tests: " . $prescription['tests']);
            }
            $pdf->MultiCell(0,8,"Issued: " . date("F j, Y, g:i a", strtotime($prescription['issued_at'])));
            $pdf->Ln(5);
            $pdf->Cell(0,0,'','T');
            $pdf->Ln(5);
        }
    } else {
        $pdf->Cell(0,10,'No prescriptions found for this consultation.',0,1,'C');
    }

    $pdf->Output('D', 'MedConnect_Prescriptions.pdf');
    exit;
}

// Review System PHP

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $consultation_id = intval($_GET['consultation_id']);
    $consultant_id = $consultant['id'];
    $user_id = $_SESSION['id'];
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    if ($rating < 1 || $rating > 5) {
        echo "<script>alert('Please select a star rating before submitting your review.'); window.history.back();</script>";
        exit;
    }

    $review_title = mysqli_real_escape_string($connection, $_POST['review_title']);
    $review_body = mysqli_real_escape_string($connection, $_POST['review_body']);

    $check_stmt = $connection->prepare("SELECT id FROM consultation_reviews WHERE consultation_id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $consultation_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $existing_review = $check_result->fetch_assoc();
        $stmt = $connection->prepare("UPDATE consultation_reviews SET rating = ?, review_title = ?, review_body = ? WHERE id = ?");
        $stmt->bind_param("issi", $rating, $review_title, $review_body, $existing_review['id']);
    } else {
        $stmt = $connection->prepare("INSERT INTO consultation_reviews (consultation_id, consultant_id, user_id, rating, review_title, review_body) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiss", $consultation_id, $consultant_id, $user_id, $rating, $review_title, $review_body);
    }
    $stmt->execute();
    header("Location: user_window.php?consultation_id=$consultation_id&section=reviews");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review_id'])) {
    $review_id = intval($_POST['delete_review_id']);
    $stmt = $connection->prepare("DELETE FROM consultation_reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    header("Location: user_window.php?consultation_id=$consultation_id&section=reviews");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Window | MedConnect</title>
  <link rel="stylesheet" href="styles.css">
  <script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
</head>
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Lora&display=swap');

body {
  background-color: #f8d4a4;
  font-family: Lora;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

a {
  text-decoration: none;
}

li {
  list-style: none;
}

.navbar {
  display: flex;
  align-items: center;
  justify-content: space-around;
  padding: 20px;
  margin-top: 50px;
}

.nav-links h3 {
  color: #704a1b;
}

.head {
  font-size: 32px;
  color: #614124;
  margin-top: -20px;
  margin-left: 50px;
  cursor: pointer;
}

.footer-head {
  font-size: 40px;
  color: white;
}

.menu {
  display: flex;
  gap: 1em;
  font-size: 18px;
  margin-left: 280px;
}

.menu li {
  padding: 5px 12px;
  cursor: pointer;
}

.profile-btn {
  border-radius: 5px;
  box-shadow: 0 1px 1px black;
  padding: 10px 10px;
  margin-top: -10px;
  background-color: #60a159;
}

.profile-btn:active {
  box-shadow: none;
}

@media screen and (max-width: 1201px) {
  .navbar {
    flex-direction: column;
    align-items: center;
    padding: 10px;
  }

  .navbar h1 {
    margin-top: 10px;
    font-size: 30px;
  }

  .menu {
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 30px;
    margin-left: 10px;
  }

  .menu li {
    padding: 10px 20px;
  }
}

.profile-img-container {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f3f3f3; /* Placeholder background */
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid black;
}

.profile-banner {
    width: 100%;
    height: 220px;
    background-color: #5FA159;
    position: relative;
}

/* Center the Consultant Header in the Middle */
.consultant-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 50px; /* Space between profile pic & info */
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background: #E3D2C3;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    position: relative;
    margin-top: -220px;
}

/* Ensure Profile Picture Stays Centered */
.profile-img-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 1em;
}

.profile-info h1 {
  font-size: 40px;
}

.profile-info h1:hover {
  text-decoration: underline;
  cursor: pointer;
}

/* Consultant Name */
.consultant-name {
    font-size: 26px;
    font-weight: bold;
    color: black;
    text-align: left; /* Ensures it stays left-aligned */
}

/* Subtext (Age, Gender, Country, Phone) */
.profile-subtext {
    font-size: 17px;
    color: black;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Icons */
.profile-subtext i {
    color: #5FA159;
}

.footer-item {
  color: white;
  display: flex;
  flex-direction: column;
  gap: 2em;
}

.footer {
  margin-top: 40px;
  display: flex;
  gap: 10em;
  justify-content: center;
  background-color: #302c2c;
  padding: 50px;
}

@media screen and (max-width: 800px) {
  .footer {
    flex-direction: column;
    gap: 3em;
  }
}

.consultation-sidebar {
  width: 570px;
  background-color: #e3d2c3;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  font-family: 'Lora', serif;
  z-index: 10;
  margin: 0 auto; /* Center horizontally */
  display: flex;
  flex-direction: column;
  align-items: center; /* Center all children horizontally */
}

.sidebar-title {
  font-size: 22px;
  font-weight: bold;
  color: #5a3e2b;
  margin-bottom: 20px;
  text-align: center;
}

.sidebar-details p {
  margin-bottom: 12px;
  font-size: 16px;
  color: #5a3e2b;
  line-height: 1.5;
}

.sidebar-details a {
  color: #5FA159;
  text-decoration: underline;
  font-weight: bold;
}

.sidebar-details a:hover {
  color: #4e8a45;
}

@media screen and (max-width: 900px) {
  .consultation-sidebar {
    position: relative;
    width: 100%;
    left: 0;
    top: 0;
    margin-bottom: 30px;
  }
}

.consult-nav {
  display: flex;
  justify-content: center;
  margin-top: 50px;
}

.consult-tabs-container {
  background-color: #f3e5d3;
  border-radius: 10px;
  padding: 12px 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
  font-family: 'Lora', serif;
}

.consult-tabs {
  display: flex;
  gap: 40px;
  justify-content: center;
}

.consult-tab {
  font-size: 18px;
  font-family: 'Lora', serif;
  color: #5a3e2b;
  cursor: pointer;
  padding: 10px 20px;
  border-radius: 8px;
  transition: 0.3s ease;
}

.consult-tab:hover {
  background-color: #d2bca9;
}

.consult-tab.active {
  background-color: #5fa159;
  color: white;
  font-weight: bold;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

 .consult-window-section {
   margin-top: 50px;
 }

 .chat-box {
  max-width: 600px;
  margin: 0 auto;
  background-color: #f3e5d3;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  font-family: 'Lora', serif;
}

.chat-messages {
  max-height: 300px;
  overflow-y: auto;
  margin-bottom: 20px;
  padding-right: 10px;
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.chat-message {
  max-width: 60%;
  padding: 8px 12px;
  border-radius: 10px;
  position: relative;
  font-size: 14px;
  line-height: 1.4;
}

.chat-message.sent {
  align-self: flex-end;
  background-color: #5fa159;
  color: white;
}

.chat-message.received {
  align-self: flex-start;
  background-color: #ffffff;
  color: #333;
  border: 1px solid #ddd;
}

.timestamp {
  font-size: 12px;
  color: black;
  margin-top: 3px;
  font-family: 'Lora', serif;
}

.chat-form {
  display: flex;
  gap: 10px;
}

.chat-form input[type="text"] {
  flex: 1;
  padding: 12px;
  font-size: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-family: 'Lora', serif;
  outline: none;
}

.chat-form button {
  background-color: #5fa159;
  color: white;
  padding: 12px 20px;
  border: none;
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Lora', serif;
}

.chat-form button:hover {
  background-color: #4e8a45;
}

.prescription-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 30px;
}

.prescription-item {
  width: 550px;
  margin: 0 auto;
  background: linear-gradient(135deg, #fdf6ee 0%, #f8e8d1 100%);
  border-left: 8px solid #60a159;
  border-radius: 12px;
  padding: 20px 24px;
  box-shadow: 0 6px 12px rgba(0,0,0,0.08);
  font-family: 'Lora', serif;
  color: #4e3c29;
  position: relative;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.prescription-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 18px rgba(0,0,0,0.12);
}

.prescription-item p {
  margin: 10px 0;
  line-height: 1.5;
}

.prescription-item strong {
  color: #3a2e20;
}

.issued-date {
  font-size: 13px;
  color: #7c6651;
  margin-top: 12px;
  font-style: italic;
}

.prescription-title {
  display: block;
  text-align: center;
  color: #5a3e2b;
  margin-bottom: 25px;
  font-size: 26px;
  font-weight: bold;
}

.no-prescriptions {
  text-align: center;
  color: #7c6651;
  margin-top: 20px;
  font-style: italic;
}

.download-pdf-btn {
    background-color: #60a159;
    color: white;
    padding: 10px 18px;
    font-size: 15px;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s ease;
    margin: 30px auto 10px;
    display: block;
    font-family: 'Lora', serif;
}

.download-pdf-btn:hover {
    background-color: #4e8a45;
}

.review-container {
  display: flex;
  gap: 20px;
  max-width: 1100px;
  margin: 0 auto;
  padding: 20px;
}

.review-form-container, .review-display-container {
  flex: 1;
  background: #fdf6ee;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  height: fit-content;
}

.review-form h2, .review-display-container h2 {
  text-align: center;
  color: #5a3e2b;
  margin-bottom: 15px;
}

.review-form label {
  display: block;
  margin-top: 12px;
  font-weight: bold;
  color: #5a3e2b;
}

.review-form input[type="text"],
.review-form textarea,
.review-form select {
  width: 100%;
  padding: 12px;
  margin-top: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-family: 'Lora', serif;
  background: #fffdf9;
}

.review-form button {
  display: block;
  margin: 20px auto 0;
  background-color: #60a159;
  color: white;
  padding: 12px 20px;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.review-form button:hover {
  background-color: #4e8a45;
}

.review-card {
  background: linear-gradient(135deg, #fdf6ee, #f8e8d1);
  border-left: 6px solid #60a159;
  border-radius: 10px;
  padding: 15px 18px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.review-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.08);
}

.review-card h3 {
  margin: 10px 0;
  color: #4e3c29;
}

.review-card p {
  margin: 0 0 8px 0;
  color: #5a3e2b;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.review-stars {
  color: #f7c948;
  font-size: 18px;
}

.review-date {
  font-size: 13px;
  color: #7c6651;
  font-style: italic;
}

.delete-review-btn {
  background-color: #d9534f;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 13px;
  cursor: pointer;
  transition: background 0.2s ease;
  margin-top: 10px;
  font-family: Lora;
  box-shadow: 0 1px 1px black;
}

.edit-review-btn {
  background-color: #5FA159;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 13px;
  cursor: pointer;
  transition: background 0.2s ease;
  margin-top: 10px;
  font-family: Lora;
  box-shadow: 0 1px 1px black;
}

.delete-review-btn:active,
.edit-review-btn:active {
  box-shadow: none;
}

.review-action-buttons {
  display: flex;
  justify-content: center;
  gap: 1em;
}

.review-form-container {
  background: #fdf6ee;
  padding: 30px;
  border-radius: 14px;
  box-shadow: 0 6px 16px rgba(0,0,0,0.06);
  max-width: 500px;
  margin: 0 auto;
  font-family: 'Lora', serif;
}

.review-form-container h2 {
  text-align: center;
  color: #5a3e2b;
  margin-bottom: 20px;
  font-size: 22px;
}

.review-form label {
  display: block;
  margin-top: 16px;
  font-weight: bold;
  color: #5a3e2b;
}

.review-form input[type="text"],
.review-form textarea {
  width: 100%;
  padding: 12px;
  margin-top: 8px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-family: 'Lora', serif;
  background: #fffdf9;
  transition: border 0.2s ease;
  resize: none;
}

.review-form input[type="text"]:focus,
.review-form textarea:focus {
  border: 1px solid #614124;
  outline: none;
}

.rating-block,
.recommend-toggle {
  margin-top: 16px;
}

.rating-options,
.recommend-options {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 8px;
}

.rating-options label,
.recommend-options label {
  background: #f8e8d1;
  padding: 8px 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.2s ease;
  font-size: 14px;
}

.rating-options label:hover,
.recommend-options label:hover {
  background: #e5d4bb;
}

.review-form button {
  font-family: Lora;
  box-shadow: 0 1px 1px black;
  display: block;
  width: 100%;
  margin-top: 24px;
  background-color: #60a159;
  color: white;
  padding: 14px;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  transition: background 0.2s ease;
}

.review-form button:hover {
  background-color: #4e8a45;
}

.review-form button:active {
  box-shadow: none;
}

.star-rating {
  display: flex;
  flex-direction: row;
  gap: 8px;
  font-size: 30px;
  cursor: pointer;
  margin-top: 10px;
  justify-content: center;
}

.star {
  color: #ccc;
  transition: color 0.2s ease;
}

.star.hover,
.star.selected {
  color: #f7c948;
}

.recommend-options {
  justify-content: center;
}

.star-display {
    display: flex;
    gap: 5px;
    justify-content: center;
    font-size: 28px;
}

.star-display .star {
    color: #ccc; /* unfilled star color */
}

.star-display .star.filled {
    color: #f7c948; /* filled star color */
}

.review-form-highlight {
    border: 1px solid #614124 !important;
}

</style>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <h1 onclick="location.href='user_index.php'" class="head">MedConnect</h1>
    <ul class="nav-links">
      <div class="menu">
        <li><h3 onclick="location.href='user_consult.php'">Consult</h3></li>
        <li><h3>Resources</h3></li>
        <li><h3>About</h3></li>
        <li><h3>Records</h3></li>
        <li><h3 onclick="location.href='user_profile.php'" class="profile-btn" style="color: white;">Your Profile</h3></li>
      </div>
    </ul>
  </nav>

  <!-- Profile Banner -->
  <div class="profile-banner"></div>

  <!-- Header Info -->
  <div class="consultant-header">
    <div class="profile-img-container">
      <img src="<?php echo htmlspecialchars($profilePic); ?>" class="profile-img" alt="Consultant Picture">
    </div>
    <div class="profile-info">
      <h1 onclick="location.href='view_consultant.php?id=<?php echo $consultant['id']; ?>'" class="consultant-name"><?php echo htmlspecialchars($consultant['name']); ?></h1>
      <p class="profile-subtext">
        <i class="fas fa-user"></i> <?php echo $consultant['age'] ?? 'Not Listed'; ?> •
        <i class="fas fa-venus-mars"></i> <?php echo htmlspecialchars($consultant['gender'] ?? 'Not Listed'); ?> •
        <i class="fas fa-globe"></i> <?php echo htmlspecialchars($consultant['country'] ?? 'Not Listed'); ?>
      </p>
      <p class="profile-subtext">
        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($consultant['phone'] ?? 'Not Listed'); ?>
      </p>
    </div>
  </div>

  <!-- Toggle Navigation Tabs -->
  <div class="consult-nav">
    <div class="consult-tabs-container">
      <div class="consult-tabs">
        <span class="consult-tab active" onclick="toggleWindowSection('details')">Details</span>
        <span class="consult-tab" onclick="toggleWindowSection('chat')">Chat</span>
        <span class="consult-tab" onclick="toggleWindowSection('prescription')">Prescription</span>
        <span class="consult-tab" onclick="toggleWindowSection('reviews')">Reviews</span>
        <span class="consult-tab" onclick="toggleWindowSection('meeting')">Meeting</span>
      </div>
    </div>
  </div>

  <!-- Toggle Sections -->
  <div id="detailsSection" class="consult-window-section" style="display: block;">
    <div class="consultation-sidebar">
      <h2 class="sidebar-title">Consultation Details</h2>
      <div class="sidebar-details">
        <p><strong>Symptoms:</strong> <?= htmlspecialchars($consultation['symptoms']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($consultation['date']) ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars($consultation['time']) ?></p>
        <p><strong>Details:</strong> <?= htmlspecialchars($consultation['details']) ?></p>
        <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($consultation['status'])) ?></p>

        <?php if (!empty($consultation['medical_docs'])): ?>
          <p><strong>Medical Documents:</strong>
            <a href="<?= htmlspecialchars($consultation['medical_docs']) ?>" target="_blank">View</a>
          </p>
        <?php endif; ?>

        <?php if (!empty($consultation['notes'])): ?>
          <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($consultation['notes'])) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div id="chatSection" class="consult-window-section" style="display: none;">
    <?php
    // Load chat messages
    $chatQuery = "SELECT cc.*, u.profile_pic
                  FROM consultation_chat cc
                  JOIN users u ON cc.sender_id = u.id
                  WHERE cc.consultation_id = $consultation_id
                  ORDER BY cc.sent_at ASC";
    $chatResult = mysqli_query($connection, $chatQuery);

    // Fetch user (patient) profile pic
    $userResult = mysqli_query($connection, "SELECT profile_pic FROM users WHERE id = $user_id");
    $userData = mysqli_fetch_assoc($userResult);
    $patientPic = !empty($userData['profile_pic']) ? $userData['profile_pic'] : 'medconnect_images/blank_profile_pic.png';

    // Consultant pic already fetched as $profilePic
    ?>

    <div class="chat-box">
      <div class="chat-messages" id="chatMessages">
        <?php while ($chat = mysqli_fetch_assoc($chatResult)): ?>
          <?php
            $isSender = $chat['sender_id'] == $_SESSION['id'];
            $pic = !empty($chat['profile_pic']) ? $chat['profile_pic'] : 'medconnect_images/blank_profile_pic.png';
            $alignment = $isSender ? 'flex-end' : 'flex-start';
            $bubble = $isSender ? 'sent' : 'received';
          ?>
          <div style="display: flex; justify-content: <?= $alignment ?>; gap: 10px;">
            <?php if (!$isSender): ?>
              <img src="<?= $pic ?>" alt="Profile" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
            <?php endif; ?>
            <div class="chat-message <?= $bubble ?>">
              <p><?= htmlspecialchars($chat['message'], ENT_QUOTES) ?></p>
            </div>
            <?php if ($isSender): ?>
              <img src="<?= $pic ?>" alt="Profile" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
            <?php endif; ?>
            <span class="timestamp"><?= date("H:i", strtotime($chat['sent_at'])) ?></span>
          </div>
        <?php endwhile; ?>
      </div>

      <form method="POST" class="chat-form" id="chatForm">
        <input type="hidden" name="consultation_id" value="<?= $consultation_id ?>">
        <input type="hidden" name="sender_id" value="<?= $_SESSION['id'] ?>">
        <input type="hidden" name="receiver_id" value="<?= $consultant['id'] ?>">
        <input type="text" id="messageInput" name="message" placeholder="Type your message..." required autocomplete="off">
        <button type="submit">Send</button>
      </form>
    </div>
  </div>

  <div id="prescriptionSection" class="consult-window-section" style="display: none;">
    <?php
      $consultation_id = intval($_GET['consultation_id']);
      $query = "SELECT * FROM consultation_prescriptions WHERE consultation_id = ? ORDER BY issued_at DESC";
      $stmt = $connection->prepare($query);
      $stmt->bind_param("i", $consultation_id);
      $stmt->execute();
      $prescriptions_result = $stmt->get_result();

      if ($prescriptions_result->num_rows > 0):
    ?>
      <h2 class="prescription-title">📄 Your Prescriptions</h2>
      <div class="prescription-list">
        <?php while ($prescription = $prescriptions_result->fetch_assoc()): ?>
          <div class="prescription-item">
            <p><strong>🩺 Issue:</strong> <?= htmlspecialchars($prescription['description']) ?></p>
            <p><strong>💊 Medication:</strong> <?= htmlspecialchars($prescription['medication']) ?></p>
            <p><strong>⏱ Dosage:</strong> <?= htmlspecialchars($prescription['dosage']) ?></p>
            <?php if (!empty($prescription['instructions'])): ?>
              <p><strong>📝 Instructions:</strong> <?= htmlspecialchars($prescription['instructions']) ?></p>
            <?php endif; ?>
            <?php if (!empty($prescription['tests'])): ?>
              <p><strong>🧪 Tests:</strong> <?= htmlspecialchars($prescription['tests']) ?></p>
            <?php endif; ?>
            <p class="issued-date">🗓 Issued: <?= date("F j, Y, g:i a", strtotime($prescription['issued_at'])) ?></p>

            <form method="POST">
              <button type="submit" name="download_prescriptions_pdf" class="download-pdf-btn">Download as PDF</button>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="no-prescriptions">No prescriptions issued yet for this consultation.</p>
    <?php endif; ?>
  </div>

  <div id="reviewsSection" class="consult-window-section" style="display: none;">
    <div class="review-container">

      <!-- Review Submission -->
      <div class="review-form-container">
        <h2>Share Your Consultation Experience</h2>
        <form method="POST" class="review-form">

          <!-- Rating Block -->
          <div class="star-rating-block">
            <label>Rate Your Experience</label>
            <div class="star-rating">
              <span class="star" data-value="1">&#9733;</span>
              <span class="star" data-value="2">&#9733;</span>
              <span class="star" data-value="3">&#9733;</span>
              <span class="star" data-value="4">&#9733;</span>
              <span class="star" data-value="5">&#9733;</span>
            </div>
            <input type="hidden" name="rating" id="ratingInput" required>
          </div>

          <!-- Title -->
          <label for="review_title">Title</label>
          <input type="text" name="review_title" required>

          <!-- Recommendation Toggle -->
          <div class="recommend-toggle">
            <label>Would you recommend this consultant?</label>
            <div class="recommend-options">
              <label><input type="radio" name="recommend" value="Yes" required> Yes</label>
              <label><input type="radio" name="recommend" value="No"> No</label>
            </div>
          </div>

          <!-- Review Body -->
          <label for="review_body">Your Review</label>
          <textarea name="review_body" rows="5" required placeholder="Write about your experience..."></textarea>

          <!-- Submit -->
          <button type="submit" name="submit_review">Submit Review</button>
        </form>
      </div>

      <!-- Display User's Review -->
      <div class="review-display-container">
        <h2>Your Review</h2>
        <?php
        $query = "SELECT * FROM consultation_reviews WHERE consultation_id = ? AND user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $consultation_id, $_SESSION['id']);
        $stmt->execute();
        $review_result = $stmt->get_result();

        if ($review_result->num_rows > 0):
            $review = $review_result->fetch_assoc();
        ?>
          <div class="review-card">
            <div class="review-header">
              <div class="star-display">
                  <?php
                  $filled = intval($review['rating']);
                  $empty = 5 - $filled;
                  for ($i = 0; $i < $filled; $i++) echo '<span class="star filled">&#9733;</span>';
                  for ($i = 0; $i < $empty; $i++) echo '<span class="star">&#9733;</span>';
                  ?>
              </div>
              <span class="review-date"><?= date("F j, Y, g:i a", strtotime($review['created_at'])) ?></span>
            </div>
            <h3><?= htmlspecialchars($review['review_title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($review['review_body'])) ?></p>
            <div class="review-action-buttons">
              <form method="POST" style="display: inline;">
                <button type="button" class="edit-review-btn" onclick="editReview()">Edit Review</button>
              </form>
              <form method="POST" onsubmit="return confirm('Are you sure you want to delete your review?');">
                <input type="hidden" name="delete_review_id" value="<?= $review['id'] ?>">
                <button type="submit" class="delete-review-btn">Delete Review</button>
              </form>
            </div>
          </div>
        <?php else: ?>
          <p>You have not submitted a review for this consultation yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div id="meetingSection" class="consult-window-section" style="display: none;">
    <!-- Meeting content goes here -->
  </div>

  <!-- Footer -->

  <footer>
    <div class="footer">
      <div class="footer-item footer-1">
        <h1 class="footer-head">MedConnect</h1>
        <h3>Need help? Contact us at <br> support@medconnect.com</h3>
        <div style="display: flex; flex-direction: column; gap: 2em;">
          <p><abbr style="cursor: pointer; border-bottom: 1px dashed white;">Terms of Service</abbr> & <abbr style="cursor: pointer; border-bottom: 1px dashed white;"> Privacy Policy</abbr></p>
          <p>MedConnect © 2025</p>
        </div>
      </div>
      <div class="footer-item footer-2">
        <u><b><p>Services</p></b></u>
        <p style="cursor: pointer;">Consult</p>
        <p style="cursor: pointer;">Profile</p>
        <p style="cursor: pointer;">Resources</p>
        <p style="cursor: pointer;">Your Directory</p>
      </div>
      <div class="footer-item footer-3">
        <u><b><p>About</p></b></u>
        <p style="cursor: pointer;">FAQ</p>
        <p style="cursor: pointer;">Testimonials</p>
        <p style="cursor: pointer;">About Us</p>
        <p style="cursor: pointer;">Contact</p>
      </div>
    </div>
  </footer>

  <script type="text/javascript">

  function toggleWindowSection(section) {
    document.querySelectorAll('.consult-tab').forEach(tab => {
      tab.classList.remove('active');
    });
    document.querySelector(`[onclick="toggleWindowSection('${section}')"]`).classList.add('active');

    // Hide all sections
    document.querySelectorAll('.consult-window-section').forEach(sec => {
      sec.style.display = 'none';
    });

    // Show selected section
    document.getElementById(`${section}Section`).style.display = 'block';
  }

  // Chat Section

  document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chatMessages");
    if (chatBox) {
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Check URL for section=chat to auto-toggle
    const params = new URLSearchParams(window.location.search);
    const section = params.get("section");

    if (section) {
      toggleWindowSection(section);
    }
  });

  function loadChatMessages() {
    const consultationId = <?= $consultation_id ?>;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `?fetchMessages=1&consultation_id=${consultationId}`, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        document.getElementById("chatMessages").innerHTML = xhr.responseText;
        // Scroll to bottom after loading
        const chatBox = document.getElementById("chatMessages");
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    };
    xhr.send();
  }

  // Load every 3 seconds
  setInterval(loadChatMessages, 3000);

  // Initial load
  loadChatMessages();

  document.getElementById("chatForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("", {
        method: "POST",
        body: formData
    })
    .then(() => {
        loadChatMessages();  // Reload chat messages only
        document.getElementById("messageInput").value = "";  // Clear input
    })
    .catch(error => console.error("Error:", error));
  });

  document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach((star, idx) => {
        star.addEventListener('mouseover', () => {
            highlightStars(idx);
        });

        star.addEventListener('mouseout', () => {
            resetStars();
        });

        star.addEventListener('click', () => {
            setRating(idx + 1);
        });
    });

    function highlightStars(index) {
        stars.forEach((star, idx) => {
            if (idx <= index) {
                star.classList.add('hover');
            } else {
                star.classList.remove('hover');
            }
        });
    }

    function resetStars() {
        stars.forEach(star => {
            star.classList.remove('hover');
        });
    }

    function setRating(rating) {
        ratingInput.value = rating;
        stars.forEach((star, idx) => {
            if (idx < rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
      }
    });

    function editReview() {
      const reviewForm = document.querySelector('.review-form-container');
      reviewForm.classList.add('review-form-highlight');

      setTimeout(() => {
          reviewForm.classList.remove('review-form-highlight');
      }, 1500);
    }

  </script>

</body>
</html>
