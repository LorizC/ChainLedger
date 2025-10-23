<?php

  require_once __DIR__ . '/../services/AuthGuard.php';
  include 'handlers/report.php';

// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Staff']);
  

?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
<head>
  <title>ChainLedger | Transaction Report</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Transaction Report Page" />
  <meta name="keywords" content="ChainLedger, Transaction, Report" />
  <meta name="author" content="Sniper 2025" />

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
  <link rel="stylesheet" href="../assets/fonts/feather.css" />
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../assets/fonts/material.css" />
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
    <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
      <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <!-- [ Sidebar Menu ] start -->
  <?php include '../includes/sidebar.php'; ?>
  <!-- [ Sidebar Menu ] end -->

  <!-- [ Header Topbar ] start -->
  <?php include '../includes/header.php'; ?>
  <!-- [ Header Topbar ] end -->

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="page-header-title">
            <h5 class="mb-0 font-medium">Transaction Report</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../staffs/dashboard.php">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Report</li>
          </ul>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

            <!-- Flash Messages -->
      <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
      <?php endif; ?>

      <!-- [ Main Content ] start -->
      <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
          <div class="card">
            <div class="card-header">
              <h5>Fill Out Report Details (<span style="color: red; font-weight: bold;">*</span> Required)</h5>
            </div>
            <div class="card-body">
<form action="" method="POST" class="form-horizontal">
  <!-- Transaction Type -->
  <div class="mb-3">
    <label for="transaction_type" class="form-label">
      Transaction Type <span style="color: red; font-weight: bold;">*</span>
    </label>
    <select name="transaction_type" id="transaction_type" class="form-control" required>
      <option value="">Select Transaction Type</option>
      <option value="PAYMENT">Payment (Online Payment)</option>
      <option value="REFUND">Refund (Receive Funds)</option>
      <option value="WITHDRAWAL">Withdrawal (Cash Out)</option>
      <option value="DEPOSIT">Deposit (Cash In)</option>
      <option value="TRANSFER">Transfer (Online Fund Transfer)</option>
    </select>
  </div>

  <!-- Category (Detail Column) -->
  <div class="mb-3">
    <label for="category" class="form-label">
      Category <span style="color: red; font-weight: bold;">*</span>
    </label>
    <select name="category" id="category" class="form-control" required>
      <option value="" disabled selected>Select Category</option>
      <option value="Equipment">Equipment</option>
      <option value="Food">Food</option>
      <option value="Health">Health</option>
      <option value="Maintenance">Maintenance</option>
      <option value="Utilities">Utilities</option>
      <option value="Transportation">Transportation</option>
    </select>
  </div>

  <!-- Merchant -->
  <div class="mb-3">
    <label for="merchant" class="form-label">
      Merchant <span style="color: red; font-weight: bold;">*</span>
    </label>
    <select name="merchant" id="merchant" class="form-control" required>
      <option value="">Select Payment Merchant</option>
      <option value="Gcash">GCash</option>
      <option value="Googlepay">GooglePay</option>
      <option value="Grabpay">GrabPay</option>
      <option value="Maya">Maya</option>
      <option value="Paypal">PayPal</option>
    </select>
  </div>

  <!-- Amount -->
  <div class="mb-3">
    <label for="amount" class="form-label">
      Amount <span style="color: red; font-weight: bold;">*</span>(Max amount:<span style="color: red"> 99,999,999.99</span>)
    </label>
    <div class="input-group">
      <span class="input-group-text">₱</span>
      <input type="number" step="0.01" min="0" max="99999999.99"  name="amount" id="amount" placeholder="0.00" class="form-control" required oninput="if(this.value > 99999999.99) this.value = 99999999.99;">
    </div>
  </div>

  <!-- Date -->
  <div class="mb-3">
    <label for="date" class="form-label">
      Date <span style="color: red; font-weight: bold;">*</span>
    </label>
    <input type="date" name="date" id="date" class="form-control"min="<?= date('1900-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
  </div>

  <div class="flex mt-1 justify-between items-center flex-wrap">
    <div class="form-check">
      <button type="reset" class="btn btn-danger mx-auto shadow-2xl">Cancel</button>      
      <button type="submit" name="submit_add" class="btn btn-success mx-auto shadow-2xl">Save Transaction</button>

    </div>
  </div>
</form>

            </div>
          </div>
        </div>
      </div>
      <!-- [ Main Content ] end -->
    </div>
  </div>
  <!-- [ Main Content ] end -->

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
