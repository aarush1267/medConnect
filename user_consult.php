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
    }

    // Set default section to 'Find a Consult'
    toggleConsultSection('find');

    function toggleNotifications() {
        const dropdown = document.getElementById('notificationsDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
</script>

<!-- Font Awesome for Icons -->
<script defer src="https://use.fontawesome.com/releases/v6.4.0/js/all.js"></script>

</body>
</html>
