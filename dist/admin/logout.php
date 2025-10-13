<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

// Initialize DB + logging
$conn = Database::getConnection();
$logService = new SecurityLogService($conn);

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    // Log logout event
    $logService->logEvent(
        $user['user_id'],
        $user['account_id'],
        $user['username'],
        'LOGOUT'
    );
}

// Destroy session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to login
header("Location: ../../index.php");
exit;
