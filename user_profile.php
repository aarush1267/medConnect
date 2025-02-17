<?php

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['signUpBtn'])) {
    header("Location: login.php");
    exit();
}

// Fetch session variables
$userName = $_SESSION['signUpName'] ?? 'Guest';
$userEmail = $_SESSION['signUpEmail'] ?? 'No email';
$userPassword = $_SESSION['signUpPassword'] ?? 'No password';

// Database connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database);
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($userEmail === 'No email' || $userPassword === 'No password') {
    $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($connection, $userEmail) . "'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Update session variables to reflect correct email and password
        $_SESSION['signUpEmail'] = $row['email'];
        $_SESSION['signUpPassword'] = $row['password'];

        // Refresh variables
        $userEmail = $_SESSION['signUpEmail'];
        $userPassword = $_SESSION['signUpPassword'];
    }
}

if (!isset($_SESSION['signUpBtn'])) {
  header("Location:login.php"); // Not Logged In (Redirect Back to Login/Sign Up Page)
} elseif (isset($_SESSION['signUpBtn']) && !isset($_SESSION['role'])) {
  header("Location:role.php");
} elseif ($_SESSION['role'] === 'consultant') {
  header("Location:consultant_index.php");
}

// user date joined
$query = "SELECT date_joined FROM users WHERE email = '$userEmail'";

$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $userDateJoined = $row['date_joined']; // This will contain the actual date
} else {
    $userDateJoined = 'Unknown';
}


// user age
if (isset($_POST['user-age-btn'])) {
    $user_age = $_POST['user_age'];
    $update = "UPDATE users SET age = '$user_age' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's age from the database after the update
        $result = mysqli_query($connection, "SELECT age FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_age = $row['age'];
            $_SESSION['user_age'] = $user_age;
        }
    }
}

if (isset($_SESSION['user_age'])) {
    $user_age = $_SESSION['user_age'];
} else {
    $user_age = "";
}

// user gender
if (isset($_POST['user-gender-btn'])) {
    $user_gender = $_POST['user_gender'];
    $update = "UPDATE users SET gender = '$user_gender' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's gender from the database after the update
        $result = mysqli_query($connection, "SELECT gender FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_gender = $row['gender'];
            $_SESSION['user_gender'] = $user_gender;
        }
    }
}

if (isset($_SESSION['user_gender'])) {
    $user_gender = $_SESSION['user_gender'];
} else {
    $user_gender = "";
}

// user nationality
if (isset($_POST['user-nationality-btn'])) {
    $user_nationality = $_POST['user_nationality'];
    $update = "UPDATE users SET country = '$user_nationality' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's nationality from the database after the update
        $result = mysqli_query($connection, "SELECT country FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_nationality = $row['country'];
            $_SESSION['user_nationality'] = $user_nationality;
        }
    }
}

if (isset($_SESSION['user_nationality'])) {
    $user_nationality = $_SESSION['user_nationality'];
} else {
    $user_nationality = "";
}

// user phone number
if (isset($_POST['user-phone-number-btn'])) {
    $user_phone_number = $_POST['user_phone_number'];
    $update = "UPDATE users SET phone = '$user_phone_number' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's phone number from the database after the update
        $result = mysqli_query($connection, "SELECT phone FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_phone_number = $row['phone'];
            $_SESSION['user_phone_number'] = $user_phone_number;
        }
    }
}

if (isset($_SESSION['user_phone_number'])) {
    $user_phone_number = $_SESSION['user_phone_number'];
} else {
    $user_phone_number = "";
}

// user address
if (!isset($_SESSION['user_address'])) {
    $result = mysqli_query($connection, "SELECT address FROM users WHERE email = '$userEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_address'] = $row['address'];
    }
}

// Update user's address when form is submitted
if (isset($_POST['user-address-btn'])) {
    $user_address = mysqli_real_escape_string($connection, $_POST['user_address']);
    $update = "UPDATE users SET address = '$user_address' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        $_SESSION['user_address'] = $user_address;
    }
}

// Assign address to variable for use in HTML
$user_address = $_SESSION['user_address'] ?? '';

