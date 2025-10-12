<?php
session_start();
ob_start();

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/SignupService.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$authService = new AuthService($userRepo);
$logService = new SecurityLogService($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountId = trim($_POST['account_id'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if (empty($accountId) || empty($password)) {
        $_SESSION['flash_error'] = "Please enter both Account ID and Password.";
        header("Location: /ChainLedger-System-/index.php");
        exit;
    }

    $result = $authService->login($accountId, $password);

    if (isset($result['error'])) {
        // ❌ Wrong credentials
        $_SESSION['flash_error'] = $result['error'];
        $logService->logFailedLogin($accountId);

        header("Location: /ChainLedger-System-/index.php");
        exit;
    } else {
        // ✅ Successful login
        $_SESSION['user'] = [
            'user_id'         => $result['user']['user_id'],
            'account_id'      => $result['user']['account_id'],
            'username'        => $result['user']['username'],
            'first_name'      => $result['user']['first_name'],
            'last_name'       => $result['user']['last_name'],
            'birthdate'       => $result['user']['birthdate'] ?? null,
            'date_registered' => $result['user']['date_registered'] ?? null,
            'company_role'    => $result['user']['company_role'] ?? 'Unassigned',
            'profile_image'   => $result['user']['profile_image'] ?? 'images/avatars/profile.png'
        ];

        $logService->logEvent(
            $result['user']['user_id'],
            $result['user']['account_id'],
            $result['user']['username'],
            'LOGIN'
        );
        //need mag lagay ng flash message sa dash para makita to
        $_SESSION['flash_success'] = "Login successful. Welcome back , " . htmlspecialchars($result['user']['username']) . "!";
        header("Location: /ChainLedger-System-/dist/admin/dashboard.php");
        exit;
    }
}
?>
