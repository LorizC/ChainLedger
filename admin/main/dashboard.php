<!-- use include '../php/handlers/dashboard.php'; ?> to connect php to html-->

<?php
// Example PHP variables (replace with DB values later)
$totalTransactions = "125,143,000,000,000,000,000,000,000,000,000,000";
$totalCosts = "₱122,434,878,991,000,000,000,000,000,000,000,000,000.25";
$totalGains = "₱5,122,991,999,669,678,700,000,000,000,000.00";
$totalTransactors = 5;

$transactions = [
  ["name" => "Loriz Neil Carlos", "merchant" => "GrabPay", "amount" => "₱5,000.00", "date" => "09-11-2025"],
  ["name" => "Bob Sponge", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "09-10-2025"],
  ["name" => "Jin Jin", "merchant" => "Maya", "amount" => "₱1,000,000.00", "date" => "08-10-2025"],
  ["name" => "Yan Yin", "merchant" => "GooglePay", "amount" => "₱1,000,000.00", "date" => "08-1-2025"],
  ["name" => "Tim Mothee", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "07-1-2025"],
  ["name" => "Pat Rick", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "06-1-2025"],
  ["name" => "Loriz Neil Carlos", "merchant" => "GrabPay", "amount" => "₱5,000.00", "date" => "09-11-2025"],
  ["name" => "Bob Sponge", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "09-10-2025"],
  ["name" => "Jin Jin", "merchant" => "Maya", "amount" => "₱1,000,000.00", "date" => "08-10-2025"],
  ["name" => "Yan Yin", "merchant" => "GooglePay", "amount" => "₱1,000,000.00", "date" => "08-1-2025"],
  ["name" => "Tim Mothee", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "07-1-2025"],
  ["name" => "Pat Rick", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "06-1-2025"],
  ["name" => "Loriz Neil Carlos", "merchant" => "GrabPay", "amount" => "₱5,000.00", "date" => "09-11-2025"],
  ["name" => "Bob Sponge", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "09-10-2025"],
  ["name" => "Jin Jin", "merchant" => "Maya", "amount" => "₱1,000,000.00", "date" => "08-10-2025"],
  ["name" => "Yan Yin", "merchant" => "GooglePay", "amount" => "₱1,000,000.00", "date" => "08-1-2025"],
  ["name" => "Tim Mothee", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "07-1-2025"],
  ["name" => "Pat Rick", "merchant" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "06-1-2025"],    
];

