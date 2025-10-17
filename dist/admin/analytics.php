<?php
require_once __DIR__ . '/../services/AuthGuard.php';
require_once __DIR__ . '/handlers/analytics.php';
// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner', 'Manager']);


if(isset($_POST['submit'])){

} else {

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
      <?php foreach ($merchants as $a): ?>
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
    <!-- Monthly Line Chart -->
    <div class="transactions-chart mt-8 w-full md:w-3/4 lg:w-2/3 xl:w-1/2 mx-auto">
      <h3 class="text-lg font-bold mb-4 text-center">Monthly Total Transactions</h3>
      <canvas id="transactionsChart" class="h-72"></canvas>
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
    <p class="text-gray-700 dark:text-gray-200 font-medium truncate"><?= htmlspecialchars($c['title']) ?></p>
    <h2 class="text-lg font-bold mt-1 text-gray-800 dark:text-white break-words truncate"><?= $c['value'] ?></h2>
    <p class="text-xs text-gray-500 mt-1"><?= $c['count'] ?> txns</p>
  </div>
</div>

      <?php endforeach; ?>
    </div>
<!-- Right arrow buttons -->
<button id="cat-right" 
    class="absolute top-1/2 -translate-y-1/2 z-10 bg-gray-200 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full shadow-md hidden"
    style="right: -10px;">&gt;</button>
</section>

    <!-- Pie Chart -->
    <div class="transactions-chart mt-8 w-full md:w-3/4 lg:w-2/3 xl:w-1/2 mx-auto">
      <h3 class="text-xl font-bold mb-4 text-center">Monthly Spending Breakdown</h3>
      <canvas id="categoryChart" class="h-72"></canvas>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Line Chart
const ctxLine = document.getElementById('transactionsChart').getContext('2d');
new Chart(ctxLine, {
    type: 'line',
    data: { 
        labels: <?= json_encode($monthly_labels) ?>, 
        datasets: [{ label:'Total Transactions', data: <?= json_encode($monthly_values) ?>, borderColor:'rgb(75,192,192)', backgroundColor:'rgba(75,192,192,0.2)', tension:0.1, fill:true }] 
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});

// Pie Chart
const ctxPie = document.getElementById('categoryChart').getContext('2d');

const labels = <?= json_encode($pie_labels) ?>;
const values = <?= json_encode($pie_values) ?>;

// Match each category to a specific color
const categoryColors = {
  'Food': 'rgba(153, 102, 255, 0.8)',         // purple
  'Utilities': 'rgba(255, 99, 132, 0.8)',     // red
  'Transportation': 'rgba(54, 162, 235, 0.8)', // blue
  'Equipment': 'rgba(255, 205, 86, 0.8)',     // yellow
  'Health': 'rgba(75, 192, 192, 0.8)',        // green
  'Maintenance': 'rgba(255, 159, 64, 0.8)'    // orange
};

// Apply the colors based on category labels
const backgroundColors = labels.map(label => categoryColors[label] || 'rgba(201, 203, 207, 0.8)');

new Chart(ctxPie, {
  type: 'pie',
  data: {
    labels: labels,
    datasets: [{
      data: values,
      backgroundColor: backgroundColors,
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    }
  }
});

// Scroll arrows + auto-center first card
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainers = [
        {scroll:'#wallet-scroll', left:'#wallet-left', right:'#wallet-right'},
        {scroll:'#cat-scroll', left:'#cat-left', right:'#cat-right'}
    ];

    scrollContainers.forEach(s => {
        const container = document.querySelector(s.scroll);
        const leftBtn = document.querySelector(s.left);
        const rightBtn = document.querySelector(s.right);
        if (!container) return;

        rightBtn.addEventListener('click', ()=>container.scrollBy({left:300, behavior:'smooth'}));
        leftBtn.addEventListener('click', ()=>container.scrollBy({left:-300, behavior:'smooth'}));

        container.addEventListener('scroll', ()=>{
            leftBtn.classList.toggle('hidden', container.scrollLeft<=0);
            rightBtn.classList.toggle('hidden', container.scrollLeft >= container.scrollWidth - container.clientWidth);
        });

        leftBtn.classList.add('hidden');
        if(container.scrollWidth <= container.clientWidth) rightBtn.classList.add('hidden');

        // Auto-center first card
        const firstCard = container.querySelector('.flex-shrink-0');
        if(firstCard){
            const containerWidth = container.offsetWidth;
            const cardWidth = firstCard.offsetWidth;
            container.scrollLeft = Math.max((firstCard.offsetLeft + cardWidth/2) - containerWidth/2, 0);
        }
    });
});
</script>
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

