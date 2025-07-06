<?php

session_start();

// Making Connection To The Database

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Sorry, couldn't connect to the database");

if (!isset($_SESSION['signUpBtn'])) {
  header("Location:login.php"); // Not Logged In (Redirect Back to Login/Sign Up Page)
} elseif (isset($_SESSION['signUpBtn']) && !isset($_SESSION['role'])) {
  header("Location:role.php");
} elseif ($_SESSION['role'] === 'consultant') {
  header("Location:consultant_index.php");
}

// Get consultant ID from URL
$consultant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($consultant_id === 0) {
    echo "Invalid consultant profile.";
    exit;
}

// Fetch consultant details from database
$stmt = $connection->prepare("SELECT id, name, age, gender, country, phone, about, services, offline, city, hospital, education, specializations, awards, experience, profile_pic
                              FROM users WHERE id = ? AND role = 'consultant'");
$stmt->bind_param("i", $consultant_id);
$stmt->execute();
$result = $stmt->get_result();
$consultant = $result->fetch_assoc();

if (!$consultant) {
    echo "Consultant not found.";
    exit;
}

// Format services properly
$servicesArray = explode(",", $consultant['services'] ?? '');
$formattedServices = array_map(function($service) {
    return ucwords(str_replace('_', ' ', trim($service))); // Converts underscores to spaces and capitalizes
}, $servicesArray);
$consultant['services'] = implode(" • ", $formattedServices);

// Format specializations properly
$specializationsArray = explode(",", $consultant['specializations'] ?? '');
$formattedSpecializations = array_map(function($specialization) {
    return ucwords(str_replace('_', ' ', trim($specialization))); // Converts underscores to spaces and capitalizes
}, $specializationsArray);
$consultant['specializations'] = implode(" • ", $formattedSpecializations);

// Reviews & Stats

// Count accepted consultations
$consultations_stmt = $connection->prepare("SELECT COUNT(*) AS total_consults FROM consultations WHERE consultant_id = ? AND status = 'completed'");
$consultations_stmt->bind_param("i", $consultant_id);
$consultations_stmt->execute();
$consultations_result = $consultations_stmt->get_result();
$total_consults = $consultations_result->fetch_assoc()['total_consults'] ?? 0;

// Count recommendations
$recommend_stmt = $connection->prepare("SELECT recommend, COUNT(*) as count FROM consultation_reviews WHERE consultant_id = ? GROUP BY recommend");
$recommend_stmt->bind_param("i", $consultant_id);
$recommend_stmt->execute();
$recommend_result = $recommend_stmt->get_result();
$recommend_yes = 0;
$recommend_no = 0;
while ($row = $recommend_result->fetch_assoc()) {
    if ($row['recommend'] === "Yes") {
        $recommend_yes = $row['count'];
    } elseif ($row['recommend'] === "No") {
        $recommend_no = $row['count'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($consultant['name']); ?> | MedConnect</title>
    <link rel="stylesheet" href="styles.css">
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
    height: 200px;
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
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    top: -130px;
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

/* Grid layout for info boxes */
.consultant-info-grid {
    display: flex;
    justify-content: center;
    gap: 70px;
    max-width: 90%;
    margin: 0 auto;
    top: -80px; /* Move Upward */
    margin-top: -50px;
}

/* Individual Info Box Styling */
.consultant-info-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease-in-out;
    position: relative;
}

/* Icon Container (Circle at the Top) */
.info-icon {
    width: 50px;
    height: 50px;
    background-color: #5FA159; /* Match Banner Color */
    color: white;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

/* Title Styling */
.consultant-info-box h2 {
    font-size: 18px;
    color: #5A5A5A;
    margin: 30px 0 10px;
}

/* Paragraph Styling */
.consultant-info-box p {
    font-size: 16px;
    color: #444;
    line-height: 1.6;
}

/* Hover Effect */
.consultant-info-box:hover {
    transform: translateY(-5px);
}

/* Left Container Wrapping About & Services */
.left-info-container {
    display: flex;
    flex-direction: column;
    gap: 40px; /* Adds spacing between About & Services */
    background: #f3f3f3; /* Light Grey Background */
    padding: 30px;
    border-radius: 10px;
    width: 500px;
}

/* Right Side: 3x2 Grid Layout */
.right-info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 columns */
    grid-template-rows: repeat(2, auto); /* 2 rows */
    gap: 60px; /* Space between grid items */
    width: 100%;
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

.consultation-section {
    text-align: center;
    padding: 50px;
    background-color: #F8D4A4; /* Matches MedConnect Theme */
}

.consultation-title {
    font-size: 35px;
    font-weight: bold;
    color: #5A3E2B;
    margin-bottom: 40px;
    margin-top: 20px;
}

/* Grid Layout */
.consultation-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 per row */
    gap: 100px;
    justify-content: center;
    max-width: 1100px;
    margin: 0 auto;
    margin-top: 100px;
}

/* Consultation Box */
.consultation-box {
    background: transparent; /* No background */
    text-align: center;
    position: relative;
}

/* Icon Half Inside Circle */
.consultation-icon {
    width: 70px;
    height: 70px;
    background-color: white;
    color: #5A3E2B;
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    position: absolute;
    left: 50%;
    top: -35px; /* Half outside the box */
    transform: translateX(-50%);
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

/* Text Box */
.consultation-box h2 {
    font-size: 18px;
    color: #5A3E2B;
    margin-top: 70px; /* Adjust to fit under the icon */
    font-weight: bold;
}

.consultation-box p {
    font-size: 16px;
    color: #5A3E2B;
    max-width: 80%;
    margin: 10px auto;
    line-height: 1.5;
}

/* Request Button */
.consultation-btn {
    margin-top: 50px;
    background-color: #5FA159;
    color: white;
    font-size: 18px;
    font-weight: bold;
    padding: 15px 30px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
    font-family: Lora;
    box-shadow: 0 1px 1px black;
}

.consultation-btn:active {
    box-shadow: none;
}

/* Responsive Adjustments */
@media screen and (max-width: 900px) {
    .consultation-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 600px) {
    .consultation-grid {
        grid-template-columns: repeat(1, 1fr);
    }
}

/* Consultation Request Popup Styling */
.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 450px;
    background: white;
    padding: 25px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1000;
    font-family: Lora;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
}

/* Overlay Background */
.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
}

.popup.show {
    display: block;
    opacity: 1;
    visibility: visible;
}

.popup-overlay.show {
    display: block;
    opacity: 1;
    visibility: visible;
}

/* Popup Content */
.popup-content {
    text-align: center;
    position: relative;
    font-family: Lora;
}

.popup button {
    background-color: #5FA159; /* Matches MedConnect theme */
    color: white;
    font-size: 13px;
    font-weight: bold;
    padding: 12px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
    font-family: 'Lora', serif;
    box-shadow: 0 1px 1px black;
    margin-top: 5px;
}

/* Button Active & Hover Effects */
.popup button:hover {
    background-color: #4e8a45;
}

.popup button:active {
    box-shadow: none;
}

/* Close Button */
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #888;
}

.close-btn:hover {
    color: black;
}

/* Input Fields */
#consultationForm label {
    font-size: 16px;
    font-weight: bold;
    color: #5A3E2B;
    display: block;
    margin: 12px 0 5px;
}

