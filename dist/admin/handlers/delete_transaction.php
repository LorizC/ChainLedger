<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];  // Secure: Cast to int
    
    // Delete transaction
    $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['flash_success'] = " Transaction deleted successfully!";
        $stmt->close();
        $conn->close();
        header("Location: dashboard.php?deleted=transaction");
        exit();
    } else {
        $_SESSION['flash_error'] = " Failed to delete transaction. Please try again.";
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