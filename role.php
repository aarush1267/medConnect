<?php

session_start();

if (isset($_SESSION['signUpBtn']) && isset($_SESSION['role'])) {
  // Redirect the user to the appropriate interface based on the role
  if ($_SESSION['role'] === 'user') {
    header("Location: user_index.php");
    exit();
  } elseif ($_SESSION['role'] === 'consultant') {
    header("Location: consultant_index.php");
    exit();
  }
} elseif (!isset($_SESSION['signUpBtn'])) {
  header("Location: login.php");
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MedConnect | Choose Your Role</title>
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Lora&display=swap');
  body {
    font-family: Lora;
    text-align: center;
    margin-top: 100px;
    background-color: #DBE2EF;
  }

  h1 {
    font-size: 40px;
    margin-bottom: 20px;
  }

  h2 {
    margin-top: 50px;
  }

  .btn-container {
    display: flex;
    justify-content: center;
    margin-top: 200px;
  }

  .btn-container div{
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 50px;
    border-radius: 5px;
    background-color: #A4907C;
    color: black;
    text-decoration: none;
    margin: 0 100px;
    border: none;
    box-shadow: 0 1px 1px black;
    cursor: pointer;
  }

  .btn:active {
    box-shadow: none;
  }

  .btn-container button {
    width: 60%;
  }

  .btn-container button span {
    font-size: 20px;
    padding-top: 10px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  @media screen and (max-width: 650px) {
    .btn {
      margin: 0 55px;
    }
  }

  </style>
</head>
<body>
  <h1>MedConnect</h1>
  <h2>Please select your Role</h2>
  <form action="process_role.php" method="POST">
    <div class="btn-container">
      <div>
        <button type="submit" name="role" value="user" class="btn"><i class="fa-solid fa-user"></i><span style="font-family: Lora;">User</span></button>
        <h5>Sign Up as a User on MedConnect</h5>
      </div>

      <div>
        <button type="submit" name="role" value="consultant" style="background-color: #5A96E3;" class="btn"><i class="fa-solid fa-hand-holding-medical"></i><span style="font-family: Lora;">Consultant</span></button>
        <h5>Sign Up as a Consultant on MedConnect</h5>
      </div>
    </div>
  </form>

  <script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
</body>
</html>
