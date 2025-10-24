<?php 
ob_start();
include('../handlers/change_password.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ChainLedger - Change Password </title>
  <link rel="stylesheet" href="../../style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
</head>
<body>
  <input type="checkbox" id="theme-toggle" hidden />
  <div class="page">
    <label for="theme-toggle" class="theme-button">
      <span class="material-icons light-icon">light_mode</span>
      <span class="material-icons dark-icon">dark_mode</span>
    </label>

    <div class="background-blur"></div>

    <div class="content-wrapper">
      <!-- Left side: Welcome message -->
      <div class="left-side">
        <div class="welcome-message">
          <h1>Welcome to ChainLedger</h1>
          <p>A Business E-wallet Transaction Monitoring System</p>
         
          <div class="features-section">
            <h2>Features</h2>
            <ul class="features-list">
              <li>Transaction Categorization</li>
              <li>Dashboard Overview</li>
              <li>Expense Monitoring</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Right side: Change Password form -->
      <div class="inFormBackground">
        <div class="inLoginForm">
          <?php if (!empty($success)): ?>
            <p style="color: green; text-align:center;"><?= htmlspecialchars($success) ?></p>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <p style="color: red; text-align:center;"><?= htmlspecialchars($error) ?></p>
          <?php endif; ?>
          
          <form method="POST" action="">
            <div class="title">
              <h3 class="login-title">Change Password</h3>
            </div>

            <div class="inputGroup">
              <label for="password">New Password</label>
              <input type="password" placeholder="Enter Password" id="password" name="new_password" minlength="8" required />
            </div>

            <div class="inputGroup">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" minlength="8" required />
            </div>

            <!-- Inline message + Show Passwords -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
             <p id="passwordMessage" style="font-size:0.8em; margin:0; text-align:left; transition:opacity 0.3s ease; opacity:0;"></p>
             <p id="togglePassword" style="font-size:0.9em; color:blue; cursor:pointer; margin:0;">Show Passwords</p>
            </div>

<div class="button-container">
  <button type="submit" class="submitForm">Next</button>
</div>

<div class="link-row">
  <p class="new-account">Already have an Account?</p>
  <a href="../../index.php" class="login-link">Log In</a>
</div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Required Js -->
<script src="../assets/js/plugins/simplebar.min.js"></script>
<script src="../assets/js/plugins/popper.min.js"></script>
<script src="../assets/js/icon/custom-icon.js"></script>
<script src="../assets/js/plugins/feather.min.js"></script>
<script src="../assets/js/component.js"></script>
<script src="../assets/js/theme.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/js/scripts.js"></script>

<script>
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirm_password');
  const submitButton = document.querySelector('.submitForm');
  const message = document.getElementById('passwordMessage');

  function checkPasswords() {
    if (password.value && confirmPassword.value) {
      if (password.value !== confirmPassword.value) {
        message.textContent = ' ⚠ Entered Passwords do not match!';
        message.style.color = 'red';
        message.style.opacity = '1';
        submitButton.disabled = true;
        submitButton.style.opacity = '0.6';
      } else {
        message.textContent = '✓ Passwords match';
        message.style.color = 'green';
        message.style.opacity = '1';
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
      }
    } else {
      message.textContent = '';
      message.style.opacity = '0';
      submitButton.disabled = false;
      submitButton.style.opacity = '1';
    }
  }

  password.addEventListener('input', checkPasswords);
  confirmPassword.addEventListener('input', checkPasswords);
</script>

</body>
</html>
<?php ob_end_flush(); ?>
