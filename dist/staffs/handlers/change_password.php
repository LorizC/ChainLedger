<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);
$logService = new SecurityLogService($conn);

$error = "";
$success = "";

if (!isset($_SESSION['reset_account_id'])) {
    $error = "No reset session found. Please use Forgot Password again.";
} else {
    $accountId = $_SESSION['reset_account_id'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $newPassword = $_POST['new_password'] ?? '';
        $confirm     = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirm) {
            $error = "Passwords do not match.";
        } elseif (strlen($newPassword) < 8) {
            $error = "Password must be at least 8 characters.";
        } else {
            try {
                //  Reset password
                $passwordService->resetPassword((int)$accountId, $newPassword);

                //  Fetch user for logging
                $user = $userRepo->findByAccountId((int)$accountId);

                if ($user) {
                    //  Log password change
                    $logService->logEvent(
                        $user['user_id'],
                        $user['account_id'],
                        $user['username'],
                        'PASSWORD_CHANGE'
                    );
                }

                // ✅ Cleanup + redirect
                unset($_SESSION['reset_account_id']); 
                $_SESSION['flash_success'] = "Your password has been reset successfully. Please login with your new password.";
                header("Location: /ChainLedger-System-/index.php");
                exit;
            } catch (Exception $e) {
                $error = "Password reset failed: " . $e->getMessage();
            }
        }
    }
}
?>
