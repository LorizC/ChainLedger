<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $newPassword = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($newPassword !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        if ($passwordService->changePassword($userId, $newPassword)) {
            header("Location: success_html.php");
            exit;
        } else {
            $error = "Password change failed.";
        }
    }
}
