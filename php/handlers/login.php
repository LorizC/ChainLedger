<?php
session_start();
ob_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/SignupService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$authService = new AuthService($userRepo);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountId = trim($_POST['account_id'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if (empty($accountId) || empty($password)) {
        $error = "Please enter both Account ID and Password.";
    } else {
        $result = $authService->login($accountId, $password);

        if (isset($result['error'])) {
            $error = $result['error'];
        } else {
            $_SESSION['user'] = $result['user']; // already cleaned (no password)
            header("Location: ../mainpages/dashboard.php");
            exit();
        }
    }
}
?>