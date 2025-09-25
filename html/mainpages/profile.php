<!-- profile.php -->
<?php
// Example PHP data (replace with DB values)
$user = [
  "name" => "Loriz Neil Carlos",
  "account_id" => "123456",
  "role" => "Business Owner",
  "birthdate" => "08-16-2006",
  "registered" => "09-30-2025",
  "spending" => "₱5,000,000.00"
];

$transactions = [
  ["name" => "Loriz Neil Carlos", "method" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "09-10-2025"],
  ["name" => "Loriz Neil Carlos", "method" => "Maya", "amount" => "₱1,000,000.00", "date" => "08-10-2025"],
  ["name" => "Loriz Neil Carlos", "method" => "GooglePay", "amount" => "₱1,000,000.00", "date" => "08-01-2025"],
  ["name" => "Loriz Neil Carlos", "method" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "07-01-2025"],
  ["name" => "Loriz Neil Carlos", "method" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "06-01-2025"],
];

// Default avatars (your relative paths)
$defaultAvatars = [
  "../../images/avatars/profile.png",
  "../../images/avatars/profile2.png",
  "../../images/avatars/profile3.png",
  "../../images/avatars/profile4.png",
  "../../images/avatars/profile5.png"
];

// Current avatar: use first one by default (no randomness)
$currentAvatar = $defaultAvatars[0];
?>
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
  <script src="../../js/user.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Header -->
    <?php include './includes/header.php'; ?>

    <!-- Title -->
    <div class="title-block mb-6">
      <h1 class="text-3xl font-bold">User Profile</h1>
      <p class="text-gray-600">Welcome to ChainLedger Profile</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

      <!-- LEFT: User Info Card -->
      <div class="bg-white p-6 rounded-xl shadow min-h-[500px]">
        <div class="flex items-center mb-6">
          
<!-- Profile Section -->
<div class="p-6" 
     x-data="{ 
       open: false, 
       avatar: '../../images/avatars/profile.png', 
       username: 'Loriz Carlos', 
       fullname: '<?= htmlspecialchars($user["name"]) ?>' 
     }">

  <div class="flex items-center space-x-6">
    <!-- Avatar -->
    <div class="relative inline-block">
      <img :src="avatar" class="w-28 h-28 rounded-full object-cover border-4 border-indigo-200 shadow" alt="User Avatar">
      <button @click="open = true" 
              class="absolute bottom-1 right-1 bg-white rounded-full p-2 shadow">
        <span class="material-icons-outlined text-lg text-indigo-600">edit</span>
      </button>
    </div>

    <!-- Names (stacked) -->
    <div>
      <!-- Editable Username -->
      <h2 class="text-4xl font-extrabold text-indigo-700" x-text="username"></h2>
      <!-- Fixed Full Name -->
      <p class="text-xl text-gray-500 mt-1" x-text="fullname"></p>
    </div>
  </div>

  <!-- Edit Modal -->
  <div x-show="open" x-cloak 
       class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-[28rem]">
      <h2 class="text-xl font-bold text-gray-700 mb-4">Edit Profile</h2>

      <!-- Current Avatar -->
      <div class="flex justify-center mb-4">
        <img :src="avatar" class="w-28 h-28 rounded-full border-4 border-indigo-200 shadow object-cover" alt="Avatar">
      </div>

      <!-- Username Input -->
      <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
      <input type="text" x-model="username" 
             class="w-full border rounded-lg px-3 py-3 mb-4 text-lg text-gray-700 focus:ring-2 focus:ring-indigo-500">

      <!-- Avatar Picker -->
      <label class="block text-sm font-medium text-gray-600 mb-2">Choose Avatar</label>
      <div class="grid grid-cols-5 gap-4 mb-6">
        <template x-for="a in [
          '../../images/avatars/profile1.png',
          '../../images/avatars/profile2.png',
          '../../images/avatars/profile3.png',
          '../../images/avatars/profile4.png',
          '../../images/avatars/profile5.png',
          '../../images/avatars/profile6.png'
        ]" :key="a">
          <img :src="a" 
               @click="avatar = a" 
               class="w-16 h-16 rounded-full cursor-pointer object-cover border hover:ring-4 hover:ring-indigo-500 transition"
               :class="{ 'ring-4 ring-indigo-600': avatar === a }">
        </template>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-3">
        <button @click="open = false" 
                class="px-5 py-2 text-base text-gray-600 hover:underline">Cancel</button>
        <button @click="open = false" 
                class="px-5 py-2 text-base bg-indigo-600 text-white rounded-lg">Save</button>
      </div>
    </div>
  </div>
</div>


        </div>

<!-- Info -->
<div class="space-y-6 text-2xl mb-8">
  <div class="flex justify-between">
    <span class="text-gray-500 font-medium">Account ID</span>
    <span class="font-bold text-gray-800"><?= htmlspecialchars($user["account_id"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 font-medium">Role</span>
    <span class="font-bold text-gray-800"><?= htmlspecialchars($user["role"]) ?></span>
  </div>
</div>

<hr class="my-8 border-gray-300">

<div class="space-y-6 text-2xl">
  <div class="flex justify-between">
    <span class="text-gray-500 font-medium">Birthdate</span>
    <span class="font-bold text-gray-800"><?= htmlspecialchars($user["birthdate"]) ?></span>
  </div>
  <div class="flex justify-between">
    <span class="text-gray-500 font-medium">Registered</span>
    <span class="font-bold text-gray-800"><?= htmlspecialchars($user["registered"]) ?></span>
  </div>
</div>

      </div>

<!-- RIGHT: Spending + Transactions -->
<div class="flex flex-col gap-6">
  
  <!-- Spending (smaller card) -->
  <div class="bg-white p-6 rounded-xl shadow md:col-span-2">
    <h2 class="text-xl font-semibold text-gray-600">Spending</h2>
    <p class="text-3xl font-bold text-indigo-700 mt-4"><?= htmlspecialchars($user["spending"]) ?></p>
  </div>

  <!-- Transactions (takes full remaining space) -->
  <div class="bg-white p-6 rounded-xl shadow flex-1">
    <h2 class="text-2xl font-bold text-indigo-700 mb-6">Transactions</h2>
    <div class="space-y-3 max-h-64 overflow-y-auto pr-2 text-lg">
      <?php foreach ($transactions as $t): ?>
        <div class="grid grid-cols-4 gap-4 border-b pb-2">
          <span class="text-gray-700 font-medium"><?= htmlspecialchars($t["name"]) ?></span>
          <span class="text-gray-500"><?= htmlspecialchars($t["method"]) ?></span>
          <span class="text-gray-800 font-semibold"><?= htmlspecialchars($t["amount"]) ?></span>
          <span class="text-gray-500"><?= htmlspecialchars($t["date"]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>


    </div>
  </main>
</body>
</html>
