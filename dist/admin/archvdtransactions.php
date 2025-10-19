<?php
require_once __DIR__ . '/../services/AuthGuard.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/handlers/ledger.php';

// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner', 'Manager']);
// Initialize UserRepository
$userRepo = new UserRepository($conn);

// FETCH USER INFO
$accountId = $_SESSION['user']['account_id'] ?? null;
$userData = $userRepo->findWithRoleByAccountId($accountId);
if (!$userData) {
    header("Location: /ChainLedger-System-/pages.php?error=user_not_found");
    exit();
}
$role = strtolower(trim($_SESSION['user']['company_role'] ?? ''));

?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
<head>
  <title>ChainLedger | Ledger</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="." />
  <meta name="keywords" content="." />
  <meta name="author" content="Sniper 2025" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
  <link rel="stylesheet" href="../assets/fonts/feather.css" />
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../assets/fonts/material.css" />
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">  
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

<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/header.php'; ?>

<!-- [ Main Content ] start -->
<div class="pc-container">
  <div class="pc-content">
    <div class="page-header">
      <div class="page-block">
        <div class="page-header-title">
          <h5 class="mb-0 font-medium">Transaction Ledger</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="../admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item"><a href="../admin/ledger.php">Ledger</a></li>
          <li class="breadcrumb-item" aria-current="page">Archives</li>
          
        </ul>
      </div>
    </div>

    <!-- [ Ledger Table Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
      <div class="col-span-12">
        <div class="card">
          <div class="card-header">
            <h5>Ledger History</h5>
          </div>
          <div class="card-body overflow-x-auto">

<form method="GET" 
  class="mb-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg shadow 
         flex flex-wrap items-center gap-4">

  <!-- User Filter -->
  <div class="flex items-center gap-2">
    <span class="material-icons-outlined text-gray-500 dark:text-gray-300">person</span>
    <label for="user" class="text-gray-700 dark:text-gray-300 font-medium">User:</label>
    <select 
      name="user" id="user"
      class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 
             bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 
             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      onchange="this.form.submit()">
      <option value="">All</option>
      <?php foreach ($userList as $user): ?>
        <option value="<?= $user['username'] ?>" <?= $filterUser == $user['username'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($user['username']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Action Filter -->
  <div class="flex items-center gap-1">
    <span class="material-icons-outlined text-gray-500 dark:text-gray-300">category</span>
    <label for="action" class="text-gray-700 dark:text-gray-300 font-medium">Category:</label>
    <select 
      name="action" id="action"
      class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 
             bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 
             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      onchange="this.form.submit()">
      <option value="">All</option>
      <?php foreach ($actions as $action): ?>
        <option value="<?= $action ?>" <?= $filterAction === $action ? 'selected' : '' ?>><?= $action ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Merchant Filter -->
  <div class="flex items-center gap-1">
    <span class="material-icons-outlined text-gray-500 dark:text-gray-300">store</span>
    <label for="merchant" class="text-gray-700 dark:text-gray-300 font-medium">Method:</label>
    <select 
      name="merchant" id="merchant"
      class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 
             bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 
             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      onchange="this.form.submit()">
      <option value="">All</option>
      <?php foreach ($merchants as $merchant): ?>
        <option value="<?= $merchant ?>" <?= $filterMerchant === $merchant ? 'selected' : '' ?>><?= $merchant ?></option>
      <?php endforeach; ?>
    </select>
  </div>

<!-- Transaction Type Filter -->
<div class="flex items-center gap-1">
  <span class="material-icons-outlined text-gray-500 dark:text-gray-300">swap_horiz</span>
  <label for="transaction_type" class="text-gray-700 dark:text-gray-300 font-medium">Type:</label>
  <select 
    name="transaction_type" id="transaction_type"
    class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 
           bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 
           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
    onchange="this.form.submit()">
    <option value="">All</option>
    <?php foreach ($transactionTypes as $type): ?>
      <option value="<?= $type ?>" <?= $filterType === $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
    <?php endforeach; ?>
  </select>
</div>

  <!-- Date Sort -->
  <div class="flex items-center gap-1">
    <span class="material-icons-outlined text-gray-500 dark:text-gray-300">calendar_month</span>
    <label for="sort_date" class="text-gray-700 dark:text-gray-300 font-medium">Date:</label>
    <select 
      name="sort_date" id="sort_date"
      class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 
             bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 
             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      onchange="this.form.submit()">
      <option value="">Default</option>
      <option value="desc" <?= $sortDate === 'desc' ? 'selected' : '' ?>>DESC</option>
      <option value="asc" <?= $sortDate === 'asc' ? 'selected' : '' ?>>ASC</option>
    </select>
  </div>

  <?php if ($page > 1): ?>
    <input type="hidden" name="page" value="<?= $page ?>">
  <?php endif; ?>
</form>
            <!-- FILTER FORM END -->

            <!-- Ledger Table -->
            <table class="table table-striped table-bordered w-full">
<thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
  <tr>
    <th>User</th>
    <th>Category</th>
    <th>Payment Method</th>
    <th>Amount</th>
    <th>Type</th> <!-- changed -->
    <th>Date</th>
  </tr>
</thead>
<tbody>
  <?php if (empty($paginatedLedger)): ?>
    <tr>
      <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">No transactions found.</td>
    </tr>
  <?php else: ?>
    <?php foreach ($paginatedLedger as $row): ?>
    <tr>
      <td><?= $row['user'] ?></td>
      <td><?= $row['details'] ?></td>
      <td><?= $row['merchant'] ?></td>
      <td class="<?= strpos($row['amount'], '-') !== false ? 'text-red-600' : 'text-green-600' ?>">
        <?= $row['amount'] ?>
      </td>
      <td>
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                     <?= $row['transaction_type'] === 'DEPOSIT' ? 'bg-green-100 text-green-800' : 
                        ($row['transaction_type'] === 'WITHDRAWAL' ? 'bg-red-100 text-red-800' : 
                         ($row['transaction_type'] === 'TRANSFER' ? 'bg-yellow-100 text-yellow-800' : 
                          ($row['transaction_type'] === 'PAYMENT' ? 'bg-orange-100 text-orange-800' :
                          ($row['transaction_type'] === 'REFUND' ? 'bg-blue-100 text-blue-800' :

                          'bg-gray-100 text-gray-800')))) ?>">
          <?= strtoupper($row['transaction_type']) ?>
        </span>
      </td>
      <td><?= $row['date'] ?></td>
    </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</tbody>

</table>
<!-- Pagination -->
<?php if ($totalPages > 1): ?>
  <div class="mt-4 flex justify-center space-x-2">
    <!-- Previous Page -->
    <?php if ($page > 1): ?>
      <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
         class="px-3 py-1 rounded border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100">
        ‹ Prev
      </a>
    <?php endif; ?>

    <?php
      $range = 1; // how many numbers to show around current page
      $start = max(1, $page - $range);
      $end = min($totalPages, $page + $range);

      // Always show first page
      if ($start > 1) {
        echo '<a href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '" 
                class="px-3 py-1 rounded border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100">1</a>';
        if ($start > 2) {
          echo '<span class="px-2 text-gray-500 dark:text-gray-400">…</span>';
        }
      }

      // Middle pages
      for ($i = $start; $i <= $end; $i++) {
        $active = $i == $page ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100';
        echo '<a href="?' . http_build_query(array_merge($_GET, ['page' => $i])) . '" 
                class="px-3 py-1 rounded border ' . $active . '">' . $i . '</a>';
      }

      // Always show last page
      if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
          echo '<span class="px-2 text-gray-500 dark:text-gray-400">…</span>';
        }
        echo '<a href="?' . http_build_query(array_merge($_GET, ['page' => $totalPages])) . '" 
                class="px-3 py-1 rounded border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100">' . $totalPages . '</a>';
      }
    ?>

    <!-- Next Page -->
    <?php if ($page < $totalPages): ?>
      <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
         class="px-3 py-1 rounded border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100">
        Next ›
      </a>
    <?php endif; ?>
  </div>
<?php endif; ?>


          </div>
        </div>
      </div>
    </div>
    <!-- [ Ledger Table Content ] end -->
  </div>
</div>
<?php include '../includes/footer.php'; ?>
<!-- Required Js -->
<script src="../assets/js/plugins/simplebar.min.js"></script>
<script src="../assets/js/plugins/popper.min.js"></script>
<script src="../assets/js/icon/custom-icon.js"></script>
<script src="../assets/js/plugins/feather.min.js"></script>
<script src="../assets/js/component.js"></script>
<script src="../assets/js/theme.js"></script>
<script src="../assets/js/script.js"></script>


</body>
</html>
