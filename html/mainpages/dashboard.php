

<?php
// Example PHP variables (replace with DB values later)
$totalTransactions = "125,143,000,000,000,000,000,000,000,000,000,000";
$totalCosts = "₱122,434,878,991,000,000,000,000,000,000,000,000,000.25";
$totalGains = "₱5,122,991,999,669,678,700,000,000,000,000.00";
$totalTransactors = 5;

$transactions = [
  ["name" => "Loriz Neil Carlos", "method" => "GrabPay", "amount" => "₱5,000.00", "date" => "09-11-2025"],
  ["name" => "Bob Sponge", "method" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "09-10-2025"],
  ["name" => "Jin Jin", "method" => "Maya", "amount" => "₱1,000,000.00", "date" => "08-10-2025"],
  ["name" => "Yan Yin", "method" => "GooglePay", "amount" => "₱1,000,000.00", "date" => "08-1-2025"],
  ["name" => "Tim Mothee", "method" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "07-1-2025"],
  ["name" => "Pat Rick", "method" => "GrabPay", "amount" => "₱1,000,000.00", "date" => "06-1-2025"],
];

$breakdown = [
  ["label" => "Food", "color" => "#712eb9ff", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Equipments", "color" => "yellow", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Health", "color" => "#1eff00ff", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Travel", "color" => "#02ab4ee9", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Maintenance", "color" => "#ff9800", "amount" => "₱120,567.00", "percent" => "20%"],
  ["label" => "Utilities", "color" => "#fb5305c1", "amount" => "₱120,567.00", "percent" => "20%"],
];
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
    <script src="../../js/user.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>
  
<!-- Main -->
  <main class="main">
  <!-- Header -->
  <?php include './includes/header.php'; ?>
  <!-- Sidebar -->


    <!-- Title & Welcome moved OUTSIDE header -->
    <div class="title-block">
      <h1>Dashboard</h1>
      <p>Welcome to ChainLedger E-Wallet Transaction Management</p>
    </div>

    
<section class="relative mt-6">
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
      <div class="icon-circle bg-blue-100 text-blue-600">
        <span class="material-icons-outlined text-2xl">payments</span>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Total Transactions</p>
        <h2 class="text-blue-600 font-bold text-lg"><?= $totalTransactions ?></h2>
      </div>
    </div>

    <!-- Total Costs -->
    <div class="card">
      <div class="icon-circle bg-red-100 text-red-600">
        <span class="material-icons-outlined text-2xl">trending_down</span>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Total Costs</p>
        <h2 class="text-red-600 font-bold text-lg"><?= $totalCosts ?></h2>
      </div>
    </div>

    <!-- Total Gains -->
    <div class="card">
      <div class="icon-circle bg-green-100 text-green-600">
        <span class="material-icons-outlined text-2xl">trending_up</span>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Total Gains</p>
        <h2 class="text-green-600 font-bold text-lg"><?= $totalGains ?></h2>
      </div>
    </div>

    <!-- Total Transactors -->
    <div class="card">
      <div class="icon-circle bg-orange-100 text-orange-600">
        <span class="material-icons-outlined text-2xl">groups</span>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Total Transactors</p>
        <h2 class="text-orange-600 font-bold text-lg"><?= $totalTransactors ?></h2>
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
        <h3>Transaction History</h3>
        <table>
          <tbody>
            <?php foreach ($transactions as $t): ?>
              <tr>
                <td><?= $t["name"] ?></td>
                <td>
                  <span class="merchant <?= strtolower($t["method"]) ?>">
                    <?= $t["method"] ?>
                  </span>
                </td>
                <td><?= $t["amount"] ?></td>
                <td><?= $t["date"] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
  </main>
</body>

