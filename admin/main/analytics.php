<!-- use include '../php/handlers/analytics.php'; ?> to connect php to html-->
<?php
$analytics = [
  ["title" => "Gcash", "value" => "₱10,200,000.00", "class" => "black", "image" => "../../images/ewallets/gcash.png"],
  ["title" => "GooglePay", "value" => "₱1,450,000,000,000.00", "class" => "black", "image" => "../../images/ewallets/googlepay.png"],
  ["title" => "GrabPay", "value" => "₱1,450,000,000,000.00", "class" => "black", "image" => "../../images/ewallets/grabpay.jpeg"],    
  ["title" => "Maya", "value" => "₱8,750,000.00", "class" => "black", "image" => "../../images/ewallets/maya.png"],
  ["title" => "Paypal", "value" => "₱1,450,000.00", "class" => "black", "image" => "../../images/ewallets/paypal.png"]
];
$categories = [
  ["title" => "Food", "value" => "₱2,000,000,000,050,000.00", "class" => "green", "icon" => "restaurant"],
  ["title" => "Utilities", "value" => "₱15,500.00", "class" => "red", "icon" => "bolt"],
  ["title" => "Travel", "value" => "₱8,200.00", "class" => "blue", "icon" => "flight"],
  ["title" => "Equipments", "value" => "₱5,000.00", "class" => "yellow", "icon" => "build"],
  ["title" => "Medical", "value" => "₱3,500.00", "class" => "purple", "icon" => "healing"],
  ["title" => "Maintenance", "value" => "₱4,200.00", "class" => "orange", "icon" => "engineering"],
];



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChainLedger Analytics</title>
  <link rel="stylesheet" href="css/main.css">
      <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>

    <!-- Icons & Charts -->
    <script src="https://unpkg.com/feather-icons"></script>
<!-- in your HTML (order matters!) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../js/charts.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <!-- Custom Theme Logic -->
    <script src="../../assets/js/js/scripts.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Header -->
    <?php include './includes/header.php'; ?>

    <!-- Title & Welcome moved OUTSIDE header -->
    <div class="title-block">
      <p>Welcome to ChainLedger Analytics</p>
      <h1>Analytics</h1>
    </div>


<section class="summary-wrapper">
  <button class="scroll-btn left  bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white 
         w-10 h-10 flex items-center justify-center rounded-full shadow-md">&lt;</button>

<!-- Summary Cards -->
<div class="summary" id="summary-scroll">
  <?php foreach ($analytics as $a): ?>
    <div class="card">
      <div class="icon-circle <?= strtolower($a["title"]) ?>">
        <img src="<?= $a["image"] ?>" alt="<?= $a["title"] ?>" class="card-icon">
      </div>
      <div class="card-text">
        <!-- 🔹 Light: black / Dark: gray-300 -->
        <p class="text-black dark:text-[#D1D5DB]"><?= $a["title"] ?></p>
        <h2 class="<?= $a["class"] ?>"><?= $a["value"] ?></h2>
      </div>
    </div>
  <?php endforeach; ?>
</div>


  <button class="scroll-btn right bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white 
         w-10 h-10 flex items-center justify-center rounded-full shadow-md">&gt;</button>
</section>



    <section class="content">
  <div class="transactions-chart">
    <h3>Monthly Total Transactions</h3>
    <canvas id="transactionsChart"></canvas>
  </div>
</section>

<!-- Category Summary Cards -->
<div class="mt-8">
  <h3 class="text-xl font-semibold mb-4">Transaction Categories</h3>

  <section class="relative">
    <!-- Left Button -->
    <button id="category-left"
        class="scroll-btn absolute -left-6 top-1/2 -translate-y-1/2 
         bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white 
         w-10 h-10 flex items-center justify-center rounded-full shadow-md">
      &lt;
    </button>

    <!-- Cards Container -->
<div id="category-scroll" class="categories">
  <?php foreach ($categories as $c): ?>
    <div class="card">
      <!-- Circle -->
      <div class="icon-circle
        <?php if($c['class']=='purple') echo 'bg-purple-100 text-purple-600'; ?>
        <?php if($c['class']=='red') echo 'bg-red-100 text-red-600'; ?>
        <?php if($c['class']=='blue') echo 'bg-blue-100 text-blue-600'; ?>
        <?php if($c['class']=='yellow') echo 'bg-yellow-100 text-yellow-600'; ?>
        <?php if($c['class']=='green') echo 'bg-green-100 text-green-600'; ?>
        <?php if($c['class']=='orange') echo 'bg-orange-100 text-orange-600'; ?>">
        <span class="material-icons-outlined text-2xl"><?= $c["icon"] ?></span>
      </div>

      <!-- Text -->
      <div>
        <!-- 🔹 Light: black / Dark: gray-300 -->
        <p class="text-black dark:text-[#D1D5DB]"><?= $c["title"] ?></p>
        <h2 class="<?= $c["class"] ?> font-bold text-lg"><?= $c["value"] ?></h2>
      </div>
    </div>
  <?php endforeach; ?>
</div>


    <!-- Right Button -->
    <button id="category-right"
        class="scroll-btn absolute -right-6 top-1/2 -translate-y-1/2 
         bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white 
         w-10 h-10 flex items-center justify-center rounded-full shadow-md">
      &gt;
    </button>
  </section>
</div>


<!-- Category Chart -->
<div class="transactions-chart mt-8">
  <h3>Monthly Spending Breakdown</h3>
  <canvas id="categoryChart"></canvas>
</div>
<?php include './includes/footer.php'; ?>
  </main>
</body>
</html>
