<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>MedConnect User | Consult</title>
    <style>
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

        /* Consult Navigation Bar */
        .consult-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 30px auto;
            width: 80%;
            font-family: Lora;
            position: relative;
        }

        /* Center the Consult Tabs */
        .consult-tabs-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }

        /* Consult Navigation Tabs */
        .consult-tabs {
            display: flex;
            gap: 2em;
            font-size: 18px;
            cursor: pointer;
        }

        /* Notifications & Settings Buttons */
        .nav-btn {
            background-color: #60a159;
            color: white;
            font-family: Lora;
            font-size: 16px;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 1px 1px black;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover {
            background-color: #4d8a48;
        }

        .nav-btn:active {
            box-shadow: none;
        }

        .nav-btn i {
            font-size: 18px;
        }

        /* Positioning Notifications & Settings */
        .notifications-btn {
            position: absolute;
            font-size: 18px;
            left: -50px;
        }

        .settings-btn {
            position: absolute;
            right: -40px;
            font-size: 18px;
            margin-top: 20px;
        }

        /* Consult Navigation Tabs */
        .consult-tabs {
            display: flex;
            gap: 2em;
            font-size: 18px;
            cursor: pointer;
        }

        .consult-tab {
            position: relative;
            padding-bottom: 5px;
            color: #704a1b;
        }

        .consult-tab.active {
            font-weight: bold;
            color: #60a159;
        }

        .consult-tab.active::after {
            content: "";
            display: block;
            width: 100%;
            height: 2px;
            background-color: #60a159;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        /* Search Bar */
        #search-bar-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        #search-bar {
            width: 500px;
            height: 35px;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #704a1b;
            border-radius: 5px 0 0 5px;
            outline: none;
            font-family: Lora;
        }

        #search-btn {
            height: 35px;
            background-color: #60a159;
            color: white;
            font-size: 16px;
            font-family: Lora;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            padding: 5px 15px;
            transition: background-color 0.3s ease;
        }

        #search-btn:hover {
            background-color: #4e8a45;
        }

        /* Notifications Dropdown */
        #notificationsDropdown {
            display: none;
            position: absolute;
            top: 220px;
            left: 30px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 5px;
            width: 250px;
        }

        .how-it-works-container {
            background-color: #5B9156; /* Matching green shade */
            padding: 60px 50px;
            text-align: center;
            margin-top: 50px;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
        }

        /* Title Styling */
        .how-it-works-title {
            color: rgba(255, 255, 255, 0.8);
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .how-it-works-heading {
            font-weight: bold;
            color: #fff;
            margin-bottom: 40px;
        }

        /* Steps Layout */
        .steps-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            min-width: 150px;
            max-width: 200px;
        }

        /* Icons */
        .step-icon {
            font-size: 30px;
            color: #fff;
            margin-bottom: 10px;
        }

        /* Step Title */
        .step-title {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 8px;
        }

        /* Step Description */
        .step-description {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.4;
        }

        /* Arrows */
        .arrow {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Button */
        .cta-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #fff;
            color: #5B9156;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 1px 1px black;
        }

        .cta-button:hover {
            background-color: #385c34;
            color: #fff;
        }

        .cta-button:active {
            box-shadow: none;
        }

        /* Testimonials Section */
        .testimonials-container {
            background-color: #E7C79A; /* Soft beige background matching the website */
            padding: 60px 50px;
            text-align: center;
            border-radius: 15px;
            margin-top: 50px;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
        }

        /* Section Title */
        .testimonials-title {
            color: rgba(50, 40, 30, 0.8);
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .testimonials-heading {
            font-weight: bold;
            color: #6D4C41; /* Brown color matching the website */
            margin-bottom: 30px; /* Moved testimonials 30px down */
        }

        /* Testimonials Wrapper */
        .testimonial-wrapper {
            display: flex;
            justify-content: center; /* Centers the testimonial */
            align-items: center;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        /* Hide testimonials except for the active one */
        .testimonial-slider {
            display: flex;
            transition: transform 0.5s ease-in-out; /* Smooth sliding effect */
            width: 210%; /* Ensures all testimonials are positioned in a row */
        }

        .testimonial {
            flex: 0 0 100%; /* Each testimonial takes full width */
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            box-sizing: border-box;
            flex-basis: 20%;
            max-width: 800px;
        }

        /* Ensure only the active testimonial is shown */
        .testimonial.active {
            display: block;
        }

        /* Star Rating */
        .star-rating {
            color: #F4C150; /* Gold stars */
            font-size: 18px;
        }

        /* Testimonial Text */
        .testimonial-text {
            font-size: 16px;
            color: rgba(50, 40, 30, 0.9);
            line-height: 1.5;
        }

        /* Testimonial Author */
        .testimonial-author {
            font-size: 18px;
            font-weight: bold;
            color: #6D4C41; /* Brown matching the website */
        }

        .testimonial-role {
            font-size: 14px;
            color: rgba(50, 40, 30, 0.7);
        }

        /* Navigation Buttons */
        .testimonial-nav {
            position: absolute;
            top: 50%; /* Centers buttons relative to testimonial */
            transform: translateY(-50%);
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 18px;
            color: #6D4C41;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .testimonial-nav:hover {
            background: #6D4C41;
            color: white;
        }

        .left-nav {
            left: 10px;
        }

        .right-nav {
            right: 10px;
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
          margin-top: 50px;
        }

        @media screen and (max-width: 800px) {
          .footer {
            flex-direction: column;
            gap: 3em;
          }
        }

        /* Search Results Popup */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            z-index: 1000;

            /* Transition effect */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s;
        }

        .popup-content {
            text-align: center;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }

        .search-result-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-result-item:hover {
            background: #f5f5f5;
        }

        /* Overlay to dim background */
        #overlay {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
            z-index: 999; /* Behind the popup, but above everything else */

            /* Transition effect */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s;
        }

        /* Ensure popup is above overlay */
        .popup {
            z-index: 1000;
        }

        /* Show popup & overlay with fade-in effect */
        .popup.show {
            opacity: 1;
            visibility: visible;
        }

        #overlay.show {
            opacity: 1;
            visibility: visible;
        }

    </style>
