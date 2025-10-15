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

// Remove only the user data, keep session for flash message
unset($_SESSION['user']);

// Set flash message
$_SESSION['flash_success'] = "You have successfully logged out.";

// Prevent caching (important for back button)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login
header("Location: ../../index.php");
exit;
