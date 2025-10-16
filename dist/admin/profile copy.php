<?php

require_once __DIR__ . '/../services/AuthGuard.php';
include 'handlers/profile.php';
// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner', 'Manager']);

 
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
<head>
  <title>ChainLedger | Profile</title>
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
  <style>[x-cloak]{display:none!important;}</style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
<!-- Preloader -->
<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
  <div class="loader-track h-[5px] w-full absolute top-0 overflow-hidden">
    <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
  </div>
</div>

<!-- Sidebar & Header -->
<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/header.php'; ?>

<!-- Main Content -->
<div class="pc-container">
  <div class="pc-content">
    <div class="page-header mb-4">
      <div class="page-block">
        <div class="page-header-title">
          <h5 class="mb-0 font-medium">Admin Profile</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="../admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item" aria-current="page">Profile</li>
        </ul>
      </div>
    </div>

<!-- Two Column Layout -->
<div class="flex flex-col md:flex-row gap-6 px-6 items-start">

  <!-- LEFT: Fixed Size Profile Card with Edit Modal -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow 
            w-full md:w-[500px] flex-shrink-0"
           x-data="{
              open:false,
              avatar:'<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? $currentAvatar) ?>',
              username:'<?= htmlspecialchars($userData['username']) ?>',
              fullname:'<?= htmlspecialchars($user['name']) ?>',
           }">

<!-- Avatar + Name -->
<div class="flex items-center space-x-6">
  <div class="relative inline-block">
    <img :src="avatar" class="w-32 h-32 rounded-full border-4 border-indigo-200 shadow dark:border-gray-600" alt="User Avatar">
    <button @click='open=true' class="absolute bottom-1 right-1 bg-white rounded-full p-2 shadow dark:bg-gray-200">
      <span class="material-icons-outlined text-lg text-indigo-600 dark:text-black">edit</span>
    </button>
  </div>
  <div>
    <h2 class="text-4xl font-extrabold text-indigo-700 dark:text-white" x-text="username"></h2>
    <p class="text-xl text-gray-500 dark:text-gray-300 mt-1" x-text="fullname"></p>
    <p class="text-xl text-gray-500 dark:text-gray-300 mt-1" x-text="fullname"></p>
  </div>
</div>


        <!-- Edit Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
          <div class="bg-white rounded-xl shadow-lg p-6 w-[28rem] dark:bg-gray-800">
            <h2 class="text-xl font-bold text-gray-700 mb-4 dark:text-gray-300">Edit Profile</h2>
            <form method="POST" action="profile.php">
              <div class="flex justify-center mb-4">
                <img :src="avatar" class="w-28 h-28 rounded-full border-4 border-indigo-200 shadow object-cover dark:border-gray-600" alt="Avatar">
              </div>

              <label class="block text-sm font-medium text-gray-600 mb-1 dark:text-gray-300">Username</label>
              <input type="text" name="username" x-model="username"
                class="w-full border rounded-lg px-3 py-3 mb-4 text-lg text-gray-700 focus:ring-2 focus:ring-indigo-500">

              <label class="block text-sm font-medium text-gray-600 mb-2 dark:text-gray-300">Choose Avatar</label>
              <div class="grid grid-cols-5 gap-4 mb-6">
                <template x-for="a in [
                  '../assets/images/user/male1.jpg','../assets/images/user/male2.jpg','../assets/images/user/male3.jpg',
                  '../assets/images/user/female1.jpg','../assets/images/user/female2.jpg','../assets/images/user/female3.jpg'
                ]" :key="a">
                  <img :src="a" @click="avatar=a"
                    class="w-16 h-16 rounded-full cursor-pointer border hover:ring-4 hover:ring-indigo-500 transition"
                    :class="{'ring-4 ring-indigo-600':avatar===a}">
                </template>
              </div>

              <input type="hidden" name="avatar" :value="avatar">
              <input type="hidden" name="update_profile" value="1">

              <div class="flex justify-end space-x-3">
                <button type="button" @click="open=false"
                  class="px-5 py-2 text-base text-gray-600 hover:underline dark:text-gray-300">Cancel</button>
                <button type="submit"
                  class="px-5 py-2 text-base bg-indigo-600 text-white rounded-lg dark:bg-slate-100 dark:text-gray-800">Save</button>
              </div>
              <input type="hidden" name="page" value="<?= $page ?>">
            </form>
          </div>
        </div>

<!-- Profile Info -->
<div class="space-y-6 text-lg mt-8">
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Account ID</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["account_id"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Role</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["role"]) ?></span>
     </div>   
    <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Birthdate</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["birthdate"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Registered</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["registered"]) ?></span>
  </div>

  <hr class="my-6 border-gray-300">
    <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Business ID</span>
     <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["account_id"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Industry</span>
     <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["role"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Account Registered</span>
 <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["registered"]) ?></span>
  </div>
  

</div>

      </div>

      <!-- RIGHT: Transactions Table -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow flex-1 min-h-[650px]">
        <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Transactions</h2>

        <?php if (empty($transactions)): ?>
          <div class="flex flex-col items-center justify-center py-16 text-center text-gray-500 dark:text-gray-400">
            <span class="material-icons-outlined text-5xl mb-3 text-gray-400 dark:text-gray-500">hourglass_empty</span>
            <p class="text-xl font-medium">No transactions found</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Your recent activity will appear here.</p>
          </div>
        <?php else: ?>
          <div class="space-y-3 pr-2 text-lg">
              <?php foreach ($paginatedTransactions as $t): ?>
              <div class="grid grid-cols-4 gap-4 border-b pb-2">
                <span class="text-gray-700 font-medium dark:text-gray-300"><?= htmlspecialchars($t["name"]) ?></span>
                <span class="text-gray-500 dark:text-gray-300"><?= htmlspecialchars($t["merchant"]) ?></span>
                <span class="text-gray-800 font-semibold dark:text-white"><?= htmlspecialchars($t["amount"]) ?></span>
                <span class="text-gray-500 dark:text-gray-300"><?= htmlspecialchars($t["transaction_date"]) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

            <?php if ($totalPages > 1): ?>
    <div class="flex justify-center mt-4 space-x-2">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" 
           class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-indigo-500 hover:text-white">Prev</a>
      <?php endif; ?>

      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="?page=<?= $p ?>"
           class="px-3 py-1 rounded 
           <?= $p == $page ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-indigo-500 hover:text-white' ?>">
           <?= $p ?>
        </a>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" 
           class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-indigo-500 hover:text-white">Next</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Required Js -->
<script src="../assets/js/plugins/simplebar.min.js"></script>
<script src="../assets/js/plugins/popper.min.js"></script>
<script src="../assets/js/icon/custom-icon.js"></script>
<script src="../assets/js/plugins/feather.min.js"></script>
<script src="../assets/js/component.js"></script>
<script src="../assets/js/theme.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
<?php  ?>