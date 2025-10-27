<?php
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';
require_once __DIR__ . '/../services/AuthGuard.php';

// Only allow logged-in users who are Staff
auth_guard(['Staff']);

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$accountId = (int) $_SESSION['user']['account_id'];
$userData  = $userRepo->findWithRoleByAccountId($accountId);

$user = [
    'name' => $userData['first_name'] . ' ' . $userData['last_name'],
    'role' => $userData['company_role'] ?? 'Unassigned',
];

$profileImage = $_SESSION['user']['profile_image'] ?? '../../images/user/default.png';
?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
  <title>ChainLedger | Profile Security</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
  <link rel="stylesheet" href="../assets/fonts/feather.css" />
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../assets/fonts/material.css" />
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />

  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>
  <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <style>[x-cloak]{display:none !important;}</style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

  <!-- Preloader -->
  <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
    <div class="loader-track h-[5px] w-full absolute top-0 overflow-hidden">
      <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
    </div>
  </div>

  <!-- Sidebar -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- Header -->
  <?php include '../includes/header.php'; ?>

  <!-- Main Content -->
  <div class="pc-container">
    <div class="pc-content">

      <!-- Page Header -->
      <div class="page-header mb-4">
        <div class="page-block">
          <div class="page-header-title">
            <h5 class="mb-0 font-medium">Profile & Security</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../staffs/dashboard.php">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Profile & Security</li>
          </ul>
        </div>
      </div>

      <!-- Flash Messages -->
      <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
          <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
          <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
      <?php endif; ?>

      <!-- Profile + Password Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6">

        <!-- LEFT: Profile Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
          <div class="flex items-center mb-6">
            <img 
              src="<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? $currentAvatar) ?>" 
              alt="User Avatar" 
              class="w-28 h-28 rounded-full border-4 border-indigo-200 shadow dark:border-gray-600"
            >

            <div class="ml-6 max-w-[220px]">
              <h2 
                class="text-3xl font-bold text-indigo-700 dark:text-white truncate" 
                title="<?= htmlspecialchars($_SESSION['user']['username']); ?>"
              >
                <?= htmlspecialchars($_SESSION['user']['username']); ?>
              </h2>

              <p 
                class="text-lg text-gray-500 dark:text-gray-300 mt-1 truncate" 
                title="<?= htmlspecialchars($user['role']) ?>"
              >
                <?= htmlspecialchars($user['role']) ?>
              </p>

              <p class="text-sm text-gray-400 mt-1">
                Account ID: <?= htmlspecialchars($_SESSION['user']['account_id']) ?>
              </p>
            </div>
          </div>

          <div class="border-t border-gray-300 mt-4 pt-4">
            <p class="text-gray-500 dark:text-gray-300">
              Ensure your account remains secure by updating your password regularly.
            </p>
          </div>

          <!-- Password Guidelines -->
          <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-4 rounded-lg text-gray-700 dark:text-gray-300 text-sm space-y-2 mt-4">
            <p class="font-semibold mb-1">Password Guidelines:</p>
            <ul class="list-disc list-inside space-y-1">
              <li>Use at least <span class="font-semibold text-indigo-600 dark:text-indigo-400">8 characters</span>.</li>
              <li>Include a mix of <span class="font-semibold">uppercase</span> and <span class="font-semibold">lowercase</span> letters.</li>
              <li>Add at least one <span class="font-semibold">number</span> and one <span class="font-semibold">symbol</span> (e.g., !, @, #, $).</li>
              <li>Avoid using your name or common words.</li>
              <li>Change your password every few months for maximum security.</li>
            </ul>
          </div>
        </div>

        <!-- RIGHT: Password Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
          <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Update Password</h2>

          <form 
            x-data="{
              showCurrent:false,
              showNew:false,
              showConfirm:false,
              confirmModal:false,
              currentPassword:'',
              newPassword:'',
              confirmPassword:'',
              passwordError:'',
              isValid:false,
              validatePasswords() {
                if (this.newPassword.length < 8) {
                  this.passwordError = 'Password must be at least 8 characters.';
                  this.isValid = false;
                  return false;
                }
                if (this.newPassword !== this.confirmPassword) {
                  this.passwordError = 'Passwords do not match.';
                  this.isValid = false;
                  return false;
                }
                this.passwordError = 'Passwords match.';
                this.isValid = true;
                return true;
              },
              submitForm() {
                if (this.validatePasswords()) {
                  this.confirmModal = true;
                }
              },
              clearState() {
                this.currentPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
                this.passwordError = '';
                this.isValid = false;
              }
            }"
            x-ref="passwordForm"
            method="POST"
            action="handlers/edit_password.php"
            class="space-y-6"
          >

            <!-- Current Password -->
            <div>
              <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Current Password</label>
              <div class="relative">
                <input 
                  :type="showCurrent ? 'text' : 'password'"
                  name="current_password"
                  x-model="currentPassword"
                  required
                  class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                  placeholder="Current password"
                >
                <span 
                  @click="showCurrent = !showCurrent"
                  class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500"
                >visibility</span>
              </div>
            </div>

            <!-- New Password -->
            <div>
              <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">New Password</label>
              <div class="relative">
                <input 
                  :type="showNew ? 'text' : 'password'"
                  name="new_password"
                  x-model="newPassword"
                  @input="validatePasswords()"
                  required
                  class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                  placeholder="New password"
                >
                <span 
                  @click="showNew = !showNew"
                  class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500"
                >visibility</span>
              </div>
            </div>

            <!-- Confirm Password -->
            <div>
              <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Confirm New Password</label>
              <div class="relative">
                <input 
                  :type="showConfirm ? 'text' : 'password'"
                  name="confirm_password"
                  x-model="confirmPassword"
                  @input="validatePasswords()"
                  required
                  class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                  placeholder="Confirm new password"
                >
                <span 
                  @click="showConfirm = !showConfirm"
                  class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500"
                >visibility</span>
              </div>

              <!-- Password Message -->
              <p 
                x-show="passwordError"
                x-text="passwordError"
                :class="isValid ? 'text-green-600 dark:text-green-400 mt-2 text-sm font-medium' : 'text-red-600 dark:text-red-400 mt-2 text-sm font-medium'"
              ></p>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4">
              <button 
                type="reset" 
                class="px-5 py-2 text-gray-600 hover:underline dark:text-gray-300"
                @click="passwordError=''"
              >Cancel</button>

              <button 
                type="button" 
                @click="submitForm()"
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 dark:bg-slate-100 dark:text-gray-800"
              >Save Changes</button>
            </div>

            <!-- Confirmation Modal -->
            <div 
              x-show="confirmModal" 
              x-cloak
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            >
              <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Confirm Password Change</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Are you sure you want to change your password?</p>

                <div class="flex justify-end gap-3">
                  <button 
                    type="button" 
                    @click="confirmModal=false"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600"
                  >Cancel</button>

                  <button 
                    type="button" 
                    @click="$refs.passwordForm.submit()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                  >Yes, Change</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <!-- Required JS -->
  <script src="../assets/js/plugins/simplebar.min.js"></script>
  <script src="../assets/js/plugins/popper.min.js"></script>
  <script src="../assets/js/icon/custom-icon.js"></script>
  <script src="../assets/js/plugins/feather.min.js"></script>
  <script src="../assets/js/component.js"></script>
  <script src="../assets/js/theme.js"></script>
  <script src="../assets/js/script.js"></script>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
