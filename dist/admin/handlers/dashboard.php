<?php
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();

if (!isset($_SESSION['user'])) {
    header("Location: /ChainLedger-System-/index.php");
    exit();
}

// --- Summary Cards (Real Data from DB) ---
$totalTransactions = 0;
$resTotal = $conn->query("SELECT COUNT(*) as count FROM transactions");
if ($resTotal && $row = $resTotal->fetch_assoc()) {
    $totalTransactions = $row['count'] ?? 0;
}

$resGain = $conn->query("
    SELECT SUM(amount) as total_gain 
    FROM transactions 
    WHERE (amount > 0 AND transaction_type NOT IN ('WITHDRAWAL', 'TRANSFER', 'PAYMENT')) 
          OR transaction_type IN ('DEPOSIT', 'REFUND')
");

if ($resGain && $row = $resGain->fetch_assoc()) {
    $totalGains = $row['total_gain'] ?? 0;
}

$totalCosts = 0;
$resCost = $conn->query("SELECT SUM(amount) as total_cost FROM transactions WHERE amount < 0 OR transaction_type IN ('WITHDRAWAL', 'TRANSFER', 'PAYMENT')");
if ($resCost && $row = $resCost->fetch_assoc()) {
    $totalCosts = abs($row['total_cost'] ?? 0);
}

$netBalance = $totalGains - $totalCosts;

// --- My Transactions ---
$transactors = [];
$sql = "SELECT * FROM transactions WHERE account_id = ? ORDER BY transaction_date DESC LIMIT 10";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $accountID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['formatted_date'] = date('m-d-Y', strtotime($row['transaction_date']));
        $transactors[] = $row;
    }
    $stmt->close();
}

// --- Transactors ---
$transactors = [];

$sqlTransactors = "
    SELECT 
        u.user_id,
        CONCAT(u.first_name, ' ', u.last_name) AS full_name,
        u.username,
        u.date_registered,
        cp.company_role
    FROM users u
    LEFT JOIN company_personnel cp ON u.account_id = cp.account_id
    ORDER BY u.date_registered DESC
    LIMIT 10
";

$resultTransactors = $conn->query($sqlTransactors);

if ($resultTransactors) {
    while ($row = $resultTransactors->fetch_assoc()) {
        $row['formatted_date'] = !empty($row['date_registered'])
            ? date('m-d-Y', strtotime($row['date_registered']))
            : 'N/A';
        $row['full_name'] = htmlspecialchars($row['full_name'] ?? 'Unknown');
        $row['username'] = htmlspecialchars($row['username'] ?? 'N/A');
        $row['role'] = htmlspecialchars($row['company_role'] ?? 'N/A');
        $transactors[] = $row;
    }
}



// --- Recent Transactions ---
$recentTransactions = [];
$sqlRecent = "SELECT t.transaction_id, t.username, t.detail, t.merchant, t.amount, t.currency, t.transaction_date, COALESCE(t.transaction_type, 'Unknown Type') AS transaction_type, t.status, t.entry_date,
              COALESCE(CONCAT(u.first_name, ' ', u.last_name), t.username) AS fullname
              FROM transactions t
              LEFT JOIN users u ON t.username = u.username
              ORDER BY t.entry_date DESC LIMIT 5";
$resultRecent = $conn->query($sqlRecent);
if ($resultRecent) {
    while ($row = $resultRecent->fetch_assoc()) {
        $currency = $row['currency'] ?? 'PHP';
        $symbol = ($currency === 'PHP') ? '₱' : $currency;
        $amount_val = floatval($row['amount']);
        $is_negative = ($amount_val < 0) || in_array($row['transaction_type'], ['WITHDRAWAL', 'TRANSFER_OUT', 'PAYMENT']);
        $formatted_amount = $symbol . number_format(abs($amount_val), 2);
        $row['formatted_amount'] = $formatted_amount;
        $row['is_negative'] = $is_negative;

        $use_date = !empty($row['transaction_date']) ? $row['transaction_date'] : $row['entry_date'];
        $row['formatted_date'] = !empty($use_date) ? date('m-d-Y', strtotime($use_date)) : 'N/A';

        $row['fullname'] = htmlspecialchars($row['fullname'] ?? $row['username'] ?? 'Unknown');
        $row['detail'] = htmlspecialchars($row['detail'] ?? $row['merchant'] ?? 'N/A');
        $row['merchant'] = htmlspecialchars($row['merchant'] ?? 'N/A');
        $row['category'] = htmlspecialchars($row['transaction_type'] ?? 'N/A');
        $row['status'] = htmlspecialchars($row['status'] ?? 'N/A');

        $recentTransactions[] = $row;
    }
}

?>
