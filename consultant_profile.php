<?php

session_start();

$csName = $_SESSION['signUpName'] ?? 'Guest';
$csEmail = $_SESSION['signUpEmail'] ?? 'No email';
$csPassword = $_SESSION['signUpPassword'] ?? 'No password';

// Database connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database);
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($csEmail === 'No email' || $csPassword === 'No password') {
    $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($connection, $csEmail) . "'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Update session variables to reflect correct email and password
        $_SESSION['signUpEmail'] = $row['email'];
        $_SESSION['signUpPassword'] = $row['password'];

        // Refresh variables
        $csEmail = $_SESSION['signUpEmail'];
        $csPassword = $_SESSION['signUpPassword'];
    }
}

if (!isset($_SESSION['signUpBtn'])) {
  header("Location:login.php"); // Not Logged In (Redirect Back to Login/Sign Up Page)
} elseif (isset($_SESSION['signUpBtn']) && !isset($_SESSION['role'])) {
  header("Location:role.php");
} elseif ($_SESSION['role'] === 'user') {
  header("Location:user_index.php");
}

// user date joined
$query = "SELECT date_joined FROM users WHERE email = '$csEmail'";

$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $csDateJoined = $row['date_joined']; // This will contain the actual date
} else {
    $csDateJoined = 'Unknown';
}

// consultant age
if (isset($_POST['consultant-age-btn'])) {
    $cs_age = $_POST['consultant_age'];
    $update = "UPDATE users SET age = '$cs_age' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's age from the database after the update
        $result = mysqli_query($connection, "SELECT age FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_age = $row['age'];
            $_SESSION['cs_age'] = $cs_age;
        }
    }
}

if (isset($_SESSION['cs_age'])) {
    $cs_age = $_SESSION['cs_age'];
} else {
    $cs_age = "";
}

// consultant gender
if (isset($_POST['consultant-gender-btn'])) {
    $cs_gender = $_POST['consultant_gender'];
    $update = "UPDATE users SET gender = '$cs_gender' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's gender from the database after the update
        $result = mysqli_query($connection, "SELECT gender FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_gender = $row['gender'];
            $_SESSION['cs_gender'] = $cs_gender;
        }
    }
}

if (isset($_SESSION['cs_gender'])) {
    $cs_gender = $_SESSION['cs_gender'];
} else {
    $cs_gender = "";
}

// consultant nationality
if (isset($_POST['consultant-nationality-btn'])) {
    $cs_nationality = $_POST['consultant_nationality'];
    $update = "UPDATE users SET country = '$cs_nationality' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's nationality from the database after the update
        $result = mysqli_query($connection, "SELECT country FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_nationality = $row['country'];
            $_SESSION['cs_nationality'] = $cs_nationality;
        }
    }
}

if (isset($_SESSION['cs_nationality'])) {
    $cs_nationality = $_SESSION['cs_nationality'];
} else {
    $cs_nationality = "";
}

// consultant phone number
if (isset($_POST['consultant-phone-number-btn'])) {
    $cs_phone_number = $_POST['consultant_phone_number'];
    $update = "UPDATE users SET phone = '$cs_phone_number' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's nationality from the database after the update
        $result = mysqli_query($connection, "SELECT phone FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_phone_number = $row['phone'];
            $_SESSION['cs_phone_number'] = $cs_phone_number;
        }
    }
}

if (isset($_SESSION['cs_phone_number'])) {
    $cs_phone_number = $_SESSION['cs_phone_number'];
} else {
    $cs_phone_number = "";
}

// consultant services
if (!isset($_SESSION['cs_services'])) {
    $result = mysqli_query($connection, "SELECT services FROM users WHERE email = '$csEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cs_services'] = $row['services'];
    }
}

// Update consultant's services when form is submitted
if (isset($_POST['consultant-services-btn'])) {
    if (isset($_POST['consultant_services']) && is_array($_POST['consultant_services'])) {
        // Convert the array into a comma-separated string
        $cs_services = implode(", ", $_POST['consultant_services']);

        // Escape the string to prevent SQL injection
        $cs_services = mysqli_real_escape_string($connection, $cs_services);

        // Update the database
        $update = "UPDATE users SET services = '$cs_services' WHERE email = '$csEmail'";
        if(mysqli_query($connection, $update)) {
            $_SESSION['cs_services'] = $cs_services;
        }
    }
}

// Assign services to a variable for use in HTML
$cs_services = $_SESSION['cs_services'] ?? '';

// consultant offline availability
if (isset($_POST['consultant-availability-btn'])) {
    $cs_offline = $_POST['consultant_availability'];
    $update = "UPDATE users SET offline = '$cs_offline' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the user's offline availability from the database after the update
        $result = mysqli_query($connection, "SELECT offline FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_offline = $row['offline'];
            $_SESSION['cs_offline'] = $cs_offline;
        }
    }
}

if (isset($_SESSION['cs_offline'])) {
    $cs_offline = $_SESSION['cs_offline'];
} else {
    $cs_offline = "";
}

// consultant city/locality
if (isset($_POST['consultant-locality-btn'])) {
    $cs_locality = $_POST['consultant_locality'];
    $update = "UPDATE users SET city = '$cs_locality' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the consultant's city/locality from the database after the update
        $result = mysqli_query($connection, "SELECT city FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_locality = $row['city'];
            $_SESSION['cs_locality'] = $cs_locality;
        }
    }
}

if (isset($_SESSION['cs_locality'])) {
    $cs_locality = $_SESSION['cs_locality'];
} else {
    $cs_locality = "";
}

// consultant hospital
if (isset($_POST['consultant-hospital-btn'])) {
    $cs_hospital = $_POST['consultant_hospital'];
    $update = "UPDATE users SET hospital = '$cs_hospital' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        // Query the consultant's hospital from the database after the update
        $result = mysqli_query($connection, "SELECT hospital FROM users WHERE email = '$csEmail'");
        if ($row = mysqli_fetch_assoc($result)) {
            $cs_hospital = $row['hospital'];
            $_SESSION['cs_hospital'] = $cs_hospital;
        }
    }
}

