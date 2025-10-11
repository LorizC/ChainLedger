<?php
session_start();
ob_start();

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/SignupService.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

// Initialize dependencies
$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$authService = new AuthService($userRepo);
$logService = new SecurityLogService($conn);

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
        //  Log failed login using helper method
        $logService->logFailedLogin($accountId);
    }
         else {
            //  Store complete user data in session
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

            //  Log successful login
            $logService->logEvent(
                $result['user']['user_id'],
                $result['user']['account_id'],
                $result['user']['username'],
                'LOGIN'
            );

            // Redirect to dashboard
            header("Location: /PWA/dist/admin/dashboard.php");
            exit();
        }
    }
}
?>
