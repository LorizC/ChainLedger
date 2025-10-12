<?php 
include 'dist/admin/handlers/login.php'; 

if (isset($_SESSION['flash_success'])): ?>
  <div style="
    background-color: #d1fae5;
    color: #065f46;
    padding: 12px 16px;
    margin: 16px;
    border-radius: 8px;
    font-weight: 500;
    text-align: center;
  ">
    <?= htmlspecialchars($_SESSION['flash_success']); ?>
  </div>
<?php
unset($_SESSION['flash_success']);
endif;

if (isset($_SESSION['flash_error'])): ?>
  <div style="
    background-color: #fee2e2;
    color: #991b1b;
    padding: 12px 16px;
    margin: 16px;
    border-radius: 8px;
    font-weight: 500;
    text-align: center;
  ">
    <?= htmlspecialchars($_SESSION['flash_error']); ?>
  </div>
<?php
unset($_SESSION['flash_error']);
endif;
?>?>

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
</head>
<body>
    <!-- Theme Button -->
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
    <!-- Home Button -->
<a href="dist/admin/landingpage/index.php" class="home-button" title="Go to Home">
  <span class="material-icons">home</span>
</a> 
      <!-- Right side: Login form -->
      <div class="right-side">
        <div class="form-wrapper">
          <div class="inFormBackground">

            <!-- ✅ Success banner -->
            <?php if (!empty($_SESSION['success_message'])): ?>
              <div class="success-message global-banner">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
              </div>
              <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="inLoginForm">
              <?php if (!empty($error)): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
              <?php endif; ?>

              <form method="POST" action="">
                <div class="title">
                  <h3 class="login-title">Login Here</h3>
                </div>

<div class="inputGroup">
  <label for="account_id">Account ID</label>
  <input type="text" 
         id="account_id" 
         name="account_id" 
         placeholder="Enter 6-digit Account ID" 
         pattern="\d{6}" 
         maxlength="6" 
         required
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

<script src="dist/assets/js/js/scripts.js"></script>
</body>
</html>

<?php ob_end_flush(); ?>