if (isset($_SESSION['cs_hospital'])) {
    $cs_hospital = $_SESSION['cs_hospital'];
} else {
    $cs_hospital = "";
}

// Retrieve consultant education if not already set
if (!isset($_SESSION['cs_education'])) {
    $result = mysqli_query($connection, "SELECT education FROM users WHERE email = '$csEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cs_education'] = $row['education'];
    }
}

// Update consultant's education when form is submitted
if (isset($_POST['consultant-education-btn'])) {
    $cs_education = mysqli_real_escape_string($connection, $_POST['consultant_education']);
    $update = "UPDATE users SET education = '$cs_education' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        $_SESSION['cs_education'] = $cs_education;
    }
}

// Assign education to a variable for use in HTML
$cs_education = $_SESSION['cs_education'] ?? '';

// consultant about
// Retrieve user about info if not already set
if (!isset($_SESSION['cs_about'])) {
    $result = mysqli_query($connection, "SELECT about FROM users WHERE email = '$csEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cs_about'] = $row['about'];
    }
}

// Update consultant about section when form is submitted
if (isset($_POST['consultant-about-btn'])) {
    $cs_about = mysqli_real_escape_string($connection, $_POST['consultant_about']);
    $update = "UPDATE users SET about = '$cs_about' WHERE email = '$csEmail'";
    if (mysqli_query($connection, $update)) {
        $_SESSION['cs_about'] = $cs_about;
    }
}

// Assign about section to a variable for use in HTML
$cs_about = $_SESSION['cs_about'] ?? '';

function isFieldFilled($field) {
    return isset($field) && !empty($field);
}

// Profile Picture PHP

$logged_in_email = $_SESSION['signUpEmail'] ?? null;

// Fetch profile picture from the database
$stmt = $connection->prepare("SELECT profile_pic FROM users WHERE email = ?");
$stmt->bind_param("s", $logged_in_email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Determine the profile picture path
$profilePic = (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) ? $row['profile_pic'] : "medconnect_images/blank_profile_pic.png";

// Store in session for persistence
$_SESSION['profile_pic'] = $profilePic;

// consultant specializations
if (!isset($_SESSION['cs_specializations'])) {
    $result = mysqli_query($connection, "SELECT specializations FROM users WHERE email = '$csEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cs_specializations'] = $row['specializations'];
    }
}

// Update consultant's specializations when form is submitted
if (isset($_POST['consultant-specializations-btn'])) {
    if (isset($_POST['consultant_specializations']) && is_array($_POST['consultant_specializations'])) {
        // Convert the array into a comma-separated string
        $cs_specializations = implode(", ", $_POST['consultant_specializations']);

        // Escape the string to prevent SQL injection
        $cs_specializations = mysqli_real_escape_string($connection, $cs_specializations);

        // Update the database
        $update = "UPDATE users SET specializations = '$cs_specializations' WHERE email = '$csEmail'";
        if(mysqli_query($connection, $update)) {
            $_SESSION['cs_specializations'] = $cs_specializations;
        }
    }
}

// Assign specializations to a variable for use in HTML
$cs_specializations = $_SESSION['cs_specializations'] ?? '';

// Retrieve consultant awards if not already set
if (!isset($_SESSION['cs_awards'])) {
    $result = mysqli_query($connection, "SELECT awards FROM users WHERE email = '$csEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cs_awards'] = $row['awards'];
    }
}

// Consultant awards
// Update consultant's awards when form is submitted
if (isset($_POST['consultant-awards-btn'])) {
    $cs_awards = mysqli_real_escape_string($connection, $_POST['consultant_awards']);
    $update = "UPDATE users SET awards = '$cs_awards' WHERE email = '$csEmail'";
    if(mysqli_query($connection, $update)) {
        $_SESSION['cs_awards'] = $cs_awards;
    }
}

// Assign awards to a variable for use in HTML
$cs_awards = $_SESSION['cs_awards'] ?? '';

// consultant experience
// Retrieve user experience info if not already set
if (!isset($_SESSION['cs_experience'])) {
    $result = mysqli_query($connection, "SELECT experience FROM users WHERE email = '$csEmail'");
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cs_experience'] = $row['experience'];
    }
}

// Update consultant experience section when form is submitted
if (isset($_POST['consultant-experience-btn'])) {
    $cs_experience = mysqli_real_escape_string($connection, $_POST['consultant_experience']);
    $update = "UPDATE users SET experience = '$cs_experience' WHERE email = '$csEmail'";
    if (mysqli_query($connection, $update)) {
        $_SESSION['cs_experience'] = $cs_experience;
    }
}

