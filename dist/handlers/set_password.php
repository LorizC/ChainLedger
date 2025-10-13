<?php
session_start();
ob_start();
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/SecurityLogService.php';

//call

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);
$logService = new SecurityLogService($conn);

$error = "";
$username = "";

// ✅ Use user_id from session instead of old username
if (isset($_SESSION['temp_user_id'])) {
    $user = $userRepo->findByUserId($_SESSION['temp_user_id']);
    if ($user) {
        $username = $user['username'];
    } else {
        $error = "User not found in session.";
    }
} else {
    $error = "No user session found. Please sign up again.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (empty($role)) {
        $error = "Please select a role.";
    } elseif (empty($user)) {
        $error = "Invalid session user.";
    } else {
        try {
            // Update/Save password using PasswordService (account_id, not user_id)
            $passwordService->setPassword((int)$user['account_id'], $password);

            // Assign role
            $userRepo->assignRole($user, $role);
            
            //Log account creation
            $logService->logEvent(
              $user['user_id'],
              $user['account_id'],
              $user['username'],
             'ACCOUNT_CREATED'
              );
      
              // Clear temp signup session
            unset($_SESSION['temp_user_id'], $_SESSION['temp_username'], $_SESSION['account_id']);


            // ✅ Redirect to login
            header("Location: success.php");
            exit;

        } catch (Exception $e) {
            $error = "Error saving password: " . $e->getMessage();
        }
    }
}
?>
