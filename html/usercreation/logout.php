<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

// ✅ Connect + initialize
$conn = Database::getConnection();
$logService = new SecurityLogService($conn);

// ✅ If user exists in session, log logout event
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    $logService->logEvent(
        $user['user_id'],
        $user['account_id'],
        $user['username'],
        'LOGOUT'
    );
}

// ✅ Destroy session after logging
session_destroy();
header("Location: login.php");
exit;
?>
