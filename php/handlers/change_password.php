<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);

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
$passwordService->resetPassword((int)$accountId, $newPassword);
unset($_SESSION['reset_account_id']); // cleanup
$_SESSION['success_message'] = "Your password has been reset successfully. Please login with your new password.";
header("Location: login.php");
exit;
            } catch (Exception $e) {
                $error = "Password reset failed: " . $e->getMessage();
            }
        }
    }
}
?>