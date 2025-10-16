<?php include('../handlers/set_password.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ChainLedger | Set Password </title>
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
      <!-- Left side -->
      <div class="left-side">
        <div class="welcome-message">
          <h1>Welcome to ChainLedger</h1>
          <p>An Organizational E-wallet Transaction Monitoring System</p>
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

      <!-- Right side -->
      <div class="form-section">
        <?php if (!empty($username)): ?>
          <div class="success-message global-banner">
            Welcome, <?= htmlspecialchars($username) ?>! <br>
            Please fill the form to complete your registration.
          </div>
        <?php endif; ?>      

        <div class="inFormBackground">
          <div class="inLoginForm">
            <?php if (!empty($error)): ?>
              <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <!-- Single form only -->
            <form method="post" action="">
              <div class="title">
                <h3 class="login-title">Set Password</h3>
              </div>

              <div class="inputGroup">
                <label for="role">Select Role:</label>
                <select name="role" id="role" required>
                  <option value="">-- Select Role --</option>
                  <option value="Business Owner">Business Owner</option>
                  <option value="Manager">Manager</option>
                  <option value="Staff">Staff</option>
                </select>
              </div>

              <div class="inputGroup">
                <label for="password">Password</label>
                <input type="password" placeholder="Enter Password" id="password" name="password" required />
              </div>

              <div class="inputGroup">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required />
              </div>

              <p id="togglePassword" style="font-size:0.9em; color:blue; cursor:pointer; margin-top:5px;">
                Show Passwords
              </p>

              <div class="button-container">
                <button type="submit" class="submitForm">Create Account</button>
                <div class="signup-row">
                  <p class="new-account">Already have an Account?</p>
                  <a href="../../index.php" class="submitForm signup-btn">Log In</a>
                </div>
              </div>
            </form>
          </div>
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
</body>
</html>