$breakdown = [
  ["label" => "Food", "color" => "#712eb9ff", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Equipments", "color" => "yellow", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Health", "color" => "#1eff00ff", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Travel", "color" => "#02ab4ee9", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Maintenance", "color" => "#ff9800", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Utilities", "color" => "#fb5305c1", "amount" => "₱120,567.00", "percent" => "20%"],  
];

$perPage = 4;
$totalPages = ceil(count($transactions) / $perPage);
$currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;
$startIndex = ($currentPage - 1) * $perPage;
$pagedTransactions = array_slice($transactions, $startIndex, $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChainLedger Dashboard</title>
  <link rel="stylesheet" href="css/main.css">
      <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>

    <!-- Icons & Charts -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <!-- Custom Theme Logic -->
    <script src="../../assets/js/js/scripts.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <?php include '../assets/includes/sidebar.php'; ?>
  
<!-- Main -->
  <main class="main">
  <!-- Header -->
  <?php include '../assets/includes/header.php'; ?>
  <!-- Sidebar -->


    <!-- Title & Welcome moved OUTSIDE header -->
    <div class="title-block">
      <p>Welcome to ChainLedger E-Wallet Transaction Management</p>
      <h1>Dashboard</h1>
    </div>

    
<section class="relative mt-6 mb-8">
  <!-- Left Button -->
  <button id="total-left"   class="scroll-btn absolute -left-6 top-1/2 -translate-y-1/2 
         bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white 
         w-10 h-10 flex items-center justify-center rounded-full shadow-md">
    &lt;
  </button>

  <!-- Cards Container -->
  <div id="total-scroll" class="categories">

    <!-- Total Transactions -->
    <div class="card">
      <div class="icon-circle bg-blue-100 text-blue-600 ">
        <span class="material-icons-outlined text-2xl">payments</span>
      </div>
      <div>
        <p class="text-gray-500  dark:text-gray-300 text-sm">Total Transactions</p>
        <h2 class="text-blue-1000 dark:text-blue-500 font-bold text-lg"><?= $totalTransactions ?></h2>
      </div>
    </div>

    <!-- Total Costs -->
    <div class="card">
      <div class="icon-circle bg-red-100 text-red-600 ">
        <span class="material-icons-outlined text-2xl">trending_down</span>
      </div>
      <div>
        <p class="text-gray-500 dark:text-gray-300 text-sm">Total Costs</p>
        <h2 class="text-red-600 dark:text-red-500 font-bold text-lg"><?= $totalCosts ?></h2>
      </div>
    </div>

    <!-- Total Gains -->
    <div class="card">
      <div class="icon-circle bg-green-100 text-green-600 ">
        <span class="material-icons-outlined text-2xl">trending_up</span>
      </div>
      <div>
        <p class="text-gray-500 dark:text-gray-300 text-sm">Total Gains</p>
        <h2 class="text-green-600 dark:text-green-500 font-bold text-lg"><?= $totalGains ?></h2>
      </div>
    </div>

    <!-- Total Transactors -->
    <div class="card">
      <div class="icon-circle bg-orange-100 text-orange-600">
        <span class="material-icons-outlined text-2xl ">groups</span>
      </div>
      <div>
        <p class="text-gray-500 dark:text-gray-300 text-sm">Total Transactors</p>
        <h2 class="text-orange-600 dark:text-orange-500 font-bold text-lg"><?= $totalTransactors ?></h2>
      </div>
    </div>

  </div>

  <!-- Right Button -->
  <button id="total-right"   class="scroll-btn absolute -right-6 top-1/2 -translate-y-1/2 
         bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white 
         w-10 h-10 flex items-center justify-center rounded-full shadow-md">
    &gt;
  </button>
</section>


    <!-- Content -->
    <section class="content">
      <div class="history">
  <h3>Recent Transactions</h3>

  <!-- Transactions List -->
  <div class="flex flex-col gap-3">
    <?php foreach ($pagedTransactions as $t): ?>
      <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
        <div>
          <p class="font-medium"><?= $t["name"] ?></p>
          <p class="text-sm text-gray-500 dark:text-gray-300"><?= $t["date"] ?> · 
            <span class="merchant <?= strtolower($t["merchant"]) ?>"><?= $t["merchant"] ?></span>
          </p>
        </div>
        <span class="font-semibold "><?= $t["amount"] ?></span>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination Controls -->
  <div class="flex justify-center items-center gap-4 mt-6">
    <?php if ($currentPage > 1): ?>
      <a href="?page=<?= $currentPage - 1 ?>" 
         class="px-3 py-1 bg-gray-200 rounded hover:bg-indigo-600 hover:text-white dark:text-black dark:hover:bg-gray-600 dark:hover:text-white">&lt;</a>
    <?php endif; ?>

    <span class="text-sm">Page <?= $currentPage ?> of <?= $totalPages ?></span>

    <?php if ($currentPage < $totalPages): ?>
      <a href="?page=<?= $currentPage + 1 ?>" 
         class="px-3 py-1 bg-gray-200 rounded hover:bg-indigo-600 hover:text-white dark:text-black dark:hover:bg-gray-600 dark:hover:text-white">&gt;</a>
    <?php endif; ?>
  </div>
</div>

      <div class="breakdown">
        <h3>Transactions Breakdown</h3>

        <!-- Stacked Colored Line -->
        <div class="stacked-line">
          <?php foreach ($breakdown as $b): ?>
            <div class="line-segment" style="background: <?= $b['color'] ?>; width: <?= $b['percent'] ?>;"></div>
          <?php endforeach; ?>
        </div>

        <!-- Labels with dots -->
        <?php foreach ($breakdown as $b): ?>
          <div class="row">
            <span class="dot" style="background: <?= $b['color'] ?>;"></span>
            <span><?= $b['label'] ?></span>
            <span><?= $b['amount'] ?> (<?= $b['percent'] ?>)</span>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php include '../assets/includes/footer.php'; ?>
  </main>
</body>
</html>

