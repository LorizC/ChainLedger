<?php
require_once __DIR__ . '/../../database/dbconfig.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: /ChainLedger/index.php");
    exit();
}

$conn = Database::getConnection();

// --- GET FILTERS ---
$filterAction   = $_GET['action'] ?? '';
$filterUser     = $_GET['user'] ?? '';
$filterMerchant = $_GET['merchant'] ?? '';
$filterType     = $_GET['transaction_type'] ?? ''; // renamed from status
$sortDate       = $_GET['sort_date'] ?? 'desc';
$page           = max(1, (int)($_GET['page'] ?? 1));
$limit          = 8;

// --- FETCH USER LIST ---
$userList = [];
$res_users = $conn->query("SELECT DISTINCT username FROM transactions WHERE username IS NOT NULL AND username != '' ORDER BY username LIMIT 50");
while($row = $res_users->fetch_assoc()) $userList[] = ['username'=>$row['username']];

// --- FETCH UNIQUE ACTIONS ---
$actions = [];
$res_actions = $conn->query("SELECT DISTINCT detail FROM transactions WHERE detail IS NOT NULL AND detail != '' ORDER BY detail LIMIT 50");
while($row = $res_actions->fetch_assoc()) $actions[] = $row['detail'];

// --- FETCH UNIQUE MERCHANTS ---
$merchants = [];
$res_merchants = $conn->query("SELECT DISTINCT merchant FROM transactions WHERE merchant IS NOT NULL AND merchant != '' ORDER BY merchant LIMIT 50");
while($row = $res_merchants->fetch_assoc()) $merchants[] = $row['merchant'];

// --- FETCH UNIQUE TRANSACTION TYPES ---
$transactionTypes = [];
$res_types = $conn->query("SELECT DISTINCT transaction_type FROM transactions WHERE transaction_type IS NOT NULL AND transaction_type != '' ORDER BY transaction_type LIMIT 50");
while($row = $res_types->fetch_assoc()) $transactionTypes[] = $row['transaction_type'];

// --- BUILD WHERE CLAUSE ---
$where = "WHERE 1=1";
$params = [];
$types = "";

if($filterAction){ $where .= " AND detail = ?"; $params[]=$filterAction; $types.="s"; }
if($filterUser){ $where .= " AND username = ?"; $params[]=$filterUser; $types.="s"; }
if($filterMerchant){ $where .= " AND merchant = ?"; $params[]=$filterMerchant; $types.="s"; }
if($filterType){ $where .= " AND transaction_type = ?"; $params[]=$filterType; $types.="s"; } // replaced status filter

// --- PAGINATION: COUNT TOTAL ---
$stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM transactions $where");
if(!empty($params)) $stmt_total->bind_param($types, ...$params);
$stmt_total->execute();
$totalRecords = $stmt_total->get_result()->fetch_assoc()['total'] ?? 0;
$totalPages = max(1, ceil($totalRecords/$limit));
$page = min($page, $totalPages);
$offset = ($page-1)*$limit;
$stmt_total->close();

$sql = "SELECT 
            transaction_id,
            username AS user, 
            detail AS details, 
            merchant, 
            amount, 
            transaction_type, 
            currency, 
            DATE_FORMAT(entry_date,'%m-%d-%Y') AS date
        FROM transactions 
        $where 
        ORDER BY entry_date ".($sortDate==='asc'?'ASC':'DESC')." 
        LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);

// Bind filters only (LIMIT & OFFSET directly in query)
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$paginatedLedger = [];
while($row = $result->fetch_assoc()){
    $currency = $row['currency'] ?? 'PHP';
    $symbol = $currency==='PHP'?'₱':$currency;
    $amount_val = floatval($row['amount']);
    $isNegative = in_array($row['transaction_type'], ['WITHDRAWAL','TRANSFER','PAYMENT']);
    $formattedAmount = $symbol . number_format(abs($amount_val),2);
    if($isNegative) $formattedAmount = '-'.$formattedAmount;

$paginatedLedger[] = [
    'transaction_id' => $row['transaction_id'],
    'user' => htmlspecialchars($row['user'] ?? 'Unknown'),
    'details' => htmlspecialchars($row['details'] ?? 'N/A'),
    'merchant' => htmlspecialchars($row['merchant'] ?? 'N/A'),
    'amount' => $formattedAmount,
    'transaction_type' => htmlspecialchars($row['transaction_type'] ?? 'N/A'),
    'date' => $row['date']
];

}
?>
