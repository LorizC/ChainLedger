<?php

// Include the helper function
require_once __DIR__ . '/../../handlers/profile_helper.php';


// Get user profile image with fallback
$profileImage = getProfileImage($_SESSION['user']['profile_image'] ?? null);
?>

<header>
  <!-- Burger Icon -->
  <button id="burgerBtn" class="burger-btn">&#9776;</button>

  <!-- Profile Icons -->
  <div class="profile">
    <span id="themeBtn" class="material-icons-outlined">light_mode</span>
    <span id="userBtn" class="material-icons-outlined">person</span>
  </div>
</header>

<!-- User Popup -->
<div id="userPopup" class="popup">
  <div class="popup-header">
    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Avatar"  class="avatar">
    <div>
      <span class="username"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
      <span class="id"><?php echo htmlspecialchars($_SESSION['user']['account_id']); ?></span>
    </div>
  </div>
<ul class="popup-menu">
  <li>
    <a href="profile.php">
      <span class="material-icons-outlined">person</span> Profile
    </a>
  </li>

  <li>
    <a href="logs.php">
      <span class="material-icons-outlined">account_balance_wallet</span> Logs
    </a>
  </li>

  <!-- Settings Drawer -->
  <li id="settingsToggle" class="settings-toggle">
    <span class="material-icons-outlined">settings</span> Settings
  </li>
  <ul id="settingsDrawer" class="settings-drawer">
    <li class="drawer-item">
      <a href="edit_password.php">
        <span class="material-icons-outlined">lock</span> Edit Password
      </a>
    </li>
    <li class="drawer-item">
      <a href="delete_account.php">
        <span class="material-icons-outlined">delete</span> Delete Account
      </a>
    </li>
  </ul>
<li class="header_logout">
  <a href="logout.php" id="logoutBtn">
    <span class="material-icons-outlined">logout</span> Logout
  </a>
</li>

</ul>
</ul>
</div>
<script src="../../assets/js/js/scripts.js"></script>
</header>