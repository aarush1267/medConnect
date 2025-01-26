<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MedConnect User | Consult</title>
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

  </body>
</html>
