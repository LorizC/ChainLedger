<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';

$conn = Database::getConnection();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = (int)$_GET['id'];  // Secure cast

    try {
        // ===============================
        // Start Transaction
        // ===============================
        $conn->begin_transaction();

        // ===============================
        // Fetch user info
        // ===============================
        $stmt = $conn->prepare("SELECT account_id, first_name, last_name, birthdate, gender, username, profile_image, date_registered FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $userData = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$userData) {
            throw new Exception("User not found.");
        }

        $accountId = $userData['account_id'];

        // ===============================
        // Archive user info
        // ===============================
        $archiveUser = $conn->prepare("
            INSERT INTO archivedaccounts (
                account_id, first_name, last_name, birthdate, gender,
                username, profile_image, date_registered, archived_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $archiveUser->bind_param(
            'isssssss',
            $userData['account_id'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['birthdate'],
            $userData['gender'],
            $userData['username'],
            $userData['profile_image'],
            $userData['date_registered']
        );
        $archiveUser->execute();
        $archiveUser->close();
    // ===============================
    //  Archive Transactions → archivedtransactions
    // ===============================
    $archiveTransactions = $conn->prepare("
        INSERT INTO archivedtransactions (
            transaction_id, account_id, old_account_id, old_username,
            username, detail, merchant, amount, currency,
            transaction_date, entry_date, transaction_type, status, archived_at
        )
        SELECT 
            t.transaction_id,
            t.account_id,
            ? AS old_account_id,
            ? AS old_username,
            t.username,
            t.detail,
            t.merchant,
            t.amount,
            t.currency,
            t.transaction_date,
            t.entry_date,
            t.transaction_type,
            t.status,
            NOW()
        FROM transactions t
        WHERE t.account_id = ?
    ");
    $archiveTransactions->bind_param('isi', $accountId, $username, $accountId);
    $archiveTransactions->execute();

if ($archiveTransactions->affected_rows === 0) {
    error_log("No transactions archived for account ID $accountId");
}
        // ===============================
        // Archive security logs
        // ===============================
        $archiveLogs = $conn->prepare("
            INSERT INTO archivedlogs (
                user_id, account_id, username, action, action_details,
                ip_address, device_info, user_agent, timestamp, archived_at
            )
            SELECT 
                user_id, account_id, username, action, action_details,
                ip_address, device_info, user_agent, timestamp, NOW()
            FROM security_logs
            WHERE account_id = ?
        ");
        $archiveLogs->bind_param('i', $accountId);
        $archiveLogs->execute();
        $archiveLogs->close();

        // ===============================
        // Delete user & related data
        // ===============================
        $tables = ['company_personnel', 'company_owners', 'security', 'users'];
        foreach ($tables as $table) {
            $stmt = $conn->prepare("DELETE FROM {$table} WHERE account_id = ?");
            $stmt->bind_param('i', $accountId);
            $stmt->execute();
            $stmt->close();
        }

        // ===============================
        // Commit & redirect
        // ===============================
        $conn->commit();
        $_SESSION['flash_success'] = "User deleted successfully! Data archived.";
        header("Location: dashboard.php?deleted=user");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error deleting user: " . $e->getMessage());
        $_SESSION['flash_error'] = "Failed to delete user: " . $e->getMessage();
        header("Location: dashboard.php?error=delete_failed");
        exit();
    }
} else {
    $_SESSION['flash_error'] = "Invalid user ID.";
    header("Location: dashboard.php?error=invalid_id");
    exit();
}
