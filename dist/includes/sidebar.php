<?php require_once __DIR__ . '/../admin/handlers/profile_helper.php'; ?>


<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar bg-primary-700 text-white min-h-screen w-[var(--tw-sidebar-width,264px)]">
  <div class="navbar-wrapper">
    <!-- Logo -->
    <div class="m-header flex items-center py-5 px-6 h-header-height border-b border-gray-700/30">
      <a href="../admin/dashboard.php" class="b-brand flex items-center gap-3">
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
            <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
          </h5>
          <p class="text-sm text-gray-300 leading-tight mt-1">
            <?php echo htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']); ?>
          </p>
          <p class="text-[12px] text-gray-400 mt-1">
            Account ID: <?php echo htmlspecialchars($_SESSION['user']['account_id']); ?>
          </p>
        </div>
      </div>

      <ul class="pc-navbar space-y-1">
        <!-- Navigation -->
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>

        <li class="pc-item">
          <a href="../admin/dashboard.php" class="pc-link">
            <span class="pc-micon"><i data-feather="home"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="../admin/ledger.php" class="pc-link">
            <span class="pc-micon"><i data-feather="file-text"></i></span>
            <span class="pc-mtext">Ledger</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="../admin/report.php" class="pc-link">
            <span class="pc-micon"><i data-feather="bar-chart-2"></i></span>
            <span class="pc-mtext">Report</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="../admin/analytics.php" class="pc-link">
            <span class="pc-micon"><i data-feather="activity"></i></span>
            <span class="pc-mtext">Analytics</span>
          </a>
        </li>

        <!-- Profile Management -->
        <li class="pc-item pc-caption mt-4">
          <label>Profile Management</label><i data-feather="user"></i>
        </li>

        <li class="pc-item">
          <a href="../admin/profile.php" class="pc-link">
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
            <li class="pc-item"><a class="pc-link" href="../admin/editpassword.php">Edit Password</a></li>
            <li class="pc-item"><a class="pc-link" href="../admin/deleteaccount.php">Delete Account</a></li>
          </ul>
        </li>

        <!-- Settings -->
        <li class="pc-item pc-caption mt-4">
          <label>Close App</label><i data-feather="wrench"></i>
        </li>

        <li class="pc-item">
          <a href="logout.php" class="pc-link">
            <span class="pc-micon"><i data-feather="log-out"></i></span>
            <span class="pc-mtext">Log-Out</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->

<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace();</script>
