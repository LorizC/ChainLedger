<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../../services/SecurityLogService.php';

$conn = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset($_POST['transaction_id'], $_POST['category'], $_POST['merchant'], $_POST['amount'], $_POST['transaction_type'], $_POST['status'])
        && !empty($_POST['transaction_id'])
    ) {
        $transaction_id   = (int) $_POST['transaction_id'];
        $detail           = trim($_POST['category']);
        $merchant         = trim($_POST['merchant']);
        $amount           = floatval($_POST['amount']);
        $currency         = $_POST['currency'] ?? 'PHP';
        $transaction_type = trim($_POST['transaction_type']);
        $status           = trim($_POST['status']);
        $transaction_date = !empty($_POST['transaction_date']) ? $_POST['transaction_date'] : date('Y-m-d');

        $stmt = $conn->prepare("
            UPDATE transactions
            SET 
                detail = ?, 
                merchant = ?, 
                amount = ?, 
                currency = ?, 
                transaction_type = ?, 
                status = ?, 
                transaction_date = ?
            WHERE transaction_id = ?
        ");

        $stmt->bind_param(
            "ssdssssi",
            $detail,
            $merchant,
            $amount,
            $currency,
            $transaction_type,
            $status,
            $transaction_date,
            $transaction_id
        );

        if ($stmt->execute()) {

// ===============================
// Log the Edit Transaction Event
// ===============================
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userId = $user['user_id'] ?? 0;
    $accountId = $user['account_id'] ?? 0;
    $username = $user['username'] ?? 'Unknown User';

    $logService = new SecurityLogService($conn);
    $action = 'TRANSACTION_EDITED';
    $details = "Edited transaction #{$transaction_id} - {$detail}, ₱{$amount}, {$transaction_type}, {$status}";

    $logService->logEvent($userId, $accountId, $username, $action, $details);
}

            $_SESSION['flash_success'] = "✅ Transaction updated successfully!";
            $stmt->close();
            $conn->close();
            header("Location: ../dashboard.php?updated=success");
            exit();

        } else {
            $_SESSION['flash_error'] = "❌ Failed to update transaction. Please try again.";
            $stmt->close();
            $conn->close();
            header("Location: ../dashboard.php?error=update_failed");
            exit();
        }
    } else {
        $_SESSION['flash_error'] = "⚠️ Missing or incomplete form data.";
        header("Location: ../dashboard.php?error=incomplete_data");
        exit();
    }
} else {
    header("Location: ../dashboard.php?error=invalid_request");
    exit();
}
?>
