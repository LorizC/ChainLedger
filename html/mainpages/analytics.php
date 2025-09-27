<!-- use include '../php/handlers/analytics.php'; ?> to connect php to html-->
<?php
$analytics = [
  ["title" => "Gcash", "value" => "₱10,200,000.00", "class" => "black", "image" => "../../images/ewallets/gcash.png"],
  ["title" => "Maya", "value" => "₱8,750,000.00", "class" => "black", "image" => "../../images/ewallets/maya.png"],
  ["title" => "GrabPay", "value" => "₱1,450,000,000,000.00", "class" => "black", "image" => "../../images/ewallets/grabpay.jpeg"],
  ["title" => "Paypal", "value" => "₱1,450,000.00", "class" => "black", "image" => "../../images/ewallets/paypal.png"],
  ["title" => "GooglePay", "value" => "₱1,450,000,000,000.00", "class" => "black", "image" => "../../images/ewallets/googlepay.png"],
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

    <!-- Title & Welcome moved OUTSIDE header -->
    <div class="title-block">
      <h1>Analytics</h1>
      <p>Welcome to ChainLedger Analytics</p>
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

<script>
  const ctx = document.getElementById('transactionsChart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
      ],
      datasets: [
        {
          label: 'GCash',
          data: [80, 120, 40, 130, 60, 40, 70, 60, 90, 60, 80, 100],
          backgroundColor: '#004ed5'
        },
        {
          label: 'Maya',
          data: [60, 0, 30, 0, 50, 0, 40, 0, 30, 0, 40, 0],
          backgroundColor: '#9cffc5'
        },
        {
          label: 'GrabPay',
          data: [0, 0, 20, 0, 30, 0, 0, 0, 20, 0, 30, 0],
          backgroundColor: '#02ab4ee9'
        },
        {
          label: 'Paypal',
          data: [0, 0, 20, 0, 30, 0, 0, 0, 20, 0, 30, 0],
          backgroundColor: '#013eae'
        },
        {
          label: 'GooglePay',
          data: [0, 0, 20, 0, 30, 0, 0, 0, 20, 0, 30, 0],
          backgroundColor: '#fb5305c1'
        }

      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Transactions' }
        }
      }
    }
  });
</script>

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

<script>
  const categoryCtx = document.getElementById('categoryChart').getContext('2d');

  new Chart(categoryCtx, {
    type: 'bar',
    data: {
      labels: [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
      ],
      datasets: [
        {
          label: 'Equipments',
          data: [500, 700, 650, 600, 750, 720, 800, 850, 900, 870, 950, 1000],
          backgroundColor: '#FFFF00'
        },        
        {
          label: 'Food',
          data: [2000, 2500, 3000, 2800, 3100, 2900, 3300, 3500, 4000, 3700, 4200, 4500],
          backgroundColor: '#712eb9ff'
        },
        {
          label: 'Maintenance',
          data: [500, 700, 650, 600, 750, 720, 800, 850, 900, 870, 950, 1000],
          backgroundColor: '#ff9800'
        },         
        {
          label: 'Health',
          data: [500, 700, 650, 600, 750, 720, 800, 850, 900, 870, 950, 1000],
          backgroundColor: '#1eff00ff'
        },        
        {
          label: 'Travel',
          data: [800, 900, 950, 880, 1000, 970, 1050, 1100, 1200, 1150, 1250, 1300],
          backgroundColor: '#3557c6ff'
        },       
        {
          label: 'Utilities',
          data: [1500, 1400, 1600, 1550, 1700, 1650, 1800, 1750, 1900, 1850, 2000, 2100],
          backgroundColor: '#d33d33ff'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: {
          beginAtZero: true,
          stacked: true, // 🔹 stack categories vertically
          title: { display: true, text: 'Amount (₱)' }
        },
        x: {
          stacked: true, // 🔹 stack categories horizontally
          title: { display: true, text: 'Month' }
        }
      }
    }
  });
</script>


  </main>
</body>
</html>
