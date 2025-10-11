<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

try {
    // ===============================
    // 1️⃣ Initialize Classes
    // ===============================
    $conn = Database::getConnection();
    $userRepo = new UserRepository($conn);
    $passwordService = new PasswordService($userRepo);
    $logService = new SecurityLogService($conn);

    if (!isset($_SESSION['user'])) {
        header("Location: /../../index.php");
        exit;
    }

    // ===============================
    // 2️⃣ Securely Get Form Data
    // ===============================
    $securityAnswer   = trim($_POST['security_answer'] ?? '');
    $currentPassword  = trim($_POST['current_password'] ?? '');
    $confirmPassword  = trim($_POST['confirm_password'] ?? '');

    if (empty($securityAnswer) || empty($currentPassword) || empty($confirmPassword)) {
        $_SESSION['flash_error'] = "All fields are required.";
        header('Location: ../../admin/deleteaccount.php');
        exit;
    }

    if ($currentPassword !== $confirmPassword) {
        $_SESSION['flash_error'] = "Passwords do not match.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    // ===============================
    // 3️⃣ Start Transaction
    // ===============================
    $conn->begin_transaction();

    $accountId = $_SESSION['user']['account_id'];
    $username  = $_SESSION['user']['username'];
    $userData  = $userRepo->findByAccountId($accountId);

    if (!$userData) {
        throw new Exception("User not found.");
    }

    // ===============================
    // 4️⃣ Verify Password (via UserRepository)
    // ===============================
    if (!password_verify($currentPassword, $userData['password'])) {
        $_SESSION['flash_error'] = "Incorrect password.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    // ===============================
    // 5️⃣ Verify Security Answer (via PasswordService)
    // ===============================
    $securityData = $userRepo->findSecurityByAccountId($accountId);
    if (!$securityData) {
        $_SESSION['flash_error'] = "Security information not found.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    if (strtolower(trim($securityData['security_answer'])) !== strtolower(trim($securityAnswer))) {
        $_SESSION['flash_error'] = "Incorrect security answer.";
        header('Location: ../deleteaccount.php');
        exit;
    }

    // ===============================
    // 6️⃣ Log Security Event (via SecurityLogService)
    // ===============================
    $logService->logEvent(
        $userData['user_id'],
        $accountId,
        $username,
        'ACCOUNT_DELETED'
    );

    // ===============================
    // 7️⃣ Archive Transactions
    // ===============================
    $archiveStmt = $conn->prepare("
        INSERT INTO archivedaccounts 
        SELECT t.*, NOW() AS archived_at, ? AS old_account_id, ? AS old_username
        FROM transactions t
        WHERE t.account_id = ?
    ");
    $archiveStmt->bind_param('isi', $accountId, $username, $accountId);
    $archiveStmt->execute();

    // ===============================
    // 8️⃣ Cascade Delete User Data
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
    // 9️⃣ Commit Transaction & Destroy Session
    // ===============================
$conn->commit();

session_unset();
session_destroy();

// Start fresh session to store flash message
session_start();
$_SESSION['flash_success'] = "Your account has been deleted. Transactions moved to archive.";

// ✅ Clean redirect (no leading slash)
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
};

