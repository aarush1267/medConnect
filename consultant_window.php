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
if ($_SESSION['role'] !== 'consultant') {
    header("Location:user_index.php");
    exit;
}

// Consultation ID
$consultation_id = isset($_GET['consultation_id']) ? intval($_GET['consultation_id']) : 0;
if ($consultation_id === 0) {
    echo "Invalid consultation.";
    exit;
}

// Fetch user info for this consultation
$query = "SELECT u.id, u.name, u.age, u.gender, u.country, u.phone, u.profile_pic
          FROM consultations c
          JOIN users u ON c.user_id = u.id
          WHERE c.id = $consultation_id AND c.consultant_id = {$_SESSION['id']}";

$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Consultation not found.";
    exit;
}

$profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'medconnect_images/blank_profile_pic.png';

$consultant_id = $_SESSION['id'];

// Fetch consultation data
$stmt = $connection->prepare("SELECT * FROM consultations WHERE id = ? AND consultant_id = ?");
$stmt->bind_param("ii", $consultation_id, $consultant_id);
$stmt->execute();
$result = $stmt->get_result();
$consultation = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Consultant Window | MedConnect</title>
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

</style>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <h1 onclick="location.href='consultant_index.php'" class="head">MedConnect</h1>
    <ul class="nav-links">
      <div class="menu">
        <li><h3 onclick="location.href='consultant_consult.php'">Consult</h3></li>
        <li><h3>Feed</h3></li>
        <li><h3>Resources</h3></li>
        <li><h3>About</h3></li>
        <li><h3 onclick="location.href='consultant_profile.php'" class="profile-btn" style="color: white;">Your Profile</h3></li>
      </div>
    </ul>
  </nav>

  <!-- Profile Banner -->
  <div class="profile-banner"></div>

  <!-- Header Info -->
  <div class="consultant-header">
    <div class="profile-img-container">
      <img src="<?php echo htmlspecialchars($profilePic); ?>" class="profile-img" alt="User Picture">
    </div>
    <div class="profile-info">
      <h1 class="consultant-name"><?php echo htmlspecialchars($user['name']); ?></h1>
      <p class="profile-subtext">
        <i class="fas fa-user"></i> <?php echo $user['age'] ?? 'Not Listed'; ?> •
        <i class="fas fa-venus-mars"></i> <?php echo htmlspecialchars($user['gender'] ?? 'Not Listed'); ?> •
        <i class="fas fa-globe"></i> <?php echo htmlspecialchars($user['country'] ?? 'Not Listed'); ?>
      </p>
      <p class="profile-subtext">
        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($user['phone'] ?? 'Not Listed'); ?>
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
        <span class="consult-tab" onclick="toggleWindowSection('notes')">Notes</span>
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
    <!-- Chat content goes here -->
  </div>

  <div id="prescriptionSection" class="consult-window-section" style="display: none;">
    <!-- Prescription content goes here -->
  </div>

  <div id="notesSection" class="consult-window-section" style="display: none;">
    <!-- Notes content goes here -->
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

  </script>

</body>
</html>

