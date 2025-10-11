<!--use include '../php/handlers/ledger.php'; ?> to connect php to html -->
<?php 
$ledger = [
  ["user" => "Loriz Carlos", "details" => "Food", "merchant" => "GrabPay", "amount" => "-₱2,255,555.55", "date" => "16-8"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],
  ["user" => "Sara Pyaya", "details" => "Equipment", "merchant" => "Maya", "amount" => "-₱10,000,000.00", "date" => "16-7"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Jon Weak", "details" => "Loan", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "date" => "16-5"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],
  ["user" => "Loriz Carlos", "details" => "Food", "merchant" => "GrabPay", "amount" => "-₱2,255,555.55", "date" => "16-8"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],
  ["user" => "Sarah Dicaya", "details" => "Equipment", "merchant" => "Maya", "amount" => "-₱10,000,000.00", "date" => "16-7"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Jon Weak", "details" => "Loan", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "date" => "16-5"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],
  ["user" => "Loriz Carlos", "details" => "Food", "merchant" => "GrabPay", "amount" => "-₱2,255,555.55", "date" => "16-8"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],
  ["user" => "Sarah Dicaya", "details" => "Equipment", "merchant" => "Maya", "amount" => "-₱10,000,000.00", "date" => "16-7"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Jon Weak", "details" => "Loan", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "date" => "16-5"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],  
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaction Ledger</title>
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
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Header -->
    <?php include './includes/header.php'; ?>

    <!-- Title & Welcome -->
    <div class="title-block">
      <p>Welcome to Transaction Ledger</p>
      <h1>ChainLedger History</h1>
    </div>

    <section class="content">
      <div class="ledger-section">
        <div class="ledger-wrapper">
          <table class="ledger-table">
<thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
  <tr>
    <!-- Transaction by -->
    <th class="px-4 py-2 text-left">
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        <span>Transaction by</span>
        <button 
          class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          @click="open = !open"
        >
          <span class="material-icons-outlined text-sm">arrow_drop_down</span>
        </button>
        <div 
          x-show="open" 
          @click.away="open = false"
          class="absolute top-full mt-1 w-40 bg-white dark:bg-gray-900 border border-gray-200 
                 dark:border-gray-700 rounded shadow-lg z-10"
        >
          <ul class="py-1 text-sm text-black dark:text-gray-200">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">All</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Loriz Carlos</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Mii Lee</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Sarah Dicaya</a></li>
          </ul>
        </div>
      </div>
    </th>

    <!-- Details -->
    <th class="px-4 py-2 text-left">
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        <span>Details</span>
        <button 
          class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" 
          @click="open = !open"
        >
          <span class="material-icons-outlined text-sm">arrow_drop_down</span>
        </button>
        <div 
          x-show="open"
          @click.away="open = false"
          class="absolute top-full mt-1 w-32 bg-white dark:bg-gray-900 border 
                 border-gray-200 dark:border-gray-700 rounded shadow-lg z-10"
        >
          <ul class="py-1 text-sm text-black dark:text-gray-200">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">All</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Equipment</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Food</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Health</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Maintenance</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Travel</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Utilities</a></li>
            
          </ul>
        </div>
      </div>
    </th>

    <!-- Merchant -->
    <th class="px-4 py-2 text-left">
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        <span>Merchant</span>
        <button 
          class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" 
          @click="open = !open"
        >
          <span class="material-icons-outlined text-sm">arrow_drop_down</span>
        </button>
        <div 
          x-show="open"
          @click.away="open = false"
          class="absolute top-full mt-1 w-32 bg-white dark:bg-gray-900 border 
                 border-gray-200 dark:border-gray-700 rounded shadow-lg z-10"
        >
          <ul class="py-1 text-sm text-black dark:text-gray-200">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">All</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Gcash</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">GrabPay</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">GooglePay</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Maya</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Paypal</a></li>
          </ul>
        </div>
      </div>
    </th>

    <!-- Amount -->
    <th class="px-4 py-2 text-left">
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        <span>Amount</span>
        <button 
          class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" 
          @click="open = !open"
        >
          <span class="material-icons-outlined text-sm">arrow_drop_down</span>
        </button>
        <div 
          x-show="open"
          @click.away="open = false"
          class="absolute top-full mt-1 w-32 bg-white dark:bg-gray-900 border 
                 border-gray-200 dark:border-gray-700 rounded shadow-lg z-10"
        >
          <ul class="py-1 text-sm text-black dark:text-gray-200">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Highest</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Lowest</a></li>
          </ul>
        </div>
      </div>
    </th>

    <!-- Date -->
    <th class="px-4 py-2 text-left">
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        <span>Date</span>
        <button 
          class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" 
          @click="open = !open"
        >
          <span class="material-icons-outlined text-sm">arrow_drop_down</span>
        </button>
        <div 
          x-show="open"
          @click.away="open = false"
          class="absolute top-full mt-1 w-32 bg-white dark:bg-gray-900 border 
                 border-gray-200 dark:border-gray-700 rounded shadow-lg z-10"
        >
          <ul class="py-1 text-sm text-black dark:text-gray-200">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Newest</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Oldest</a></li>
          </ul>
        </div>
      </div>
    </th>
  </tr>
</thead>

<tbody id="ledger-body">
  <?php foreach ($ledger as $index => $row): ?>
    <tr class="ledger-row">
      <td class="user-cell">
        <img src="../../images/avatars/profile.png"alt="Profile" class="user-icon">
        <?= $row["user"] ?>
      </td>
      <td><?= $row["details"] ?></td>
      <td class="merchant <?= strtolower(str_replace(' ', '', $row['merchant'])) ?>">
        <?= $row["merchant"] ?>
      </td>
      <td class="amount <?= strpos($row["amount"], '-') !== false ? 'negative' : 'positive' ?>">
        <?= $row["amount"] ?>
      </td>
      <td><?= $row["date"] ?></td>
    </tr>
  <?php endforeach; ?>
</tbody>
</table>

<!-- Pagination -->
<div class="flex justify-center items-center gap-4 mt-4">
  <button id="prev-page" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded disabled:opacity-50">&lt;</button>
  <span id="page-info" class="text-gray-700 dark:text-gray-300"></span>
  <button id="next-page" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded disabled:opacity-50">&gt;</button>
</div>
        </div>
      </div>
    </section>
    <?php include './includes/footer.php'; ?>
  </main>
</body>
</html>
