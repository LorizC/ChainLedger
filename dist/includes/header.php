<!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow">
    <div class="me-auto pc-mob-drp">
      <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
        <li class="pc-h-item pc-sidebar-collapse max-lg:hidden lg:inline-flex">
          <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="sidebar-hide">
            <i data-feather="menu"></i>
          </a>
        </li>
        <li class="pc-h-item pc-sidebar-popup lg:hidden">
          <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
            <i data-feather="menu"></i>
          </a>
        </li>
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#" role="button"
            aria-haspopup="false" aria-expanded="false">
            <i data-feather="search"></i>
          </a>
          <div class="dropdown-menu pc-h-dropdown drp-search dark:bg-gray-900 dark:text-gray-100">
            <form class="px-2 py-1">
              <input type="search" class="form-control !border-0 !shadow-none dark:bg-gray-800 dark:text-gray-100" placeholder="Search here..."/>
            </form>
          </div>
        </li>
      </ul>
    </div>

    <div class="ms-auto">
      <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">

        <!-- dark/light mode toggle -->
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#" role="button"
            aria-haspopup="false" aria-expanded="false">
            <i data-feather="sun"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end pc-h-dropdown dark:bg-gray-900 dark:text-gray-100">
            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
              <i data-feather="moon"></i>
              <span>Dark</span>
            </a>
            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
              <i data-feather="sun"></i>
              <span>Light</span>
            </a>
            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
              <i data-feather="settings"></i>
              <span>Default</span>
            </a>
          </div>
        </li>

        <!-- User Profile Dropdown -->
        <li class="dropdown pc-h-item header-user-profile">
          <a class="pc-head-link dropdown-toggle arrow-none me-0" data-pc-toggle="dropdown" href="#" role="button"
            aria-haspopup="false" data-pc-auto-close="outside" aria-expanded="false">
            <i data-feather="user"></i>
          </a>
          <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2 overflow-hidden dark:bg-gray-900 dark:text-gray-100">
            <div class="dropdown-header flex items-center justify-between py-4 px-5 bg-primary-500 dark:bg-indigo-700">
              <div class="flex mb-1 items-center">
                <div class="shrink-0">
                  <img src="<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? $currentAvatar) ?>"" alt="user-image" class="w-10 rounded-full" />
                </div>
                <div class="grow ms-4 text-white">
                  <h4 class="mb-1"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></h4>
                  <span>Account ID: <?php echo htmlspecialchars($_SESSION['user']['account_id']); ?></span>
                </div>
              </div>
            </div>
            <div class="dropdown-body py-4 px-5">
              <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                <a href="profile.php" class="dropdown-item dark:hover:bg-gray-800">
                  <span><i class="ti ti-user"></i><span>My Profile</span></span>
                </a>
                <a href="change_password.php" class="dropdown-item dark:hover:bg-gray-800">
                  <span>
                    <svg class="pc-icon text-muted me-2 inline-block">
                      <use xlink:href="#custom-lock-outline"></use>
                    </svg>
                    <span>Change Password</span>
                  </span>
                </a>
                <a href="#" class="dropdown-item dark:hover:bg-gray-800" onclick="alert('About this System...');">
                  <span><i class="ti ti-report"></i><span>Security Logs</span></span>
                </a>
                <div class="grid my-3">
                  <button class="btn btn-primary flex items-center justify-center dark:bg-indigo-600 dark:text-gray-100">
                    <svg class="pc-icon me-2 w-[22px] h-[22px]">
                      <use xlink:href="#custom-logout-1-outline"></use>
                    </svg>
                    <a href="./logout.php">Log-Out</a>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </li>
        <!-- User Profile Dropdown end -->

      </ul>
    </div>
  </div>
</header>
<!-- [ Header ] end -->