// Assign experience section to a variable for use in HTML
$cs_experience = $_SESSION['cs_experience'] ?? '';

 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>MedConnect Consultant | Your Profile</title>
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
     height: 100px;
     object-fit: cover;
     border-radius: 50%;
     margin-top: 20px;
     cursor: pointer;
     border: 2px solid black;
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

   #user_password::placeholder {
     color: black;
   }

   .consultant-experience::placeholder {
     color: black;
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
     <div style="display: flex; flex-direction: column; gap: 0.2em;" onclick="location.href='consultant_index.php'">
       <h1 class="head">MedConnect</h1>
       <h4 style="margin-left: 83px; color: #614124;">For Consultants</h4>
     </div>

     <ul class="nav-links">
       <div class="menu">
         <li><h3>Consult</h3></li>
         <li><h3>Feed</h3></li>
         <li><h3>Resources</h3></li>
         <li><h3>About</h3></li>
         <li><h3 onclick="location.href='consultant_profile.php'" class="profile-btn" style="color: white;">Your Profile</h3></li>
       </div>
     </ul>
   </nav>

   <div class="heading-content">
       <div class="heading-img" id="cs-heading-img">
         <!-- Profile Picture (Clickable) -->
         <img id="profile-pic"
              src="<?php echo $_SESSION['profile_pic']; ?>"
              alt="Profile Picture"
              style="cursor: pointer;" onclick="triggerFileUpload()">

         <!-- Hidden File Input (Fixing the ID) -->
         <input type="file" id="file-input" style="display: none;" accept="image/*">
       </div>

       <div class="heading-txt">
           <h1>Your Profile</h1>
           <h3>Welcome, Dr. <?php echo $csName; ?></h3>
       </div>
   </div>

   <div class="container-1">
     <div class="left-buttons">
       <button id="personal-details-wrapper" class="wrapper wrapper-1"><i style="margin-right: 10px;" class="fa-solid fa-circle-info"></i>Personal Details</button>
       <button id="contact-details-wrapper" class="wrapper wrapper-2"><i style="margin-right: 10px;" class="fa-solid fa-address-card"></i>Contact and About</button>
       <button id="your-reviews-wrapper" class="wrapper wrapper-3"><i style="margin-right: 10px;" class="fa-solid fa-star"></i>Your Given Reviews</button>
       <button id="expertise-and-specialization-wrapper" class="wrapper wrapper-4"><i style="margin-right: 10px;" class="fa-solid fa-user-doctor"></i>Expertise & Specialization</button>
       <button id="manage-profile-wrapper" class="wrapper wrapper-5"><i style="margin-right: 10px;" class="fa-solid fa-list-check"></i>Manage Profile</button>
     </div>
     <hr>
     <div class="personal-details" id="personal-details">
       <div class="personal-details-1">
         <div class="personal-details-name">
           <h2 style="color: white;">Name</h2>
           <h3 style="background-color: white; padding: 8px; width: 250px; border-radius: 5px;"><?php echo $csName; ?></h3>
         </div>
         <div class="personal-details-email">
           <h2 style="color: white;">Email Address</h2>
           <h3 style="background-color: white; padding: 8px; border-radius: 5px;"><?php echo $csEmail; ?></h3>
         </div>
         <div class="personal-details-date-joined">
           <h2 style="color: white;">Date Joined</h2>
           <h3 id="user_date_joined" style="background-color: white; padding: 8px; border-radius: 5px;"><?php echo $csDateJoined; ?></h3>
           <button style="display: none; font-family: Lora; font-size: 20px; border-radius: 5px; margin-top: -50px; margin-left: 200px; width: 40px; border: none; cursor: pointer;" type="button" onclick="togglePassword('user_password')"><i class="fa-solid fa-eye"></i></button>
         </div>
       </div>
       <div class="personal-details-2">
         <div class="personal-details-age">
           <div>
             <h2 style="color: white;">Age</h2>
           </div>
           <form style="display: flex; gap: 1em;" method="post">
             <input class="user-age-input" type="number" name="consultant_age" style="width: 100px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($cs_age) ? 'disabled' : ''; ?>>
             <button <?php echo isFieldFilled($cs_age) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-age-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 57px; margin-left: 10px;"><?php echo $cs_age; ?></h3>
         </div>
         <div class="personal-details-gender">
           <div>
             <h2 style="color: white;">Gender</h2>
           </div>
           <form style="display: flex; gap: 1em;" method="post">
             <select name="consultant_gender" id="user_gender" style="font-family: Lora; font-size: 15px; width: 100px; border-radius: 5px; outline: none;" <?php echo isFieldFilled($cs_gender) ? 'disabled' : ''; ?>>
               <option value="blank"></option>
               <option value="male">Male</option>
               <option value="female">Female</option>
               <option value="other">Other</option>
             </select>
             <button <?php echo isFieldFilled($cs_gender) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-gender-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 57px; margin-left: 10px;"><?php echo $cs_gender; ?></h3>
         </div>
         <div class="personal-details-nationality">
           <div>
             <h2 style="color: white;">Country/Nation</h2>
           </div>
           <form style="display: flex; gap: 1em;" method="post">
             <input class="user-age-input" type="text" name="consultant_nationality" style="width: 150px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($cs_nationality) ? 'disabled' : ''; ?>>
             <button <?php echo isFieldFilled($cs_nationality) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-nationality-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 57px; margin-left: 10px;"><?php echo $cs_nationality; ?></h3>
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
             <input class="user-phone-input" type="number" name="consultant_phone_number" style="padding: 5px; width: 250px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($cs_phone_number) ? 'disabled' : ''; ?>>
             <button <?php echo isFieldFilled($cs_phone_number) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-phone-number-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 50px; margin-left: 10px;"><?php echo $cs_phone_number; ?></h3>
         </div>
         <div class="contact-details-address">
           <div>
             <h2 style="color: white;">Services</h2>
           </div>
           <?php if (!empty($cs_services)): ?>
               <!-- Show button if services exist -->
               <button id="showServicesPopupBtn" style="background-color: #6499E9; width: 130px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" onclick="showServicesPopup()">Your Services</button>
           <?php else: ?>
               <!-- Show input field if no services exist -->
               <form style="display: flex; gap: 1em;" method="post">
                   <select class="user-address-input" type="text" name="consultant_services[]" multiple style="width: 250px; height: 40px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
                      <option value="general_checkup">General Check-Up</option>
                      <option value="flu_shot">Flu Shot Administration</option>
                      <option value="bp_screening">Blood Pressure Screening</option>
                      <option value="cholesterol_screening">Cholesterol Screening</option>
                      <option value="diabetes_screening">Diabetes Screening</option>
                      <option value="heart_health">Heart Health Counseling</option>
                      <option value="weight_mgmt">Weight Management Program</option>
                      <option value="nutrition_diet">Nutrition &amp; Diet Consultation</option>
                      <option value="stress_mgmt">Stress Management Session</option>
                      <option value="sleep_hygiene">Sleep Hygiene Counseling</option>
                      <option value="smoking_cessation">Smoking Cessation Assistance</option>
                      <option value="allergy_testing">Allergy Testing &amp; Advice</option>
                      <option value="immun_updates">Immunization Updates (Tetanus, MMR)</option>
                      <option value="women_health">Women’s Health Consultation</option>
                      <option value="men_health">Men’s Health Consultation</option>
                      <option value="pediatric_wellness">Pediatric Wellness Check</option>
                      <option value="adolescent_health">Adolescent Health Counseling</option>
                      <option value="family_planning">Family Planning Advice</option>
                      <option value="travel_vaccinations">Travel Health &amp; Vaccinations</option>
                      <option value="routine_blood_work">Routine Blood Work Ordering</option>
                      <option value="thyroid_counseling">Thyroid Function Test &amp; Counseling</option>
                      <option value="vision_screening">Vision Screening</option>
                      <option value="hearing_screening">Hearing Screening</option>
                      <option value="minor_ailments">Minor Ailments (Cold, Cough, Fever)</option>
                      <option value="migraine_management">Migraine &amp; Headache Management</option>
                      <option value="asthma_ed">Asthma Management &amp; Education</option>
                      <option value="ecg_counseling">ECG Interpretation &amp; Counseling</option>
                      <option value="wound_care">Basic Wound Care &amp; Dressing</option>
                      <option value="pain_consult">Pain Management Consultation</option>
                      <option value="preop_evaluation">Pre-Operative Evaluation</option>
                      <option value="postop_followup">Post-Operative Follow-Up</option>
                      <option value="chronic_hypertension">Chronic Disease Management (Hypertension)</option>
                      <option value="medication_review">Medication Refill &amp; Review</option>
                      <option value="lab_interpretation">Lab Result Interpretation</option>
                      <option value="referral_coordination">Referral Coordination (Specialist &amp; Hospital)</option>
                      <option value="mental_health_screen">Mental Health Screening (Depression, Anxiety)</option>
                      <option value="stress_ekg">Stress EKG Coordination</option>
                      <option value="spirometry_pft">Spirometry &amp; Pulmonary Function Tests</option>
                      <option value="weightloss_support">Weight-Loss Counseling &amp; Support</option>
                      <option value="otc_advice">Over-The-Counter Medication Advice</option>
                      <option value="seasonal_allergy">Seasonal Allergy Management</option>
                      <option value="skin_derma_referral">Skin Rash &amp; Dermatological Referral</option>
                      <option value="lifestyle_mod">Lifestyle Modification Guidance</option>
                      <option value="bone_density">Bone Density Screening Coordination</option>
                      <option value="joint_arthritis">Joint Pain &amp; Arthritis Counseling</option>
                      <option value="acid_reflux">Heartburn &amp; Acid Reflux Management</option>
                      <option value="tobacco_assessment">Smoking &amp; Tobacco Use Assessment</option>
                      <option value="alcohol_screening">Alcohol Use &amp; Dependency Screening</option>
                      <option value="supplements_advice">Nutritional Supplements &amp; Vitamins Advice</option>
                      <option value="vitamin_d_screening">Vitamin D Level Screening</option>
                      <option value="minor_emergency">Minor Emergency Assessment</option>
                      <option value="imaging_referral">Referral for Imaging (X-Ray, Ultrasound, MRI)</option>
                      <option value="breast_exam">Breast Exam &amp; Self-Exam Education</option>
                      <option value="prostate_ed">Prostate Health Education</option>
                      <option value="menopause_mgmt">Menopause Symptom Management</option>
                      <option value="pediatric_vaccine">Pediatric Vaccination Coordination</option>
                      <option value="teen_health_edu">Teenage Health Education (Puberty &amp; Growth)</option>
                      <option value="geriatric_care">Geriatric Care Planning</option>
                      <option value="dementia_screen">Dementia &amp; Alzheimer’s Early Screening</option>
                      <option value="home_hospice">Home Care &amp; Hospice Coordination</option>
                      <option value="copd_mgmt">Chronic Obstructive Pulmonary Disease (COPD) Management</option>
                      <option value="basic_suturing">Basic Suturing &amp; Stitch Removal</option>
                      <option value="sore_throat_strep">Sore Throat &amp; Strep Testing Coordination</option>
                      <option value="uti_screening">Urinary Tract Infection Screening</option>
                      <option value="kidney_function">Kidney Function Test Coordination</option>
                      <option value="foot_care">Foot Care Advice (Diabetic Foot Check)</option>
                      <option value="cardio_risk">Cardiovascular Risk Assessment</option>
                      <option value="flu_consult">Seasonal Flu Consultation</option>
                      <option value="ear_syringing">Ear Syringing &amp; Wax Removal Coordination</option>
                      <option value="blood_sugar_edu">Blood Sugar Monitoring Education</option>
                      <option value="blood_donation_coord">Blood Donation Guidance &amp; Coordination</option>
                      <option value="prenatal_vitamins">Prenatal Vitamins Advice</option>
                      <option value="domestic_abuse">Domestic Violence &amp; Abuse Screening</option>
                      <option value="sexual_health">Sexual Health Counseling &amp; STI Screening Coordination</option>
                      <option value="blood_rh_typing">Blood Group &amp; Rh Typing Coordination</option>
                      <option value="thyroid_ultrasound">Thyroid Ultrasound Referral &amp; Follow-Up</option>
                      <option value="sprains_strains">Minor Sprains &amp; Strains Assessment</option>
                      <option value="anxiety_stress_relief">Anxiety &amp; Stress Relief Techniques</option>
                      <option value="injection_admin">Injection Administration (Vitamin B12)</option>
                      <option value="tb_screen_referral">Tuberculosis Screening &amp; Referral</option>
                      <option value="postnatal_check">Postnatal Health Check Coordination</option>
                      <option value="obesity_screening">Overweight &amp; Obesity Screening (BMI)</option>
                      <option value="office_procedure_coord">Office Procedure Coordination (ECG, Nebulization)</option>
                      <option value="migraine_trigger">Migraine Trigger Identification &amp; Management</option>
                      <option value="breastfeeding_support">Breastfeeding Support &amp; Guidance</option>
                      <option value="child_development">Child Development Milestones Counseling</option>
                      <option value="postpartum_depression">Postpartum Depression Screening</option>
                      <option value="cardio_fitness_test">Cardiovascular Fitness Testing Coordination</option>
                      <option value="oral_health_referral">Oral Health Referral &amp; Basic Examination</option>
                      <option value="patient_education">Patient Education Seminars &amp; Workshops</option>
                      <option value="school_forms">School Health Forms &amp; Physicals</option>
                      <option value="fitness_to_work">Fitness-to-Work Assessments</option>
                      <option value="blood_donation_elig">Blood Donation Eligibility Assessment</option>
                      <option value="family_counsel_referral">Family Counseling &amp; Conflict Resolution Referral</option>
                      <option value="mind_body_wellness">Mind-Body Wellness &amp; Meditation Techniques</option>
                      <option value="rehab_coord">Coordinating Rehab Services (Physical Therapy)</option>
                      <option value="adolescent_mental_referral">Adolescent Mental Health Support &amp; Referral</option>
                      <option value="dyslipidemia_mgmt">Dyslipidemia Management &amp; Advice</option>
                      <option value="pap_smear_coord">Pap Smear Coordination</option>
                      <option value="community_outreach">Community Health Outreach &amp; Education</option>
                   </select>
                   <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-services-btn">Add</button>
               </form>
           <?php endif; ?>

           <!-- Popup Overlay -->
           <div id="servicesOverlay"
                style="display: none;
                       position: fixed;
                       top: 0;
                       left: 0;
                       width: 100%;
                       height: 100%;
                       background: rgba(0, 0, 0, 0.5);
                       z-index: 999;">
           </div>

           <!-- Popup Container -->
           <div id="servicesPopup"
                style="display: none;
                       position: fixed;
                       top: 50%;
                       left: 50%;
                       transform: translate(-50%, -50%);
                       background: #fff;
                       width: 400px;
                       padding: 30px;
                       border-radius: 8px;
                       box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
                       z-index: 1000;
                       text-align: center;
                       font-family: Lora;">
               <h2 style="font-size: 24px; color: #333; margin-bottom: 20px;">Your Services</h2>
               <p id="servicesContent"
                  style="font-size: 18px;
                         color: black;
                         line-height: 1.5;
                         max-width: 100%;
                         word-wrap: break-word;">
                   <?php
                   if (!empty($cs_services)) {
                    // Convert string to array
                    $services_array = explode(", ", $cs_services);

                    // Convert each service to a readable format
                    $formatted_services = array_map(function($service) {
                        return ucwords(str_replace("_", " ", $service));
                    }, $services_array);

                    // Join the formatted services back into a string
                    echo htmlspecialchars(implode(", ", $formatted_services));
                      } else {
                    echo "No services selected.";
                      }
                   ?>
               </p>
               <button style="padding: 12px 20px;
                              margin-top: 20px;
                              border: none;
                              background-color: #60a159;
                              font-size: 16px;
                              color: white;
                              border-radius: 5px;
                              cursor: pointer;
                              box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"
                       onclick="closeServicesPopup()">Close</button>
           </div>
           </div>
           <div class="contact-details-about">
            <h2 style="color: white; margin-top: -10px;">About</h2>

            <?php if (!empty($cs_about)): ?>
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
                    <?php echo nl2br(htmlspecialchars($cs_about)); ?>
                </div>
            <?php else: ?>
                <!-- Show input field if no about info exists -->
                <form style="display: flex; flex-direction: column; gap: 1em;" method="post">
                    <textarea class="user-about-input" type="text" name="consultant_about"
                              style="width: 330px; height: 80px; resize: none; padding: 10px; font-family: Lora; outline: none; border: none; border-radius: 5px; margin-top: 10px;"
                              placeholder="Write a little bit about yourself"></textarea>
                    <button style="margin: auto; background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                            type="submit" name="consultant-about-btn">Add</button>
                </form>
            <?php endif; ?>
        </div>
       </div>
       <div class="contact-details-2">
         <div class="contact-details-blood-group">
           <div>
             <h2 style="color: white;">Offline Availability</h2>
           </div>
           <form style="display: flex; gap: 1em;" method="post">
             <select name="consultant_availability" id="offline_availability" style="font-family: Lora; font-size: 15px; width: 150px; border-radius: 5px; outline: none;" <?php echo isFieldFilled($cs_offline) ? 'disabled' : ''; ?>>
               <option value="blank"></option>
               <option value="Yes">Yes</option>
               <option value="No">No</option>
             </select>
             <button <?php echo isFieldFilled($cs_offline) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-availability-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 50px; margin-left: 10px;"><?php echo $cs_offline; ?></h3>
         </div>
         <div class="contact-details-weight">
           <div>
             <h2 style="color: white;">City/Locality</h2>
           </div>
           <form style="display: flex; gap: 1em;" method="post">
             <input class="user-weight-input" type="text" name="consultant_locality" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($cs_locality) ? 'disabled' : ''; ?>>
             <button <?php echo isFieldFilled($cs_locality) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-locality-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 49px; margin-left: 10px;"><?php echo $cs_locality; ?></h3>
         </div>
         <div class="contact-details-height">
           <div>
             <h2 style="color: white;">Hospital</h2>
           </div>
           <form style="display: flex; gap: 1em;" method="post">
             <input class="user-height-input" type="text" name="consultant_hospital" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;" <?php echo isFieldFilled($cs_hospital) ? 'disabled' : ''; ?>>
             <button <?php echo isFieldFilled($cs_hospital) ? 'disabled' : ''; ?> style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="submit" name="consultant-hospital-btn">Add</button>
           </form>
           <h3 style="position: absolute; margin-top: 49px; margin-left: 10px;"><?php echo $cs_hospital; ?></h3>
         </div>
         <div class="contact-details-medical-history">
        <div>
            <h2 style="color: white;">Education</h2>
        </div>
          <?php if (!empty($cs_education)): ?>
              <!-- Show button if education exists -->
              <button id="showEducationPopupBtn"
                      style="background-color: #6499E9; width: 130px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                      onclick="showEducationPopup()">Your Education</button>
          <?php else: ?>
              <!-- Show input field if no education exists -->
              <form style="display: flex; gap: 1em;" method="post">
                  <input class="user-education-input" type="text" name="consultant_education"
                         style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
                  <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                          type="submit" name="consultant-education-btn">Add</button>
              </form>
          <?php endif; ?>

          <!-- Popup Overlay -->
          <div id="educationOverlay"
               style="display: none;
                      position: fixed;
                      top: 0;
                      left: 0;
                      width: 100%;
                      height: 100%;
                      background: rgba(0, 0, 0, 0.5);
                      z-index: 999;">
          </div>

          <!-- Popup Container -->
          <div id="educationPopup"
               style="display: none;
                      position: fixed;
                      top: 50%;
                      left: 50%;
                      transform: translate(-50%, -50%);
                      background: #fff;
                      width: 400px;
                      padding: 30px;
                      border-radius: 8px;
                      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
                      z-index: 1000;
                      text-align: center;
                      font-family: Lora;">
              <h2 style="font-size: 24px; color: #333; margin-bottom: 20px;">Your Education</h2>
              <p id="educationContent"
                 style="font-size: 18px;
                        color: #555;
                        line-height: 1.5;
                        max-width: 100%;
                        word-wrap: break-word;">
                  <?php echo htmlspecialchars($cs_education); ?>
              </p>
              <button style="padding: 12px 20px;
                             margin-top: 20px;
                             border: none;
                             background-color: #60a159;
                             font-size: 16px;
                             color: white;
                             border-radius: 5px;
                             cursor: pointer;
                             box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"
                      onclick="closeEducationPopup()">Close</button>
          </div>
      </div>
       </div>
     </div>

     <div style="display: none;" class="your-reviews" id="your-reviews">
       <h2>Reviews given by your patients will show here.</h2>
       <h3>Make a consultation to get your first review.</h3>
       <button type="button" name="first_review_btn">Get your first review today</button>
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
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-name-btn">Change</button>
             </div>
           </div>
           <div class="personal-details-email">
             <h2 style="color: white;">Email Address</h2>
             <h3 style="background-color: white; width: 250px; padding: 8px; border-radius: 5px;"><?php echo $csEmail; ?></h3>
           </div>
           <div class="personal-details-password">
             <h2 style="color: white;">Password</h2>
             <div style="display: flex; gap: 2em;">
               <input id="consultant_password" style="width: 250px; background-color: white; padding: 8px; border-radius: 5px; border: none; font-family: Lora; outline: none;" placeholder="Enter your new password"></input>
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-password-btn">Change</button>
             </div>
             <button style="font-family: Lora; font-size: 15px; border-radius: 5px; margin-top: -43px; margin-left: 200px; width: 40px; border: none; cursor: pointer;" type="button" onclick="togglePassword('user_password')"><i class="fa-solid fa-eye"></i></button>
           </div>
         </div>
         <div class="personal-details-2" style="display: flex; gap: 2.2em;">
           <div class="personal-details-age">
             <div>
               <h2 style="color: white;">Age</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-age-input" type="number" name="consultant_age" style="width: 100px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-age-btn">Change</button>
             </div>
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
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-gender-btn">Change</button>
             </div>
           </div>
           <div class="personal-details-nationality">
             <div>
               <h2 style="color: white;">Nationality</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-age-input" type="text" name="user_nationality" style="width: 150px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-nationality-btn">Change</button>
             </div>
           </div>
         </div>
       </div>

       <div style="display: none; margin-left: -600px;" class="contact-details" id="manage-contact-details">
         <div class="contact-details-1">
           <div class="contact-details-phone-number">
             <div>
               <h2 style="color: white;">Phone Number</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-phone-input" type="number" name="change_consultant_phone_number" style="padding: 5px; width: 250px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-phone-number-btn">Change</button>
             </div>
           </div>
           <div class="contact-details-address">
             <div>
               <h2 style="color: white;">Specialty/Services</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-address-input" type="text" name="change_consultant_speciality" style="width: 250px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-specialty-btn">Change</button>
             </div>
             </div>
           <div class="contact-details-about" style="display: flex; flex-direction: column; gap: 1em;">
             <h2 style="color: white;">About</h2>
             <textarea class="user-about-input" type="text" name="change_consultant_about" style="width: 330px; height: 60px; resize: none; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;" placeholder="Change your bio"></textarea>
             <button style="margin: auto; background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-about-btn">Change</button>
           </div>
         </div>
         <div class="contact-details-2">
           <div class="contact-details-blood-group">
             <div>
               <h2 style="color: white;">Offline Availability</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <select name="change_consultant_availability" id="user_blood_group" style="font-family: Lora; font-size: 15px; width: 150px; border-radius: 5px; outline: none;">
                 <option value="blank"></option>
                 <option value="Yes">Yes</option>
                 <option value="No">No</option>
               </select>
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-availability-btn">Change</button>
             </div>
           </div>
           <div class="contact-details-weight">
             <div>
               <h2 style="color: white;">City/Locality</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-weight-input" type="number" name="change_consultant_locality" style="width: 200px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-locality-btn">Change</button>
             </div>
           </div>
           <div class="contact-details-height">
             <div>
               <h2 style="color: white;">Hospital</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-height-input" type="number" name="change_consultant_hospital" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-hospital-btn">Change</button>
             </div>
           </div>
           <div class="contact-details-medical-history">
             <div>
               <h2 style="color: white;">Education</h2>
             </div>
             <div style="display: flex; gap: 1em;">
               <input class="user-medical-history-input" type="text" name="change_consultant_education" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
               <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="change-consultant-education-btn">Change</button>
             </div>
           </div>
         </div>
       </div>
     </div>

     <div style="display: none;" class="contact-details" id="expertise-and-specialization">
       <div class="contact-details-1">
         <div class="contact-details-phone-number">
             <div>
                 <h2 style="color: white;">Specializations</h2>
             </div>
             <?php if (!empty($cs_specializations)): ?>
                 <!-- Show button if specializations exist -->
                 <button id="showSpecializationsPopupBtn"
                         style="background-color: #6499E9; width: 150px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                         onclick="showSpecializationsPopup()">Your Specializations</button>
             <?php else: ?>
                 <!-- Show input field if no specializations exist -->
                 <form style="display: flex; gap: 1em;" method="post">
                     <select class="consultant-specializations" name="consultant_specializations[]" multiple
                             style="width: 250px; height: 40px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
                         <option value="general_medicine">General Medicine</option>
                         <option value="pediatrics">Pediatrics</option>
                         <option value="cardiology">Cardiology</option>
                         <option value="neurology">Neurology</option>
                         <option value="orthopedics">Orthopedics</option>
                         <option value="dermatology">Dermatology</option>
                         <option value="psychiatry">Psychiatry</option>
                         <option value="oncology">Oncology</option>
                         <option value="gastroenterology">Gastroenterology</option>
                         <option value="endocrinology">Endocrinology</option>
                         <option value="rheumatology">Rheumatology</option>
                         <option value="nephrology">Nephrology</option>
                         <option value="ophthalmology">Ophthalmology</option>
                         <option value="urology">Urology</option>
                         <option value="pulmonology">Pulmonology</option>
                         <option value="obstetrics_gynecology">Obstetrics & Gynecology</option>
                         <option value="anesthesiology">Anesthesiology</option>
                         <option value="hematology">Hematology</option>
                         <option value="immunology">Immunology</option>
                         <option value="infectious_diseases">Infectious Diseases</option>
                         <option value="sports_medicine">Sports Medicine</option>
                         <option value="plastic_surgery">Plastic Surgery</option>
                         <option value="vascular_surgery">Vascular Surgery</option>
                         <option value="pain_management">Pain Management</option>
                     </select>
                     <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                             type="submit" name="consultant-specializations-btn">Add</button>
                 </form>
             <?php endif; ?>

             <!-- Popup Overlay -->
             <div id="specializationsOverlay"
                  style="display: none;
                         position: fixed;
                         top: 0;
                         left: 0;
                         width: 100%;
                         height: 100%;
                         background: rgba(0, 0, 0, 0.5);
                         z-index: 999;">
             </div>

             <!-- Popup Container -->
             <div id="specializationsPopup"
                  style="display: none;
                         position: fixed;
                         top: 50%;
                         left: 50%;
                         transform: translate(-50%, -50%);
                         background: #fff;
                         width: 400px;
                         padding: 30px;
                         border-radius: 8px;
                         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
                         z-index: 1000;
                         text-align: center;
                         font-family: Lora;">
                 <h2 style="font-size: 24px; color: #333; margin-bottom: 20px;">Your Specializations</h2>
                 <p id="specializationsContent"
                    style="font-size: 18px;
                           color: black;
                           line-height: 1.5;
                           max-width: 100%;
                           word-wrap: break-word;">
                     <?php
                     if (!empty($cs_specializations)) {
                         // Convert string to array
                         $specializations_array = explode(", ", $cs_specializations);

                         // Convert each specialization to a readable format
                         $formatted_specializations = array_map(function($specialization) {
                             return ucwords(str_replace("_", " ", $specialization));
                         }, $specializations_array);

                         // Join the formatted specializations back into a string
                         echo htmlspecialchars(implode(", ", $formatted_specializations));
                     } else {
                         echo "No specializations selected.";
                     }
                     ?>
                 </p>
                 <button style="padding: 12px 20px;
                                margin-top: 20px;
                                border: none;
                                background-color: #60a159;
                                font-size: 16px;
                                color: white;
                                border-radius: 5px;
                                cursor: pointer;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"
                         onclick="closeSpecializationsPopup()">Close</button>
             </div>
         </div>
         <div class="contact-details-awards">
             <div>
                 <h2 style="color: white;">Awards/Recognitions</h2>
             </div>
             <?php if (!empty($cs_awards)): ?>
                 <!-- Show button if awards exist -->
                 <button id="showAwardsPopupBtn"
                         style="margin-top: 20px; background-color: #6499E9; width: 130px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                         onclick="showAwardsPopup()">Your Awards</button>
             <?php else: ?>
                 <!-- Show input field if no awards exist -->
                 <form style="margin-top: 20px; display: flex; gap: 1em;" method="post">
                     <input class="consultant-awards" type="text" name="consultant_awards"
                            style="width: 250px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
                     <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                             type="submit" name="consultant-awards-btn">Add</button>
                 </form>
             <?php endif; ?>

             <!-- Popup Overlay -->
             <div id="awardsOverlay"
                  style="display: none;
                         position: fixed;
                         top: 0;
                         left: 0;
                         width: 100%;
                         height: 100%;
                         background: rgba(0, 0, 0, 0.5);
                         z-index: 999;">
             </div>

             <!-- Popup Container -->
             <div id="awardsPopup"
                  style="display: none;
                         position: fixed;
                         top: 50%;
                         left: 50%;
                         transform: translate(-50%, -50%);
                         background: #fff;
                         width: 400px;
                         padding: 30px;
                         border-radius: 8px;
                         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
                         z-index: 1000;
                         text-align: center;
                         font-family: Lora;">
                 <h2 style="font-size: 24px; color: #333; margin-bottom: 20px;">Your Awards</h2>
                 <p id="awardsContent"
                    style="font-size: 18px;
                           color: #555;
                           line-height: 1.5;
                           max-width: 100%;
                           word-wrap: break-word;">
                     <?php echo htmlspecialchars($cs_awards); ?>
                 </p>
                 <button style="padding: 12px 20px;
                                margin-top: 20px;
                                border: none;
                                background-color: #60a159;
                                font-size: 16px;
                                color: white;
                                border-radius: 5px;
                                cursor: pointer;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"
                         onclick="closeAwardsPopup()">Close</button>
             </div>
         </div>
         <div class="contact-details-experience">
             <h2 style="color: white; margin-top: -10px;">Experience</h2>

             <?php if (!empty($cs_experience)): ?>
                 <!-- Display the experience content in a scrollable div -->
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
                     <?php echo nl2br(htmlspecialchars($cs_experience)); ?>
                 </div>
             <?php else: ?>
                 <!-- Show input field if no experience info exists -->
                 <form style="display: flex; flex-direction: column; gap: 1em;" method="post">
                     <textarea class="user-experience-input" type="text" name="consultant_experience"
                               style="width: 330px; height: 80px; resize: none; padding: 10px; font-family: Lora; outline: none; border: none; border-radius: 5px; margin-top: 10px;"
                               placeholder="Write a little bit about your experiences"></textarea>
                     <button style="margin: auto; background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;"
                             type="submit" name="consultant-experience-btn">Add</button>
                 </form>
             <?php endif; ?>
         </div>
       </div>
       <div class="contact-details-2" style="display: flex; gap: 3em;">
         <div class="contact-details-blood-group">
           <div>
             <h2 style="color: white;">Medical Proofs</h2>
           </div>
           <div style="display: flex; gap: 1em;">
             <input name="consultant_medical_proofs" id="consultant_medical_proofs" type="file" style="font-family: Lora; font-size: 15px; width: 230px; padding: 3px; background-color: white; cursor: pointer; border-radius: 5px; outline: none;">
             <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="consultant-medical-proofs-btn">Add</button>
           </div>
         </div>
         <div class="contact-details-weight">
           <div>
             <h2 style="color: white;">Memberships</h2>
           </div>
           <div style="display: flex; gap: 1em;">
             <input class="consultant-memberships-input" type="text" name="consultant_memberships" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
             <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="consultant-memberships-btn">Add</button>
           </div>
         </div>
         <div class="contact-details-height">
           <div>
             <h2 style="color: white;">Registrations</h2>
           </div>
           <div style="display: flex; gap: 1em;">
             <input class="user-registrations-input" type="text" name="consultant_registrations" style="width: 230px; height: 30px; padding: 5px; font-family: Lora; outline: none; border: none; border-radius: 5px;">
             <button style="background-color: #6499E9; width: 70px; height: 30px; font-family: Lora; outline: none; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 1px 1px black;" type="button" name="consultant-registrations-btn">Add</button>
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
     document.getElementById('expertise-and-specialization').style.display = 'none';
   });

   document.getElementById('personal-details-wrapper').addEventListener('click', () => {
     document.getElementById('personal-details').style.display = 'flex';
     document.getElementById('contact-details').style.display = 'none';
     document.getElementById('your-reviews').style.display = 'none';
     document.getElementById('manage-profile').style.display = 'none';
     document.getElementById('expertise-and-specialization').style.display = 'none';
   });

   document.getElementById('your-reviews-wrapper').addEventListener('click', () => {
     document.getElementById('personal-details').style.display = 'none';
     document.getElementById('contact-details').style.display = 'none';
     document.getElementById('your-reviews').style.display = 'flex';
     document.getElementById('manage-profile').style.display = 'none';
     document.getElementById('expertise-and-specialization').style.display = 'none';
   });

   document.getElementById('manage-profile-wrapper').addEventListener('click', () => {
     document.getElementById('personal-details').style.display = 'none';
     document.getElementById('contact-details').style.display = 'none';
     document.getElementById('your-reviews').style.display = 'none';
     document.getElementById('expertise-and-specialization').style.display = 'none';
     document.getElementById('manage-profile').style.display = 'flex';
   });

   document.getElementById('expertise-and-specialization-wrapper').addEventListener('click', () => {
     document.getElementById('personal-details').style.display = 'none';
     document.getElementById('contact-details').style.display = 'none';
     document.getElementById('your-reviews').style.display = 'none';
     document.getElementById('manage-profile').style.display = 'none';
     document.getElementById('expertise-and-specialization').style.display = 'flex';
   });

   function showServicesPopup() {
      document.getElementById('servicesOverlay').style.display = 'block';
      document.getElementById('servicesPopup').style.display = 'block';
  }

  function closeServicesPopup() {
      document.getElementById('servicesPopup').style.display = 'none';
      document.getElementById('servicesOverlay').style.display = 'none';
  }

  function showEducationPopup() {
    document.getElementById('educationOverlay').style.display = 'block';
    document.getElementById('educationPopup').style.display = 'block';
  }

  function closeEducationPopup() {
      document.getElementById('educationOverlay').style.display = 'none';
      document.getElementById('educationPopup').style.display = 'none';
  }

  function showSpecializationsPopup() {
    document.getElementById("specializationsPopup").style.display = "block";
    document.getElementById("specializationsOverlay").style.display = "block";
}

