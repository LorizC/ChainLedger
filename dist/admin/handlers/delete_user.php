<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();




if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];  // Secure: Cast to int
    
    // Delete user (use prepared statement)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['flash_success'] = " User deleted successfully!";
        $stmt->close();
        $conn->close();
        header("Location: dashboard.php?deleted=user");
        exit();
    } else {
        $_SESSION['flash_error'] = " User deleted successfully!";
        $stmt->close();
        $conn->close();
        header("Location: dashboard.php?error=delete_failed");
        exit();
    }
} else {
    header("Location: dashboard.php?error=invalid_id");
    exit();
}
?>