<?php
require_once __DIR__ . '/../services/AuthGuard.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/handlers/logs.php';


// Only allow logged-in users who are Business Owner or Manager
auth_guard(['Business Owner']);

// Initialize UserRepository
$userRepo = new UserRepository($conn);

// FETCH USER INFO
$accountId = $_SESSION['user']['account_id'] ?? null;
$userData = $userRepo->findWithRoleByAccountId($accountId);
if (!$userData) {
    header("Location: /ChainLedger-System-/pages.php?error=user_not_found");
    exit();
}



// Grab the current user info safely
$user = $_SESSION['user'];
$role = $user['company_role'] ?? 'Unassigned';
$accountId = $user['account_id'] ?? null;


// Preserve filters for pagination
$baseQuery = $_GET;
unset($baseQuery['page']);
$baseURL = '/ChainLedger-System-/dist/admin/security_logs.php?' . http_build_query($baseQuery);

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
          <li class="breadcrumb-item"><a href="../admin/archives.php" class="hover:text-blue-800 transition">Archives</a></li>
        </ul>
      </div>
    </div>

    <!-- [ Logs Table Content ] start -->
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
           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
           max-w-[220px]"
    onchange="this.form.submit()">
    <option value="">All</option>
    <?php foreach ($userList as $user): 
      $fullName = htmlspecialchars($user['full_name']);
      $shortName = mb_strlen($fullName) > 20 
        ? mb_substr($fullName, 0, 20) . '…' 
        : $fullName;
    ?>
      <option 
        value="<?= $user['user_id'] ?>" 
        <?= $filterUser == $user['user_id'] ? 'selected' : '' ?>
        title="<?= $fullName ?>">
        <?= $shortName ?>
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
<option value="desc" <?= $filterSort === 'desc' ? 'selected' : '' ?>>Newest First</option>
<option value="asc" <?= $filterSort === 'asc' ? 'selected' : '' ?>>Oldest First</option>

    </select>
  </div>

  <?php if ($page > 1): ?>
    <input type="hidden" name="page" value="<?= $page ?>">
  <?php endif; ?>
  <input type="hidden" name="type" value="<?= basename($_SERVER['PHP_SELF'], '.php') ?>">

</form>
            <!-- FILTER FORM END -->

            <!-- Logs Table -->
            <table class="table table-striped table-bordered w-full">
              <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                  <th>Account ID</th>
                  <th>User</th>
                  <th>Action</th>
                  <th>Timestamp</th>
                </tr>
              </thead>
<tbody>
  <?php if (empty($logs)): ?>
    <tr>
      <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
        No Logs found.
      </td>
    </tr>
  <?php else: ?>
    <?php foreach ($logs as $row): ?>
<tr>
        <td class="px-4 py-2 text-sm">
        <?= htmlspecialchars($row['user_account_id']) ?>
      </td>
  <!-- Name + Username -->
  <td class="px-4 py-2 text-sm">
    <span class="block max-w-[180px] truncate" 
          title="<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>">
      <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>
    </span>
    <br>
    <small class="block text-gray-500 max-w-[180px] truncate" 
           title="<?= htmlspecialchars($row['username']) ?>">
      <?= htmlspecialchars($row['username']) ?>
    </small>
  </td>
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
    <!-- [ Logs Table Content ] end -->
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
