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

// Ensure account_id is in session
if (!isset($_SESSION['user']['account_id'])) {
    $error = "No account session found. Please login first.";
} else {
    $accountId = $_SESSION['user']['account_id'];
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['account_id'])) {
    $accountId = $_SESSION['account_id'];
    $newPassword = $_POST['password'] ?? '';
    $confirm = $_POST['confirmPassword'] ?? '';

    if ($newPassword !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPassword) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        try {
            $passwordService->changePassword($accountId, $newPassword);
            $success = "Password changed successfully.";
            // Optionally log the user out or redirect
            // unset($_SESSION['account_id']);
            // header("Location: login.php");
            // exit;
        } catch (Exception $e) {
            $error = "Password change failed: " . $e->getMessage();
        }
    }
}
?>
