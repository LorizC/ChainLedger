<?php require_once '../../php/handlers/logs.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Security Logs</title>
  <link rel="stylesheet" href="css/main.css">
  <!-- hide x-cloak content until Alpine is ready -->
  <style>[x-cloak]{display:none !important;}</style>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>

  <!-- Alpine.js (explicit version) -->
  <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

  <!-- Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <!-- Custom Theme Logic -->
  <script src="../../assets/js/js/scripts.js"></script>
</head>
</head>
<body class="dark:bg-gray-900 dark:text-white">
  <?php include './includes/sidebar.php'; ?>

  <main class="main">
    <?php include './includes/header.php'; ?>

    <div class="title-block mb-2">
      <p>Welcome to ChainLedger Logs</p>
      <h1>Security Logs</h1>
    </div>

    <section class="content mt-0 pt-0">
      <div class="ledger-section mt-0">
        <div class="ledger-wrapper">

          <!-- FILTER FORM -->
          <form method="GET" class="mb-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg shadow flex flex-col sm:flex-row sm:items-center gap-3">
            <!-- Action Filter -->
            <div class="flex items-center gap-2">
              <span class="material-icons-outlined text-gray-500 dark:text-gray-300">filter_alt</span>
              <label for="action" class="text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">Action:</label>
              <select 
                name="action" 
                id="action" 
                class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                onchange="this.form.submit()"
              >
                <option value="">All</option>
                <?php
                $actions = ['LOGIN','LOGOUT','PASSWORD_CHANGE','ACCOUNT_CREATED','ACCOUNT_DELETED'];
                foreach ($actions as $action): ?>
                  <option value="<?= $action ?>" <?= $filterAction === $action ? 'selected' : '' ?>><?= $action ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- User Filter -->
            <div class="flex items-center gap-2">
              <span class="material-icons-outlined text-gray-500 dark:text-gray-300">person</span>
              <label for="user" class="text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">User:</label>
              <select 
                name="user" 
                id="user" 
                class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                onchange="this.form.submit()"
              >
                <option value="">All</option>
                <?php while ($user = $userList->fetch_assoc()): ?>
                  <option value="<?= $user['user_id'] ?>" <?= $filterUser == $user['user_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['full_name']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>

            <?php if ($page > 1): ?>
              <input type="hidden" name="page" value="<?= $page ?>">
            <?php endif; ?>
          </form>

          <!-- TABLE -->
          <table class="ledger-table w-full border-collapse" id="logsTable">
            <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
              <tr>
                <th class="px-4 py-2 text-left">User</th>
                <th class="px-4 py-2 text-left">Action</th>
                <th class="px-4 py-2 text-left cursor-pointer select-none group" id="timestampHeader">
                  <div class="flex items-center gap-1">
                    <span>Timestamp</span>
                    <span class="material-icons-outlined text-gray-500 group-hover:text-white dark:group-hover:text-white transition" id="sortIcon">unfold_more</span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($logs)): ?>
<?php foreach ($logs as $log): ?>
  <?php
    $hasUser = !empty($log['first_name']) || !empty($log['last_name']);
    $profileImage = $hasUser && !empty($log['profile_image'])
        ? htmlspecialchars($log['profile_image'])
        : '../../images/avatars/profile.png';

    $displayName = $hasUser 
        ? htmlspecialchars($log['first_name'] . ' ' . $log['last_name'])
        : htmlspecialchars($log['log_username']);
  ?>
  <tr class="border-b dark:border-gray-700">
    <td class="px-4 py-2">
      <div class="flex items-center gap-2">
        <img src="<?= $profileImage ?>" class="w-6 h-6 rounded-full object-cover" alt="Profile">
        <span><?= $displayName ?></span>
      </div>
    </td>
    <td class="px-4 py-2 font-semibold 
      <?= $log['action'] === 'ACCOUNT_DELETED' ? 'text-red-500' : 'text-green-500' ?>">
      <?= htmlspecialchars($log['action']) ?>
    </td>
    <td class="px-4 py-2"><?= htmlspecialchars($log['timestamp']) ?></td>
  </tr>
<?php endforeach; ?>

              <?php else: ?>
                <tr><td colspan="3" class="px-4 py-2 text-center text-gray-500">No logs found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>

          <!-- PAGINATION -->
          <div class="flex justify-center items-center gap-4 mt-6">
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>&action=<?= urlencode($filterAction) ?>&user=<?= urlencode($filterUser) ?>" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300">&lt; Prev</a>
            <?php else: ?>
              <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded opacity-50">&lt; Prev</span>
            <?php endif; ?>

            <span class="text-gray-700 dark:text-gray-300">
              Page <?= $page ?> of <?= $totalPages ?>
            </span>

            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>&action=<?= urlencode($filterAction) ?>&user=<?= urlencode($filterUser) ?>" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300">Next &gt;</a>
            <?php else: ?>
              <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded opacity-50">Next &gt;</span>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </section>
    <?php include './includes/footer.php'; ?>
  </main>
</body>
</html>
