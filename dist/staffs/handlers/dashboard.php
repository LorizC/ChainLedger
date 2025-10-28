<?php
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();
$accountID = $_SESSION['user']['account_id'] ?? 0;

// --- Summary Cards (Real Data from DB) ---
$totalTransactions = 0;
$resTotal = $conn->query("SELECT COUNT(*) as count FROM transactions WHERE account_id = $accountID");
if ($resTotal && $row = $resTotal->fetch_assoc()) {
    $totalTransactions = $row['count'] ?? 0;
}

$resGain = $conn->query("
    SELECT SUM(amount) as total_gain 
    FROM transactions 
    WHERE account_id = $accountID
          AND ((amount > 0 AND transaction_type NOT IN ('WITHDRAWAL', 'TRANSFER', 'PAYMENT')) 
          OR transaction_type IN ('DEPOSIT', 'REFUND'))
");
$totalGains = ($resGain && $row = $resGain->fetch_assoc()) ? ($row['total_gain'] ?? 0) : 0;

$resCost = $conn->query("
    SELECT SUM(amount) as total_cost 
    FROM transactions 
    WHERE account_id = $accountID 
          AND (amount < 0 OR transaction_type IN ('WITHDRAWAL', 'TRANSFER', 'PAYMENT'))
");
$totalCosts = ($resCost && $row = $resCost->fetch_assoc()) ? abs($row['total_cost'] ?? 0) : 0;

$netBalance = $totalGains - $totalCosts;

// --- My Transactions ---
$transactions = [];
$sql = "
    SELECT * FROM transactions 
    WHERE account_id = ? 
    ORDER BY transaction_date DESC 
    LIMIT 10
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $accountID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['formatted_date'] = !empty($row['entry_date'])
            ? date('m-d-Y', strtotime($row['entry_date']))
            : 'N/A';
        $transactions[] = $row;
    }
    $stmt->close();
}

// --- Recent Transactions (all users) ---
$recentTransactions = [];
$sqlRecent = "SELECT t.transaction_id, t.username, t.detail, t.merchant, t.amount, t.currency, t.entry_date, 
                     COALESCE(t.transaction_type, 'Unknown Type') AS transaction_type, t.status, t.entry_date,
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
        $is_negative = ($amount_val < 0) || in_array($row['transaction_type'], ['WITHDRAWAL', 'TRANSFER', 'PAYMENT']);
                $row['formatted_amount'] = $symbol . number_format(abs($amount_val), 2);
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