</head>
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

<div class="consult-nav">
    <!-- Centered Tabs Container -->
    <div class="consult-tabs-container">
        <div class="consult-tabs">
            <span class="consult-tab" onclick="toggleConsultSection('previous')">Previous Consultations</span>
            <span class="consult-tab active" onclick="toggleConsultSection('find')">Find a Consult</span>
            <span class="consult-tab" onclick="toggleConsultSection('current')">Current Consultations</span>
        </div>
    </div>
</div>

<!-- Search Bar (Only visible in 'Find a Consult' section) -->

<div id="search-bar-container">
    <!-- Notifications Button (Left of Search Bar) -->
    <button style="margin-right: 20px;" class="nav-btn" onclick="toggleNotifications()">
        <i class="fa-solid fa-bell"></i>
    </button>

    <!-- Search Bar -->
    <input type="text" id="search-bar" placeholder="Search for consultants...">
    <button id="search-btn">Search</button>

    <!-- Settings Button (Right of Search Bar) -->
    <button style="margin-left: 20px;" class="nav-btn">
        <i class="fa-solid fa-cog"></i>
    </button>
</div>

<!-- Notifications Dropdown (Initially Hidden) -->
<div id="notificationsDropdown">
    <p>No new notifications</p> <!-- Dynamic Content Here -->
</div>

<!-- Search Results Popup -->
<div id="searchResultsPopup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <h2>Search Results</h2>
        <div id="searchResults"></div>
    </div>
</div>

<div id="overlay"></div>

<!-- Find Consult Section -->

<div id="findConsultSection">
  <div class="how-it-works-container">
      <h2 class="how-it-works-title">How It Works</h2>
      <h1 class="how-it-works-heading">Find & Book a Consultation</h1>

      <div class="steps-container">
          <div class="step">
              <div class="step-icon"><i class="fas fa-search"></i></div>
              <div class="step-title">Search</div>
              <div class="step-description">Find a consultant based on specialty, name or relevant information.</div>
          </div>

          <div class="arrow">→</div>

          <div class="step">
              <div class="step-icon"><i class="fas fa-user-md"></i></div>
              <div class="step-title">View</div>
              <div class="step-description">Check consultant profile: details, expertise, and availability first.</div>
          </div>

          <div class="arrow">→</div>

          <div class="step">
              <div class="step-icon"><i class="fas fa-file-alt"></i></div>
              <div class="step-title">Request</div>
              <div class="step-description">Fill out the form details and submit a consultation request.</div>
          </div>

          <div class="arrow">→</div>

          <div class="step">
              <div class="step-icon"><i class="fas fa-check-circle"></i></div>
              <div class="step-title">Approval</div>
              <div class="step-description">The consultant accepts or rejects your request, based on your data.</div>
          </div>

          <div class="arrow">→</div>

          <div class="step">
              <div class="step-icon"><i class="fas fa-calendar-alt"></i></div>
              <div class="step-title">Schedule</div>
              <div class="step-description">Pick a time for the consultation and enter it in the consultation.</div>
          </div>

          <div class="arrow">→</div>

          <div class="step">
              <div class="step-icon"><i class="fas fa-video"></i></div>
              <div class="step-title">Attend</div>
              <div class="step-description">Join the consultation at the scheduled time, with the details.</div>
          </div>
      </div>

      <a class="cta-button" onclick="focusSearchBar()">Start Your Search</a>
  </div>

  <!-- Testimonials -->

  <div class="testimonials-container">
      <h2 class="testimonials-title">What Our Users Say</h2>
      <h1 class="testimonials-heading">Trusted by Users & Consultants</h1>

      <div class="testimonial-wrapper">
          <button class="testimonial-nav left-nav" onclick="prevTestimonial()">&#10094;</button>

          <div class="testimonial-slider">
              <div class="testimonial">
                  <div class="star-rating">★★★★★</div>
                  <div class="testimonial-text">
                      "This consultation service made it easy to find the right expert. The process was seamless, and I got valuable insights!"
                  </div>
                  <div class="testimonial-author">James R.</div>
                  <div class="testimonial-role">Consultation User</div>
              </div>

              <div class="testimonial">
                  <div class="star-rating">★★★★★</div>
                  <div class="testimonial-text">
                      "The platform is intuitive and efficient. I booked a consult within minutes and got exactly the advice I needed!"
                  </div>
                  <div class="testimonial-author">Samantha L.</div>
                  <div class="testimonial-role">User - Business Strategy Consultation</div>
              </div>

              <div class="testimonial">
                  <div class="star-rating">★★★★★</div>
                  <div class="testimonial-text">
                      "As a consultant, I appreciate the seamless booking system. Clients can connect with me easily, and the interface is smooth!"
                  </div>
                  <div class="testimonial-author">Dr. Michael K.</div>
                  <div class="testimonial-role">Registered Consultant</div>
              </div>
          </div>

          <button class="testimonial-nav right-nav" onclick="nextTestimonial()">&#10095;</button>
      </div>
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
        <p>MedConnect © 2023</p>
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

