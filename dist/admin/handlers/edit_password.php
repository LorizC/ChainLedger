<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);
$logService = new SecurityLogService($conn);

if (!isset($_SESSION['user'])) {
    header("Location: /../../index.php");
    exit;
}

$accountId = (int)$_SESSION['user']['account_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword     = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    try {
        // Check if new and confirm passwords match
        if ($newPassword !== $confirmPassword) {
            throw new Exception("New passwords do not match.");
        }

        // Minimum length check
        if (strlen($newPassword) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        // Fetch current hashed password
        $stmt = $conn->prepare("SELECT password, username FROM security WHERE account_id = ?");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();
        $security = $result->fetch_assoc();

        if (!$security) {
            throw new Exception("Security record not found.");
        }

        // Verify current password
        if (!password_verify($currentPassword, $security['password'])) {
            throw new Exception("Current password is incorrect.");
        }

        // Prevent reuse of same password
        if ($currentPassword === $newPassword) {
            throw new Exception("New password cannot be the same as your current password.");
        }

        // Hash and update
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE security SET password = ? WHERE account_id = ?");
        $update->bind_param("si", $hashedPassword, $accountId);
        $update->execute();

        // Log the change
        $user = $userRepo->findByAccountId($accountId);
        $logService->logEvent($user['user_id'], $accountId, $user['username'], 'PASSWORD_CHANGE');

        $_SESSION['flash_success'] = "Password updated successfully!";
    } catch (Exception $e) {
        $_SESSION['flash_error'] = $e->getMessage();
    }

    header("Location: ../editpassword.php");
    exit;
}
