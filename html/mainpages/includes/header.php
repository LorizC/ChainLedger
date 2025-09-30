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
    <li><a href="profile.php"><span class="material-icons-outlined">person</span> Profile</li></a>
    <li><a href="ledger.php"><span class="material-icons-outlined">account_balance_wallet</span> Transaction Logs</li></a>
      <li><a href="../usercreation/change_password.php"><span class="material-icons-outlined">lock</span> Change Password</li></a>
    <li class="header_logout"><a href="../usercreation/login.php"><span class="material-icons-outlined">logout</span> Logout</li></a>
  </ul>
</div>
</header>