<?php include '../../php/handlers/profile.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChainLedger Profile</title>
  <link rel="stylesheet" href="css/main.css">

  <!-- hide x-cloak content until Alpine is ready -->
  <style>[x-cloak]{display:none !important;}</style>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>

  <!-- Alpine.js (explicit version) -->
  <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

  <!-- Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <!-- Custom Theme Logic -->
  <script src="../../assets/js/js/scripts.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main max-h-screen overflow-y-auto">
    <!-- Header -->
    <?php include './includes/header.php'; ?>

    <!-- Title -->
    <div class="title-block">
      <p>Welcome to ChainLedger Profile</p>
      <h1>User Profile</h1>
    </div>

    <div class="flex flex-col md:flex-row gap-6 mt-10">

      <!-- LEFT: User Info Card -->
      <div class="bg-white p-6 rounded-xl shadow min-h-[500px] max-h-[500px] w-[700px] dark:bg-gray-800">
        <div class="flex items-center mb-6">
          
<!-- Profile Section -->
<div class="p-6" 
x-data="{ 
open: false,
 avatar: '<?= htmlspecialchars($_SESSION['user']['profile_image'] ?? $currentAvatar) ?>',
 username: '<?= htmlspecialchars($userData['username']) ?>',
fullname: '<?= htmlspecialchars($user['name']) ?>' }">

<!-- Avatar -->
  <div class="flex items-center space-x-6">    
    <div class="relative inline-block">
      <img :src="avatar" class="w-28 h-28 rounded-full object-cover border-4 border-indigo-200 shadow dark:border-gray-600" alt="User Avatar">
      <button @click="open = true" 
              class="absolute bottom-1 right-1 bg-white rounded-full p-2 shadow dark:bg-gray-200">
        <span class="material-icons-outlined text-lg text-indigo-600 dark:text-black">edit</span>
      </button>
    </div>
    <div>
    <!-- Names (stacked) -->

      <!-- Editable Username -->
      <h2 class="text-4xl font-extrabold text-indigo-700 dark:text-white" x-text="username"></h2>
      <!-- Fixed Full Name -->
      <p class="text-xl text-gray-500 dark:text-gray-300 mt-1" x-text="fullname"></p>
    </div>
  </div>

  <!-- Edit Modal -->
  <div x-show="open" x-cloak 
       class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 ">
    <div class="bg-white rounded-xl shadow-lg p-6 w-[28rem] dark:bg-gray-800">
      <h2 class="text-xl font-bold text-gray-700 mb-4 dark:text-gray-300">Edit Profile</h2>

          <!-- Form -->
    <form method="POST" action="../../php/handlers/profile.php">
      <!-- Current Avatar -->
      <div class="flex justify-center mb-4">
        <img :src="avatar" class="w-28 h-28 rounded-full border-4 border-indigo-200 shadow object-cover dark:border-gray-600" alt="Avatar">
      </div>

<!-- Username Input -->
<label class="block text-sm font-medium text-gray-600 mb-1 dark:text-gray-300">Username</label>
<input 
  type="text" 
  name="username" 
  x-model="username" 
  class="w-full border rounded-lg px-3 py-3 mb-4 text-lg text-gray-700 focus:ring-2 focus:ring-indigo-500">

      <!-- Avatar Picker -->
      <label class="block text-sm font-medium text-gray-600 mb-2 dark:text-gray-300">Choose Avatar</label>
      <div class="grid grid-cols-5 gap-4 mb-6">
        <template x-for="a in [
          '../../images/avatars/male1.jpg',
          '../../images/avatars/male2.jpg',
          '../../images/avatars/male3.jpg',
          '../../images/avatars/female1.jpg',
          '../../images/avatars/female2.jpg',
          '../../images/avatars/female3.jpg'
        ]" :key="a">
          <img :src="a" 
               @click="avatar = a" 
               class="w-16 h-16 rounded-full cursor-pointer object-contain border hover:ring-4 hover:ring-indigo-500 transition"
               :class="{ 'ring-4 ring-indigo-600': avatar === a }">
        </template>
      </div>

      <!-- Hidden Avatar Input -->
      <input type="hidden" name="avatar" :value="avatar">
      <input type="hidden" name="update_profile" value="1">

      <!-- Buttons -->
      <div class="flex justify-end space-x-3">
        <button type="button" @click="open = false"
                class="px-5 py-2 text-base text-gray-600 hover:underline dark:text-gray-300">Cancel</button>
        <button type="submit"
                class="px-5 py-2 text-base bg-indigo-600 text-white rounded-lg dark:bg-slate-100 dark:text-gray-800">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>

<!-- Info -->
<div class="space-y-6 text-2xl mb-8">
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Account ID</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["account_id"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Role</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["role"]) ?></span>
  </div>
</div>

<hr class="my-8 border-gray-300">

<div class="space-y-6 text-2xl">
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Birthdate</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["birthdate"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 dark:text-gray-300 font-medium">Registered</span>
    <span class="font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($user["registered"]) ?></span>
  </div>
</div>
</div>

<!-- RIGHT: Spending + Transactions -->
<div class="flex flex-col gap-6 w-[900px]">
  
  <!-- Spending (smaller card) -->
  <div class="bg-white p-6 rounded-xl shadow md:col-span-2 dark:bg-gray-800">
    <h2 class="text-xl font-semibold text-gray-600 dark:text-gray-300">Spending</h2>
    <p class="text-3xl font-bold text-indigo-700 dark:text-indigo-500 mt-4"><?= htmlspecialchars($user["spending"]) ?></p>
  </div>

<!-- Transactions (takes full remaining space) -->
<div class="bg-white p-6 rounded-xl shadow dark:bg-gray-800">
  <h2 class="text-2xl font-bold text-indigo-700 dark:text-gray-300 mb-6">Transactions</h2>

  <?php if (empty($transactions)): ?>
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-16 text-center text-gray-500 dark:text-gray-400">
      <span class="material-icons-outlined text-5xl mb-3 text-gray-400 dark:text-gray-500">hourglass_empty</span>
      <p class="text-xl font-medium">No transactions found</p>
      <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Your recent activity will appear here.</p>
    </div>
  <?php else: ?>
    <!-- Transaction List -->
    <div class="space-y-3 max-h-96 overflow-y-auto pr-2 text-lg">
      <?php foreach ($transactions as $t): ?>
        <div class="grid grid-cols-4 gap-4 border-b pb-2">
          <span class="text-gray-700 font-medium dark:text-gray-300"><?= htmlspecialchars($t["name"]) ?></span>
          <span class="text-gray-500 dark:text-gray-300"><?= htmlspecialchars($t["merchant"]) ?></span>
          <span class="text-gray-800 font-semibold dark:text-white"><?= htmlspecialchars($t["amount"]) ?></span>
          <span class="text-gray-500 dark:text-gray-300"><?= htmlspecialchars($t["transaction_date"]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (!empty($totalPages) && $totalPages > 1): ?>
      <div class="flex justify-center items-center space-x-2 mt-6">
        <?php if (!empty($page) && $page > 1): ?>
          <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
            Previous
          </a>
        <?php endif; ?>

        <span class="text-gray-600 dark:text-gray-300 text-sm">Page <?= htmlspecialchars($page ?? 1) ?> of <?= htmlspecialchars($totalPages) ?></span>

        <?php if (!empty($page) && $page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
            Next
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
</div>
    </div>
    <?php include './includes/footer.php'; ?>
  </main>
</body>
</html>
