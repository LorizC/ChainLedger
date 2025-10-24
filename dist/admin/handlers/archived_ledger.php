<?php
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();

// Filters
$filterUser = $_GET['user'] ?? '';
$filterAction = $_GET['action'] ?? '';
$filterMerchant = $_GET['merchant'] ?? '';
$filterType = $_GET['transaction_type'] ?? '';
$sortDate = $_GET['sort_date'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$conditions = [];
$params = [];
$types = '';

if ($filterUser) {
  $conditions[] = "username = ?";
  $params[] = $filterUser;
  $types .= 's';
}
if ($filterAction) {
  $conditions[] = "detail = ?";
  $params[] = $filterAction;
  $types .= 's';
}
if ($filterMerchant) {
  $conditions[] = "merchant = ?";
  $params[] = $filterMerchant;
  $types .= 's';
}
if ($filterType) {
  $conditions[] = "transaction_type = ?";
  $params[] = $filterType;
  $types .= 's';
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
$order = ($sortDate === 'asc') ? 'ASC' : 'DESC';

// Count total rows for pagination
$countQuery = "SELECT COUNT(*) AS total FROM archivedtransactions $where";
$countStmt = $conn->prepare($countQuery);
if ($types) {
  $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'] ?? 0;
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// Fetch paginated data
$query = "SELECT * FROM archivedtransactions $where ORDER BY transaction_date $order, transaction_id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

if ($types) {
  $bindTypes = $types . 'ii';
  $bindParams = array_merge($params, [$limit, $offset]);
  $stmt->bind_param($bindTypes, ...$bindParams);
} else {
  $stmt->bind_param('ii', $limit, $offset);
}


$stmt->execute();
$result = $stmt->get_result();
$archivedLedger = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch dropdown data
$userList = $conn->query("SELECT DISTINCT username FROM archivedtransactions ORDER BY username ASC")->fetch_all(MYSQLI_ASSOC);

// ENUM extraction helper
function getEnumValues($conn, $table, $column) {
    $query = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    $row = $query->fetch_assoc();
    preg_match_all("/'([^']+)'/", $row['Type'], $matches);
    return $matches[1];
}

$actions = getEnumValues($conn, 'archivedtransactions', 'detail');
$merchants = getEnumValues($conn, 'archivedtransactions', 'merchant');
$transactionTypes = getEnumValues($conn, 'archivedtransactions', 'transaction_type');
