<?php
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();

// Pagination
$logsPerPage = 7;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $logsPerPage;

// Filters
$filterAction = $_GET['action'] ?? '';
$filterUser   = $_GET['user'] ?? '';
$sortOrder    = (isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC'])) ? $_GET['sort'] : 'DESC';

// WHERE conditions
$whereParts = ["sl.action != 'FAILED_LOGIN'"];
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

// Total count
$countSQL = "
    SELECT COUNT(*) AS total
    FROM security_logs sl
    LEFT JOIN users u ON sl.user_id = u.user_id
    $whereSQL
";
$stmt = $conn->prepare($countSQL);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalResult = $stmt->get_result();
$totalLogs = (int)($totalResult->fetch_assoc()['total'] ?? 0);
$totalPages = max(1, ceil($totalLogs / $logsPerPage));
$stmt->close();

// Fetch logs
$dataSQL = "
    SELECT 
        sl.action, 
        sl.timestamp, 
        sl.username AS log_username,
        u.first_name, 
        u.last_name, 
        u.profile_image
    FROM security_logs sl
    LEFT JOIN users u ON sl.user_id = u.user_id
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
    $stmt->bind_param('ii', $logsPerPage, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}
$stmt->close();

// User list for dropdown
$userList = $conn->query("
    SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name
    FROM users ORDER BY first_name ASC
");
