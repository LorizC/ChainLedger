<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../../repositories/UserRepository.php';
require_once __DIR__ . '/../../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$sql = "SELECT transaction_id AS id, username AS user, detail AS details, merchant, amount, transaction_date AS date, transaction_type, status, currency FROM transactions ORDER BY transaction_date DESC";
$result = $conn->query($sql);

$ledger = [];  // Array for the foreach loop in HTML
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Format date
        $row['date'] = !empty($row['date']) && strtotime($row['date']) ? date('M j, Y', strtotime($row['date'])) : 'N/A';
        
        // Format amount with currency (default PHP/₱) and sign based on type
        $currency = $row['currency'] ?? 'PHP';
        $symbol = ($currency === 'PHP') ? '₱' : $currency;
        $amount_val = floatval($row['amount']);
        $is_negative = in_array($row['transaction_type'], ['WITHDRAWAL', 'TRANSFER_OUT', 'PAYMENT']);  // Adjust based on your enum
        $formatted_amount = $symbol . number_format(abs($amount_val), 2);
        if ($is_negative) {
            $formatted_amount = '-' . $formatted_amount;  // Add '-' for withdrawals
        }
        $row['amount'] = $formatted_amount;
        
        // Secure output
        $row['user'] = htmlspecialchars($row['user'] ?? 'Unknown User');
        $row['details'] = htmlspecialchars($row['details'] ?? 'N/A');
        $row['merchant'] = htmlspecialchars($row['merchant'] ?? 'N/A');
        
        $ledger[] = $row;
    }
} else {
    // If no data or query error, show empty or error
    if (!$result) {
        error_log("Query Error: " . $conn->error);  // Log error without breaking page
    }
    $ledger = [];  // Empty array for no rows
}
?>