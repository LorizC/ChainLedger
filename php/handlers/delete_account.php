<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../php/db/dbconfig.php';
require_once '../../php/repositories/UserRepository.php';
require_once '../../php/services/PasswordService.php';
require_once '../../php/services/SecurityLogService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../html/mainpages/delete_account.php');
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['flash_error'] = "You must be logged in to delete your account.";
    header('Location: ../../html/usercreation/login.php');
    exit;
}

// Get form data securely
$securityAnswer   = trim($_POST['security_answer'] ?? '');
$currentPassword  = trim($_POST['current_password'] ?? '');
$confirmPassword  = trim($_POST['confirm_password'] ?? '');

if (empty($securityAnswer) || empty($currentPassword) || empty($confirmPassword)) {
    $_SESSION['flash_error'] = "All fields are required.";
    header('Location: ../../html/mainpages/delete_account.php');
    exit;
}

if ($currentPassword !== $confirmPassword) {
    $_SESSION['flash_error'] = "Passwords do not match.";
    header('Location: ../../html/mainpages/delete_account.php');
    exit;
}

try {
    $conn = Database::getConnection();
    $conn->begin_transaction();

    $userRepo = new UserRepository($conn);
    $passwordService = new PasswordService($userRepo);
    $logService = new SecurityLogService($conn);

    $accountId = $_SESSION['user']['account_id'];
    $username  = $_SESSION['user']['username'];
    $userData  = $userRepo->findByAccountId($accountId);

    if (!$userData) {
        throw new Exception("User not found.");
    }

    // Verify password
    if (!password_verify($currentPassword, $userData['password'])) {
        $_SESSION['flash_error'] = "Incorrect password.";
        header('Location: ../../html/mainpages/delete_account.php');
        exit;
    }

    // Verify security answer
    $securityData = $userRepo->findSecurityByAccountId($accountId);
    if (!$securityData) {
        $_SESSION['flash_error'] = "Security information not found.";
        header('Location: ../../html/mainpages/delete_account.php');
        exit;
    }

    if (strtolower(trim($securityData['security_answer'])) !== strtolower(trim($securityAnswer))) {
        $_SESSION['flash_error'] = "Incorrect security answer.";
        header('Location: ../../html/mainpages/delete_account.php');
        exit;
    }

    // 🧾 Log event before deletion
    $logService->logEvent(
        $userData['user_id'],
        $accountId,
        $username,
        'ACCOUNT_DELETED'
    );

    // ✅ Move user's transactions to archivedaccounts (with username)
    $archiveStmt = $conn->prepare("
        INSERT INTO archivedaccounts 
        SELECT t.*, NOW() AS archived_at, ? AS old_account_id, ? AS old_username
        FROM transactions t
        WHERE t.account_id = ?
    ");
    $archiveStmt->bind_param('isi', $accountId, $username, $accountId);
    $archiveStmt->execute();

    // ✅ Delete from company tables if exists
    $deletePersonnel = $conn->prepare("DELETE FROM company_personnel WHERE account_id = ?");
    $deletePersonnel->bind_param('i', $accountId);
    $deletePersonnel->execute();

    $deleteOwner = $conn->prepare("DELETE FROM company_owners WHERE account_id = ?");
    $deleteOwner->bind_param('i', $accountId);
    $deleteOwner->execute();

    // ✅ Delete from security table
    $deleteSecurity = $conn->prepare("DELETE FROM security WHERE account_id = ?");
    $deleteSecurity->bind_param('i', $accountId);
    $deleteSecurity->execute();

    // ✅ Delete from users table
    $deleteUser = $conn->prepare("DELETE FROM users WHERE account_id = ?");
    $deleteUser->bind_param('i', $accountId);
    $deleteUser->execute();

    if ($deleteUser->affected_rows === 0) {
        throw new Exception("Account deletion failed.");
    }

    $conn->commit();

    // Destroy session
    session_unset();
    session_destroy();

    session_start();
    $_SESSION['flash_success'] = "Your account has been deleted. Transactions moved to archive.";

    header('Location: ../../html/usercreation/login.php');
    exit;

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    $_SESSION['flash_error'] = "Error: " . $e->getMessage();
    header('Location: ../../html/mainpages/delete_account.php');
    exit;
}
?>
