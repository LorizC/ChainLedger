<?php
// dist/admin/handlers/logs.php
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();
$accountId = $_SESSION['user']['account_id'] ?? null;

// ===============================
// DETERMINE USER ROLE
// ===============================
$role = null;
if ($accountId) {
    $stmt = $conn->prepare("SELECT company_role FROM company_personnel WHERE account_id = ? LIMIT 1");
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $role = $row['company_role'];
    }
    $stmt->close();
}

// ===============================
// PAGINATION & FILTERS (for logs)
// ===============================
$logsPerPage = 8;
// Active logs pagination
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $logsPerPage;

// Archived logs pagination
$archivedPage = isset($_GET['archived_page']) && is_numeric($_GET['archived_page']) ? (int)$_GET['archived_page'] : 1;
$archivedOffset = ($archivedPage - 1) * $logsPerPage;


$filterAction = $_GET['action'] ?? '';
$filterUser   = $_GET['user'] ?? '';
$filterSort   = $_GET['sort_timestamp'] ?? '';
$sortOrder = (strtoupper($filterSort) === 'ASC') ? 'ASC' : 'DESC';

// ===============================
// WHERE CLAUSE BUILDER
// ===============================
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

// Helper: dynamic bind_param
function bind_params_dynamic(mysqli_stmt $stmt, string $types, array &$params) {
    if (empty($params)) return;
    $bind_names = [];
    $bind_names[] = $types;
    foreach ($params as $key => $value) {
        $bind_names[] = &$params[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

//
// ====================================================
//  ACTIVE LOGS (security_logs)
// ====================================================
//
$countSQL = "
    SELECT COUNT(*) AS total
    FROM security_logs sl
    LEFT JOIN users u ON sl.account_id = u.account_id
    $whereSQL
";
$stmt = $conn->prepare($countSQL);
if (!empty($params)) bind_params_dynamic($stmt, $types, $params);
$stmt->execute();
$totalLogs = (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
$totalPages = max(1, ceil($totalLogs / $logsPerPage));
$stmt->close();

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

//
// ====================================================
//  ARCHIVED LOGS (archivedlogs)
// ====================================================
$archivedWhereParts = [];
$archivedParams = [];
$archivedTypes = '';

// Filter by action
if ($filterAction !== '') {
    $archivedWhereParts[] = "al.action = ?";
    $archivedParams[] = $filterAction;
    $archivedTypes .= 's';
}

// Filter by user
if ($filterUser !== '') {
    $archivedWhereParts[] = "al.account_id = ?";
    $archivedParams[] = $filterUser;
    $archivedTypes .= 'i';
}

$archivedWhereSQL = count($archivedWhereParts) ? "WHERE " . implode(" AND ", $archivedWhereParts) : "";

// Count total archived logs
$countArchivedSQL = "
    SELECT COUNT(*) AS total
    FROM archivedlogs al
    $archivedWhereSQL
";
$stmt = $conn->prepare($countArchivedSQL);
if (!empty($archivedParams)) bind_params_dynamic($stmt, $archivedTypes, $archivedParams);
$stmt->execute();
$totalArchived = (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
$totalArchivedPages = max(1, ceil($totalArchived / $logsPerPage));
$stmt->close();

// Fetch archived logs with username from archivedaccounts
$archivedSQL = "
    SELECT 
        al.account_id AS user_account_id,
        ac.username,
        al.action,
        al.archived_at AS timestamp
    FROM archivedlogs al
    LEFT JOIN archivedaccounts ac ON al.account_id = ac.account_id
    $archivedWhereSQL
    ORDER BY al.archived_at $sortOrder
    LIMIT ? OFFSET ?
";
$stmt = $conn->prepare($archivedSQL);

if (!empty($archivedParams)) {
    $archivedParamsData = $archivedParams;
    $archivedParamsData[] = $logsPerPage;
    $archivedParamsData[] = $archivedOffset;

    $archivedTypesData = $archivedTypes . 'ii';
    bind_params_dynamic($stmt, $archivedTypesData, $archivedParamsData);
} else {
    $stmt->bind_param('ii', $logsPerPage, $archivedOffset);
}

$stmt->execute();
$archivedResult = $stmt->get_result();
$archivedLogs = [];
while ($row = $archivedResult->fetch_assoc()) {
    $archivedLogs[] = $row;
}
$stmt->close();

//
// ====================================================
//  FILTER OPTIONS
// ====================================================
//
$actions = [];
$actionsResult = $conn->query("SELECT DISTINCT action FROM security_logs ORDER BY action ASC");
while ($a = $actionsResult->fetch_assoc()) {
    $actions[] = $a['action'];
}

$userList = [];
$userSQL = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name FROM users ORDER BY first_name, last_name";
$usersResult = $conn->query($userSQL);
while ($u = $usersResult->fetch_assoc()) {
    $userList[] = $u;
}

return [
    'logs'              => $logs,
    'archivedLogs'      => $archivedLogs,
    'actions'           => $actions,
    'userList'          => $userList,
    'totalPages'        => $totalPages,
    'totalArchivedPages'=> $totalArchivedPages,
    'page'              => $page,
    'archivedPage'      => $archivedPage,
    'filterAction'      => $filterAction,
    'filterUser'        => $filterUser,
    'filterSort'        => $filterSort,
    'role'              => $role,
];

?>
