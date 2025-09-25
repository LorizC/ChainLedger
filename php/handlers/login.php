<?php
session_start();
ob_start();

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/AuthService.php';

// ✅ Database connection
$conn = new mysqli("localhost", "root", "", "ChainledgerDB");
if ($conn->connect_error) {
    die("Database connection failed. Please try again later.");
}

// ✅ Init Repositories & Services
$userRepo = new UserRepository($conn);
$authService = new AuthService($userRepo);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Safely grab form inputs
    $accountId = trim($_POST['account_id'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if (empty($accountId) || empty($password)) {
        $error = "Please enter both Account ID and Password.";
    } else {
        try {
            $result = $authService->login($accountId, $password);

            if (isset($result['error'])) {
                $error = $result['error'];
            } else {
                $_SESSION['user'] = $result['user'];
                header("Location: index_html.php");
                exit();
            }
        } catch (Exception $e) {
            // Catch unexpected errors
            $error = "An unexpected error occurred. Please try again.";
            error_log("Login error: " . $e->getMessage()); // ✅ log for debugging
        }
    }
}
?>
