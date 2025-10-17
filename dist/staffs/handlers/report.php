<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../../repositories/UserRepository.php';
require_once __DIR__ . '/../../services/SecurityLogService.php';
require_once __DIR__ . '/../../services/TransactionService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$securityLog = new SecurityLogService($conn);
$transactionService = new TransactionService($conn);

// Ensure user is logged in
$current_username = $_SESSION['user']['username'] ?? null;
if (!$current_username) {
    $_SESSION['flash_error'] = "You must be logged in to record transactions.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch user record
$userRecord = $userRepo->findByUsername($current_username);
if (!$userRecord) {
    $_SESSION['flash_error'] = "User not found in database.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$account_id = (int)$userRecord['account_id'];
$user_id = (int)$userRecord['user_id'];

//  ADD TRANSACTION ONLY
if (isset($_POST['submit_add'])) {
    $category          = trim($_POST['category'] ?? '');
    $merchant          = trim($_POST['merchant'] ?? '');
    $amount            = (float)($_POST['amount'] ?? 0);
    $transaction_type  = trim($_POST['transaction_type'] ?? '');
    $status_input      = trim($_POST['status'] ?? '');
    $date_input        = trim($_POST['date'] ?? '');

    $result = $transactionService->addTransaction(
        $account_id,
        $current_username,
        $category,
        $merchant,
        $amount,
        $transaction_type,
        $status_input,
        $date_input
    );

    if (isset($result['success'])) {
        $_SESSION['flash_success'] = "Transaction Added Successfully:";
        $securityLog->logEvent($user_id, $account_id, $current_username, "TRANSACTION_ADDED");
    } else {
        $_SESSION['flash_error'] = $result['error'];
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>