#consultationForm select,
#consultationForm input,
#consultationForm textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 15px;
    background: #f9f9f9;
    font-family: Lora;
    resize: none;
}

#consultationForm input[type="date"],
#consultationForm input[type="time"] {
    cursor: pointer;
}

/* Responsive Styling */
@media screen and (max-width: 500px) {
    .popup {
        width: 90%;
    }
}

/* Notification Bar */
.notification-bar {
    display: none;
    position: fixed;
    top: -50px; /* Initially hidden above the screen */
    left: 50%;
    transform: translateX(-50%);
    background-color: #60a159; /* Green Background */
    color: white;
    padding: 15px 25px;
    font-size: 18px;
    font-family: 'Lora', serif;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: top 0.5s ease-in-out;
    z-index: 1000;
}

.consultant-stats-section {
    display: flex;
    justify-content: center;
    gap: 50px;
    background: #fdf6ee;
    padding: 30px 20px;
    border-radius: 12px;
    max-width: 1000px;
    margin: 0 auto 30px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    font-family: 'Lora', serif;
}

.consultant-stats-box {
    text-align: center;
    flex: 1;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
}

.consultant-stats-box:hover {
    transform: translateY(-5px);
}

.stats-icon {
    font-size: 28px;
    color: #5FA159;
    margin-bottom: 10px;
}

