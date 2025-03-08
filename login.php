<?php
session_start();

// Database connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "root";
$database = "medconnect";

$connection = mysqli_connect($dbHost, $dbUser, $dbPass, $database);
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logInEmail'], $_POST['logInPassword'])) {
    $email = mysqli_real_escape_string($connection, $_POST['logInEmail']);
    $password = mysqli_real_escape_string($connection, $_POST['logInPassword']);

    // Query to check user existence
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password (assuming it's hashed in the database)
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['signUpBtn'] = true;
            $_SESSION['signUpName'] = $row['name'];
            $_SESSION['signUpEmail'] = $row['email'];
            $_SESSION['signUpPassword'] = $password; // Store only if necessary
            $_SESSION['role'] = $row['role'];
            $_SESSION['id'] = $row['id'];

            // Redirect based on role
            if ($row['role'] === 'user') {
                header("Location: user_index.php");
                exit();
            } elseif ($row['role'] === 'consultant') {
                header("Location: consultant_index.php");
                exit();
            }
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.');</script>";
    }
}

// If already logged in, redirect based on role
if (isset($_SESSION['signUpBtn'], $_SESSION['role'])) {
    if ($_SESSION['role'] === 'user') {
        header("Location: user_index.php");
        exit();
    } elseif ($_SESSION['role'] === 'consultant') {
        header("Location: consultant_index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedConnect | Log In or Sign Up</title>
    <style media="screen">
    @import url('https://fonts.googleapis.com/css2?family=Lora&display=swap');
         *,
    *:before,
    *:after {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #63a05c;
      font-family: Lora, sans-serif;
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Lora;
    }

    .container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-template-rows: 1fr;
      max-width: 80%;
      max-height: 80%;
      margin-top: 20px;
      box-shadow: 0 1px 1px black;
      border-radius: 5px;
    }

    .login {
      display: grid;
      grid-template-columns: 1fr;
      grid-template-rows: repeat(8, auto);
      padding: 1em 3em;
      background-color: #A4907C;
      margin-top: -20px;
      height: 550px;
      width: 550px;
      border-top-left-radius: 5px;
      border-bottom-left-radius: 5px;
    }

     .textHead, .logintxt {
      text-align: center;
    }

    .loginemailtxt {
      border: 1px solid black;
      outline: none;
      border-radius: 5px;
      margin: 1em 0 2em 0;
      font-family: Lora;
      width: 250px;
      height: 35px;
      margin-left: 100px;
      margin-top: 20px;
    }

    .loginemailtxt::placeholder {
      color: black;
      padding-left: 3px;
    }

    .loginemailtxt:focus {
      border: 2px solid #3F72AF;
    }

    .loginpwdtxt {
      border: 1px solid black;
      outline: none;
      border-radius: 5px;
      font-family: Lora;
      width: 250px;
      height: 35px;
      margin-left: 100px;
      margin-top: 20px;
    }

    .loginpwdtxt::placeholder {
      color: black;
      padding-left: 3px;
    }

    .loginpwdtxt:focus {
      border: 2px solid #3F72AF;
    }

    .loginbtn {
      border-radius: 5px;
      background-color: #3F72AF;
      border: none;
      cursor: pointer;
      font-family: Lora;
      width: 120px;
      height: 40px;
      margin-left: 170px;
      margin-top: 50px;
      box-shadow: 0 1px 1px black;
    }

    .loginbtn:active {
      box-shadow: none;
    }

    #loginNoEmail {
      position: absolute;
      background-color: #F24C3D;
      border-radius: 5px;
      margin-top: 210px;
      margin-left: 120px;
      padding-left: 2px;
      padding-right: 2px;
      visibility: hidden;
    }

    #loginNoPassword {
      position: absolute;
      background-color: #F24C3D;
      border-radius: 5px;
      margin-top: 290px;
      margin-left: 135px;
      padding-left: 2px;
      padding-right: 2px;
      visibility: hidden;
    }

    #loginIncorrectCreds {
      position: absolute;
      background-color: #F24C3D;
      border-radius: 5px;
      margin-top: 290px;
      margin-left: 135px;
      padding-left: 2px;
      padding-right: 2px;
      visibility: hidden;
    }

    .error-message {
      position: absolute;
      background-color: #F24C3D;
      border-radius: 5px;
      padding-left: 2px;
      padding-right: 2px;
      visibility: hidden;
    }

    #signupNoName {
      margin-top: 200px;
      margin-left: 145px;
    }

    #signupNoEmail {
      margin-top: 261px;
      margin-left: 125px;
    }

    #signupNoPwd {
      margin-top: 322px;
      margin-left: 150px;
    }

    #signupValidEmail {
      margin-top: 261px;
      margin-left: 120px;
    }

    #signupValidPwd {
      margin-top: 322px;
      margin-left: 90px;
    }

    #signupEmailExists {
      margin-top: 261px;
      margin-left: 140px;
    }

    .information {
      display: grid;
      grid-template-columns: 1fr;
      grid-template-rows: repeat(5, auto);
      padding: 1em 3em;
      background-color: #F3DEBA;
      text-align: center;
      margin-top: -20px;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
    }

    .information h6 {
      margin: 0;
    }

    .infoHead1 {
      margin-top: 30px;
    }

    .infoHead2 {
      background-color: #A0BFE0;
      border-radius: 5px;
      margin-top: -40px;
      height: 33px;
      width: 340px;
      margin-left: 50px;
    }

     .infoIcons {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: repeat(2, 1fr);
      align-items: center;
      justify-items: center;
    }

    .infoIcons svg {
      font-size: 40px;
    }

    .signup {
      display: grid;
      grid-template-columns: 1fr;
      grid-template-rows: repeat(8, auto);
      padding: 1em 3em;
      background-color: #A4907C;
      margin-top: -20px;
      height: 550px;
      width: 550px;
      border-top-left-radius: 5px;
      border-bottom-left-radius: 5px;
    }

    .signupnametxt {
      border: 1px solid black;
      outline: none;
      border-radius: 5px;
      margin: 1em 0 2em 0;
      font-family: Lora;
      width: 250px;
      height: 35px;
      margin-left: 100px;
      margin-top: 20px;
    }

    .signupnametxt::placeholder {
      color: black;
      padding-left: 3px;
    }

    .signupnametxt:focus {
      border: 2px solid #3F72AF;
    }

    .signupemailtxt {
      border: 1px solid black;
      outline: none;
      border-radius: 5px;
      font-family: Lora;
      width: 250px;
      height: 35px;
      margin-left: 100px;
    }

    .signupemailtxt::placeholder {
      color: black;
      padding-left: 3px;
    }

    .signupemailtxt:focus {
      border: 2px solid #3F72AF;
    }

    .signuppwdtxt {
      border: 1px solid black;
      outline: none;
      border-radius: 5px;
      font-family: Lora;
      width: 250px;
      height: 35px;
      margin-left: 100px;
      margin-top: 25px;
    }

    .signuppwdtxt::placeholder {
      color: black;
      padding-left: 3px;
    }

    .signuppwdtxt:focus {
      border: 2px solid #3F72AF;
    }

    .signupbtn {
      border-radius: 5px;
      background-color: #3F72AF;
      border: none;
      cursor: pointer;
      font-family: Lora;
      width: 120px;
      height: 40px;
      margin-left: 170px;
      margin-top: 30px;
      box-shadow: 0 1px 1px black;
    }

    .signupbtn:active {
      box-shadow: none;
    }

    </style>
  </head>
  <body>
    <div class="container">
      <form id="mainForm" action="javascript:void(0)" method="post">
        <div class="login">
          <h2 class="textHead">MedConnect</h2>
          <h2 class="logintxt">Log In to Your Account</h2>
          <input type="text" id="loginemailtxt" class="loginemailtxt" name="logInEmail" placeholder="Email Address">
          <input type="password" id="loginpwdtxt" class="loginpwdtxt" name="logInPassword" placeholder="Password">
          <button class="loginbtn" id="loginBtn" type="submit" name="logInBtn" onclick="checkLogin()">Log In</button>
          <h5 id="signuptext" onclick="showorHide('signup','login')" style="margin-top: 50px; text-align: center;">Don't have an Account? <u style="cursor: pointer;">Sign Up</u></h5>
          <h6 style="text-align: center;">By clicking "Log In", you log back into your Existing Account</h6>
          <h5 id="loginNoEmail">Please enter your email address</h5>
          <h5 id="loginNoPassword">Please enter your password</h5>
          <h5 id="loginIncorrectCreds">Incorrect Email or Password</h5>
        </div>

        <div class="signup" style="display: none">
          <h2 class="textHead">MedConnect</h2>
          <h2 class="logintxt">Create a New Account</h2>
          <input id="signupnametxt" type="text" class="signupnametxt" name="signUpName" placeholder="Name">
          <input id="signupemailtxt" type="text" class="signupemailtxt" name="signUpEmail" placeholder="Email Address">
          <input id="signuppwdtxt" type="password" class="signuppwdtxt" name="signUpPassword" placeholder="Create Password">
          <button id="signupbtn" class="signupbtn" type="submit" name="signUpBtn" onclick="checkEmail()">Sign Up</button>
          <h5 id="logintext" onclick="showorHide('login','signup')" style="margin-top: 50px; text-align: center;">Already a Member? <u style="cursor: pointer;">Log In</u></h5>
          <h6 style="margin-top: 5px; text-align: center;">By clicking "Sign Up", you agree to our <u style="cursor: pointer;">Terms</u> and <u style="cursor: pointer;">Privacy Policy</u></h6>
          <h5 id="signupNoName" class="error-message">Please enter your name</h5>
          <h5 id="signupNoEmail" class="error-message">Please enter your email address</h5>
          <h5 id="signupNoPwd" class="error-message">Please create a password</h5>
          <h5 id="signupValidEmail" class="error-message">Please enter a valid email address</h5>
          <h5 id="signupValidPwd" class="error-message">Your password must be above 6 characters</h5>
          <h5 id="signupEmailExists" class="error-message">Email is already registered</h5>
        </div>
      </form>

      <div class="information">
        <h2 class="infoHead1">All-In-One</h2>
        <h2 class="infoHead2">Medical Consultancy Platform</h2>
        <div class="infoIcons">
          <i class="fa-solid fa-person"></i>
          <i class="fa-solid fa-calendar-check"></i>
          <i class="fa-solid fa-handshake-angle"></i>
          <h6 style="text-align: center;">Connect with <br>Consultants</h6>
          <h6 style="text-align: center;;">Book Online Interactions <br>Anytime</h6>
          <h6 style="text-align: center;">Volunteer as a <br>Verified Consultant</h6>
        </div>
        <div class="infoIcons" style="margin-top: 20px;">
          <i class="fa-brands fa-youtube"></i>
          <i class="fa-solid fa-file"></i>
          <i class="fa-solid fa-magnifying-glass"></i>
          <h6 style="text-align: center;">Self-Educate with <br>Resources</h6>
          <h6 style="text-align: center;">Get Personalised, Detailed <br>Prescriptions</h6>
          <h6 style="text-align: center;">Search for specific <br>Medical Help</h6>
        </div>
        <h5>Non Profit • Start for Free • Cancel Anytime</h5>
      </div>
    </div>

    <script type="text/javascript">

        // Toggling between the Log In and Sign Up DIVs

        function showorHide(showDivName, hideDivName) {
            var showDiv = document.getElementsByClassName(showDivName);
            var hideDiv = document.getElementsByClassName(hideDivName);

            hideDiv[0].style.display = "none";
            showDiv[0].style.display = "grid";
        }

        // Log In Validation System

        // Input Fields
        var logInEmail = document.getElementById("loginemailtxt");
        var logInPwd = document.getElementById("loginpwdtxt");
        // Error Messages
        var loginNoEmail = document.getElementById("loginNoEmail");
        var loginNoPassword = document.getElementById("loginNoPassword");
        var loginIncorrectCreds = document.getElementById("loginIncorrectCreds");
        // Log In button
        var loginBtn = document.getElementById("loginBtn");

        loginBtn.addEventListener("click", function(e) {
          e.preventDefault();
          if(logInEmail.value === "" && logInPwd.value === "") {
            // error messages
            loginNoEmail.style.visibility = 'visible';
            loginNoPassword.style.visibility = 'visible';
            loginIncorrectCreds.style.visibility = 'hidden';
            // borders
            logInEmail.style.border = '2px solid red';
            logInPwd.style.border = '2px solid red';
          } else if (logInEmail.value === "") {
            // error messages
            loginNoEmail.style.visibility = 'visible';
            loginNoPassword.style.visibility = 'hidden';
            loginIncorrectCreds.style.visibility = 'hidden';
            // borders
            logInEmail.style.border = '2px solid red';
            logInPwd.style.border = '2px solid green';
          } else if (logInPwd.value === "") {
            // error messages
            loginNoEmail.style.visibility = 'hidden';
            loginNoPassword.style.visibility = 'visible';
            loginIncorrectCreds.style.visibility = 'hidden';
            // borders
            logInEmail.style.border = '2px solid green';
            logInPwd.style.border = '2px solid red';
          } else {
            // error messages
            loginNoEmail.style.visibility = 'hidden';
            loginNoPassword.style.visibility = 'hidden';
            loginIncorrectCreds.style.visibility = 'hidden';
            // borders
            logInEmail.style.border = '2px solid green';
            logInPwd.style.border = '2px solid green';
            document.getElementById('mainForm').submit();
          }
        });

        // Sign Up Validation System

        // Input Fields
        var signUpName = document.getElementById('signupnametxt');
        var signUpEmail = document.getElementById('signupemailtxt');
        var signUpPassword = document.getElementById('signuppwdtxt');
        // Error Messages
        var signupNoName = document.getElementById('signupNoName');
        var signupNoEmail = document.getElementById('signupNoEmail');
        var signupNoPwd = document.getElementById('signupNoPwd');
        var signupValidEmail = document.getElementById('signupValidEmail');
        var signupValidPwd = document.getElementById('signupValidPwd');
        var signupEmailExists = document.getElementById('signupEmailExists');
        // Sign Up button
        var btnSubmit = document.getElementById('signupbtn');

        btnSubmit.addEventListener('click', () => {
          if(signUpName.value === "") {
            signUpName.style.border = '2px solid red';
            signupNoName.style.visibility = 'visible';
            signupEmailExists.style.visibility = 'hidden';
          } else {
            signUpName.style.border = '2px solid green';
            signupNoName.style.visibility = 'hidden';
          }

          if(signUpEmail.value === "") {
            signUpEmail.style.border = '2px solid red';
            signupNoEmail.style.visibility = 'visible';
          } else if (!validateEmail(signUpEmail.value)) {
            signUpEmail.style.border = '2px solid red';
            signupValidEmail.style.visibility = 'visible';
            signupEmailExists.style.visibility = 'hidden';
          } else {
            signUpEmail.style.border = '2px solid green';
            signupValidEmail.style.visibility = 'hidden';
            signupNoEmail.style.visibility = 'hidden';
            signupEmailExists.style.visibility = 'hidden';
          }

          if(signUpPassword.value === "") {
            signUpPassword.style.border = '2px solid red';
            signupNoPwd.style.visibility = 'visible';
            signupEmailExists.style.visibility = 'hidden';
          } else if (signUpPassword.value.length < 6) {
            signUpPassword.style.border = '2px solid red';
            signupValidPwd.style.visibility = 'visible';
            signupEmailExists.style.visibility = 'hidden';
          } else {
            signUpPassword.style.border = '2px solid green';
            signupValidPwd.style.visibility = 'hidden';
            signupNoPwd.style.visibility = 'hidden';
          }

          if(signUpName.value.length > 0 && signUpEmail.value.length > 0 && signUpPassword.value.length >= 6) {
            document.getElementById('mainForm').submit();
          }
        });

    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
  </body>
</html>