// user about
if (isset($_POST['user-about-btn'])) {
    $user_about = $_POST['user_about'];
    $update = "UPDATE users SET about = '$user_about' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's about from the database after the update
        $result = mysqli_query($connection, "SELECT about FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_about = $row['about'];
            $_SESSION['user_about'] = $user_about;
        }
    }
}

if (isset($_SESSION['user_about'])) {
    $user_about = $_SESSION['user_about'];
} else {
    $user_about = "";
}

// user blood group
if (isset($_POST['user-blood-group-btn'])) {
    $user_blood_group = $_POST['user_blood_group'];
    $update = "UPDATE users SET blood = '$user_blood_group' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's blood group from the database after the update
        $result = mysqli_query($connection, "SELECT blood FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_blood_group = $row['blood'];
            $_SESSION['user_blood_group'] = $user_blood_group;
        }
    }
}

if (isset($_SESSION['user_blood_group'])) {
    $user_blood_group = $_SESSION['user_blood_group'];
} else {
    $user_blood_group = "";
}

// user weight
if (isset($_POST['user-weight-btn'])) {
    $user_weight = $_POST['user_weight'];
    $update = "UPDATE users SET weight = '$user_weight' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's weight from the database after the update
        $result = mysqli_query($connection, "SELECT weight FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_weight = $row['weight'];
            $_SESSION['user_weight'] = $user_weight;
        }
    }
}

if (isset($_SESSION['user_weight'])) {
    $user_weight = $_SESSION['user_weight'];
} else {
    $user_weight = "";
}

// user height
if (isset($_POST['user-height-btn'])) {
    $user_height = $_POST['user_height'];
    $update = "UPDATE users SET height = '$user_height' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's height from the database after the update
        $result = mysqli_query($connection, "SELECT height FROM users WHERE email = '$userEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $user_height = $row['height'];
            $_SESSION['user_height'] = $user_height;
        }
    }
}

if (isset($_SESSION['user_height'])) {
    $user_height = $_SESSION['user_height'];
} else {
    $user_height = "";
}

// user medical history
if (!isset($_SESSION['user_medical_history'])) {
    $result = mysqli_query($connection, "SELECT history FROM users WHERE email = '$userEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_medical_history'] = $row['history'];
    }
}

// Update user's medical history when form is submitted
if (isset($_POST['user-medical-history-btn'])) {
    $user_medical_history = mysqli_real_escape_string($connection, $_POST['user_medical_history']);
    $update = "UPDATE users SET history = '$user_medical_history' WHERE email = '$userEmail'";
    if(mysqli_query($connection, $update)) {
        $_SESSION['user_medical_history'] = $user_medical_history;
    }
}

// Assign address to variable for use in HTML
$user_medical_history = $_SESSION['user_medical_history'] ?? '';

// user about
// Retrieve user about info if not already set
if (!isset($_SESSION['user_about'])) {
    $result = mysqli_query($connection, "SELECT about FROM users WHERE email = '$userEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_about'] = $row['about'];
    }
}

// Update user about section when form is submitted
if (isset($_POST['user-about-btn'])) {
    $user_about = mysqli_real_escape_string($connection, $_POST['user_about']);
    $update = "UPDATE users SET about = '$user_about' WHERE email = '$userEmail'";
    if (mysqli_query($connection, $update)) {
        $_SESSION['user_about'] = $user_about;
    }
}

// Assign about section to a variable for use in HTML
$user_about = $_SESSION['user_about'] ?? '';

