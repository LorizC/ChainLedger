<?php
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';
require_once __DIR__ . '/../services/AuthGuard.php';
// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Staff']);
$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$accountId = (int)$_SESSION['user']['account_id'];
$userData = $userRepo->findWithRoleByAccountId($accountId);

$user = [
    'name'  => $userData['first_name'] . ' ' . $userData['last_name'],
    'role'  => $userData['company_role'] ?? 'Unassigned',
];

$profileImage = $_SESSION['user']['profile_image'] ?? '../../images/avatars/default.png';


?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
<head>
  <title>ChainLedger | Account Deletion</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
  <link rel="stylesheet" href="../assets/fonts/feather.css" />
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../assets/fonts/material.css" />
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />
  
  <!-- Tailwind CSS + Alpine.js -->
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
    <div class="page-header mb-4">
      <div class="page-block">
        <div class="page-header-title">
          <h5 class="mb-0 font-medium">Account Deletion</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="../staffs/dashboard.php">Home</a></li>
          <li class="breadcrumb-item" aria-current="page">Account Deletion</li>
        </ul>
      </div>
    </div>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="bg-green-100 text-green-800 p-3 rounded mb-4 mx-6">
    <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
  </div>
<?php endif; ?>
<?php if (isset($_SESSION['redirect_after_delete']) && $_SESSION['redirect_after_delete']): ?>
<script>
  setTimeout(function() {
    window.location.href = '/../../index.php';
  }, 2000); // 2 seconds delay
</script>
<?php unset($_SESSION['redirect_after_delete']); endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="bg-red-100 text-red-800 p-3 rounded mb-4 mx-6">
    <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
  </div>
<?php endif; ?>

    <!-- Profile + Password Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6">

      <!-- LEFT: Profile Card -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <div class="flex items-center mb-6">
          <img src="<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? $currentAvatar) ?>" alt="User Avatar" class="w-28 h-28 rounded-full border-4 border-indigo-200 shadow dark:border-gray-600">
          <div class="ml-6">
            <h2 class="text-3xl font-bold text-indigo-700 dark:text-white"><?= htmlspecialchars($user['name']) ?></h2>
            <p class="text-lg text-gray-500 dark:text-gray-300 mt-1"><?= htmlspecialchars($user['role']) ?></p>
            <p class="text-sm text-gray-400 mt-1">Account ID: <?= htmlspecialchars($_SESSION['user']['account_id']) ?></p>
          </div>
        </div>
        <div class="border-t border-gray-300 mt-4 pt-4">
          <p class="text-gray-500 dark:text-gray-300">Before deleting your account, please review the important information below.</p>
        </div>
            <!-- Guidelines -->
    <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-4 rounded-lg text-gray-700 dark:text-gray-300 text-sm space-y-2">
      <p class="font-semibold mb-1">Password Guidelines:</p>
          <!-- Deletion Guidelines -->
          <ul class="list-disc list-inside text-sm text-gray-500 dark:text-gray-400 space-y-1">
            <li>Your <span class="font-semibold text-indigo-600 dark:text-indigo-400">profile, data, and transaction history</span> will be permanently deleted.</li>
            <li>This action <span class="font-semibold text-red-500">cannot be undone</span> once confirmed.</li>
            <li>Make sure to <span class="font-semibold">download or back up</span> any important records before proceeding.</li>
            <li>All linked accounts and access to company records will be archived.</li>
            <li>If you change your mind, you’ll need to <span class="font-semibold">create a new account</span> to use ChainLedger again.</li>
          </ul>
    </div>
      </div>

