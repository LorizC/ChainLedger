<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);

$error = "";
$step = 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($step === 1 && isset($_POST['user_id'])) {
        $_SESSION['reset_user_id'] = (int) $_POST['user_id'];
        $step = 2;
    }
    elseif ($step === 2 && isset($_POST['security_question'], $_POST['security_answer'])) {
        $userId = $_SESSION['reset_user_id'] ?? 0;
        if ($passwordService->verifySecurityAnswer($userId, $_POST['security_question'], $_POST['security_answer'])) {
            $step = 3;
        } else {
            $error = "Security answer incorrect.";
        }
    }
    elseif ($step === 3 && isset($_POST['new_password'], $_POST['confirm_password'])) {
        $userId = $_SESSION['reset_user_id'] ?? 0;
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = "Passwords do not match.";
        } else {
            if ($passwordService->resetPassword($userId, $_POST['new_password'])) {
                unset($_SESSION['reset_user_id']);
                header("Location: login.php");
                exit;
            } else {
                $error = "Password reset failed.";
            }
        }
    }
}
