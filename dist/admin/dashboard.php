<?php
require_once __DIR__ . '/../services/AuthGuard.php';

// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner', 'Manager']);

$role = strtolower(trim($_SESSION['user']['company_role'] ?? ''));

// Only business owners or managers
if ($role !== 'business owner' && $role !== 'manager') {
    header("Location: /ChainLedger-System-/pages.php");
    exit;
}

// Example data (you can replace this with dynamic values later)
$breakdown = [
  ['label' => 'Food', 'amount' => '$450.20', 'percent' => '30%', 'color' => '#bb71f8ff'],
  ['label' => 'Transport', 'amount' => '$200.00', 'percent' => '13%', 'color' => '#60a5fa'],
  ['label' => 'Equipments', 'amount' => '$350.50', 'percent' => '23%', 'color' => '#defb24ff'],
  ['label' => 'Health', 'amount' => '$490.00', 'percent' => '34%', 'color' => '#34d399'],
  ['label' => 'Utilities', 'amount' => '$490.00', 'percent' => '34%', 'color' => '#d33434ff'],
  ['label' => 'Maintenance', 'amount' => '$490.00', 'percent' => '34%', 'color' => '#d3ce34ff'],
];

if (isset($_POST['submit'])) {
    // Handle post requests here
} else {
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
    <!-- Flash Messages -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
      </div>
    <?php endif; ?>
      <!-- Summary Cards -->
      <section class="relative mt-6 mb-8">
        <div class="grid grid-cols-12 gap-6">
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <h6 class="text-gray-500 mb-2">Total Transactions</h6>
              <h3 class="text-2xl font-semibold">12,459.32</h3>
              <p class="text-green-500 text-sm mt-2"><i data-feather="trending-up"></i> +8.2%</p>
            </div>
          </div>
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <h6 class="text-gray-500 mb-2">Total Gains</h6>
              <h3 class="text-2xl font-semibold text-green-600">$7,832.10</h3>
              <p class="text-green-500 text-sm mt-2"><i data-feather="arrow-up-right"></i> +4.7%</p>
            </div>
          </div>
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <h6 class="text-gray-500 mb-2">Total Costs</h6>
              <h3 class="text-2xl font-semibold text-red-600">$4,627.22</h3>
              <p class="text-red-500 text-sm mt-2"><i data-feather="arrow-down-right"></i> -2.1%</p>
            </div>
          </div>
          <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="card p-6 text-center">
              <h6 class="text-gray-500 mb-2">Net Balance</h6>
              <h3 class="text-2xl font-semibold text-blue-600">$3,204.88</h3>
              <p class="text-blue-500 text-sm mt-2"><i data-feather="dollar-sign"></i> Stable</p>
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
      <tr> 
        <td>1</td> 
        <td>John Will Smith</td> 
        <td>John Smith</td> 
        <td>Business Owner</td> 
        <td>Jan 15, 2025</td> 
        <td> 
        <a href="#" class="text-gray-500 hover:text-red-500" title="Delete">
        <i data-feather="trash-2">
        </i> 
      </a> 
    </td> 
  </tr> 
  <tr> 
    <td>2</td> 
    <td>Jane P Doe</td> 
    <td>Jane Doe</td> 
    <td>Staff</td> 
    <td>Feb 20, 2025</td> 
    <td> 
      <a href="#" class="text-gray-500 hover:text-red-500" title="Delete"> 
      <i data-feather="trash-2"></i> 
    </a> 
  </td> 
</tr>
 <tr> 
  <td>3</td> 
  <td>Mark Lee Taylor</td> 
  <td>Mark Taylor</td> 
  <td>Manager</td> 
  <td>Mar 5, 2025</td> 
  <td> <a href="#" class="text-gray-500 hover:text-red-500" title="Delete"> 
    <i data-feather="trash-2"></i> 
  </a> 
</td> 
</tr> 
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
            <!-- ✅ New Column --> 
             <th>Category</th> 
             <th>Details</th> 
             <th>Amount</th> 
             <th>Status</th> 
             <th>Date</th> 
             <th>Action</th> 
            </tr> 
          </thead> 
          <tbody> 
            <tr> 
              <td>John Smith</td> <!-- ✅ Example Transactor --> 
              <td>Salary</td> 
              <td> Payment</td> 
              <td class="text-green-500">+ $2,000.00</td> 
              <td><span class="badge bg-theme-bg-1 text-white">Complete</span></td> 
              <td>10-09-2025</td> 
              <td><a href="#" class="text-gray-500 hover:text-red-500" title="Delete"><i data-feather="trash-2"></i>
            </a>
          </td> 
        </tr> 
        <tr> 
          <td>John Smith</td> <!-- ✅ Example Transactor --> 
          <td>Salary</td> 
          <td>Refund</td> 
          <td class="text-green-500">+ $2,000.00</td> 
          <td><span class="badge bg-theme-bg-2 text-white">Failed</span></td> 
          <td>10-09-2025</td> 
          <td><a href="#" class="text-gray-500 hover:text-red-500" title="Delete"><i data-feather="trash-2">

          </i>
        </a>
      </td>
     </tr>
      <tr> 
        <td>John Smith</td> <!-- ✅ Example Transactor --> 
        <td>Salary</td> <td>Withdrawal</td> 
        <td class="text-green-500">+ $2,000.00</td> 
        <td><span class="badge bg-theme-bg-1 text-white">Pending</span></td> 
        <td>10-09-2025</td> <td><a href="#" class="text-gray-500 hover:text-red-500" title="Delete">
          <i data-feather="trash-2"></i></a>
        </td> 
      </tr> 
          <tr> 
            <td>John Smith</td> <!-- ✅ Example Transactor --> 
            <td>Salary</td> 
            <td>Deposit</td>
             <td class="text-green-500">+ $2,000.00</td> 
             <td><span class="badge bg-theme-bg-2 text-white">Cancelled</span></td> 
             <td>10-09-2025</td> <td><a href="#" class="text-gray-500 hover:text-red-500" title="Delete">
              <i data-feather="trash-2"></i>
            </a>
          </td> 
        </tr> 
        <tr> 
          <td>John Smith</td> <!-- ✅ Example Transactor --> 
          <td>Salary</td> 
          <td>Monthly Payment</td> 
          <td class="text-green-500">+ $2,000.00</td> 
          <td><span class="badge bg-theme-bg-1 text-white">Received</span></td> 
          <td>10-09-2025</td> <td><a href="#" class="text-gray-500 hover:text-red-500" title="Delete">
            <i data-feather="trash-2"></i>
          </a>
        </td> 
      </tr> 
    </tbody> 
  </table> 
</div>
</section>
      <!-- Transactions Breakdown (MATCHES TABLE CARD STYLE) -->
      <section class="content mt-8">
        <div class="card table-card">
          <div class="card-header flex justify-between items-center">
            <h5>Transactions Breakdown</h5>
            <a href="#" class="text-primary-500 text-sm flex items-center">
              <i data-feather="download" class="w-4 h-4 mr-1"></i> Export
            </a>
          </div>

          <div class="card-body">
            <!-- Stacked Colored Line -->
            <div class="w-full h-4 rounded-full overflow-hidden flex mb-5 bg-gray-200 dark:bg-gray-700">
              <?php foreach ($breakdown as $b): ?>
                <div 
                  class="h-full" 
                  style="background: <?= $b['color'] ?>; width: <?= $b['percent'] ?>;">
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Breakdown Table Style -->
            <table class="table table-hover w-full">
              <thead>
                <tr>
                  <th>Category</th>
                  <th>Amount</th>
                  <th>Percent</th>
                </tr>
              </thead>
<tbody>
  <?php foreach ($breakdown as $b): ?>
    <tr>
      <td>
        <div class="flex items-center space-x-2">
          <!-- ✅ Colored Dot -->
          <span 
            class="inline-block w-4 h-4 rounded-full" 
            style="background-color: <?= $b['color'] ?>;">
          </span>
          <span><?= $b['label'] ?></span>
        </div>
      </td>
      <td><?= $b['amount'] ?></td>
      <td><?= $b['percent'] ?></td>
    </tr>
  <?php endforeach; ?>
</tbody>

            </table>
          </div>
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
<?php } ?>
