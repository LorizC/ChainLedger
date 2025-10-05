<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ChainLedgerDB";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Pagination setup
$logsPerPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $logsPerPage;

// Filters
$filterAction = isset($_GET['action']) ? trim($_GET['action']) : '';
$filterUser   = isset($_GET['user']) ? trim($_GET['user']) : '';
$sortOrder    = isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC']) ? $_GET['sort'] : 'DESC';

// Build dynamic WHERE clause
$whereParts = [];
$params = [];
$types = '';

if ($filterAction !== '') {
    $whereParts[] = "sl.action = ?";
    $params[] = $filterAction;
    $types .= 's';
}
if ($filterUser !== '') {
    $whereParts[] = "u.user_id = ?";
    $params[] = $filterUser;
    $types .= 'i';
}

$whereSQL = count($whereParts) ? "WHERE " . implode(" AND ", $whereParts) : "";

// Count total logs
$countSQL = "
    SELECT COUNT(*) AS total
    FROM security_logs sl
    JOIN users u ON sl.user_id = u.user_id
    $whereSQL
";
$stmt = $conn->prepare($countSQL);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalResult = $stmt->get_result();
$totalLogs = $totalResult->fetch_assoc()['total'] ?? 0;
$totalPages = max(1, ceil($totalLogs / $logsPerPage));
$stmt->close();

// Fetch logs
$dataSQL = "
  SELECT sl.security_id, sl.account_id, sl.username, sl.action, sl.ip_address, 
         sl.device_info, sl.timestamp, sl.user_agent, 
         u.first_name, u.last_name, u.user_id, u.profile_image
  FROM security_logs sl
  JOIN users u ON sl.user_id = u.user_id
  $whereSQL
  ORDER BY sl.timestamp $sortOrder
  LIMIT ? OFFSET ?
";
$stmt = $conn->prepare($dataSQL);
if (!empty($params)) {
    $types .= 'ii';
    $params[] = $logsPerPage;
    $params[] = $offset;
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param("ii", $logsPerPage, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

// Store logs
$logs = [];
while ($row = $result->fetch_assoc()) $logs[] = $row;
$stmt->close();

// Fetch user list for dropdown
$userList = $conn->query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name FROM users ORDER BY first_name ASC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Security Logs</title>
  <link rel="stylesheet" href="css/main.css">

  <!-- Alpine.js -->
  <script src="https://unpkg.com/alpinejs" defer></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>

  <!-- Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <script src="../../js/user.js"></script>  
</head>
<body class="dark:bg-gray-900 dark:text-white">
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main">
    <?php include './includes/header.php'; ?>

    <div class="title-block">
      <p>Welcome to ChainLedger Logs</p>
      <h1>Security Logs</h1>
    </div>

    <section class="content">
      <div class="ledger-section">
        <div class="ledger-wrapper">

<!-- Filter Dropdown -->
<form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
  <!-- Action Filter -->
  <label for="action" class="text-gray-700 dark:text-gray-300 font-medium">Filter by Action:</label>
  <select 
    name="action" 
    id="action" 
    class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 dark:bg-gray-800 dark:text-gray-100"
    onchange="this.form.submit()">
    <option value="">All Actions</option>
    <?php
    $actions = [
      'LOGIN',
      'LOGOUT',
      'FAILED_LOGIN',
      'PASSWORD_CHANGE',
      'ACCOUNT_CREATED',
      'ACCOUNT_DELETED'
    ];
    foreach ($actions as $action): ?>
      <option value="<?= $action ?>" <?= $filterAction === $action ? 'selected' : '' ?>>
        <?= $action ?>
      </option>
    <?php endforeach; ?>
  </select>

  <!-- User Filter -->
  <label for="user" class="text-gray-700 dark:text-gray-300 font-medium ml-2">Filter by User:</label>
  <select 
    name="user" 
    id="user" 
    class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 dark:bg-gray-800 dark:text-gray-100"
    onchange="this.form.submit()">
    <option value="">All Users</option>
    <?php while ($user = $userList->fetch_assoc()): ?>
      <option value="<?= $user['user_id'] ?>" <?= $filterUser == $user['user_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($user['full_name']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  <!-- Sort Order -->
  <label for="sort" class="text-gray-700 dark:text-gray-300 font-medium ml-2">Sort by Timestamp:</label>
  <select 
    name="sort" 
    id="sort" 
    class="border border-gray-300 dark:border-gray-700 rounded px-2 py-1 dark:bg-gray-800 dark:text-gray-100"
    onchange="this.form.submit()">
    <option value="DESC" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Newest First</option>
    <option value="ASC" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>Oldest First</option>
  </select>

  <!-- Keep current page when filtering -->
  <?php if ($page > 1): ?>
    <input type="hidden" name="page" value="<?= $page ?>">
  <?php endif; ?>
</form>


          <!-- Logs Table -->
          <table class="ledger-table w-full border-collapse">
            <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
              <tr>
                <th class="px-4 py-2 text-left">User</th>
                <th class="px-4 py-2 text-left">Action</th>
                <th class="px-4 py-2 text-left">IP Address</th>
                <th class="px-4 py-2 text-left">Device</th>
                <th class="px-4 py-2 text-left">User Agent</th>
                <th class="px-4 py-2 text-left">Timestamp</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($logs)): ?>
                <?php foreach ($logs as $log): ?>
                  <?php
                    $profileImage = !empty($log['profile_image']) 
                        ? htmlspecialchars($log['profile_image']) 
                        : '../../images/avatars/profile.png';
                  ?>
                  <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-2">
                      <div class="flex items-center gap-2">
                        <img src="<?= $profileImage ?>" class="w-6 h-6 rounded-full object-cover" alt="Profile">
                        <span><?= htmlspecialchars($log['first_name'] . " " . $log['last_name']) ?></span>
                      </div>
                    </td>
                    <td class="px-4 py-2 font-semibold 
                      <?= $log['action'] === 'FAILED_LOGIN' || $log['action'] === 'ACCOUNT_DELETED' ? 'text-red-500' : 'text-green-500' ?>">
                      <?= htmlspecialchars($log['action']) ?>
                    </td>
                    <td class="px-4 py-2"><?= htmlspecialchars($log['ip_address']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($log['device_info']) ?></td>
                    <td class="px-4 py-2 truncate max-w-xs"><?= htmlspecialchars($log['user_agent']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($log['timestamp']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="px-4 py-2 text-center text-gray-500">No logs found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="flex justify-center items-center gap-4 mt-6">
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>&action=<?= urlencode($filterAction) ?>" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300">&lt; Prev</a>
            <?php else: ?>
              <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded opacity-50">&lt; Prev</span>
            <?php endif; ?>

            <span class="text-gray-700 dark:text-gray-300">
              Page <?= $page ?> of <?= $totalPages ?>
            </span>

            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>&action=<?= urlencode($filterAction) ?>" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300">Next &gt;</a>
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
