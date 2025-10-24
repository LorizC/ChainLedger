<?php
session_start();
ob_start();

require_once __DIR__ . '/../database/dbconfig.php';
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
        header("Location: /ChainLedger/index.php");
        exit;
    }

    $result = $authService->login($accountId, $password);

    if (isset($result['error'])) {
        // Wrong credentials
        $_SESSION['flash_error'] = $result['error'];
        $logService->logFailedLogin($accountId);
        header("Location: /ChainLedger/index.php");
        exit;
    } else {
        //  Successful login
        $user = $result['user'];

        $_SESSION['user'] = [
            'user_id'         => $user['user_id'],
            'account_id'      => $user['account_id'],
            'username'        => $user['username'],
            'first_name'      => $user['first_name'],
            'last_name'       => $user['last_name'],
            'birthdate'       => $user['birthdate'] ?? null,
            'date_registered' => $user['date_registered'] ?? null,
            'company_role'    => $user['company_role'] ?? 'Unassigned',
            'profile_image'   => $user['profile_image'] ?? 'images/avatars/profile.png'
        ];

        $logService->logEvent(
            $user['user_id'],
            $user['account_id'],
            $user['username'],
            'LOGIN'
        );

        $_SESSION['flash_success'] = "Login successful. Welcome back, " . htmlspecialchars($user['username']) . "!";

        // Role-based redirection
        $role = strtolower(trim($user['company_role'] ?? ''));

        if ($role === 'staff') {
            header("Location: /ChainLedger/dist/staffs/dashboard.php");
        } elseif ($role === 'business owner' || $role === 'manager') {
            header("Location: /ChainLedger/dist/admin/dashboard.php");
        } else {
            // If not staff/manager/business owner → deny access
            $_SESSION['flash_error'] = "Your account does not have dashboard access.";
            header("Location: /ChainLedger/index.php");
        }

        exit;
    }
}
?>
