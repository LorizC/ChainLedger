<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../usercreation/login.php");
    exit;
}

// Include helper function
require_once __DIR__ . '/../../handlers/profile_helper.php';

//  Always get the latest session image, fallback to default
$rawProfileImage = $_SESSION['user']['profile_image'] ?? null;
$profileImage = getProfileImage($rawProfileImage);

//  Add cache-busting to ensure image refresh after update
$profileImage .= '?v=' . time();
?>

<aside class="sidebar">
  <!-- Logo -->
  <div class="logo">
    <img src="../../assets/images/logos/logo.png" alt="Logo" class="logo-img">
    <span class="label">ChainLedger</span>
  </div>

  <!-- Profile -->
  <div class="profile">
    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Avatar" class="avatar">
    <div class="profile-info">
      <span class="username"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
      <span class="fullname"><?php echo htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']); ?></span>
      <span class="account-id">Account ID: <?php echo htmlspecialchars($_SESSION['user']['account_id']); ?></span>
    </div>
  </div>

  <!-- Nav -->
  <nav>
    <div class="nav-item">
      <a href="dashboard.php">
        <span class="material-icons-outlined">home</span>
        <span class="label">Dashboard</span>
      </a>
      <a href="ledger.php">
        <span class="material-icons-outlined">receipt_long</span>
        <span class="label">Ledger</span>
      </a>
      <a href="report.php">
        <span class="material-icons-outlined">description</span>
        <span class="label">Report</span>
      </a>
      <a href="analytics.php">
        <span class="material-icons-outlined">bar_chart</span>
        <span class="label">Analytics</span>
      </a>
      <a href="profile.php">
        <span class="material-icons-outlined">person</span>
        <span class="label">Profile</span>
      </a>
    </div>
  </nav>

  <!-- Logout -->
  <div class="logout">
    <a href="logout.php" id="logoutBtn">
      <span class="material-icons-outlined">logout</span>
      <span class="label">Logout</span>
    </a>
  </div>
</aside>
<div class="overlay"></div>
<script src="../../assets/js/js/scripts.js"></script>
