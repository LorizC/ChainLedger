<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Password - ChainLedger</title>
  <link rel="stylesheet" href="../../../css/style.css" />
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
      <!-- Left Side -->
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

      <!-- Right Side: Change Password -->
      <div class="inFormBackground">
        <div class="inLoginForm">
          
          <?php if (!empty($_SESSION['flash_success'])): ?>
            <p style="color: green; text-align:center;">
              <?= htmlspecialchars($_SESSION['flash_success']); ?>
            </p>
            <?php unset($_SESSION['flash_success']); ?>
          <?php endif; ?>

          <?php if (!empty($_SESSION['flash_error'])): ?>
            <p style="color: red; text-align:center;">
              <?= htmlspecialchars($_SESSION['flash_error']); ?>
            </p>
            <?php unset($_SESSION['flash_error']); ?>
          <?php endif; ?>

          <form method="POST" action="password.php">
            <div class="title">
              <h3 class="login-title">Change Password</h3>
            </div>

            <div class="inputGroup">
              <label for="username">Username</label>
              <input 
                type="text" 
                id="username" 
                name="username" 
                value="<?= htmlspecialchars($_SESSION['user']['username'] ?? ''); ?>" 
                readonly 
                class="bg-gray-100 cursor-not-allowed"
              />
            </div>

            <div class="inputGroup">
              <label for="current-password">Current Password</label>
              <input 
                type="password" 
                id="current-password" 
                name="current_password" 
                placeholder="Enter your current password" 
                required 
              />
            </div>

            <div class="inputGroup">
              <label for="new-password">New Password</label>
              <input 
                type="password" 
                id="new-password" 
                name="new_password" 
                placeholder="Enter your new password" 
                required 
              />
            </div>

            <div class="inputGroup">
              <label for="confirm-password">Confirm Password</label>
              <input 
                type="password" 
                id="confirm-password" 
                name="confirm_password" 
                placeholder="Re-enter your new password" 
                required 
              />
            </div>          

            <div class="button-container">
              <button type="submit" class="submitForm">Change Password</button>
              <div class="signup-row">
                <p class="new-account">Go back</p>
                <a href="../dashboard.php" class="submitForm signup-btn">Back</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script src="/../../../js/user.js"></script>
</body>
</html>

<?php ob_end_flush(); ?>
