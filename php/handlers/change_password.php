<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user']['account_id'])) {
    $accountId = $_SESSION['user']['account_id'];
    $newPassword = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        try {
            $passwordService->changePassword($accountId, $newPassword);
            header("Location: /../../html/usercreation/login.php");
            exit;
        } catch (Exception $e) {
            $error = "Password change failed: " . $e->getMessage();
        }
    }
}
