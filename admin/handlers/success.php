<?php
// success.php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$error = "";

$username = "N/A";
$accountID = "000000";

// ✅ Use stored session info first
if (isset($_SESSION['success_account_id']) && isset($_SESSION['success_username'])) {
    $username = htmlspecialchars($_SESSION['success_username']);
    $accountID = htmlspecialchars($_SESSION['success_account_id']);
} else {
    // fallback if no session
    $query = "SELECT username, account_id FROM users ORDER BY user_id DESC LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = htmlspecialchars($user['username']);
        $accountID = htmlspecialchars($user['account_id']);
    }
}

$conn->close();
?>