.consultant-stats-box h3 {
    font-size: 18px;
    color: #5A3E2B;
    margin-bottom: 8px;
}

.consultant-stats-box p {
    font-size: 20px;
    color: #4e3c29;
    font-weight: bold;
}

.view-reviews-btn {
    background-color: #60a159;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    font-family: 'Lora', serif;
    transition: background 0.2s ease;
    box-shadow: 0 1px 1px black;
}

.view-reviews-btn:hover {
    background-color: #4e8a45;
}

.view-reviews-btn:active {
  box-shadow: none;
}

.review-card {
    background: linear-gradient(to bottom right, #fdf6ee, #f8e8d1);
    border-left: 6px solid #5FA159;
    border-radius: 14px;
    padding: 15px 20px;
    max-width: 500px;
    margin: 15px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.07);
    font-family: 'Lora', serif;
    transition: transform 0.2s ease;
}

.review-card h3 {
    font-size: 20px;
    color: #4e3c29;
    margin: 10px 0 8px 0;
}

.review-card p {
    font-size: 15px;
    color: #5a3e2b;
    margin: 6px 0;
}

.review-date {
    font-size: 12px;
    color: #7c6651;
    font-style: italic;
    float: right;
}

.review-patient {
    font-size: 14px;
    color: #5a3e2b;
    margin-top: 5px;
}

.star-display {
    display: flex;
    gap: 2px;
    font-size: 18px;
    margin-bottom: 6px;
}

.star-display .star {
    color: #ccc;
}

.star-display .star.filled {
    color: #f7c948;
}

.reviews-list {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 8px;
}

.consultant-stats-title {
    text-align: center;
    font-size: 35px;
    font-weight: bold;
    color: #5A3E2B;
    margin-top: 80px;
    margin-bottom: 30px;
}

</style>
<body>

  <!-- Notification Bar -->
<div id="notificationBar" class="notification-bar">
    Consultation request submitted successfully!
