<?php 
ob_start();
include('../handlers/forgot_password.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ChainLedger | Forgot Password</title>
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

      <!-- Right side: Forgot Password form -->
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
              <h3 class="login-title">Forgot Password</h3>
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
              <label for="security-question">Security Question</label>
              <select id="security-question" name="security_question" required>
              <option value="" disabled selected>Select a question</option>
                <option value="First Pet">What is the name of your first pet?</option>
                <option value="Elementary School">What is the name of your elementary school?</option>
                <option value="Favorite Food">What is your favorite food?</option>
                <option value="Childhood Nickname">What was your childhood nickname?</option>
              </select>
            </div>

            <div class="inputGroup">
              <label for="security-answer">Your Answer</label>
              <input type="text" placeholder="Enter your answer" id="security-answer" name="security_answer" required />
            </div>           

            <div class="button-container">
              <button type="submit" class="submitForm">Next</button>
              <div class="signup-row">
                <p class="new-account">Remembered?</p>
                <a href="../../index.php" class="submitForm signup-btn">Login</a>
              </div>
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
</body>
</html>

<?php ob_end_flush(); ?>
