<?php
session_start();
ob_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/AuthService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$signupService = new SignupService($userRepo);

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
        // ✅ Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
// ✅ Update password
$userRepo->updatePassword($user['user_id'], $hashedPassword);

// ✅ Assign role (cleaner now)
$userRepo->assignRole($user, $role);
            // Assign role to relevant tables

            // ✅ Clear temp signup session
            unset($_SESSION['temp_user_id'], $_SESSION['temp_username'], $_SESSION['account_id']);

            // ✅ Redirect to login
            header("Location: login.php");
            exit;

        } catch (Exception $e) {
            $error = "Error saving password: " . $e->getMessage();
        }
    }
}

?>
