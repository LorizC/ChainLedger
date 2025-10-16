<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate required fields
    if (
        isset($_POST['transaction_id'], $_POST['category'], $_POST['merchant'], $_POST['amount'], $_POST['transaction_type'], $_POST['status'])
        && !empty($_POST['transaction_id'])
    ) {
        // Retrieve and sanitize inputs
        $transaction_id   = (int) $_POST['transaction_id'];
        $detail           = trim($_POST['category']); // matches `detail` column
        $merchant         = trim($_POST['merchant']);
        $amount           = floatval($_POST['amount']);
        $currency         = $_POST['currency'] ?? 'PHP';
        $transaction_type = trim($_POST['transaction_type']);
        $status           = trim($_POST['status']);
        $transaction_date = !empty($_POST['transaction_date']) ? $_POST['transaction_date'] : date('Y-m-d');

        // Update query
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
