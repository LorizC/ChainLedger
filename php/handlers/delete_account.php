<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/dbconfig.php';
require_once __DIR__ . '/../../repositories/UserRepository.php';
require_once __DIR__ . '/../../services/SecurityLogService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$logService = new SecurityLogService($conn);

if (!isset($_SESSION['user'])) {
    header("Location: ../../usercreation/login.php");
    exit;
}

$accountId = (int)$_SESSION['user']['account_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $securityAnswer  = trim($_POST['security_answer'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    try {
        if ($currentPassword !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }

        // Get security details
        $stmt = $conn->prepare("SELECT password, security_answer, username FROM security WHERE account_id = ?");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();
        $security = $result->fetch_assoc();

        if (!$security) {
            throw new Exception("Security record not found for this account.");
        }

        if (!password_verify($currentPassword, $security['password'])) {
            throw new Exception("Incorrect password.");
        }

        if (strcasecmp($securityAnswer, $security['security_answer']) !== 0) {
            throw new Exception("Incorrect security answer.");
        }

        // Start transaction
        $conn->begin_transaction();

        // Delete user (cascade deletes security, transactions, company tables)
        $delUser = $conn->prepare("DELETE FROM users WHERE account_id = ?");
        $delUser->bind_param("i", $accountId);
        $delUser->execute();

        $conn->commit();

        // Log event
        $user = $_SESSION['user'];
        $logService->logEvent(
            $user['user_id'] ?? 0,
            $accountId,
            $security['username'],
            'ACCOUNT_DELETED'
        );

        $_SESSION['flash_success'] = "Your account has been deleted successfully.";

        // Destroy session
        session_unset();
        session_destroy();

        header("Location: ../../usercreation/login.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();

        $_SESSION['flash_error'] = $e->getMessage();
        header("Location: ../../html/mainpages/delete_account.php");
        exit;
    }
}
