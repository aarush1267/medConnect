<?php

session_start();

if (!isset($_SESSION['signUpBtn'])) {
  header("Location:login.php"); // Not Logged In (Redirect Back to Login/Sign Up Page)
} elseif (isset($_SESSION['signUpBtn']) && !isset($_SESSION['role'])) {
  header("Location:role.php");
} elseif ($_SESSION['role'] === 'user') {
  header("Location:user_index.php");
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MedConnect Consultant | Home</title>
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

    .index-first p {
      color: #614124;
      font-size: 40px;
    }

    .index-first h4 {
      color: #704a1b;
      font-size: 20px;
    }

    .index-first-text {
      margin: 130px 120px;
      display: flex;
      flex-direction: column;
      gap: 2em;
    }

    .index-first-text img {
      width: 600px;
      margin-left: 700px;
      margin-top: -350px;
    }

    .consult-today-btn {
      border-radius: 5px;
      box-shadow: 0 1px 1px black;
      padding: 25px 25px;
      background-color: #60a159;
      width: 163px;
      cursor: pointer;
      margin-top: 20px;
    }

    .consult-today-btn:active {
      box-shadow: none;
    }

    @media screen and (max-width: 1201px) {
      .index-first-text {
        align-items: center;
        margin: 50px;
      }

      .index-first-text img {
        margin: 0;
        margin-bottom: 70px;
      }

      body {
        overflow-x: hidden;
      }
    }

    @media screen and (max-width: 790px) {
      .index-first-text p {
        text-align: center;
        font-size: 30px;
      }

      .index-first-text h4 {
        text-align: center;
        font-size: 15px;
      }
    }

    @media screen and (max-width: 1130px) {
      .index-second {
        flex-direction: column;
        gap: 5em;
      }
    }

      .index-second {
        display: flex;
        justify-content: space-around;
        background-color: #385c34;
        margin-top: -100px;
        padding: 50px;
      }

      .wrapper {
        text-align: center;
      }

      .wrapper h4 {
        margin-top: 60px;
        color: white;
        font-size: 20px;
      }

      .wrapper p {
        margin-top: 50px;
        color: white;
      }

      .item {
        text-align: center;
        background-color: #fff;
        padding: 15px;
        width: 160px;
        height: 100px;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0 1px 1px black;
      }

      .item p {
        margin-top: 20px;
      }

      .index-third-grid {
        display: grid;
        grid-template-rows: 100px;
        grid-template-columns: repeat(3, 100px);
        row-gap: 2em;
        column-gap: 5em;
        color: #704a1b;
        font-size: 20px;
        margin: 100px;
        margin-right: 170px;
        margin-top: -10px;
        float: right;
      }

      .index-third-text {
        margin-top: 70px;
      }

      .index-third-text p {
        font-size: 25px;
        color: #704a1b;
        margin-left: 100px;
        position: absolute;
      }

      .index-third-text h4 {
        font-size: 35px;
        color: #614124;
        margin-left: 100px;
        margin-top: 40px;
        position: absolute;
      }

      .index-third-text h5 {
        font-size: 15px;
        color: #614124;
        margin-left: 130px;
        margin-top: 120px;
        position: absolute;
      }

      .index-third-text hr {
        font-size: 35px;
        border: 0.5px solid #614124;
        width: 0px;
        height: 120px;
        margin-left: 100px;
        margin-top: 110px;
        position: absolute;
      }

      @media screen and (min-width: 1050px) and (max-width: 1280px) {
        .index-third-grid {
          grid-template-rows: 100px;
          grid-template-columns: repeat(2, 100px);
          margin-top: -100px;
        }

        .index-third-text {
          margin-top: 150px;
        }

        body {
          overflow-x: hidden;
        }
      }

      @media screen and (min-width: 1050px) {
        body {
          overflow-x: hidden;
        }
      }

      @media screen and (max-width: 1049px) {
        .index-third {
          display: flex;
          justify-content: center;
        }

        .index-third-grid {
          margin-top: 350px;
        }
      }

      @media screen and (max-width: 640px) {
        .item {
          width: 120px;
          font-size: 15px;
        }

        .index-third-grid {
          column-gap: 2em;
        }

        .index-third-text h5 {
          font-size: 13px;
          margin-top: 125px;
        }

        .index-third-text {
          margin-left: 35px;
        }
      }

      .index-fourth {
        margin-top: 350px;
        background-color: #684414;
        padding: 50px;
        color: white;
      }

      .index-fourth-head {
        display: flex;
        flex-direction: column;
        gap: 1em;
        align-items: center;
      }

      .index-fourth-content-1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 70px;
        gap: 7.6em;
      }

      .index-fourth-content-2 {
        display: flex;
        flex-direction: column;
        margin-top: -280px;
        align-items: center;
        gap: 7em;
      }

      @media screen and (min-width: 1050px) and (max-width: 1280px) {
        .index-third {
          margin-bottom: 480px;
        }
      }

      @media screen and (max-width: 1049px) {
        .index-fourth {
          margin-top: -50px;
        }
      }

      .index-fifth-head {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 50px;
        gap: 1em;
        color: #704a1b;
      }

      .index-fifth-head h1 {
        color: #614124;
      }

      .index-fifth-wrappers {
        padding: 20px;
        border-radius: 5px;
        color: #704a1b;
        display: flex;
        gap: 2rem;
        width: 100%;
      }

      .flex-item {
        background-color: #ffdc84;
        flex: 1;
        padding: 1rem;
        border-radius: 5px;
      }

      *,
      *::before,
      *::after {
        box-sizing: border-box;
      }

      @media screen and (max-width: 900px) {
        .index-fifth-wrappers {
          flex-direction: column;
        }
      }

      .index-sixth-head {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
        color: #704a1b;
      }

      .index-sixth-head h1 {
        color: #614124;
      }

      .index-sixth-head hr {
        font-size: 35px;
        border: 0.5px solid #614124;
        width: 0px;
        height: 120px;
        margin-top: 10px
      }

      .index-sixth-head p {
        margin-left: 30px;
        margin-top: -120px;
        width: 600px;
      }

      .box {
        background-color: #60a159;
        padding: 15px;
        width: 340px;
        text-align: center;
        border-radius: 5px;
        color: black;
      }

      .index-sixth-wrappers {
        display: flex;
        flex-direction: column;
        gap: 2em;
      }

      .index-sixth {
        display: flex;
        justify-content: center;
        margin: 50px;
        gap: 5em;
      }

      @media screen and (max-width: 1150px) {
        .index-sixth {
          flex-direction: column-reverse;
          gap: 3em;
          align-items: center;
        }
      }

      @media screen and (max-width: 695px) {
        .index-sixth-head {
          text-align: center;
        }

        .index-sixth-head p {
          font-size: 15px;
          width: 400px;
          margin-top: 20px;
        }

        .index-sixth-head hr {
          display: none;
        }
      }

      @media screen and (max-width: 542px) {
        .index-sixth-head p {
          margin-left: 10px;
        }
      }

      .index-seventh {
        margin-top: 50px;
        display: flex;
        gap: 10em;
        justify-content: center;
        background-color: #e8bc94;
        padding: 40px;
      }

      .index-seventh-head {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
        color: #704a1b;
        margin-top: 20px;
      }

      .index-seventh-head h1 {
        color: #614124;
      }

      .index-seventh-head hr {
        font-size: 35px;
        border: 0.5px solid #614124;
        width: 0px;
        height: 120px;
        margin-top: 10px
      }

      .index-seventh-head p {
        margin-left: 30px;
        margin-top: -120px;
        width: 600px;
      }

      .index-seventh-checks {
        display: grid;
        grid-template-rows: repeat(2, 100px);
        grid-template-columns: repeat(2, 100px);
        column-gap: 8em;
      }

      .get-started-today-btn {
        border-radius: 5px;
        box-shadow: 0 1px 1px black;
        padding: 20px 20px;
        background-color: #60a159;
        width: 200px;
        text-align: center;
        cursor: pointer;
        margin-left: 80px;
        margin-top: 50px;
      }

      .get-started-today-btn:active {
        box-shadow: none;
      }

      @media screen and (max-width: 1000px) {
        .index-seventh {
          flex-direction: column;
          align-items: center;
          gap: 5em;
        }

        .index-seventh-checks {
          column-gap: 7em;
        }
      }

      .individual {
        background-color: #684414;
        width: 350px;
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
        justify-content: space-around;
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

      .index-ninth {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2em;
        background-color: #68a45c;
        padding: 50px;
        color: white;
        text-align: center;
      }

      .index-ninth button {
        font-family: Lora;
        height: 50px;
        width: 200px;
        border-radius: 5px;
        box-shadow: 0 1px 1px black;
        border: none;
        cursor: pointer;
        color: #385c34;
        font-size: 20px;
      }

      .fourth:hover {
        background-color: #385c34;
        color: #fff;
      }

      .fourth:active {
        box-shadow: none;
      }

      @media screen and (max-width: 1250px) {
        .index-eighth-wrappers {
          flex-direction: column;
          align-items: center;
          gap: 3em;
        }
      }

      .footer-item {
        color: white;
        display: flex;
        flex-direction: column;
        gap: 2em;
      }

      .footer {
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
</head>
<body>

<!-- Navigation Bar -->

<nav class="navbar">
  <div style="display: flex; flex-direction: column; gap: 0.2em;">
    <h1 class="head">MedConnect</h1>
    <h4 style="margin-left: 83px; color: #614124;">For Consultants</h4>
  </div>

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

<!-- First Section -->

<div class="index-first">
  <div class="index-first-text">
    <p><b>Consult and Reach more Patients.</b></p>
    <h4>Maintain your profile, interact with patients and share your knowledge.</h4>
    <h3 class="consult-today-btn" style="color: white;" onclick="location.href='consultant_consult.php'">Consult Now</h3>
    <img src="medconnect_images/hospital_img.png" alt="hospital_img">
  </div>
</div>

<!-- Second Section -->

<div class="index-second">
  <div class="wrapper wrapper-1">
    <i style="color: #7A9D54; font-size: 50px;" class="fa-solid fa-hospital-user"></i>
    <h4>Connect with Patients</h4>
    <p>Consult with patients as per your need <br> and keep them engaged. Answer medical queries, <br> and increase your visibility online.</p>
  </div>
  <div class="wrapper wrapper-2">
    <i style="color: #7A9D54; font-size: 50px;" class="fa-solid fa-user-doctor"></i>
    <h4>Manage your Practice</h4>
    <p>Provide consultations efficiently <br> to reach more patients, offer premium <br> experiences, and build your medical practice.</p>
  </div>
  <div class="wrapper wrapper-3">
    <i style="color: #7A9D54; font-size: 50px;" class="fa-solid fa-newspaper"></i>
    <h4>Share your Knowledge</h4>
    <p>Create your personal feed to create and store your <br> personal medical articles to educate others. <br> Share your content with other users.</p>
  </div>
</div>

<!-- Third Section -->

<div class="index-third">
  <div class="index-third-text">
    <p id="heading">How You Can Help Others</p>
    <h4 id="subheading">Our Services</h4>
    <hr>
    <h5 id="body">The services we offer are to ensure seamless consultation <br> processes. These features intend for consultants to provide <br> effective medical assistance to those in need. <br> <br> Click on the buttons to learn more!</h5>
  </div>
  <div class="index-third-grid">
    <div class="item item-1">
      <i class="fa-solid fa-check-to-slot"></i>
      <p>Consult</p>
    </div>
    <div class="item item-2">
      <i class="fa-solid fa-folder"></i>
      <p>Maintain</p>
    </div>
    <div class="item item-3">
      <i class="fa-solid fa-user"></i>
      <p>Interact</p>
    </div>
    <div class="item item-4">
      <i class="fa-solid fa-book"></i>
      <p>Share</p>
    </div>
    <div class="item item-5">
      <i class="fa-solid fa-id-card"></i>
      <p>Build</p>
    </div>
    <div class="item item-6">
      <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
      <p>Expand</p>
    </div>
  </div>
</div>

<!-- Fourth Section -->

<div class="index-fourth">
  <div class="index-fourth-head">
    <h2>MedConnect</h2>
    <h1>How It Works</h1>
  </div>
  <div class="index-fourth-content-1">
    <h2><span style="border: 2px solid white; border-radius: 50px; padding: 10px;">1</span> Get Notified to Appoint</h2>
    <h2><span style="border: 2px solid white; border-radius: 50px; padding: 10px;">2</span> Address your Patient</h2>
    <h2><span style="border: 2px solid white; border-radius: 50px; padding: 10px;">3</span> Gain Feedback</h2>
  </div>
  <div class="index-fourth-content-2">
    <h5>After building and updating your profile accordingly, you will be <br> notified when a patient books a consultation with you. Prioritize <br> the mentioned time slot and appoint your patient through chat.</h5>
    <h5>In the consultation, interact with your patient and address <br> their medical needs with the consultation services we offer.</h5>
    <h5>When the consultation's over, you can view the patient's feedback <br> which helps build and grow your profile to others. You can maintain <br> your past consultations and update them on your consult page too.</h5>
  </div>
</div>

<!-- Fifth Section -->

<div class="index-fifth">
  <div class="index-fifth-head">
    <h2>Why Us?</h2>
    <h1>Our Advantage</h1>
  </div>
  <div class="index-fifth-wrappers">
    <div class="flex-item index-fifth-wrapper-1">
      <div style="display: flex; gap: 1em; padding-top: 15px;">
        <i class="fa-solid fa-square-check"></i>
        <p>Secure</p>
      </div>
      <p style="margin-top: 40px;">The details of your consultations and the patients you connect with, along with the feedback you provide, will remain secure no matter what. Furthermore, any data you provide on MedConnect on sign up is completely secure and will not be used for any other purposes.</p>
    </div>
    <div class="flex-item index-fifth-wrapper-2">
      <div style="display: flex; gap: 1em; padding-top: 15px;">
        <i class="fa-solid fa-chart-simple"></i>
        <p>Simple</p>
      </div>
      <p style="margin-top: 40px;">Our user-friendly interface helps ensure simple and convenient, but secure, consultations. Furthermore, through the click of a few buttons, MedConnect helps support all types of medical needs and is very suitable and convenient for consultants to work with.</p>
    </div>
    <div class="flex-item index-fifth-wrapper-3">
      <div style="display: flex; gap: 1em; padding-top: 15px;">
        <i class="fa-solid fa-list-check"></i>
        <p>Manageable</p>
      </div>
      <p style="margin-top: 40px;">Keep track of your previous and future consultations with ease on the go. Manage and update any details of your past or future consultations, and view your appointment history. Maintain your patient feedback and use MedConnect to build your practice and grow. </p>
    </div>
  </div>
</div>

<!-- Sixth Section -->

<div class="index-sixth">
  <div class="index-sixth-wrappers">
    <div class="box box-1">
      <h4>Write and share your knowledge</h4>
    </div>
    <div class="box box-2">
      <h4>Build and maintain your profile</h4>
    </div>
    <div class="box box-3">
      <h4>Work on your feedback to grow</h4>
    </div>
  </div>
  <div class="index-sixth-head">
    <h2>Don't Just Consult</h2>
    <h1>There's More To MedConnect</h1>
    <hr>
    <p>Just because it's called MedConnect, doesn't mean that you only have to connect with patients. You can also use MedConnect to do various other things like accessing your feed in order to create medical related articles to share your knowledge. On top of that, you can also build your profile, grow your practice and use your patient feedback to improve your consultations.</p>
  </div>
</div>

<!-- Seventh Section -->

<div class="index-seventh">
  <div class="index-seventh-head">
    <h3 style="text-align: center;">Have Various Specializations?</h3>
    <h1>Build Your Profile Now</h1>
    <h3 class="get-started-today-btn" style="color: white;" onclick="location.href='consultant_profile.php'">Your Profile</h3>
  </div>
  <div class="index-seventh-checks">
    <div style="display: flex">
      <i style="background-color: green; color: white; padding: 5px; border-radius: 20px; font-size: 20px;" class="fa-solid fa-check"></i>
      <span style="color: #614124; margin-left: 20px; margin-top: 5px; font-size: 19px;">Cold/Cough</span>
    </div>
    <div style="display: flex">
      <i style="background-color: green; color: white; padding: 5px; border-radius: 20px; font-size: 20px;" class="fa-solid fa-check"></i>
      <span style="color: #614124; margin-left: 10px; margin-top: -5px; padding: 10px; font-size: 19px;">Stomach</span>
    </div>
    <div style="display: flex">
      <i style="background-color: green; color: white; padding: 5px; border-radius: 20px; font-size: 20px;" class="fa-solid fa-check"></i>
      <span style="color: #614124; margin-left: 10px; margin-top: -5px; padding: 10px; font-size: 19px;">Psychiatry</span>
    </div>
    <div style="display: flex">
      <i style="background-color: green; color: white; padding: 5px; border-radius: 20px; font-size: 20px;" class="fa-solid fa-check"></i>
      <span style="color: #614124; margin-left: 10px; margin-top: -5px; padding: 10px; font-size: 19px;">Infections</span>
    </div>
    <div style="display: flex">
      <i style="background-color: green; color: white; padding: 5px; border-radius: 20px; font-size: 20px;" class="fa-solid fa-check"></i>
      <span style="color: #614124; margin-left: 10px; margin-top: -5px; padding: 10px; font-size: 19px;">Injuries</span>
    </div>
    <div style="display: flex">
      <i style="background-color: green; color: white; padding: 5px; border-radius: 20px; font-size: 20px;" class="fa-solid fa-check"></i>
      <span style="color: #614124; margin-left: 10px; margin-top: -5px; padding: 10px; font-size: 19px;">Concerns</span>
    </div>
  </div>
</div>

<!-- Eighth Section -->

<div class="index-eighth">
  <div class="index-eighth-wrappers">
    <div class="individual individual-1">
      <center><i style="background-color: white; color: #684414; padding: 20px; border-radius: 30px; width: 20px; font-size: 20px; margin-top: -50px;" class="fa-solid fa-circle-info"></i></center>
      <h2>About Us</h2>
      <h4>Learn more about MedConnect, including our mission and vision, our testimonials and more about why MedConnect was founded and what purpose we strive to serve.</h4>
      <button class="first">About Us</button>
    </div>
    <div class="individual individual-2">
      <center><i style="background-color: white; color: #684414; padding: 20px; border-radius: 30px; width: 20px; font-size: 20px; margin-top: -50px;" class="fa-solid fa-envelope"></i></center>
      <h2>Contact</h2>
      <h4>If you have any questions or complaints, feel free to contact us and reach out us with your perspectives and will we will respond back to you within a convenient period of time for you.</h4>
      <button class="second">Contact</button>
    </div>
    <div class="individual individual-3">
      <center><i style="background-color: white; color: #684414; padding: 20px; border-radius: 30px; width: 20px; font-size: 20px; margin-top: -50px;" class="fa-solid fa-question"></i></center>
      <h2>FAQ</h2>
      <h4>If you're stuck or need some help, view our most frequently asked questions and answers in order for us to make the entire process easy and generalised as per your convenience.</h4>
      <button class="third">FAQ</button>
    </div>
  </div>
</div>

<!-- Ninth Section -->

<div class="index-ninth">
  <h1>100% Satisfaction Guarantee</h1>
  <h4>Consult with patients and grow your practice through MedConnect. It's quick, effective and convenient.</h4>
  <button class="fourth">Get Started</button>
</div>

<!-- Tenth Section (Footer) -->

<footer>
  <div class="footer">
    <div class="footer-item footer-1">
      <h1 class="footer-head">MedConnect</h1>
      <h3>Need help? Contact us at <br> support@medconnect.com</h3>
      <div style="display: flex; flex-direction: column; gap: 2em;">
        <p><abbr style="cursor: pointer; border-bottom: 1px dashed white;">Terms of Service</abbr> & <abbr style="cursor: pointer; border-bottom: 1px dashed white;"> Privacy Policy</abbr></p>
        <p>MedConnect © 2023</p>
      </div>
    </div>
    <div class="footer-item footer-2">
      <u><b><p>Services</p></b></u>
      <p style="cursor: pointer;">Consult</p>
      <p style="cursor: pointer;">Profile</p>
      <p style="cursor: pointer;">Resources</p>
      <p style="cursor: pointer;">Feed</p>
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

<!-- Javascript -->

<script type="text/javascript">

var heading = document.getElementById('heading');
var subheading = document.getElementById('subheading');
var body = document.getElementById('body');
var each_item = document.getElementsByClassName('item');

// Item 1

each_item[0].addEventListener('click', function onClick() {
  each_item[0].style.backgroundColor = '#805c24';
  each_item[0].style.color = 'white';
  each_item[1].style.backgroundColor = 'white';
  each_item[1].style.color = '#704a1b';
  each_item[2].style.backgroundColor = 'white';
  each_item[2].style.color = '#704a1b';
  each_item[3].style.backgroundColor = 'white';
  each_item[3].style.color = '#704a1b';
  each_item[4].style.backgroundColor = 'white';
  each_item[4].style.color = '#704a1b';
  each_item[5].style.backgroundColor = 'white';
  each_item[5].style.color = '#704a1b';
  heading.innerText = "Appoint Your Patients";
  subheading.innerText = "Consult";
  body.innerText = "Patients will book consultations and seek medical assistance \n from you. Using your consult tab, you can work with your \n present patients and manage your consultations. When a \n patient consults with you, address their medical needs and \n effectively interact with them in order to grow your practice.";
});

// Item 2

each_item[1].addEventListener('click', function onClick() {
  each_item[0].style.backgroundColor = 'white';
  each_item[0].style.color = '#704a1b';
  each_item[1].style.backgroundColor = '#805c24';
  each_item[1].style.color = 'white';
  each_item[2].style.backgroundColor = 'white';
  each_item[2].style.color = '#704a1b';
  each_item[3].style.backgroundColor = 'white';
  each_item[3].style.color = '#704a1b';
  each_item[4].style.backgroundColor = 'white';
  each_item[4].style.color = '#704a1b';
  each_item[5].style.backgroundColor = 'white';
  each_item[5].style.color = '#704a1b';
  heading.innerText = "Keep Track Of Everything";
  subheading.innerText = "Maintain";
  body.innerText = "Keep track of all your consultations, patients, history and \n everything more and between. Our user-friendly interface \n helps support simple maintainence of your information and \n your patient's. Furthermore, using your consult tab, you can \n manage and update as per your needs.";
});

// Item 3

each_item[2].addEventListener('click', function onClick() {
  each_item[0].style.backgroundColor = 'white';
  each_item[0].style.color = '#704a1b';
  each_item[1].style.backgroundColor = 'white';
  each_item[1].style.color = '#704a1b';
  each_item[2].style.backgroundColor = '#805c24';
  each_item[2].style.color = 'white';
  each_item[3].style.backgroundColor = 'white';
  each_item[3].style.color = '#704a1b';
  each_item[4].style.backgroundColor = 'white';
  each_item[4].style.color = '#704a1b';
  each_item[5].style.backgroundColor = 'white';
  each_item[5].style.color = '#704a1b';
  heading.innerText = "Communicate With Your Patients";
  subheading.innerText = "Interact";
  body.innerText = "Work closely and communicate effectively with your patients \n in order to address their medical needs, provide remote medical \n assistance, and share your knowledge efficiently. MedConnect \n allows for seamless online communication with a user-friendly \n interface, so you can stay in touch with your patient always.";
});

// Item 4

each_item[3].addEventListener('click', function onClick() {
  each_item[0].style.backgroundColor = 'white';
  each_item[0].style.color = '#704a1b';
  each_item[1].style.backgroundColor = 'white';
  each_item[1].style.color = '#704a1b';
  each_item[2].style.backgroundColor = 'white';
  each_item[2].style.color = '#704a1b';
  each_item[3].style.backgroundColor = '#805c24';
  each_item[3].style.color = 'white';
  each_item[4].style.backgroundColor = 'white';
  each_item[4].style.color = '#704a1b';
  each_item[5].style.backgroundColor = 'white';
  each_item[5].style.color = '#704a1b';
  heading.innerText = "Show Your Knowledge";
  subheading.innerText = "Share";
  body.innerText = "Using your MedConnect Consultant Feed, you can create and edit \n personal articles which can be made by you. Although others won't \n directly be able to view the article on MedConnect, you can share \n and embed your article to share on other platforms. Show your \n medical knowledge and expertise to grow your online practice.";
});

// Item 5

each_item[4].addEventListener('click', function onClick() {
  each_item[0].style.backgroundColor = 'white';
  each_item[0].style.color = '#704a1b';
  each_item[1].style.backgroundColor = 'white';
  each_item[1].style.color = '#704a1b';
  each_item[2].style.backgroundColor = 'white';
  each_item[2].style.color = '#704a1b';
  each_item[3].style.backgroundColor = 'white';
  each_item[3].style.color = '#704a1b';
  each_item[4].style.backgroundColor = '#805c24';
  each_item[4].style.color = 'white';
  each_item[5].style.backgroundColor = 'white';
  each_item[5].style.color = '#704a1b';
  heading.innerText = "Develop Your Profile";
  subheading.innerText = "Build";
  body.innerText = "Patients are only going to consult with you if you seem credible \n and experienced. On that note, build your online medical profile \n as a doctor/consultant to be involved in more appointments with \n patients and to gain better satisfactory reviews and referrals on \n MedConnect for others to consult with you and address their needs.";
});

// Item 6

each_item[5].addEventListener('click', function onClick() {
  each_item[0].style.backgroundColor = 'white';
  each_item[0].style.color = '#704a1b';
  each_item[1].style.backgroundColor = 'white';
  each_item[1].style.color = '#704a1b';
  each_item[2].style.backgroundColor = 'white';
  each_item[2].style.color = '#704a1b';
  each_item[3].style.backgroundColor = 'white';
  each_item[3].style.color = '#704a1b';
  each_item[4].style.backgroundColor = 'white';
  each_item[4].style.color = '#704a1b';
  each_item[5].style.backgroundColor = '#805c24';
  each_item[5].style.color = 'white';
  heading.innerText = "Grow Your Practice";
  subheading.innerText = "Expand";
  body.innerText = "Through MedConnect, you can expand and efficiently grow your \n medical practice by reaching out to more patients and addressing \n their medical needs and providing remote, effective medical \n assistance in order to reach larger audiences. With MedConnect, \n you can seamlessly go about consultation processes to expand.";
});

</script>

<script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>
</body>
</html>
