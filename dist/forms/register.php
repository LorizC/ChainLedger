<?php include('../handlers/registerbusiness.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account - ChainLedger</title>
  <link rel="stylesheet" href="../../style.css" />
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
    <!-- Home Button -->
<a href="../landingpage/index.php" class="home-button" title="Go to Home">
  <span class="material-icons">home</span>
</a>  
      <!-- Right side: Sign Up form -->
      <div class="inFormBackground">
        <div class="inLoginForm">
<?php if ($error): ?>
  <p style="color:red; text-align:center; margin-bottom:0px;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

          <form method="POST" action="signup.php">
            <div class="title">
              <h3 class="signup-title">Business Registration</h3>
            </div>

            <div class="inputGroup">
              <label for="businessName">Business Name</label>
              <input type="text" placeholder="Enter Business Name" id="businessName" name="business_name" required
                     pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"
                     value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>" />
            </div>

            <div class="inputGroup">
              <label for="businessIndustry">Business Industry</label>
              <select id="business-industry" name="business_industry" required>
                <option value="" disabled <?= !isset($_POST['business_industry']) ? 'selected' : '' ?>>Select a question</option>
                <option value="Convenience Store" <?= (($_POST['business_industry'] ?? '') === 'Convenience Store') ? 'selected' : '' ?>>Convenience Store</option>
                <option value="Food Store" <?= (($_POST['business_industry'] ?? '') === 'Food Store') ? 'selected' : '' ?>>Food Store</option>
                <option value="Clothing Store" <?= (($_POST['business_industry'] ?? '') === 'Clothing Store') ? 'selected' : '' ?>>Clothing Store</option>
                <option value="Supply Store" <?= (($_POST['business_industry'] ?? '') === 'Supply Store') ? 'selected' : '' ?>>Supply Store</option>
                <option value="Equipment Store" <?= (($_POST['business_industry'] ?? '') === 'Equipment Store') ? 'selected' : '' ?>>Equipment Store</option>
              </select>
            </div>

            <div class="inputGroup">
              <label for="date_registered">Date Registered</label>
              <input type="date" id="date_registered" name="date_registered" required
                     max="<?= date('Y-m-d') ?>"
                     value="<?= htmlspecialchars($_POST['date_registered'] ?? '') ?>" />
            </div>

            <div class="button-container">
              <button type="submit" class="submitForm">Next</button>
              <div class="signup-row">
                <p class="new-account">Already Registered?</p>
                <a href="../../index.php" class="submitForm signup-btn">Log In</a>
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