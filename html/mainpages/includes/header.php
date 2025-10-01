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
    <img src="profile.jpg" alt="User Avatar" class="avatar">
    <div>
      <h4 id="username">Username</h4>
      <p id="id">123456</p>
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
      <a href="../usercreation/change_password.php">
        <span class="material-icons-outlined">lock</span> Change Password
      </a>
    </li>
    <li class="drawer-item">
      <a href="../usercreation/delete_account.php">
        <span class="material-icons-outlined">delete</span> Delete Account
      </a>
    </li>
  </ul>

  <li class="header_logout">
    <a href="../usercreation/logout.php">
      <span class="material-icons-outlined">logout</span> Logout
    </a>
  </li>
</ul>

</ul>


</div>
</header>