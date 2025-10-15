<?php
require_once __DIR__ . '/../services/AuthGuard.php';
require_once __DIR__ . '/handlers/dashboard.php';

// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner', 'Manager']);

$role = strtolower(trim($_SESSION['user']['company_role'] ?? ''));

// Only business owners or managers
if ($role !== 'business owner' && $role !== 'manager') {
    header("Location: /ChainLedger-System-/pages.php");
    exit;
}
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr">

<head>
  <title>ChainLedger | Dashboard</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Transaction summary and breakdown" />
  <meta name="author" content="Sniper 2025" />

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">  
  <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
  <link rel="stylesheet" href="../assets/fonts/feather.css" />
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../assets/fonts/material.css" />

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />
  <!-- Tailwind CSS -->
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
  <main class="pc-container">
    <div class="pc-content">

      <!-- Page Header -->
      <div class="page-header mb-4">
        <div class="page-block">
          <div class="page-header-title">
            <h5 class="mb-0 font-medium">Dashboard</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../admin/dashboard.php">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Transactions</li>
          </ul>
        </div>
      </div>
<!-- Flash & Status Messages -->
<?php
// Flash success message (session)
if (!empty($_SESSION['flash_success'])): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <?= $_SESSION['flash_success']; ?>
  </div>
  <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php
// Success messages (from URL parameters)
if (isset($_GET['deleted'])): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <?php if ($_GET['deleted'] === 'user'): ?>
      User deleted successfully!
    <?php elseif ($_GET['deleted'] === 'transaction'): ?>
      Transaction deleted successfully!
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php
// Error messages (from URL parameters)
if (isset($_GET['error'])): ?>
  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <?php if ($_GET['error'] === 'delete_failed'): ?>
      Delete failed. Try again.
    <?php elseif ($_GET['error'] === 'invalid_id'): ?>
      Invalid ID. No action taken.
    <?php endif; ?>
  </div>
<?php endif; ?>

      <!-- Summary Cards -->
      <section class="relative mt-6 mb-8">
        <div class="grid grid-cols-12 gap-6">
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">receipt_long</span>
              <h6 class="text-gray-500 mb-2">Total Transactions</h6>
              <h3 class="text-2xl font-semibold text-blue-600"><?= number_format($totalTransactions) ?></h3>
            </div>
          </div>
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">trending_up</span>
              <h6 class="text-gray-500 mb-2">Total Gains</h6>
              <h3 class="text-2xl font-semibold text-green-600">₱<?= number_format($totalGains, 2) ?></h3>
            </div>
          </div>
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
               <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">trending_down</span>
              <h6 class="text-gray-500 mb-2">Total Costs</h6>
              <h3 class="text-2xl font-semibold text-red-600">₱<?= number_format($totalCosts, 2) ?></h3>
            </div>
          </div>
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">account_balance_wallet</span>
              <h6 class="text-gray-500 mb-2">Net Balance</h6>
              <h3 class="text-2xl font-semibold <?= $netBalance >= 0 ? 'text-green-600' : 'text-red-600' ?> mb-0">₱<?= number_format($netBalance, 2) ?></h3>
            </div>
          </div>
        </div>
      </section>

<!-- Users Table --> 
 <section class="content mt-8"> 
  <div class="card table-card"> 
  <div class="card-header flex justify-between items-center"> 
  <h5>Transactors</h5> <a href="#" class="text-primary-500 text-sm flex items-center"> 
  <i data-feather="download" class="w-4 h-4 mr-1"></i> Export </a> </div> <div class="card-body overflow-x-auto"> 
  <table class="table table-hover w-full"> 
    <thead>
     <tr>
     <th>User ID</th> 
     <th>Full Name</th> 
     <th>Username</th> 
     <th>Role</th> 
     <th>Date Registered</th> 
     <th>Action</th> 
    </tr> 
    </thead> 
    <tbody> 
                                <?php foreach($transactors as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                                        <td><?= $user['full_name'] ?></td>
                                        <td><?= $user['username'] ?></td>
                                        <td><span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800"><?= ucfirst($user['role']) ?></span></td>
                                        <td><?= $user['formatted_date'] ?></td>
                                        <td>
                                            <a href="handlers/delete_user.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="text-red-600 hover:text-red-800">
                                                <span class="material-icons-outlined text-base align-middle">delete</span> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
</tbody> 
</table> 
</div> 
</div> 
</section> 
<!-- Transactions Table --> 
 <section class="content mt-8"> 
  <div class="card table-card"> 
    <div class="card-header flex justify-between items-center">
       <h5>Recent Transactions</h5>
        <a href="#" class="text-primary-500 text-sm flex items-center"> 
          <i data-feather="download" class="w-4 h-4 mr-1"></i> Export </a> 
        </div> 
        <div class="card-body overflow-x-auto"> 
          <table class="table table-hover w-full"> 
            <thead> <tr> <th>Transaction By</th> 
            <!-- New Column --> 
             <th>Category</th> 
             <th>Details</th> 
             <th>Amount</th> 
             <th>Status</th> 
             <th>Date</th> 
             <th>Action</th> 
            </tr> 
          </thead> 
          <tbody> 
                                <?php foreach($recentTransactions as $tx): ?>
                                    <tr>
                                        <td><?= $tx['fullname'] ?></td>
                                        <td><span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"><?= $tx['category'] ?></span></td>
                                        <td><?= $tx['detail'] ?></td>
                                        <td class="<?= $tx['is_negative'] ? 'text-red-500 font-semibold' : 'text-green-500 font-semibold' ?>">  <!-- Red if cost, no minus -->
                                            <?= $tx['formatted_amount'] ?>  <!-- Positive always -->
                                        </td>
                                        <td>
                                            <span class="px-2 py-1 rounded text-xs 
                                                <?= $tx['status'] === 'Completed' ? 'bg-green-100 text-green-800' : 
                                                   ($tx['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($tx['status'] === 'Failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                                <?= ucfirst($tx['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $tx['formatted_date'] ?></td>
                                        <td>
                                            <a href="handlers/delete_transaction.php?id=<?= $tx['transaction_id'] ?>" onclick="return confirm('Are you sure you want to delete this transaction?')" class="text-red-600 hover:text-red-800">
                                                <span class="material-icons-outlined text-base align-middle">delete</span> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>      
    </tbody> 
  </table> 
</div>
</section>
    </div>
  </main>

  <?php include '../includes/footer.php'; ?>

  <!-- Required JS -->
  <script src="../assets/js/plugins/simplebar.min.js"></script>
  <script src="../assets/js/plugins/popper.min.js"></script>
  <script src="../assets/js/icon/custom-icon.js"></script>
  <script src="../assets/js/plugins/feather.min.js"></script>
  <script src="../assets/js/component.js"></script>
  <script src="../assets/js/theme.js"></script>
  <script src="../assets/js/script.js"></script>

  <script>
    layout_change('false');
    layout_theme_sidebar_change('dark');
    change_box_container('false');
    layout_caption_change('true');
    layout_rtl_change('false');
    preset_change('preset-1');
    main_layout_change('vertical');
    feather.replace();
  </script>
</body>
</html>