function closeSpecializationsPopup() {
    document.getElementById("specializationsPopup").style.display = "none";
    document.getElementById("specializationsOverlay").style.display = "none";
}

function showAwardsPopup() {
    document.getElementById("awardsPopup").style.display = "block";
    document.getElementById("awardsOverlay").style.display = "block";
}

function closeAwardsPopup() {
    document.getElementById("awardsPopup").style.display = "none";
    document.getElementById("awardsOverlay").style.display = "none";
}

  // Profile Picture AJAX

  // Function to trigger file upload when profile picture is clicked
  function triggerFileUpload() {
      document.getElementById('file-input').click();
  }

  // Handle file selection and upload process
  document.getElementById('file-input').addEventListener('change', function() {
      let file = this.files[0];
      if (!file) return;

      let formData = new FormData();
      formData.append("profile_pic", file);
      formData.append("email", "<?php echo $logged_in_email; ?>"); // Send consultant's email

      fetch("upload_profile_pic.php", {
          method: "POST",
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              document.getElementById('profile-pic').src = data.file_path; // Update the image immediately
              // Save in session storage to persist across page reloads
              sessionStorage.setItem('profile_pic', data.file_path);
          } else {
              alert("Error uploading image: " + data.error);
          }
      })
      .catch(error => console.error("Upload failed:", error));
  });

  document.addEventListener("DOMContentLoaded", function () {
    let savedProfilePic = sessionStorage.getItem('profile_pic');
    if (savedProfilePic) {
        document.getElementById('profile-pic').src = savedProfilePic;
    }
  });

   </script>

   <script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
   </body>
 </html>
