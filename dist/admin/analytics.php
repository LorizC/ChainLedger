<?php
require_once __DIR__ . '/../services/AuthGuard.php';

// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner', 'Manager']);


if(isset($_POST['submit'])){

} else {

$analytics = [
  ["title" => "Gcash", "value" => "₱10,200,000.00", "image" => "../assets/images/ewallets/gcash1.jpg"],
  ["title" => "GooglePay", "value" => "₱1,450,000,000,000.00", "image" => "../assets/images/ewallets/googlepay.png"],
  ["title" => "GrabPay", "value" => "₱1,450,000,000,000.00", "image" => "../assets/images/ewallets/grabpay.jpeg"],
  ["title" => "Maya", "value" => "₱8,750,000.00", "image" => "../assets/images/ewallets/maya.png"],
  ["title" => "Paypal", "value" => "₱1,450,000,000,000,000,000.00", "image" => "../assets/images/ewallets/paypal.png"]
];

$categories = [
  ["title" => "Food", "value" => "₱2,000,000.00", "color" => "bg-green-100 text-green-600", "icon" => "restaurant"],
  ["title" => "Utilities", "value" => "₱15,500.00", "color" => "bg-red-100 text-red-600", "icon" => "bolt"],
  ["title" => "Travel", "value" => "₱8,200.00", "color" => "bg-blue-100 text-blue-600", "icon" => "flight"],
  ["title" => "Equipments", "value" => "₱5,000.00", "color" => "bg-yellow-100 text-yellow-600", "icon" => "build"],
  ["title" => "Medical", "value" => "₱3,500.00", "color" => "bg-purple-100 text-purple-600", "icon" => "healing"],
  ["title" => "Maintenance", "value" => "₱4,200.00", "color" => "bg-orange-100 text-orange-600", "icon" => "engineering"],
];

?>
<!doctype html>
<html lang="en">
<head>
  <title>ChainLedger | Analytics</title>
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
    <style>
    [x-cloak]{display:none !important;}
    /* Hide scrollbar */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

<!-- Preloader -->
<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
  <div class="loader-track h-[5px] w-full absolute top-0 overflow-hidden">
    <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
  </div>
</div>

<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="pc-container">
  <div class="pc-content">

    <!-- Page Header -->
      <div class="page-header">
        <div class="page-block">
          <div class="page-header-title">
            <h5 class="mb-0 font-medium">Analytics</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../admin/dashboard.php">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Analytics</li>
          </ul>
        </div>
      </div>

<!-- E-Wallet Summary -->
<section class="relative px-2 py-6">
  <h5 class="text-lg font-semibold mb-4">Transaction Merchants</h5>
  <div class="relative">
    <button id="wallet-left" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-gray-200 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full shadow-md hidden">&lt;</button>
    <div id="wallet-scroll" class="flex gap-6 overflow-x-auto scroll-smooth px-12 no-scrollbar">
      <?php foreach ($analytics as $a): ?>
<div class="flex-shrink-0 w-auto max-w-xs bg-gray-50 dark:bg-gray-700 p-5 rounded-2xl shadow-md flex items-center gap-4">
  <!-- Image on the left -->
  <img src="<?= $a['image'] ?>" alt="<?= $a['title'] ?>" class="w-14 h-14 object-contain rounded-full flex-shrink-0">
  <!-- Details on the right -->
  <div class="flex flex-col min-w-0">
    <p class="text-gray-700 dark:text-gray-200 font-medium truncate"><?= $a['title'] ?></p>
    <h2 class="text-lg font-bold mt-1 text-gray-800 dark:text-white break-words truncate"><?= $a['value'] ?></h2>
  </div>
</div>
      <?php endforeach; ?>
    </div>
    <!-- Right arrow buttons -->
<button id="wallet-right" 
    class="absolute top-1/2 -translate-y-1/2 z-10 bg-gray-200 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full shadow-md hidden"
    style="right: -10px;">&gt;</button>
</section>
<!-- Transactions Chart -->
<div class="transactions-chart mt-8 w-full md:w-4/5 lg:w-3/4 xl:w-2/3 mx-auto">
  <h3 class="text-lg font-bold mb-4">Monthly Total Transactions</h3>
  <canvas id="transactionsChart" class="w-full h-96"></canvas>
</div>
<!-- Transaction Categories -->
<section class="relative px-2 py-6">
  <h5 class="text-lg font-semibold mb-4">Transaction Categories</h5>
  <div class="relative">
    <button id="cat-left" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-gray-200 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full shadow-md hidden">&lt;</button>
    <div id="cat-scroll" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar">
      <?php foreach ($categories as $c): ?>
<div class="flex-shrink-0 w-auto max-w-xs bg-gray-50 dark:bg-gray-700 p-5 rounded-2xl shadow-md flex items-center gap-4">
  <!-- Icon on the left -->
  <div class="p-3 rounded-full <?= $c['color'] ?> flex items-center justify-center flex-shrink-0">
    <span class="material-icons-outlined text-2xl"><?= $c['icon'] ?></span>
  </div>
  <!-- Details on the right -->
  <div class="flex flex-col min-w-0">
    <p class="text-gray-700 dark:text-gray-200 font-medium truncate"><?= $c['title'] ?></p>
    <h2 class="text-lg font-bold mt-1 text-gray-800 dark:text-white break-words truncate"><?= $c['value'] ?></h2>
  </div>
</div>

      <?php endforeach; ?>
    </div>
<!-- Right arrow buttons -->
<button id="cat-right" 
    class="absolute top-1/2 -translate-y-1/2 z-10 bg-gray-200 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full shadow-md hidden"
    style="right: -10px;">&gt;</button>
</section>

<!-- Category Chart -->
<div class="transactions-chart mt-8 w-full md:w-4/5 lg:w-3/4 xl:w-2/3 mx-auto">
  <h3 class="text-xl font-bold mb-4">Monthly Spending Breakdown</h3>
  <canvas id="categoryChart" class="w-full h-96"></canvas>
</div>
  </div>
</div>

</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/js/charts.js"></script>
<!-- Required Js -->
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
<?php } ?>
