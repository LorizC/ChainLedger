<?php
session_start();

// Get filter values from GET
$filterAction   = $_GET['action'] ?? '';
$filterUser     = $_GET['user'] ?? '';
$filterMerchant = $_GET['merchant'] ?? '';
$filterStatus   = $_GET['status'] ?? '';
$sorttimestamp       = $_GET['sort_timestamp'] ?? ''; // 'asc' or 'desc'
$page           = $_GET['page'] ?? 1;

// Mock user list for filter dropdown
$userList = new ArrayIterator([
  ['user_id' => 1, 'full_name' => 'Loriz Carlos'],
  ['user_id' => 2, 'full_name' => 'Mii Lee'],
  ['user_id' => 3, 'full_name' => 'Sara Pyaya'],
  ['user_id' => 4, 'full_name' => 'Dave Smith'],
  ['user_id' => 5, 'full_name' => 'Jon Weak'],
  ['user_id' => 6, 'full_name' => 'Sarah Dicaya'],
]);

// Ledger data
$ledger = [
  ["user" => "Sara Pyaya", "action" => "LOGIN", "merchant" => "Maya", "amount" => "-₱10,000,000.00", "timestamp" => "10-07-2025", "status" => "Pending"],  
  ["user" => "Loriz Carlos", "action" => "ACCOUNT_CREATED", "merchant" => "GrabPay", "amount" => "-₱2,255,555.55", "timestamp" => "10-08-2025", "status" => "Completed"],
  ["user" => "Jon Weak", "action" => "LOGOUT", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "timestamp" => "10-05-2025", "status" => "Failed"], 
  ["user" => "Jon Weak", "action" => "ACCOUNT_DELETED", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "timestamp" => "10-05-2025", "status" => "Completed"],
  ["user" => "Dave Smith", "action" => "PASSWOORD_CHANGE", "merchant" => "Paypal", "amount" => "+₱15,000.00", "timestamp" => "10-06-2025", "status" => "Cancelled"],
  ["user" => "Sara Pyaya", "action" => "LOGIN", "merchant" => "Maya", "amount" => "-₱10,000,000.00", "timestamp" => "10-07-2025", "status" => "Pending"],  
  ["user" => "Loriz Carlos", "action" => "ACCOUNT_CREATED", "merchant" => "GrabPay", "amount" => "-₱2,255,555.55", "timestamp" => "10-08-2025", "status" => "Completed"],
  ["user" => "Jon Weak", "action" => "LOGOUT", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "timestamp" => "10-05-2025", "status" => "Failed"], 
  ["user" => "Jon Weak", "action" => "ACCOUNT_DELETED", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "timestamp" => "10-05-2025", "status" => "Completed"],
  ["user" => "Dave Smith", "action" => "PASSWOORD_CHANGE", "merchant" => "Paypal", "amount" => "+₱15,000.00", "timestamp" => "10-06-2025", "status" => "Cancelled"]

];


// Extract unique actions (action) and merchants dynamically
$actions = array_values(array_unique(array_map(fn($row) => $row['action'], $ledger)));
$merchants = array_values(array_unique(array_map(fn($row) => $row['merchant'], $ledger)));

// Map user IDs to names
$userNamesById = [
    1 => 'Loriz Carlos',
    2 => 'Mii Lee',
    3 => 'Sara Pyaya',
    4 => 'Dave Smith',
    5 => 'Jon Weak',
    6 => 'Sarah Dicaya',
];

// FILTER
$filteredLedger = array_filter($ledger, function($row) use ($filterAction, $filterUser, $filterMerchant, $filterStatus, $userNamesById) {
    $actionMatch = $filterAction === '' || $row['action'] === $filterAction;
    $merchantMatch = $filterMerchant === '' || $row['merchant'] === $filterMerchant;

    $userMatch = true;
    if ($filterUser !== '') {
        $userMatch = isset($userNamesById[$filterUser]) && $row['user'] === $userNamesById[$filterUser];
    }
    $statusMatch = $filterStatus === '' || $row['status'] === $filterStatus;

    return $actionMatch && $merchantMatch && $userMatch && $statusMatch;
});

// SORT by timestamp if selected
if ($sorttimestamp === 'asc' || $sorttimestamp === 'desc') {
    usort($filteredLedger, function($a, $b) use ($sorttimestamp) {
        $atimestamp = strtotime(str_replace('-', '/', $a['timestamp']));
        $btimestamp = strtotime(str_replace('-', '/', $b['timestamp']));
        return $sorttimestamp === 'asc' ? $atimestamp <=> $btimestamp : $btimestamp <=> $atimestamp;
    });
}


  // PAGINATION SETUP
$limit = 8; // number of rows per page
$totalRecords = count($filteredLedger);
$totalPages = max(1, ceil($totalRecords / $limit));
$page = max(1, min($page, $totalPages)); // clamp current page

$offset = ($page - 1) * $limit;
$paginatedLedger = array_slice($filteredLedger, $offset, $limit);

?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
<head>
  <title>ChainLedger | Security Logs</title>
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
          <h5 class="mb-0 font-medium">Security Logs</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="../admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item" aria-current="page">Logs</li>
        </ul>
      </div>
    </div>

    <!-- [ Ledger Table Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
      <div class="col-span-12">
        <div class="card">
          <div class="card-header">
            <h5>Security Logs</h5>
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
        <option value="<?= $user['user_id'] ?>" <?= $filterUser == $user['user_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($user['full_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Action Filter -->
  <div class="flex items-center gap-1">
    <span class="material-icons-outlined text-gray-500 dark:text-gray-300">category</span>
    <label for="action" class="text-gray-700 dark:text-gray-300 font-medium">Action:</label>
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

  <!-- timestamp Sort -->
  <div class="flex items-center gap-1">
    <span class="material-icons-outlined text-gray-500 dark:text-gray-300">calendar_month</span>
    <label for="sort_timestamp" class="text-gray-700 dark:text-gray-300 font-medium">Timestamp:</label>
    <select 
      name="sort_timestamp" id="sort_timestamp"
      class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 
             bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 
             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      onchange="this.form.submit()">
      <option value="">Default</option>
      <option value="desc" <?= $sorttimestamp === 'desc' ? 'selected' : '' ?>>Newest First</option>
      <option value="asc" <?= $sorttimestamp === 'asc' ? 'selected' : '' ?>>Oldest First</option>
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
                  <th>Action</th>
                  <th>Timestamp</th>
                </tr>
              </thead>
<tbody>
  <?php if (empty($paginatedLedger)): ?>
    <tr>
      <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
        No transactions found.
      </td>
    </tr>
  <?php else: ?>
    <?php foreach ($paginatedLedger as $row): ?>
<tr>
  <td><?= htmlspecialchars($row["user"]) ?></td>
  <td><?= htmlspecialchars($row["action"]) ?></td>
  <td><?= htmlspecialchars($row["timestamp"]) ?></td>
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
