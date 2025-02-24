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
    width: 200px; /* Adjust as per your other files */
    height: 200px;
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

  <div class="consultant-profile-container">
      <div class="consultant-header">
          <!-- Profile Picture -->
          <div class="profile-img-container">
              <img src="<?php echo !empty($consultant['profile_pic']) ? htmlspecialchars($consultant['profile_pic']) : 'medconnect_images/blank_profile_pic.png'; ?>" alt="Profile Picture" class="profile-img">
          </div>

          <!-- Basic Info -->
          <div class="consultant-info">
              <h1><?php echo htmlspecialchars($consultant['name']); ?></h1>
              <p><?php echo !empty($consultant['age']) ? $consultant['age'] : "Not listed"; ?> •
                 <?php echo !empty($consultant['gender']) ? htmlspecialchars($consultant['gender']) : "Not listed"; ?> •
                 <?php echo !empty($consultant['country']) ? htmlspecialchars($consultant['country']) : "Not listed"; ?>
              </p>
              <p><strong>Phone:</strong> <?php echo !empty($consultant['phone']) ? htmlspecialchars($consultant['phone']) : "Not listed"; ?></p>
          </div>
      </div>

      <!-- About Section -->
      <div class="consultant-section">
          <h2>About</h2>
          <p><?php echo !empty($consultant['about']) ? nl2br(htmlspecialchars($consultant['about'])) : "Not listed"; ?></p>
      </div>

      <!-- Services -->
      <div class="consultant-section">
          <h2>Services</h2>
          <p><?php echo !empty($consultant['services']) ? htmlspecialchars($consultant['services']) : "Not listed"; ?></p>
      </div>

      <!-- Offline Availability -->
      <div class="consultant-section">
          <h2>Offline Availability</h2>
          <p><?php echo !empty($consultant['offline']) ? htmlspecialchars($consultant['offline']) : "Not listed"; ?></p>
      </div>

      <!-- City & Hospital -->
      <div class="consultant-section">
          <h2>Location</h2>
          <p><strong>City:</strong> <?php echo !empty($consultant['city']) ? htmlspecialchars($consultant['city']) : "Not listed"; ?></p>
          <p><strong>Hospital:</strong> <?php echo !empty($consultant['hospital']) ? htmlspecialchars($consultant['hospital']) : "Not listed"; ?></p>
      </div>

      <!-- Education -->
      <div class="consultant-section">
          <h2>Education</h2>
          <p><?php echo !empty($consultant['education']) ? nl2br(htmlspecialchars($consultant['education'])) : "Not listed"; ?></p>
      </div>

      <!-- Specializations -->
      <div class="consultant-section">
          <h2>Specializations</h2>
          <p><?php echo !empty($consultant['specializations']) ? htmlspecialchars($consultant['specializations']) : "Not listed"; ?></p>
      </div>

      <!-- Awards/Recognitions -->
      <div class="consultant-section">
          <h2>Awards & Recognitions</h2>
          <p><?php echo !empty($consultant['awards']) ? nl2br(htmlspecialchars($consultant['awards'])) : "Not listed"; ?></p>
      </div>

      <!-- Experience -->
      <div class="consultant-section">
          <h2>Experience</h2>
          <p><?php echo !empty($consultant['experience']) ? nl2br(htmlspecialchars($consultant['experience'])) : "Not listed"; ?></p>
      </div>

      <!-- Request Consultation Button -->
      <div class="consultation-actions">
          <button class="request-consult-btn">Request Consultation</button>
      </div>
  </div>

</body>
</html>