<script>

    function toggleConsultSection(section) {
        // Remove active class from all tabs
        document.querySelectorAll('.consult-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        // Add active class to the clicked tab
        document.querySelector(`[onclick="toggleConsultSection('${section}')"]`).classList.add('active');

        // Show/hide search bar based on section
        document.getElementById('search-bar-container').style.display = section === 'find' ? 'flex' : 'none';

        // Show "How It Works" & "Testimonials" only in "Find a Consult"
        document.getElementById('findConsultSection').style.display = section === 'find' ? 'block' : 'none';
    }

    // Set default section to 'Find a Consult'
    toggleConsultSection('find');

    function toggleNotifications() {
        const dropdown = document.getElementById('notificationsDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    function focusSearchBar() {
        document.getElementById("search-bar").focus();
    }

    let currentTestimonialIndex = 0;
    const testimonialsPerSlide = 3;
    const totalTestimonials = 9; // Total testimonials
    const totalSections = totalTestimonials / testimonialsPerSlide;
    const slider = document.querySelector(".testimonial-slider");

    function showTestimonial(index) {
        let offset = -(index * 100); // Moves 100% per section
        slider.style.transform = `translateX(${offset}%)`;
    }

    function prevTestimonial() {
        currentTestimonialIndex = (currentTestimonialIndex === 0) ? totalSections - 1 : currentTestimonialIndex - 1;
        showTestimonial(currentTestimonialIndex);
    }

    function nextTestimonial() {
        currentTestimonialIndex = (currentTestimonialIndex === totalSections - 1) ? 0 : currentTestimonialIndex + 1;
        showTestimonial(currentTestimonialIndex);
    }

    // AJAX for search results

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("search-btn").addEventListener("click", function() {
            let searchQuery = document.getElementById("search-bar").value.trim();

            if (searchQuery === "") {
                alert("Please enter a search term.");
                return;
            }

            console.log("Sending search query:", searchQuery); // Debugging

            fetch("search_consultants.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({ query: searchQuery }), // Correct format
            })
            .then(response => response.text()) // Get raw response first
            .then(text => {
                console.log("Raw Response:", text); // Debugging output

                try {
                    let data = JSON.parse(text); // Parse JSON correctly
                    console.log("Parsed Data:", data); // Debugging

                    let resultsContainer = document.getElementById("searchResults");
                    resultsContainer.innerHTML = "";

                    if (data.length === 0) {
                        resultsContainer.innerHTML = "<p>No consultants found.</p>";
                    } else {
                        data.forEach(consultant => {
                            let consultantDiv = document.createElement("div");
                            consultantDiv.classList.add("search-result-item");
                            consultantDiv.innerHTML = `<strong>${consultant.name}</strong> - ${consultant.services}`;
                            consultantDiv.onclick = function() {
                                window.location.href = "consultant_profile.php?id=" + consultant.id;
                            };
                            resultsContainer.appendChild(consultantDiv);
                        });
                    }

                    console.log("Opening popup...");
                    openPopup();
                } catch (error) {
                    console.error("JSON Parsing Error:", error, "Response:", text);
                }
            })
            .catch(error => console.error("AJAX Error:", error));
        });
    });

    function openPopup() {
        let popup = document.getElementById("searchResultsPopup");
        let overlay = document.getElementById("overlay");

        popup.classList.add("show"); // Apply fade-in effect
        overlay.classList.add("show");

        popup.style.display = "block"; // Ensure it's visible after animation
        overlay.style.display = "block";
    }

    function closePopup() {
        let popup = document.getElementById("searchResultsPopup");
        let overlay = document.getElementById("overlay");

        popup.classList.remove("show"); // Apply fade-out effect
        overlay.classList.remove("show");

        // Wait for animation to complete before hiding
        setTimeout(() => {
            popup.style.display = "none";
            overlay.style.display = "none";
        }, 300); // Same duration as CSS transition
    }

</script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="script.js"></script>
  <script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>

</body>
</html>