function isFieldFilled($field) {
    return isset($field) && !empty($field);
}

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MedConnect User | Your Profile</title>
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

  .heading-txt {
    display: flex;
    flex-direction: column;
    gap: 1em;
    align-items: center;
    margin-top: 30px;
  }

  .heading-img img {
    width: 100px;
    border-radius: 100px;
    margin-top: 20px;
    cursor: pointer;
  }

  .heading-content {
    display: flex;
    justify-content: center;
    flex-direction: row;
    gap: 2em;
  }

  .heading-txt h1 {
    color: #702c14;
  }

  .heading-txt h3 {
    color: #614124;
  }

  .left-buttons {
    display: flex;
    flex-direction: column;
    cursor: pointer;
    margin-top: 60px;
    margin-left: 100px;
  }

  .wrapper {
    width: 300px;
    height: 80px;
    font-family: Lora;
    font-size: 17px;
    background-color: #B0926A;
    color: white;
    border: 1.5px solid grey;
    box-shadow: 0 2px 2px black;
    cursor: pointer;
  }

  .wrapper-1 {
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }

  .wrapper-5 {
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
  }

  .wrapper:active, .wrapper:focus {
    box-shadow: none;
    border: none;
    background-color: #BCA37F;
  }

  .wrapper:hover {
    background-color: #BCA37F;
    border: none;
  }

  hr {
    color: black;
    width: 0px;
    height: 300px;
    margin-top: 115px;
    border: 0.5px solid grey;
    border-radius: 3px;
  }

  .container-1 {
    display: flex;
    gap: 8em;
  }

  @media screen and (max-width: 1390px) {
    .container-1 {
      gap: 5em;
    }
  }

  @media screen and (max-width: 1201px) {
    .container-1 {
      flex-direction: column;
      gap: 1em;
    }

    .left-buttons {
      flex-direction: row;
      justify-content: center;
      margin-left: 0px;
    }


    .personal-details {
      justify-content: center;
    }

    .contact-details {
      justify-content: center;
    }

    hr {
      height: 0px;
      margin: auto;
    }

    .manage-profile {
      justify-content: center;
    }
  }

  .personal-details {
    margin-top: 70px;
    display: flex;
    gap: 10em;
    background-color: #60a159;
    padding: 20px;
    height: 380px;
    border-radius: 5px;
  }

  .personal-details-name, .personal-details-email, .personal-details-date-joined, .personal-details-password {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .personal-details-1 {
    display: flex;
    flex-direction: column;
    gap: 2em;
  }

  .personal-details-2 {
    display: flex;
    flex-direction: column;
    gap: 2em;
  }

  .user-age-input {
    padding-left: 5px;
  }

  .personal-details-age {
    display: flex;
    flex-direction: column;
    gap: 1.5em;
  }

  .personal-details-gender {
    display: flex;
    flex-direction: column;
    gap: 1.5em;
  }

  .personal-details-nationality {
    display: flex;
    flex-direction: column;
    gap: 1.5em;
  }

  .contact-details {
    margin-top: 50px;
    display: flex;
    gap: 4em;
    background-color: #60a159;
    padding: 20px;
    height: 410px;
    border-radius: 5px;
    margin-left: -50px;
  }

  .contact-details-1 {
    display: flex;
    flex-direction: column;
    gap: 2em;
  }

  .contact-details-phone-number {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .contact-details-address {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .contact-details-blood-group {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .contact-details-weight {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .contact-details-height {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .contact-details-medical-history {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .contact-details-2 {
    display: flex;
    flex-direction: column;
    gap: 1em;
  }

  .user-about-input::placeholder {
    color: black;
  }

  .your-reviews {
    margin-top: 170px;
    display: flex;
    flex-direction: column;
    gap: 2em;
    align-items: center;
    margin-left: 50px;
  }

  .your-reviews button {
    border-radius: 5px;
    box-shadow: 0 1px 1px black;
    padding: 10px 10px;
    background-color: #60a159;
    font-family: Lora;
    font-size: 15px;
    color: white;
    cursor: pointer;
    border: none;
  }

  .your-reviews button:active {
    box-shadow: none;
  }

  .manage-profile {
    display: flex;
    gap: 10em;
    padding: 20px;
    border-radius: 5px;
    margin-left: 280px;
  }

  .manage-profile-div .fa-circle-info, .manage-profile-div .fa-address-card {
    font-size: 30px;
    padding: 10px;
    border-radius: 5px;
    background-color: #B0926A;
    box-shadow: 0 1px 1px grey;
    cursor: pointer;
    border: 1px solid grey;
    color: white;
  }

  .manage-profile-div .fa-circle-info:hover, .manage-profile-div .fa-address-card:hover {
    background-color: #BCA37F;
  }

  .individual {
    background-color: #684414;
    width: 250px;
    margin-left: -100px;
    margin-top: 90px;
    padding: 20px;
    border-radius: 5px;
    color: white;
    text-align: center;
    gap: 2em;
    display: flex;
    flex-direction: column;
  }

  .index-eighth {
    margin: 50px;
  }

  .index-eighth-wrappers {
    display: flex;
    gap: 8em;
  }

  .individual button {
    font-family: Lora;
    height: 50px;
    border-radius: 5px;
    box-shadow: 0 1px 1px black;
    border: none;
    cursor: pointer;
    color: #614124;
    font-size: 15px;
  }

  .first:hover,
  .second:hover,
  .third:hover {
    background-color: #D8D9DA;
  }

  .first:active,
  .second:active,
  .third:active {
    box-shadow: none;
  }

  /* Maintain styles for disabled input fields */
   input[disabled], select[disabled] {
       background-color: white !important;
       color: black !important;
       cursor: not-allowed;
       opacity: 1 !important;
   }

   /* Maintain button styles when disabled */
   button[disabled] {
       background-color: #6499E9 !important;
       color: black !important;
       box-shadow: 0 1px 1px black !important;
       cursor: not-allowed;
       opacity: 1 !important;
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

  <div class="heading-content">
    <div class="heading-img" id="user-heading-img">
      <img src="medconnect_images/blank_profile_pic.png" alt="profile picture" name="user_heading_img">
    </div>
    <div class="heading-txt">
      <h1>Your Profile</h1>
      <h3>Welcome, <?php echo $userName; ?></h3>
    </div>
  </div>

  <div class="container-1">
    <div class="left-buttons">
      <button id="personal-details-wrapper" class="wrapper wrapper-1"><i style="margin-right: 10px;" class="fa-solid fa-circle-info"></i>Personal Details</button>
      <button id="contact-details-wrapper" class="wrapper wrapper-2"><i style="margin-right: 10px;" class="fa-solid fa-address-card"></i>Contact and About</button>
      <button id="your-reviews-wrapper" class="wrapper wrapper-3"><i style="margin-right: 10px;" class="fa-solid fa-star"></i>Your Reviews</button>
      <button id="manage-profile-wrapper" class="wrapper wrapper-4"><i style="margin-right: 10px;" class="fa-solid fa-list-check"></i>Manage Profile</button>
      <button id="help-and-more-wrapper" class="wrapper wrapper-5"><i style="margin-right: 10px;" class="fa-solid fa-question"></i>Help and More</button>
    </div>
    <hr>
    <div class="personal-details" id="personal-details">
      <div class="personal-details-1">
        <div class="personal-details-name">
          <h2 style="color: white;">Name</h2>
          <h3 style="background-color: white; padding: 8px; width: 250px; border-radius: 5px;"><?php echo $userName; ?></h3>
        </div>
        <div class="personal-details-email">
          <h2 style="color: white;">Email Address</h2>
          <h3 style="background-color: white; padding: 8px; border-radius: 5px;"><?php echo $userEmail; ?></h3>
        </div>
        <div class="personal-details-date-joined">
          <h2 style="color: white;">Date Joined</h2>
          <h3 id="user_date_joined" style="background-color: white; padding: 8px; border-radius: 5px;"><?php echo $userDateJoined; ?></h3>
          <button style="display: none; font-family: Lora; font-size: 20px; border-radius: 5px; margin-top: -50px; margin-left: 200px; width: 40px; border: none; cursor: pointer;" type="button" onclick="togglePassword('user_password')"><i class="fa-solid fa-eye"></i></button>
        </div>
      </div>
      <div class="personal-details-2">
        <div class="personal-details-age">
          <div>
            <h2 style="color: white;">Age</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <input class="user-age-input" type="number" name="user_age" style="width: 100px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($user_age) ? 'disabled' : ''; ?>>
            <button <?php echo isFieldFilled($user_age) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-age-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 57px; margin-left: 10px;"><?php echo $user_age; ?></h3>
        </div>
        <div class="personal-details-gender">
          <div>
            <h2 style="color: white;">Gender</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <select name="user_gender" id="user_gender" style="font-family: Lora; font-size: 15px; width: 100px; border-radius: 5px; outline: none;" <?php echo isFieldFilled($user_gender) ? 'disabled' : ''; ?>>
              <option value="blank"></option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <button <?php echo isFieldFilled($user_gender) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-gender-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 57px; margin-left: 10px;"><?php echo $user_gender; ?></h3>
        </div>
        <div class="personal-details-nationality">
          <div>
            <h2 style="color: white;">Country/Nation</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <input class="user-age-input" type="text" name="user_nationality" style="width: 150px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($user_nationality) ? 'disabled' : ''; ?>>
            <button <?php echo isFieldFilled($user_nationality) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-nationality-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 57px; margin-left: 10px;"><?php echo $user_nationality; ?></h3>
        </div>
      </div>
    </div>

    <div style="display: none;" class="contact-details" id="contact-details">
      <div class="contact-details-1">
        <div class="contact-details-phone-number">
          <div>
            <h2 style="color: white;">Phone Number</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <input class="user-phone-input" type="number" name="user_phone_number" style="padding: 5px; width: 250px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($user_phone_number) ? 'disabled' : ''; ?>>
            <button <?php echo isFieldFilled($user_phone_number) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-phone-number-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 49px; margin-left: 10px;"><?php echo $user_phone_number; ?></h3>
        </div>
        <div class="contact-details-address">
          <div>
            <h2 style="color: white;">Address</h2>
          </div>
          <?php if (!empty($user_address)): ?>
              <!-- Show button if address exists -->
          <button id="showAddressBtn" style="background-color: #6499E9; width: 130px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" onclick="showAddressPopup()">Your Address</button>
          <?php else: ?>
              <!-- Show input field if no address exists -->
              <form style="display: flex; gap: 1em;" method="post">
                  <input class="user-address-input" type="text" name="user_address" style="width: 250px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
                  <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-address-btn">Add</button>
              </form>
          <?php endif; ?>
          </div>
        <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;"></div>
        <div id="addressPopup" style="text-align: center; display: none; position: fixed; width: 300px; height: 300px; padding: 10px; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); z-index: 1000;">
          <h2>Your Address</h2>
          <p style="padding-top: 10px;" id="addressContent"><?php echo htmlspecialchars($user_address); ?></p>
          <button style="padding: 10px; margin-top: 20px; border: none; background-color: #60a159; color: white; border-radius: 5px; cursor: pointer;" onclick="closeAddressPopup()">Close</button>
        </div>
        <div class="contact-details-about">
          <h2 style="color: white; margin-top: -10px;">About</h2>

          <?php if (!empty($user_about)): ?>
              <!-- Display the about content in a scrollable div -->
              <div style="width: 330px;
                          height: 80px;
                          padding: 10px;
                          margin-top: 25px;
                          background-color: white;
                          font-family: Lora;
                          font-size: 16px;
                          color: black;
                          border-radius: 5px;
                          overflow-y: auto;
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                  <?php echo nl2br(htmlspecialchars($user_about)); ?>
              </div>
          <?php else: ?>
              <!-- Show input field if no about info exists -->
              <form style="display: flex; flex-direction: column; gap: 1em;" method="post">
                  <textarea class="user-about-input" type="text" name="user_about"
                            style="width: 330px; height: 80px; resize: none; padding: 10px; font-family: Lora; outline: none; border: none; border-radius: 5px; margin-top: 10px;"
                            placeholder="Write a little bit about yourself"></textarea>
                  <button style="margin: auto; background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                          type="submit" name="user-about-btn">Add</button>
              </form>
          <?php endif; ?>
      </div>
      </div>
      <div class="contact-details-2">
        <div class="contact-details-blood-group">
          <div>
            <h2 style="color: white;">Blood Group</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <select name="user_blood_group" id="user_blood_group" style="font-family: Lora; font-size: 15px; width: 150px; border-radius: 5px; outline: none;" <?php echo isFieldFilled($user_blood_group) ? 'disabled' : ''; ?>>
              <option value="blank"></option>
              <option value="A+">A+</option>
              <option value="B+">B+</option>
              <option value="AB+">AB+</option>
              <option value="A-">A-</option>
              <option value="B-">B-</option>
              <option value="AB-">AB-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
            </select>
            <button <?php echo isFieldFilled($user_blood_group) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-blood-group-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 49px; margin-left: 10px;"><?php echo $user_blood_group; ?></h3>
        </div>
        <div class="contact-details-weight">
          <div>
            <h2 style="color: white;">Weight (kg)</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <input class="user-weight-input" type="number" name="user_weight" style="width: 150px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($user_weight) ? 'disabled' : ''; ?>>
            <button <?php echo isFieldFilled($user_weight) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-weight-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 49px; margin-left: 10px;"><?php echo $user_weight; ?></h3>
        </div>
        <div class="contact-details-height">
          <div>
            <h2 style="color: white;">Height (cm)</h2>
          </div>
          <form style="display: flex; gap: 1em;" method="post">
            <input class="user-height-input" type="number" name="user_height" style="width: 150px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($user_height) ? 'disabled' : ''; ?>>
            <button <?php echo isFieldFilled($user_height) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-height-btn">Add</button>
          </form>
          <h3 style="position: absolute; margin-top: 49px; margin-left: 10px;"><?php echo $user_height; ?></h3>
        </div>
        <div class="contact-details-medical-history">
          <div>
            <h2 style="color: white;">Medical History</h2>
          </div>
          <?php if (!empty($user_medical_history)): ?>
              <!-- Show button if medical history exists -->
          <button id="showMedicalHistoryBtn" style="background-color: #6499E9; width: 150px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" onclick="showMedicalHistoryPopup()">Your Medical History</button>
          <?php else: ?>
              <!-- Show input field if no medical history exists -->
              <form style="display: flex; gap: 1em;" method="post">
                  <input class="user-medical-history-input" type="text" name="user_medical_history" style="width: 250px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
                  <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="user-medical-history-btn">Add</button>
              </form>
          <?php endif; ?>
          <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;"></div>
          <div id="medicalHistoryPopup" style="text-align: center; display: none; width: 300px; height: 300px; padding: 10px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); z-index: 1000;">
            <h2>Medical History</h2>
            <p style="padding-top: 10px;" id="medicalHistoryContent"><?php echo htmlspecialchars($user_medical_history); ?></p>
            <button style="padding: 10px; margin-top: 20px; border: none; background-color: #60a159; color: white; border-radius: 5px; cursor: pointer;" onclick="closeMedicalHistoryPopup()">Close</button>
          </div>
        </div>

        <script>
          document.addEventListener("DOMContentLoaded", function() {
              var medicalHistoryBtn = document.getElementById("showMedicalHistoryBtn");
              var medicalHistoryForm = document.getElementById("medicalHistoryForm");
              var contactDetails = document.querySelector(".contact-details");

              if (medicalHistoryBtn) {
                  // If the button is displayed, adjust the margin
                  contactDetails.style.marginLeft = "0px";
              } else if (medicalHistoryForm) {
                  // Reset margin if input field is shown
                  contactDetails.style.marginLeft = "0px";
              }
          });
        </script>

      </div>
    </div>

    <div style="display: none;" class="your-reviews" id="your-reviews">
      <h2>Your reviews on consultants will show here.</h2>
      <h3>Make a consultation to give your first review.</h3>
      <button type="button" name="first_review_btn">Give your first review today</button>
    </div>

    <div style="display: none;" class="manage-profile" id="manage-profile">
      <div class="manage-profile-div" style="display: flex; margin-top: -20px;">
        <i id="manage-profile-top" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px; background-color: #BCA37F;" class="fa-solid fa-circle-info"></i>
        <i id="manage-profile-next" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;" class="fa-solid fa-address-card"></i>
      </div>
      <div style="margin-left: -570px; gap: 5em;" class="personal-details" id="manage-personal-details">
        <div class="personal-details-1" style="display: flex; gap: 2.5em;">
          <div class="personal-details-name">
            <h2 style="color: white;">Name</h2>
            <div style="display: flex; gap: 2em;">
              <input id="user_name" style="background-color: white; padding: 8px; border-radius: 5px; width: 250px; border: none; font-family: Lora; outline: none;"></input>
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-name-btn">Change</button>
            </div>
          </div>
          <div class="personal-details-email">
            <h2 style="color: white;">Email Address</h2>
            <h3 style="background-color: white; width: 250px; padding: 8px; border-radius: 5px;"><?php echo $userEmail; ?></h3>
          </div>
          <div class="personal-details-password">
            <h2 style="color: white;">Password</h2>
            <div style="display: flex; gap: 2em;">
              <input id="user_password" style="width: 250px; background-color: white; padding: 8px; border-radius: 5px; border: none; font-family: Lora; outline: none;"></input>
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-age-btn">Change</button>
            </div>
            <button style="font-family: Lora; font-size: 15px; border-radius: 5px; margin-top: -43px; margin-left: 200px; width: 40px; border: none; cursor: pointer;" type="button" onclick="togglePassword('user_password')"><i class="fa-solid fa-eye"></i></button>
          </div>
        </div>
        <div class="personal-details-2" style="display: flex; gap: 2.2em;">
          <div class="personal-details-age">
            <div>
              <h2 style="color: white;">Age</h2>
            </div>
            <form style="display: flex; gap: 1em;" method="post">
              <input class="user-age-input" type="number" name="user_age" style="width: 100px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-age-btn">Change</button>
            </form>
          </div>
          <div class="personal-details-gender">
            <div>
              <h2 style="color: white;">Gender</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <select name="user_gender" id="user_gender" style="font-family: Lora; font-size: 15px; width: 100px; border-radius: 5px; outline: none;">
                <option value="blank"></option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-gender-btn">Change</button>
            </div>
          </div>
          <div class="personal-details-nationality">
            <div>
              <h2 style="color: white;">Country/Nation</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <input class="user-age-input" type="text" name="user_nationality" style="width: 150px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-nationality-btn">Change</button>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-left: -600px; display: none;" class="contact-details" id="manage-contact-details">
        <div class="contact-details-1">
          <div class="contact-details-phone-number">
            <div>
              <h2 style="color: white;">Phone Number</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <input class="user-phone-input" type="number" name="user_phone_number" style="padding: 5px; width: 250px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-phone-number-btn">Change</button>
            </div>
          </div>
          <div class="contact-details-address">
            <div>
              <h2 style="color: white;">Address</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <input class="user-address-input" type="text" name="user_address" style="width: 250px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-address-btn">Change</button>
            </div>
            </div>
          <div class="contact-details-about" style="display: flex; flex-direction: column; gap: 1em;">
            <h2 style="color: white;">About</h2>
            <textarea class="user-about-input" type="text" name="user_about" style="width: 330px; height: 60px; resize: none; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;" placeholder="Change your bio"></textarea>
            <button style="margin: auto; background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-about-btn">Change</button>
          </div>
        </div>
        <div class="contact-details-2">
          <div class="contact-details-blood-group">
            <div>
              <h2 style="color: white;">Blood Group</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <select name="user_blood_group" id="user_blood_group" style="font-family: Lora; font-size: 15px; width: 150px; border-radius: 5px; outline: none;">
                <option value="blank"></option>
                <option value="A+">A+</option>
                <option value="B+">B+</option>
                <option value="AB+">AB+</option>
                <option value="A-">A-</option>
                <option value="B-">B-</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
              </select>
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-blood-group-btn">Change</button>
            </div>
          </div>
          <div class="contact-details-weight">
            <div>
              <h2 style="color: white;">Weight (kg)</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <input class="user-weight-input" type="number" name="user_weight" style="width: 150px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-weight-btn">Change</button>
            </div>
          </div>
          <div class="contact-details-height">
            <div>
              <h2 style="color: white;">Height (cm)</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <input class="user-height-input" type="number" name="user_height" style="width: 150px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-height-btn">Change</button>
            </div>
          </div>
          <div class="contact-details-medical-history">
            <div>
              <h2 style="color: white;">Medical History</h2>
            </div>
            <div style="display: flex; gap: 1em;">
              <input class="user-medical-history-input" type="text" name="user_medical_history" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
              <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-user-medical-history-btn">Change</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="display: none;" class="index-eighth" id="help-and-more">
      <div class="index-eighth-wrappers">
        <div class="individual individual-1">
          <center><i style="background-color: white; color: #684414; padding: 20px; border-radius: 30px; width: 20px; font-size: 20px; margin-top: -50px;" class="fa-solid fa-circle-info"></i></center>
          <h2>About Us</h2>
          <button class="first">About Us</button>
        </div>
        <div class="individual individual-2">
          <center><i style="background-color: white; color: #684414; padding: 20px; border-radius: 30px; width: 20px; font-size: 20px; margin-top: -50px;" class="fa-solid fa-envelope"></i></center>
          <h2>Contact</h2>
          <button class="second">Contact</button>
        </div>
        <div class="individual individual-3">
          <center><i style="background-color: white; color: #684414; padding: 20px; border-radius: 30px; width: 20px; font-size: 20px; margin-top: -50px;" class="fa-solid fa-question"></i></center>
          <h2>FAQ</h2>
          <button class="third">FAQ</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">

  function togglePassword(elementId) {
    const passwordElement = document.getElementById('user_password');
    if (passwordElement) {
        const currentContent = passwordElement.textContent;
        const maskedText = '*'.repeat(currentContent.length);

        // Check if the current content is already masked
        if (currentContent === maskedText) {
            // If masked, reveal the actual text
            passwordElement.textContent = passwordElement.getAttribute('data-original-text');
        } else {
            // If not masked, store the original text and then mask it
            passwordElement.setAttribute('data-original-text', currentContent);
            passwordElement.textContent = maskedText;
        }
    }
  }

  document.getElementById('contact-details-wrapper').addEventListener('click', () => {
    document.getElementById('personal-details').style.display = 'none';
    document.getElementById('your-reviews').style.display = 'none';
    document.getElementById('contact-details').style.display = 'flex';
    document.getElementById('manage-profile').style.display = 'none';
    document.getElementById('help-and-more').style.display = 'none';
  });

  document.getElementById('personal-details-wrapper').addEventListener('click', () => {
    document.getElementById('personal-details').style.display = 'flex';
    document.getElementById('contact-details').style.display = 'none';
    document.getElementById('your-reviews').style.display = 'none';
    document.getElementById('manage-profile').style.display = 'none';
    document.getElementById('help-and-more').style.display = 'none';
  });

  document.getElementById('your-reviews-wrapper').addEventListener('click', () => {
    document.getElementById('personal-details').style.display = 'none';
    document.getElementById('contact-details').style.display = 'none';
    document.getElementById('your-reviews').style.display = 'flex';
    document.getElementById('manage-profile').style.display = 'none';
    document.getElementById('help-and-more').style.display = 'none';
  });

  document.getElementById('manage-profile-wrapper').addEventListener('click', () => {
    document.getElementById('personal-details').style.display = 'none';
    document.getElementById('contact-details').style.display = 'none';
    document.getElementById('your-reviews').style.display = 'none';
    document.getElementById('help-and-more').style.display = 'none';
    document.getElementById('manage-profile').style.display = 'flex';
  });

  document.getElementById('help-and-more-wrapper').addEventListener('click', () => {
    document.getElementById('personal-details').style.display = 'none';
    document.getElementById('contact-details').style.display = 'none';
    document.getElementById('your-reviews').style.display = 'none';
    document.getElementById('manage-profile').style.display = 'none';
    document.getElementById('help-and-more').style.display = 'inline';
  });

    function showAddressPopup() {
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('addressPopup').style.display = 'block';
    }

    function closeAddressPopup() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('addressPopup').style.display = 'none';
    }

    function showMedicalHistoryPopup() {
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('medicalHistoryPopup').style.display = 'block';
    }

    function closeMedicalHistoryPopup() {
        document.getElementById('medicalHistoryPopup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
  </script>

  <script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
  </body>
</html>