<!-- Delete Account Form (Right Column) -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow flex-1"
     x-data="{
       confirmModal:false,
       showPassword:false,
       showConfirm:false,
       showSecurity:false,
       securityAnswer: '',
       password: '',
       confirmPassword: '',
       passwordError: '',
       isValid: false,
       validate() {
         // Security answer required
         if (!this.securityAnswer || this.securityAnswer.trim().length === 0) {
           this.passwordError = 'Security answer is required.';
           this.isValid = false;
           return false;
         }
         // Password required
         if (!this.password || this.password.trim().length === 0) {
           this.passwordError = 'Password is required.';
           this.isValid = false;
           return false;
         }
         // Password match check
         if (this.password !== this.confirmPassword) {
           this.passwordError = 'Passwords do not match.';
           this.isValid = false;
           return false;
         }
         this.passwordError = 'Ready to delete.';
         this.isValid = true;
         return true;
       },
       submitForm() {
         if (this.validate()) {
           this.confirmModal = true;
         }
       },
       confirmDelete() {
         this.confirmModal = false;
         $refs.deleteForm.submit();
       },
       clearState() {
         this.securityAnswer = '';
         this.password = '';
         this.confirmPassword = '';
         this.passwordError = '';
         this.isValid = false;
       }
     }">

  <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Delete Your Account</h2>

  <form x-ref="deleteForm" id="deleteForm" method="POST" action="/ChainLedger-System-/dist/staffs/handlers/delete_account.php" class="space-y-6">

    <!-- Security Answer -->
    <div>
      <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Security Answer</label>
      <div class="relative">
        <input :type="showSecurity ? 'text' : 'password'" name="security_answer"
               x-model="securityAnswer" @input="validate()"
               class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
               placeholder="Enter your security answer" required>
        <span @click="showSecurity = !showSecurity"
              class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500">visibility</span>
      </div>
    </div>

    <!-- Password -->
    <div>
      <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Password</label>
      <div class="relative">
        <input :type="showPassword ? 'text' : 'password'" name="current_password"
               x-model="password" @input="validate()"
               class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
               placeholder="Enter current password" required>
        <span @click="showPassword = !showPassword"
              class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500">visibility</span>
      </div>
    </div>

    <!-- Confirm Password -->
    <div>
      <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Confirm Password</label>
      <div class="relative">
        <input :type="showConfirm ? 'text' : 'password'" name="confirm_password"
               x-model="confirmPassword" @input="validate()"
               class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
               placeholder="Re-enter password" required>
        <span @click="showConfirm = !showConfirm"
              class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500">visibility</span>
      </div>

      <!-- Real-time Error / Success Message -->
      <p x-show="passwordError" x-text="passwordError"
         :class="isValid ? 'text-green-600 dark:text-green-400 mt-2 text-sm font-medium' : 'text-red-600 dark:text-red-400 mt-2 text-sm font-medium'">
      </p>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end gap-3 pt-4">
      <button type="reset" 
              @click.prevent="clearState(); $refs.deleteForm.reset()"
              class="px-5 py-2 text-gray-600 hover:underline dark:text-gray-300">Cancel</button>

      <button type="button" @click="submitForm()"
              class="px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 dark:bg-red-500 dark:text-gray-900">
        Delete Account
      </button>
    </div>

  </form>

  <!-- Confirmation Modal -->
  <div x-show="confirmModal" x-cloak
       class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full p-6 flex flex-col items-center text-center">
      <span class="material-icons-outlined text-red-600 text-6xl mb-4">warning</span>

      <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Confirm Account Deletion</h2>
      <p class="text-gray-700 dark:text-gray-300 mb-6">
        Are you sure you want to delete your account? 
        <span class="font-semibold text-red-500">This action cannot be undone!</span>
      </p>
      <div class="flex justify-center gap-3">
        <button @click="confirmModal=false"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
          Cancel
        </button>
        <button type="button" @click="confirmDelete()"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
          Yes, Delete
        </button>
      </div>
    </div>
  </div>

</div>

  </form>
</div>

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

<?php include '../includes/footer.php'; ?>

<!-- Trigger confirmation modal when pressing Enter -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteForm = document.getElementById('deleteForm');

    if (deleteForm) {
      // Prevent default submission on Enter in the form
      deleteForm.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();

          // Find the Alpine component and show the modal
          const alpineRoot = deleteForm.closest('[x-data]');
          if (alpineRoot && alpineRoot.__x) {
            alpineRoot.__x.$data.confirmModal = true;
          }
        }
      });

      // Also block any accidental "submit" events that bypass keypress
      deleteForm.addEventListener('submit', function (e) {
        const alpineRoot = deleteForm.closest('[x-data]');
        if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data.confirmModal === false) {
          e.preventDefault();
        }
      });
    }
  });
</script>

</body>
</html>

