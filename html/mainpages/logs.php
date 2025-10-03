<!-- use include '../php/handlers/ledger.php'; ?> to connect php to html -->
<?php
// Database connection (adjust credentials as needed)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ChainLedgerDB";
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Fetch security logs with username from users table
$sql = "
  SELECT sl.security_id, sl.account_id, sl.username, sl.action, sl.ip_address, 
         sl.device_info, sl.timestamp, sl.user_agent, u.first_name, u.last_name, u.profile_image
  FROM security_logs sl
  JOIN users u ON sl.user_id = u.user_id
  ORDER BY sl.timestamp DESC
";
$result = $conn->query($sql);

$profileImage = !empty($log['profile_image']) 
    ? htmlspecialchars($log['profile_image']) 
    : '../../images/avatars/profile.png'; // fallback

// Put into array
$logs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}
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

    <div class="title-block">
      <p>Welcome to ChainLedger Logs</p>
      <h1>Security Logs</h1>
    </div>

    <section class="content">
      <div class="ledger-section">
        <div class="ledger-wrapper">
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
                  <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-2">
                      <div class="flex items-center gap-2">
<img src="<?= $profileImage ?>" class="w-6 h-6 rounded-full object-cover" alt="Profile">
<span><?= htmlspecialchars($log['first_name'] . " " . $log['last_name']) ?></span>
                      </div>
                    </td>
                    <td class="px-4 py-2 font-semibold 
                      <?= $log['action'] === 'FAILED_LOGIN' || $log['action'] === 'ACCOUNT_LOCKED' ? 'text-red-500' : 'text-green-500' ?>">
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
<div class="flex justify-center items-center gap-4 mt-4">
  <button id="prev-page" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded disabled:opacity-50">&lt;</button>
  <span id="page-info" class="text-gray-700 dark:text-gray-300"></span>
  <button id="next-page" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded disabled:opacity-50">&gt;</button>
</div>
        </div>
      </div>
    </section>
    <?php include './includes/footer.php'; ?>
  </main>
</body>
</html>
