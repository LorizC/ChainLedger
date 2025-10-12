<?php 
include 'dist/admin/handlers/login.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to ChainLedger</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
  <style>
    /* ✅ Flash message styling */
    .flash-message {
      max-width: 400px;
      margin: 1rem auto;
      padding: 12px 16px;
      border-radius: 8px;
      font-weight: 500;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      animation: fadeDown 0.4s ease;
    }
    .flash-success {
      background-color: #d1fae5;
      color: #065f46;
    }
    .flash-error {
      background-color: #fee2e2;
      color: #991b1b;
    }
    @keyframes fadeDown {
      from { opacity: 0; transform: translateY(-10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- Theme Button -->
  <input type="checkbox" id="theme-toggle" hidden />
  <div class="page">
    <label for="theme-toggle" class="theme-button">
      <span class="material-icons light-icon">light_mode</span>
      <span class="material-icons dark-icon">dark_mode</span>
    </label>
      <!-- Flash messages -->
      <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="flash-message flash-success">
          <?= htmlspecialchars($_SESSION['flash_success']); ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash-message flash-error">
          <?= htmlspecialchars($_SESSION['flash_error']); ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
      <?php endif; ?>
    <div class="background-blur"></div>

    <div class="content-wrapper">



      <!-- Left side -->
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

      <!-- Home Button -->
      <a href="dist/admin/landingpage/index.php" class="home-button" title="Go to Home">
        <span class="material-icons">home</span>
      </a> 

      <!-- Right side: Login form -->
      <div class="right-side">
        <div class="form-wrapper">
          <div class="inFormBackground">
            <div class="inLoginForm">
              <form method="POST" action="">
                <div class="title">
                  <h3 class="login-title">Login Here</h3>
                </div>

                <div class="inputGroup">
                  <label for="account_id">Account ID</label>
                  <input type="text" id="account_id" name="account_id" placeholder="Enter 6-digit Account ID" 
                         pattern="\d{6}" maxlength="6" required
                         oninput="this.value=this.value.replace(/[^0-9]/g,''); this.setCustomValidity('');"
                         oninvalid="this.setCustomValidity('Please enter exactly 6 digits')" />
                </div>

                <div class="inputGroup">
                  <label for="password">Password</label>
                  <div style="position: relative;">
                    <input type="password" placeholder="Enter Password" id="password" name="password" required />
                    <span data-toggle="#password" class="toggle-password" 
                          style="position:absolute; right:25px; top:55%; transform:translateY(-50%); cursor:pointer;">
                      <i class="fas fa-eye"></i>
                    </span>
                  </div>
                  <div class="forgot-password">
                    <a href="dist/admin/forms/forgot_password.php">Forgot Password?</a>
                  </div>
                </div>

                <div class="button-container">
                  <button type="submit" class="submitForm">Log In</button> 
                  <div class="signup-row">
                    <p class="new-account">Create New Account</p>
                    <a href="dist/admin/forms/signup.php" class="submitForm signup-btn">Sign Up</a>
                  </div>
                </div>

              </form>
            </div>
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
<script src="dist/assets/js/js/scripts.js"></script>
</body>
</html>

<?php ob_end_flush(); ?>
