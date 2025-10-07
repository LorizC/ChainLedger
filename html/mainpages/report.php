
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ChainLedger Reports</title>
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
  <script src="../../js/scripts.js"></script>
</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>
  
<!-- Main -->
  <main class="main">
  <!-- Header -->
  <?php include './includes/header.php'; ?>
  <!-- Sidebar -->

    <!-- Success Message -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="bg-green-500 text-white p-4 rounded mb-6 text-center" 
             x-data="{ show: true }" x-show="show" 
             x-init="setTimeout(() => show = false, 4000)">
            ✅ Save Transaction successfully!
        </div>
    <?php endif; ?>
    <!-- Title & Welcome moved OUTSIDE header -->
    <div class="title-block">
      <p>Welcome to ChainLedger Reports</p>
      <h1>Report Transactions</h1>
    </div>


<!-- Content -->
<section class="px-8 mt-10">
  <div class="bg-[#2c2f92] text-white rounded-xl p-10 flex justify-between items-start min-h-[500px] dark:bg-[#1F2937]">
    
   <!-- Form + User Icon + Save Button -->
    <form action="../../php/handlers/report.php" method="POST" class="flex w-full space-x-10">

    <!-- Form -->
      <div class="space-y-6 w-2/3">
        <div>
        <label for="details" class="block mb-2 text-base">Details</label>
        <select name="details" id="details" class="w-full px-4 py-2.5 rounded text-black text-base">
          <option value="">Select Details</option>
          <option value="PAYMENT">Payment</option><!--minus-->
          <option value="REFUND">Refund</option><!--plus-->
          <option value="WITHDRAWAL">Withdrawal</option><!--minus-->
          <option value="DEPOSIT">Deposit</option><!--plus-->
        </select>
      </div>

      <div>
        <label for="category" class="block mb-2 text-base">Category</label>
        <select name="category" id="category" class="w-full px-4 py-2.5 rounded text-black text-base">
          <option value="" disabled selected>Select Category</option>
          <option value="Equipment">Equipment</option>
          <option value="Food">Food</option>
          <option value="Health">Health</option>
          <option value="Maintenance">Maintenance</option>
          <option value="Utilities">Utilities</option>
          <option value="Travel">Travel</option>
        </select>
      </div>

      <div>
        <label for="merchant" class="block mb-2 text-base">Merchant</label>
        <select name="merchant" id="merchantSelect" class="w-full px-4 py-2.5 rounded text-black text-base">
          <option value="">Select Payment Merchant</option>
          <option value="Gcash">GCash</option>
          <option value="Googlepay">GooglePay</option>
          <option value="Grabpay">GrabPay</option>
          <option value="Maya">Maya</option>
          <option value="Paypal">PayPal</option>
        </select>
      </div>

<div>
  <label for="amount" class="block mb-2 text-base">Amount</label>
  <div class="flex items-center relative w-full">
    <!-- Currency Button -->
    <button id="currencyBtn" type="button"
      class="px-4 py-2.5 bg-gray-100 text-black rounded-l-lg text-base font-medium border border-gray-300 hover:bg-gray-200 transition duration-150 ease-in-out focus:outline-none flex items-center gap-1"
      onclick="toggleCurrencyDropdown()">
      <span id="currencySymbol">₱</span>
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown -->
    <div id="currencyDropdown"
      class="absolute left-0 top-12 w-44 bg-white text-black rounded-xl shadow-lg border border-gray-200 hidden z-20 overflow-hidden transform transition-all duration-200 ease-out">
      <ul class="text-base divide-y divide-gray-100">
        <li><button type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-left transition" onclick="selectCurrency('₱', 'PHP')">₱ Philippine Peso</button></li>
        <li><button type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-left transition" onclick="selectCurrency('$', 'USD')">$ US Dollar</button></li>
        <li><button type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-left transition" onclick="selectCurrency('€', 'EUR')">€ Euro</button></li>
        <li><button type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-left transition" onclick="selectCurrency('¥', 'JPY')">¥ Japanese Yen</button></li>
        <li><button type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-left transition" onclick="selectCurrency('₩', 'KRW')">₩ Korean Won</button></li>
      </ul>
    </div>

    <!-- Amount Input -->
    <input type="number" name="amount" placeholder="0.00"
      class="w-full px-4 py-2.5 rounded-r-lg text-black focus:outline-none focus:ring-2 focus:ring-blue-300 border border-l-0 border-gray-300 text-base transition duration-150 ease-in-out">

    <!-- Hidden Currency Input (for backend use) -->
    <input type="hidden" name="currency" id="currencyInput" value="PHP">
  </div>
</div>
 
      <div>
        <label for="date" class="block mb-2 text-base">Date</label>
        <input type="date" name="date" id="date" class="w-full px-4 py-2.5 rounded text-black text-base">
      </div>

      <div>
        <label for="status" class="block mb-2 text-base">Status</label>
        <select  name="status" id="statusSelect" class="w-full px-4 py-2.5 rounded text-black text-base">
          <option value="">Select Payment Status</option>
          <option value="COMPLETED">Complete</option>
          <option value="PENDING">Pending</option>
          <option value="FAILED">Failed</option>
          <option value="CANCELLED">Cancelled</option>
        </select>
      </div> 
    </div>   
    <!-- User Icon + Save Button -->
    <div name="merchantLogo" class="flex flex-col justify-start items-center w-1/3 ml-5">
      <img id="merchantLogo" src="../../images/logos/logo.png" 
           alt="logo" 
           class="w-60 h-60 object-contain rounded-full border-4 border-white shadow-lg mt-8">

      <button type="submit" class="bg-blue-100 text-black px-5 py-2.5 rounded-lg hover:bg-blue-200 mt-20 text-base font-medium dark:bg-slate-100">
        Save Transaction
      </button>
    </div>
    </form>
  </div>
</section>
<?php include './includes/footer.php'; ?>
<script>
  function toggleCurrencyDropdown() {
    const dropdown = document.getElementById('currencyDropdown');
    dropdown.classList.toggle('hidden');
  }

  function selectCurrency(symbol, code) {
    document.getElementById('currencySymbol').textContent = symbol;
    document.getElementById('currencyInput').value = code;
    document.getElementById('currencyDropdown').classList.add('hidden');
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('currencyDropdown');
    const button = document.getElementById('currencyBtn');
    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>
  </main>
</body>
</html>
