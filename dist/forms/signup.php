<?php include('../handlers/signup.php'); ?>

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
              <h3 class="signup-title">Personal Information</h3>
            </div>

            <div class="inputGroup">
              <label for="firstName">First Name</label>
              <input type="text" placeholder="Enter First Name" id="firstName" name="first_name" required
                     pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"
                     value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" />
            </div>

            <div class="inputGroup">
              <label for="lastName">Last Name</label>
              <input type="text" placeholder="Enter Last Name" id="lastName" name="last_name" required
                     pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"
                     value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" />
            </div>

            <div class="inputGroup">
              <label for="birthdate">Birthdate</label>
              <input type="date" id="birthdate" name="birthdate" required
                     max="<?= date('Y-m-d') ?>"
                     value="<?= htmlspecialchars($_POST['birthdate'] ?? '') ?>" />
            </div>

            <div class="inputGroup">
              <label for="gender">Gender</label>
              <select id="gender" name="gender" required>
                <option value="" disabled <?= !isset($_POST['gender']) ? 'selected' : '' ?>>Select your gender</option>
                <option value="Male" <?= (($_POST['gender'] ?? '') === 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (($_POST['gender'] ?? '') === 'Female') ? 'selected' : '' ?>>Female</option>
              </select>
            </div>

            <div class="inputGroup">
              <label for="security-question">Security Question</label>
              <select id="security-question" name="security_question" required>
                <option value="" disabled <?= !isset($_POST['security_question']) ? 'selected' : '' ?>>Select a question</option>
                <option value="First Pet" <?= (($_POST['security_question'] ?? '') === 'First Pet') ? 'selected' : '' ?>>What is the name of your first pet?</option>
                <option value="Elementary School" <?= (($_POST['security_question'] ?? '') === 'Elementary School') ? 'selected' : '' ?>>What is the name of your elementary school?</option>
                <option value="Favorite Food" <?= (($_POST['security_question'] ?? '') === 'Favorite Food') ? 'selected' : '' ?>>What is your favorite food?</option>
                <option value="Childhood Nickname" <?= (($_POST['security_question'] ?? '') === 'Childhood Nickname') ? 'selected' : '' ?>>What was your childhood nickname?</option>
              </select>
            </div>

            <div class="inputGroup">
              <label for="security-answer">Your Answer</label>
              <input type="text" placeholder="Enter your answer" id="security-answer" name="security_answer" required
                     value="<?= htmlspecialchars($_POST['security_answer'] ?? '') ?>" />
            </div>

            <div class="button-container">
              <button type="submit" class="submitForm">Next</button>
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