<?php 
require_once __DIR__ . '/../admin/handlers/profile_helper.php'; 

// Determine role and base path
$role = strtolower($_SESSION['user']['company_role'] ?? '');
$basePath = ($role === 'staff') ? '../staffs/' : '../admin/';
?>

<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar bg-indigo-800 text-white min-h-screen w-[var(--tw-sidebar-width,264px)]">
  <div class="navbar-wrapper">
    <!-- Logo -->
    <div class="m-header flex items-center py-5 px-6 h-header-height border-b border-gray-700/30">
      <a href="<?= $basePath ?>dashboard.php" class="b-brand flex items-center gap-3">
        <img src="../assets/images/logos/logo.png" alt="Logo" class="logo-img w-10 h-10">
        <span class="label font-semibold text-lg">ChainLedger</span>
      </a>
    </div>

    <div class="navbar-content h-[calc(100vh_-_90px)] py-4">
      <!-- Profile Section -->
      <div class="flex flex-col items-center justify-center mb-6 px-2">
        <img
          src="<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? $currentAvatar) ?>"
          alt="User Avatar"
          class="w-16 h-16 rounded-full mb-2 object-cover"
        />
        <div class="text-center">
          <h5 class="font-semibold text-lg text-white">
            <?= htmlspecialchars($_SESSION['user']['company_role'] ?? '') ?>
          </h5>
          <p class="text-sm text-gray-300 leading-tight mt-1 max-w-[150px] truncate">
            <?= htmlspecialchars($_SESSION['user']['username']) ?>
          </p>
          <p class="text-[12px] text-gray-400 mt-1">
            Account ID: <?= htmlspecialchars($_SESSION['user']['account_id']) ?>
          </p>
        </div>
      </div>

      <ul class="pc-navbar space-y-1">
        <!-- Navigation -->
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>

        <li class="pc-item">
          <a href="<?= $basePath ?>dashboard.php" class="pc-link">
            <span class="pc-micon"><i data-feather="home"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="<?= $basePath ?>ledger.php" class="pc-link">
            <span class="pc-micon"><i data-feather="file-text"></i></span>
            <span class="pc-mtext">Ledger</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="<?= $basePath ?>report.php" class="pc-link">
            <span class="pc-micon"><i data-feather="bar-chart-2"></i></span>
            <span class="pc-mtext">Report</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="<?= $basePath ?>analytics.php" class="pc-link">
            <span class="pc-micon"><i data-feather="activity"></i></span>
            <span class="pc-mtext">Analytics</span>
          </a>
        </li>

        <!-- Profile Management -->
        <li class="pc-item pc-caption mt-4">
          <label>Profile Management</label><i data-feather="user"></i>
        </li>

        <li class="pc-item">
          <a href="<?= $basePath ?>profile.php" class="pc-link">
            <span class="pc-micon"><i data-feather="user"></i></span>
            <span class="pc-mtext">Profile</span>
          </a>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i data-feather="settings"></i></span>
            <span class="pc-mtext">Account Settings</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="<?= $basePath ?>editpassword.php">Edit Password</a></li>
            <li class="pc-item"><a class="pc-link" href="<?= $basePath ?>deleteaccount.php">Delete Account</a></li>
          </ul>
        </li>

        <!-- Close App -->
        <li class="pc-item pc-caption mt-4">
          <label>Close App</label><i data-feather="wrench"></i>
        </li>

        <li class="pc-item">
          <a href="logout.php" id='logoutBtn' class="pc-link">
            <span class="pc-micon"><i data-feather="log-out"></i></span>
            <span class="pc-mtext">Log-Out</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace();</script>

<!-- Logout confirmation script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const logoutButtons = document.querySelectorAll('a[href*="logout.php"]');
  logoutButtons.forEach(button => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      if (document.getElementById("logoutConfirmBox")) return;

      const confirmBox = document.createElement("div");
      confirmBox.id = "logoutConfirmBox";
      confirmBox.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";

      confirmBox.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center max-w-sm w-full transition transform scale-95 opacity-0 animate-fadeIn">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Confirm Logout</h2>
          <p class="text-gray-600 dark:text-gray-300 mb-5">Are you sure you want to log out?</p>
          <div class="flex justify-center gap-4">
            <button id="cancelLogout" class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-600">Cancel</button>
            <a id="confirmLogout" href="${button.href}" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white">Logout</a>
          </div>
        </div>
      `;
      document.body.appendChild(confirmBox);
      requestAnimationFrame(() => {
        const box = confirmBox.querySelector("div");
        box.classList.remove("scale-95", "opacity-0");
        box.classList.add("scale-100", "opacity-100");
      });
      document.getElementById("cancelLogout").addEventListener("click", () => confirmBox.remove());
    });
  });
});
</script>
