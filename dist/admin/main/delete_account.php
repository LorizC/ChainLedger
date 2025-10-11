<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../usercreation/login.php");
    exit;
}

require_once __DIR__ . '/../../php/db/dbconfig.php';
require_once __DIR__ . '/../../php/repositories/UserRepository.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$accountId = (int)$_SESSION['user']['account_id'];
$userData = $userRepo->findWithRoleByAccountId($accountId);

$user = [
    'name'  => $userData['first_name'] . ' ' . $userData['last_name'],
    'role'  => $userData['company_role'] ?? 'Unassigned',
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChainLedger Account</title>
  <link rel="stylesheet" href="css/main.css">

  <!-- hide x-cloak content until Alpine is ready -->
  <style>[x-cloak]{display:none !important;}</style>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>

  <!-- Alpine.js (explicit version) -->
  <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

    <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom Theme Logic -->
  <script src="../../assets/js/js/scripts.js" defer></script>
  <!-- Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Header -->
    <?php include './includes/header.php'; ?>

    <!-- Title -->
    <div class="title-block">
      <p>Welcome to ChainLedger Account Deletion</p>
      <h1>Delete Account</h1>
    </div>

    <div class="flex flex-col md:flex-row gap-6 mt-10">

      <!-- LEFT: User Info -->
      <div class="bg-white p-6 rounded-xl shadow min-h-[450px] w-[600px] dark:bg-gray-800">
        <div class="flex items-center mb-6">
          <img src="<?= htmlspecialchars($profileImage); ?>" alt="User Avatar"
               class="w-28 h-28 rounded-full border-4 border-indigo-200 shadow dark:border-gray-600">
          <div class="ml-6">
            <h2 class="text-3xl font-bold text-indigo-700 dark:text-white">
              <?= htmlspecialchars($_SESSION['user']['username']); ?>
            </h2>
            <p class="text-lg text-gray-500 dark:text-gray-300 mt-1">
            <?= htmlspecialchars($user['role']) ?></p>


            <p class="text-sm text-gray-400 mt-1">Account ID: <?= htmlspecialchars($_SESSION['user']['account_id']); ?></p>
          </div>
        </div>
        <div class="border-t border-gray-300 mt-4 pt-4">
          <p class="text-gray-500 dark:text-gray-300">
            Before deleting your account, please review the important information below.
          </p>
  <!-- Deletion Guidelines -->
  <ul class="mt-3 list-disc list-inside text-sm text-gray-500 dark:text-gray-400 space-y-1">
    <li>Your <span class="font-semibold text-indigo-600 dark:text-indigo-400">profile, data, and transaction history</span> will be permanently deleted.</li>
    <li>This action <span class="font-semibold text-red-500">cannot be undone</span> once confirmed.</li>
    <li>Make sure to <span class="font-semibold">download or back up</span> any important records before proceeding.</li>
    <li>All linked accounts and access to company records will be removed.</li>
    <li>If you change your mind, you’ll need to <span class="font-semibold">create a new account</span> to use ChainLedger again.</li>
  </ul>
</div>
      </div>

      <!-- RIGHT: Change Password Form -->
      <div class="bg-white p-8 rounded-xl shadow flex-1 dark:bg-gray-800">
        <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Delete Your Account</h2>

        <?php if (isset($_SESSION['flash_error'])): ?>
          <div class="mb-4 text-red-600 bg-red-100 dark:bg-red-900 dark:text-red-200 p-3 rounded">
            <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_success'])): ?>
          <div class="mb-4 text-green-600 bg-green-100 dark:bg-green-900 dark:text-green-200 p-3 rounded">
            <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
          </div>
        <?php endif; ?>

        <form id="deleteForm"
        x-data="{showPassword:false,showNew:false,showConfirm:false}"
              method="POST"
              action="../../php/handlers/delete_account.php"
              class="space-y-6">

          <!-- A ID -->
          <div>
            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Security Answer</label>
            <div class="relative">
              <input :type="showNew ? 'text' : 'password'" name="security_answer"
                     class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                     placeholder="Enter your security answer" required>
              <span @click="showNew=!showNew"
                    class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500">visibility</span>
            </div>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Password</label>
            <div class="relative">
              <input :type="showPassword ? 'text' : 'password'" name="current_password"
                     class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                     placeholder="Enter current password" required>
              <span @click="showPassword=!showPassword"
                    class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500">visibility</span>
            </div>
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Confirm Password</label>
            <div class="relative">
              <input :type="showConfirm ? 'text' : 'password'" name="confirm_password"
                     class="w-full border rounded-lg px-3 py-3 text-gray-700 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                     placeholder="Re-enter password" required>
              <span @click="showConfirm=!showConfirm"
                    class="material-icons-outlined absolute right-3 top-3.5 cursor-pointer text-gray-500">visibility</span>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-3 pt-4">
            <button type="reset" class="px-5 py-2 text-gray-600 hover:underline dark:text-gray-300">Cancel</button>
<button id="confirmDeleteBtn" type="button"
        class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 dark:bg-slate-100 dark:text-gray-800">
  Delete Account
</button>
          </div>
        </form>
      </div>
    </div>
     <?php include './includes/footer.php'; ?>
  </main>
<?php if (isset($_SESSION['flash_success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      icon: 'success',
      title: 'Account Deleted',
      text: "<?php echo addslashes($_SESSION['flash_success']); ?>",

      confirmButtonText: 'OK'
    }).then(() => {
      window.location.href = '../../html/usercreation/login.php';
    });
  });
</script>
<?php unset($_SESSION['flash_success']); endif; ?>

</body>
</html>
