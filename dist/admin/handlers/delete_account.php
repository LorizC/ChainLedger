<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../../services/PasswordService.php';
require_once __DIR__ . '/../../services/SecurityLogService.php';

try {
    // ===============================
    //  Initialize Classes
    // ===============================
    $conn = Database::getConnection();
    $userRepo = new UserRepository($conn);
    $passwordService = new PasswordService($userRepo);
    $logService = new SecurityLogService($conn);

    if (!isset($_SESSION['user'])) {
        header("Location: ../../../index.php");
        exit;
    }

    // ===============================
    //  Securely Get Form Data
    // ===============================
    $securityAnswer   = trim($_POST['security_answer'] ?? '');
    $currentPassword  = trim($_POST['current_password'] ?? '');
    $confirmPassword  = trim($_POST['confirm_password'] ?? '');

    if (empty($securityAnswer) || empty($currentPassword) || empty($confirmPassword)) {
        $_SESSION['flash_error'] = "All fields are required.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    if ($currentPassword !== $confirmPassword) {
        $_SESSION['flash_error'] = "Passwords do not match.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    // ===============================
    //  Start Transaction
    // ===============================
    $conn->begin_transaction();

    $accountId = $_SESSION['user']['account_id'];
    $username  = $_SESSION['user']['username'];
    $userData  = $userRepo->findByAccountId($accountId);

    if (!$userData) {
        throw new Exception("User not found.");
    }

    // ===============================
    //  Verify Password (via password_verify)
    // ===============================
    if (!password_verify($currentPassword, $userData['password'])) {
        $_SESSION['flash_error'] = "Incorrect password.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    // ===============================
    //  Verify Security Answer
    // ===============================
    $securityData = $userRepo->findSecurityByAccountId($accountId);
    if (!$securityData) {
        $_SESSION['flash_error'] = "Security information not found.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    if (!password_verify($securityAnswer, $securityData['security_answer'])) {
        $_SESSION['flash_error'] = "Incorrect security answer.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    // ===============================
    //  Log Security Event
    // ===============================
    $logService->logEvent(
        $userData['user_id'],
        $accountId,
        $username,
        'ACCOUNT_DELETED'
    );

    // ===============================
    //  Archive User Info → archivedaccounts
    // ===============================
    $archiveUser = $conn->prepare("
        INSERT INTO archivedaccounts (
            account_id, first_name, last_name, birthdate, gender,
            username, profile_image, date_registered, archived_at
        )
        SELECT account_id, first_name, last_name, birthdate, gender,
               username, profile_image, date_registered, NOW()
        FROM users
        WHERE account_id = ?
    ");
    $archiveUser->bind_param('i', $accountId);
    $archiveUser->execute();

    if ($archiveUser->affected_rows === 0) {
        throw new Exception("Failed to archive user information.");
    }

// ===============================
//  Archive Security Logs → archivedlogs
// ===============================
$archiveLogs = $conn->prepare("
    INSERT INTO archivedlogs (
        user_id, account_id, username, action, action_details,
        ip_address, device_info, user_agent, timestamp, archived_at
    )
    SELECT 
        user_id, account_id, username, action, action_details,
        ip_address, device_info, user_agent, timestamp, NOW()
    FROM security_logs
    WHERE account_id = ?
");
$archiveLogs->bind_param('i', $accountId);
$archiveLogs->execute();

if ($archiveLogs->affected_rows === 0) {
    error_log("No security logs archived for account ID $accountId");
}
    // ===============================
    //  Archive Transactions → archivedtransactions
    // ===============================
    $archiveTransactions = $conn->prepare("
        INSERT INTO archivedtransactions (
            transaction_id, account_id, old_account_id, old_username,
            username, detail, merchant, amount, currency,
            transaction_date, entry_date, transaction_type, status, archived_at
        )
        SELECT 
            t.transaction_id,
            t.account_id,
            ? AS old_account_id,
            ? AS old_username,
            t.username,
            t.detail,
            t.merchant,
            t.amount,
            t.currency,
            t.transaction_date,
            t.entry_date,
            t.transaction_type,
            t.status,
            NOW()
        FROM transactions t
        WHERE t.account_id = ?
    ");
    $archiveTransactions->bind_param('isi', $accountId, $username, $accountId);
    $archiveTransactions->execute();

if ($archiveTransactions->affected_rows === 0) {
    error_log("No transactions archived for account ID $accountId");
}
    // ===============================
    //  Cascade Delete User Data
    // ===============================
    $tables = [
        'company_personnel',
        'company_owners',
        'security',
        'users'
    ];

    foreach ($tables as $table) {
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE account_id = ?");
        $stmt->bind_param('i', $accountId);
        $stmt->execute();

        if ($table === 'users' && $stmt->affected_rows === 0) {
            throw new Exception("Account deletion failed.");
        }
    }

    // ===============================
    //  Commit Transaction & Destroy Session
    // ===============================
    $conn->commit();

    session_unset();
    session_destroy();
    session_start();

    $_SESSION['flash_success'] = "Your account has been deleted. Data moved to archives.";
    $_SESSION['redirect_after_delete'] = true;

    header('Location: ../../../index.php');
    exit;

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    error_log("Delete Account Error: " . $e->getMessage());
    $_SESSION['flash_error'] = "Error: " . $e->getMessage();
    header('Location: ../deleteaccount.php');
    exit;
}
