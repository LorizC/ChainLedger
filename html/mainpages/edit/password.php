<?php
session_start();
require_once __DIR__ . '/../../../php/db/dbconfig.php';
require_once __DIR__ . '/../../../php/repositories/UserRepository.php';
require_once __DIR__ . '/../../../php/services/PasswordService.php';
require_once __DIR__ . '/../../../php/services/SecurityLogService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);
$logService = new SecurityLogService($conn);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username         = trim($_POST['username'] ?? '');
    $currentPassword  = trim($_POST['current_password'] ?? '');
    $newPassword      = trim($_POST['new_password'] ?? '');
    $confirmPassword  = trim($_POST['confirm_password'] ?? '');

    // Validate fields
    if (empty($username) || empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['flash_error'] = "Please fill in all required fields.";
        header("Location: edit_password.php");
        exit;
    } elseif ($newPassword !== $confirmPassword) {
        $_SESSION['flash_error'] = "New password and confirmation do not match.";
        header("Location: edit_password.php");
        exit;
    } else {
        // Fetch user data
        $stmt = $conn->prepare("
            SELECT u.account_id, u.user_id, s.password, u.username 
            FROM users u
            INNER JOIN security s ON u.account_id = s.account_id
            WHERE u.username = ? LIMIT 1
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['flash_error'] = "Invalid username.";
            header("Location: edit_password.php");
            exit;
        } else {
            $user = $result->fetch_assoc();

            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['flash_error'] = "Current password is incorrect.";
                header("Location: edit_password.php");
                exit;
            } else {
                // Hash and update password
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare("UPDATE security SET password = ? WHERE account_id = ?");
                $updateStmt->bind_param("si", $newHashedPassword, $user['account_id']);

                if ($updateStmt->execute()) {
                    // ✅ Log password change
                    $logService->logEvent(
                        $user['user_id'],
                        $user['account_id'],
                        $user['username'],
                        'PASSWORD_CHANGE'
                    );

                    $_SESSION['flash_success'] = "Password updated successfully!";
                } else {
                    $_SESSION['flash_error'] = "Error updating password. Please try again.";
                }

                $updateStmt->close();
                header("Location: edit_password.php");
                exit;
            }
        }
        $stmt->close();
    }
}
?>
