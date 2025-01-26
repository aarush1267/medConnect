// Validate the Email

function validateEmail(signUpEmail) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(signUpEmail);
}

function checkEmail() {
  var signUpName = $("#signupnametxt").val();
  var signUpEmail = $("#signupemailtxt").val();
  var signUpPassword = $("#signuppwdtxt").val();
  if (signUpName && signUpEmail && signUpPassword && signUpPassword.length >= 6 && validateEmail(signUpEmail)) {
    jQuery.ajax ({
      url: "config.php",
      data: {
        signUpName:signUpName,
        signUpEmail:signUpEmail,
        signUpPassword:signUpPassword
      },
      type: "POST",
      success:function(data){
        $("#signupEmailExists").show();
        $("#signupemailtxt").css("border", "2px solid green");
        if(data) {
          $("#signupemailtxt").css("border", "2px solid red");
          $("#signupEmailExists").css("visibility","visible");
        } else {
          $("#signupemailtxt").css("border", "2px solid green");
          $("#signupEmailExists").css("visibility","hidden");
          window.location.href = 'role.php';
        }
        $("#signupEmailExists").html(data);
      },
      error:function () {
      }
    });
  }
}

// Function to get the appropriate interface URL based on the user's role

function getRedirectURL(role) {
  if (role === 'user') {
    return 'user_index.php';
  } else if (role === 'consultant') {
    return 'consultant_index.php';
  } else {
    return 'login.php';
  }
}

// AJAX for Login System

function checkLogin() {
  var logInEmail = $("#loginemailtxt").val();
  var logInPassword = $("#loginpwdtxt").val();

  if (logInEmail.length > 0 && logInPassword.length > 0) {
    jQuery.ajax ({
      url: "backend.php",
      data: {
        logInEmail: logInEmail,
        logInPassword: logInPassword
      },
      type: "POST",
      success: function(data) {
        $("#loginIncorrectCreds").show();
        $("#loginemailtxt").css("border", "2px solid green");
        $("#loginpwdtxt").css("border", "2px solid green");
        if (data) {
          $("#loginemailtxt").css("border", "2px solid red");
          $("#loginpwdtxt").css("border", "2px solid red");
          $("#loginIncorrectCreds").css("visibility", "visible");
        } else {
          $("#loginemailtxt").css("border", "2px solid green");
          $("#loginpwdtxt").css("border", "2px solid green");
          $("#loginIncorrectCreds").css("visibility", "hidden");
          // Redirect to the appropriate interface
          var role = '<?php echo $_SESSION["role"]; ?>';
          var redirectURL = getRedirectURL(role);
          window.location.href = redirectURL;
        }
        $("#loginIncorrectCreds").html(data);
      },
      error: function () {
        // Handle error case
      }
    });
  }
}