</div>

  <!-- Navigation Bar -->

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

  <div class="consultant-profile-container">
    <div class="consultant-header">
        <!-- Profile Picture -->
        <div class="profile-img-container">
            <img src="<?php echo !empty($consultant['profile_pic']) ? htmlspecialchars($consultant['profile_pic']) : 'medconnect_images/blank_profile_pic.png'; ?>"
                 alt="Profile Picture" class="profile-img">
        </div>

        <!-- Profile Info (Placed to the Right) -->
        <div class="profile-info">
            <h1 class="consultant-name"><?php echo htmlspecialchars($consultant['name']); ?></h1>

            <!-- Age, Gender, Country -->
            <p class="profile-subtext">
                <i class="fas fa-user"></i> <?php echo !empty($consultant['age']) ? $consultant['age'] : "Not listed"; ?> •
                <i class="fas fa-venus-mars"></i> <?php echo !empty($consultant['gender']) ? htmlspecialchars($consultant['gender']) : "Not listed"; ?> •
                <i class="fas fa-globe"></i> <?php echo !empty($consultant['country']) ? htmlspecialchars($consultant['country']) : "Not listed"; ?>
            </p>

            <!-- Phone Number -->
            <p class="profile-subtext">
                <i class="fas fa-phone"></i> <?php echo !empty($consultant['phone']) ? htmlspecialchars($consultant['phone']) : "Not listed"; ?>
            </p>
        </div>
    </div>

    <div class="consultant-info-grid">
      <div class="left-info-container">
        <div class="consultant-info-box">
            <div class="info-icon">
                <i class="fas fa-user"></i> <!-- Change Icon as Needed -->
            </div>
            <h2>About</h2>
            <p><?php echo !empty($consultant['about']) ? nl2br(htmlspecialchars($consultant['about'])) : "Not listed"; ?></p>
        </div>

        <div class="consultant-info-box">
            <div class="info-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
            <h2>Services</h2>
            <p><?php echo !empty($consultant['services']) ? htmlspecialchars($consultant['services']) : "Not listed"; ?></p>
        </div>
      </div>

      <!-- Right Side: Grid Layout for Additional Info -->
      <div class="right-info-grid">
        <div class="consultant-info-box">
            <div class="info-icon"><i class="fas fa-calendar"></i></div>
            <h2>Offline Availability</h2>
            <p><?php echo !empty($consultant['offline']) ? htmlspecialchars($consultant['offline']) : "Not listed"; ?></p>
        </div>

        <div class="consultant-info-box">
            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h2>Location</h2>
            <p><strong>City:</strong> <?php echo !empty($consultant['city']) ? htmlspecialchars($consultant['city']) : "Not listed"; ?></p>
            <p><strong>Hospital:</strong> <?php echo !empty($consultant['hospital']) ? htmlspecialchars($consultant['hospital']) : "Not listed"; ?></p>
        </div>

        <div class="consultant-info-box">
            <div class="info-icon"><i class="fas fa-graduation-cap"></i></div>
            <h2>Education</h2>
            <p><?php echo !empty($consultant['education']) ? nl2br(htmlspecialchars($consultant['education'])) : "Not listed"; ?></p>
        </div>

        <div class="consultant-info-box">
            <div class="info-icon"><i class="fas fa-medal"></i></div>
            <h2>Awards & Recognitions</h2>
            <p><?php echo !empty($consultant['awards']) ? nl2br(htmlspecialchars($consultant['awards'])) : "Not listed"; ?></p>
        </div>

        <div class="consultant-info-box">
            <div class="info-icon"><i class="fas fa-briefcase"></i></div>
            <h2>Experience</h2>
            <p><?php echo !empty($consultant['experience']) ? nl2br(htmlspecialchars($consultant['experience'])) : "Not listed"; ?></p>
        </div>

        <div class="consultant-info-box">
            <div class="info-icon"><i class="fas fa-user-md"></i></div>
            <h2>Specializations</h2>
            <p><?php echo !empty($consultant['specializations']) ? htmlspecialchars($consultant['specializations']) : "Not listed"; ?></p>
        </div>
      </div>
    </div>

    <h1 class="consultant-stats-title">Consultant Stats</h1>

    <div class="consultant-stats-section">
      <div class="consultant-stats-box">
        <i class="fas fa-calendar-check stats-icon"></i>
        <h3>Consultations Completed</h3>
        <p><?= $total_consults ?></p>
      </div>
      <div class="consultant-stats-box">
        <i class="fas fa-users stats-icon"></i>
        <h3>Recommendations</h3>
        <p>
          <i class="fas fa-thumbs-up" style="color: #5FA159; margin-right: 6px;"></i><?= $recommend_yes ?>
          &nbsp;|&nbsp;
          <i class="fas fa-thumbs-down" style="color: #d9534f; margin: 0 6px;"></i><?= $recommend_no ?>
        </p>
      </div>
      <div class="consultant-stats-box">
        <i class="fas fa-star stats-icon"></i>
        <h3>Patient Reviews</h3>
        <button class="view-reviews-btn" onclick="openReviewsPopup()">View Reviews</button>
      </div>
    </div>

    <!-- Reviews Popup -->
    <div id="reviewsPopupOverlay" class="popup-overlay" onclick="closeReviewsPopup()"></div>
    <div id="reviewsPopup" class="popup">
      <div class="popup-content">
        <span class="close-btn" onclick="closeReviewsPopup()">&times;</span>
        <h2>Reviews for Dr. <?= htmlspecialchars($consultant['name']) ?></h2>
        <div class="reviews-list">
          <?php
          $reviews_stmt = $connection->prepare("
              SELECT cr.*, u.name AS patient_name
              FROM consultation_reviews cr
              JOIN users u ON cr.user_id = u.id
              WHERE cr.consultant_id = ?
              ORDER BY cr.created_at DESC
          ");
          $reviews_stmt->bind_param("i", $consultant_id);
          $reviews_stmt->execute();
          $reviews_result = $reviews_stmt->get_result();

          if ($reviews_result->num_rows > 0):
              while ($review = $reviews_result->fetch_assoc()):
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
                  <span class="review-date"><?= date("F j, Y", strtotime($review['created_at'])) ?></span>
                </div>
                <h3><?= htmlspecialchars($review['review_title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($review['review_body'])) ?></p>
                <p class="review-patient">👤 <?= htmlspecialchars($review['patient_name']) ?></p>
              </div>
          <?php
              endwhile;
          else:
              echo "<p>No reviews have been submitted for this consultant yet.</p>";
          endif;
          ?>
        </div>
      </div>
    </div>

    <div class="consultation-section">
      <h1 class="consultation-title">Why Book A Consultation?</h1>

      <div class="consultation-grid">
          <div class="consultation-box">
              <div class="consultation-icon">
                  <i class="fas fa-user-md"></i> <!-- Doctor icon -->
              </div>
              <h2>Medical Guidance</h2>
              <p>Get professional advice from verified healthcare experts who volunteer to tailor to your health needs.</p>
          </div>

          <div class="consultation-box">
              <div class="consultation-icon">
                  <i class="fas fa-clock"></i> <!-- Clock icon -->
              </div>
              <h2>Flexible Scheduling</h2>
              <p>Book consultations at your convenience with available time slots, by providing relevant details.</p>
          </div>

          <div class="consultation-box">
              <div class="consultation-icon">
                  <i class="fas fa-video"></i> <!-- Video Call icon -->
              </div>
              <h2>Online or In-Person</h2>
              <p>Choose between online video consultations or in-person visits with our consultants, accordingly.</p>
          </div>

          <div class="consultation-box">
              <div class="consultation-icon">
                  <i class="fas fa-shield-alt"></i> <!-- Shield/Security icon -->
              </div>
              <h2>Confidential & Secure</h2>
              <p>Your consultations are private and handled with the highest level of security at all times.</p>
          </div>

          <div class="consultation-box">
              <div class="consultation-icon">
                  <i class="fas fa-stethoscope"></i> <!-- Stethoscope icon -->
              </div>
              <h2>Comprehensive Care</h2>
              <p>Receive personalized prescriptions and relevant medical assistance.</p>
          </div>

          <div class="consultation-box">
              <div class="consultation-icon">
                  <i class="fas fa-check-circle"></i> <!-- Check mark icon -->
              </div>
              <h2>Trustworthy</h2>
              <p>Join the other patients who trust MedConnect for their healthcare needs.</p>
          </div>
      </div>

      <button class="consultation-btn" onclick="openConsultForm()">Request a Consultation From Dr. <?php echo htmlspecialchars($consultant['name']); ?></button>
    </div>
  </div>

  <!-- Popup Overlay -->
  <div id="popupOverlay" class="popup-overlay" onclick="closePopup()"></div>

  <!-- Consultation Request Popup -->
  <div id="consultationPopup" class="popup">
      <div class="popup-content">
          <span class="close-btn" onclick="closeConsultForm()">&times;</span>
          <h2>Request a Consultation</h2>

          <form id="consultationForm" enctype="multipart/form-data">
              <input type="hidden" name="consultant_id" value="<?php echo $consultant_id; ?>">

              <label for="symptoms">Symptoms:</label>
              <select id="symptoms" name="symptoms" required>
                  <option value="">Select Symptoms</option>
                  <option value="Fever">Fever</option>
                  <option value="Cough">Cough</option>
              </select>

              <label for="reason">Details/Reason:</label>
              <textarea id="reason" name="details" rows="3" required></textarea>

              <label for="date">Preferred Date:</label>
              <input type="date" id="date" name="date" required>

              <label for="time">Preferred Time:</label>
              <input type="time" id="time" name="time" required>

              <label for="medical_docs">Medical Documents (Optional):</label>
              <input type="file" id="medical_docs" name="medical_docs" accept=".pdf,.jpg,.png">

              <label for="notes">Additional Notes (Optional):</label>
              <textarea id="notes" name="notes" rows="2"></textarea>

              <button type="submit">Submit Request</button>
          </form>
      </div>
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

  function openConsultForm() {
      let popup = document.getElementById("consultationPopup");
      let overlay = document.getElementById("popupOverlay");

      popup.style.display = "block";
      overlay.style.display = "block";

      setTimeout(() => {
          popup.classList.add("show");
          overlay.classList.add("show");
      }, 10);
  }

  function closeConsultForm() {
      let popup = document.getElementById("consultationPopup");
      let overlay = document.getElementById("popupOverlay");

      popup.classList.remove("show");
      overlay.classList.remove("show");

      setTimeout(() => {
          popup.style.display = "none";
          overlay.style.display = "none";
      }, 300);
  }

  document.getElementById("consultationForm").addEventListener("submit", function(event) {
      event.preventDefault();
      let formData = new FormData(this);

      fetch("request_consult.php", {
          method: "POST",
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          if (data.trim() === "Success") {
              showNotification("Consultation request submitted successfully!", "success");
              closeConsultForm();
          } else {
              showNotification("Error: " + data, "error");
          }
      })
      .catch(error => {
          showNotification("Something went wrong. Please try again.", "error");
          console.error("Error:", error);
      });
  });

  // Function to Show Notification
  function showNotification(message, type) {
      const notificationBar = document.getElementById("notificationBar");
      notificationBar.innerText = message;

      if (type === "success") {
          notificationBar.style.backgroundColor = "#60a159"; // Green for success
      } else {
          notificationBar.style.backgroundColor = "#d9534f"; // Red for error
      }

      notificationBar.style.top = "20px"; // Slide Down
      notificationBar.style.display = "block";

      // Hide after 3 seconds
      setTimeout(() => {
          notificationBar.style.top = "-50px"; // Slide Up
          setTimeout(() => {
              notificationBar.style.display = "none";
          }, 500);
      }, 3000);
  }

  function openReviewsPopup() {
    const popup = document.getElementById("reviewsPopup");
    const overlay = document.getElementById("reviewsPopupOverlay");
    popup.style.display = "block";
    overlay.style.display = "block";
    setTimeout(() => {
        popup.classList.add("show");
        overlay.classList.add("show");
    }, 10);
  }

  function closeReviewsPopup() {
      const popup = document.getElementById("reviewsPopup");
      const overlay = document.getElementById("reviewsPopupOverlay");
      popup.classList.remove("show");
      overlay.classList.remove("show");
      setTimeout(() => {
          popup.style.display = "none";
          overlay.style.display = "none";
      }, 300);
  }

  </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="script.js"></script>
<script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
</body>
</html>
