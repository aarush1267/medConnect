<?php

session_start();

// Making Connection To The Database

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database) or die ("Sorry, couldn't connect to the database");

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
$servicesArray = explode(",", $consultant['services']);
$formattedServices = array_map(function($service) {
    return ucwords(str_replace('_', ' ', trim($service))); // Converts underscores to spaces and capitalizes
}, $servicesArray);
$consultant['services'] = implode(" • ", $formattedServices);

// Format specializations properly
$specializationsArray = explode(",", $consultant['specializations']);
$formattedSpecializations = array_map(function($specialization) {
    return ucwords(str_replace('_', ' ', trim($specialization))); // Converts underscores to spaces and capitalizes
}, $specializationsArray);
$consultant['specializations'] = implode(" • ", $formattedSpecializations);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($consultant['name']); ?> | MedConnect</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure styles are properly linked -->
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
    top: -80px; /* Move Upward */
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
    gap: 20px; /* Adds spacing between About & Services */
    background: #f3f3f3; /* Light Grey Background */
    padding: 20px;
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

</style>
<body>

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

      <!-- Request Consultation Button -->
      <div class="consultation-actions">
          <button class="request-consult-btn">Request Consultation</button>
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

<script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
</body>
</html>
