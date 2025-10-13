<?php
// dist/admin/handlers/logs.php
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();
$accountId = $_SESSION['user']['account_id'];

$role = null;
if ($accountId) {
    $stmt = $conn->prepare("
        SELECT company_role 
        FROM company_personnel 
        WHERE account_id = ?
        LIMIT 1
    ");
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $role = $row['company_role'];
    }
    $stmt->close();
}

// Pagination setup
$logsPerPage = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $logsPerPage;

// Filters
$filterAction = $_GET['action'] ?? '';
$filterUser   = $_GET['user'] ?? '';
$filterSort   = $_GET['sort_timestamp'] ?? ''; // asc or desc

$sortOrder = (strtoupper($filterSort) === 'ASC') ? 'ASC' : 'DESC';

// Build WHERE clause dynamically
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

// Helper for dynamic bind
function bind_params_dynamic(mysqli_stmt $stmt, string $types, array &$params) {
    if (empty($params)) return;
    $bind_names = [];
    $bind_names[] = $types;
    foreach ($params as $key => $value) {
        $bind_names[] = &$params[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

// =======================
// TOTAL COUNT (Pagination)
// =======================
$countSQL = "
    SELECT COUNT(*) AS total
    FROM security_logs sl
    LEFT JOIN users u ON sl.account_id = u.account_id
    $whereSQL
";

$stmt = $conn->prepare($countSQL);
if (!$stmt) {
    die("Prepare failed (count): " . $conn->error);
}
if (!empty($params)) {
    bind_params_dynamic($stmt, $types, $params);
}
$stmt->execute();
$totalResult = $stmt->get_result();
$totalLogs = (int)($totalResult->fetch_assoc()['total'] ?? 0);
$totalPages = max(1, ceil($totalLogs / $logsPerPage));
$stmt->close();

// ====================
// FETCH PAGINATED LOGS
// ====================
$dataSQL = "
    SELECT 
        sl.action,
        sl.timestamp,
        sl.account_id AS user_account_id,
        u.user_id,
        u.username,
        u.first_name,
        u.last_name
    FROM security_logs sl
    LEFT JOIN users u ON sl.account_id = u.account_id
    $whereSQL
    ORDER BY sl.timestamp $sortOrder
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($dataSQL);
if (!$stmt) {
    die("Prepare failed (data): " . $conn->error);
}

if (!empty($params)) {
    $paramsData = $params;
    $paramsData[] = $logsPerPage;
    $paramsData[] = $offset;
    $typesData = $types . 'ii';
    bind_params_dynamic($stmt, $typesData, $paramsData);
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

// ====================
// FETCH ACTIONS (Filter)
// ====================
$actions = [];
$actionsResult = $conn->query("SELECT DISTINCT action FROM security_logs ORDER BY action ASC");
while ($a = $actionsResult->fetch_assoc()) {
    $actions[] = $a['action'];
}

// ====================
// FETCH USERS (Filter)
// ====================
$userList = [];
$userSQL = "
    SELECT 
        user_id,
        CONCAT(first_name, ' ', last_name) AS full_name
    FROM users
    ORDER BY first_name, last_name
";
$usersResult = $conn->query($userSQL);
while ($u = $usersResult->fetch_assoc()) {
    $userList[] = $u;
}

// ====================
// Return to main file
// ====================
return [
    'logs'         => $logs,
    'actions'      => $actions,
    'userList'     => $userList,
    'totalPages'   => $totalPages,
    'page'         => $page,
    'filterAction' => $filterAction,
    'filterUser'   => $filterUser,
    'filterSort'   => $filterSort,
    'role'         => $role,
];
