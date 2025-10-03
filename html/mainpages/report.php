<!-- use include '../php/handlers/report.php'; ?> to connect php to html -->
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
      <p>Welcome to ChainLedger Reports</p>
      <h1>Report Transactions</h1>
    </div>


<!-- Content -->
<section class="px-8 mt-10">
  <div class="bg-[#2c2f92] text-white rounded-xl p-10 flex justify-between items-start min-h-[500px] dark:bg-[#1F2937]">
    
    <!-- Form -->
    <form class="space-y-6 w-2/3">
      <div>
        <label name="details" class="block mb-2 text-base">Details</label>
        <select class="w-full px-4 py-2.5 rounded text-black text-base">
          <option>Select Details</option>
          <option>Payment</option>
          <option>Refund</option>
        </select>
      </div>

      <div>
        <label name="category" class="block mb-2 text-base">Category</label>
        <select class="w-full px-4 py-2.5 rounded text-black text-base">
          <option value="" disabled selected>Select Category</option>
          <option>Equipment</option>
          <option>Food</option>
          <option>Health</option>
          <option>Maintenance</option>
          <option>Utilities</option>
          <option>Travel</option>
        </select>
      </div>

      <div>
        <label name="merchant" class="block mb-2 text-base">Merchant</label>
        <select id="merchantSelect" class="w-full px-4 py-2.5 rounded text-black text-base">
          <option value="">Select Payment Merchant</option>
          <option value="gcash">GCash</option>
          <option value="googlepay">GooglePay</option>
          <option value="grabpay">GrabPay</option>
          <option value="maya">Maya</option>
          <option value="paypal">PayPal</option>
        </select>
      </div>

      <div>
        <label name="amount" class="block mb-2 text-base">Amount</label>
        <div class="flex items-center">
          <span class="px-4 py-2.5 bg-gray-200 text-black rounded-l text-base">₱</span>
          <input type="number" placeholder="0.00" class="w-full px-4 py-2.5 rounded-r text-black focus:outline-none text-base">
        </div>
      </div>

      <div>
        <label name="date" class="block mb-2 text-base">Date</label>
        <input type="date" class="w-full px-4 py-2.5 rounded text-black text-base" max="<?= date('Y-m-d') ?>">
      </div>
    </form>

    <!-- User Icon + Save Button -->
    <div name="merchantLogo" class="flex flex-col justify-start items-center w-1/3 ml-5">
      <img id="merchantLogo" src="../../images/logos/logo.png" 
           alt="User Profile" 
           class="w-60 h-60 object-contain rounded-full border-4 border-white shadow-lg mt-8">

      <button type="submit" class="bg-blue-100 text-black px-5 py-2.5 rounded-lg hover:bg-blue-200 mt-20 text-base font-medium dark:bg-slate-100">
        Save Transaction
      </button>
    </div>

  </div>
</section>
<?php include './includes/footer.php'; ?>

  </main>
</body>
</html>
