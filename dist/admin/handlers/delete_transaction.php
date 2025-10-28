<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../../services/SecurityLogService.php'; // ✅ Include the logger class

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = Database::getConnection();
$logService = new SecurityLogService($conn); // ✅ Initialize logging service

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $conn->begin_transaction();

        // Fetch the transaction record
        $fetch = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
        $fetch->bind_param("i", $id);
        $fetch->execute();
        $result = $fetch->get_result();
        $transaction = $result->fetch_assoc();
        $fetch->close();

        if (!$transaction) {
            $_SESSION['flash_error'] = "Transaction not found.";
            header("Location: ../ledger.php?error=not_found");
            exit();
        }

        // Insert into archivedtransactions
        $archive = $conn->prepare("
            INSERT INTO archivedtransactions 
            (transaction_id, account_id, old_account_id, old_username, username, detail, merchant, 
             amount, currency, transaction_date, entry_date, transaction_type, status, archived_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        if (!$archive) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $archive->bind_param(
            "iiissssdsssss",
            $transaction['transaction_id'],
            $transaction['account_id'],
            $transaction['account_id'],  // old_account_id
            $transaction['username'],    // old_username
            $transaction['username'],
            $transaction['detail'],
            $transaction['merchant'],
            $transaction['amount'],
            $transaction['currency'],
            $transaction['transaction_date'],
            $transaction['entry_date'],
            $transaction['transaction_type'],
            $transaction['status']
        );

        $archive->execute();

        if ($archive->error) {
            throw new Exception("Insert failed: " . $archive->error);
        }

        $archive->close();

        // Delete the original transaction
        $delete = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
        $delete->bind_param("i", $id);
        $delete->execute();

        if ($delete->error) {
            throw new Exception("Delete failed: " . $delete->error);
        }

        $delete->close();

        // ✅ Log the deletion event
        $userId = $_SESSION['user']['user_id'] ?? 0;
        $accountId = $_SESSION['user']['account_id'] ?? 0;
        $username = $_SESSION['user']['username'] ?? 'Unknown';
        $detail = $transaction['detail'] ?? 'No details';

        $logDetails = sprintf(
            "Transaction #%d ('%s', Amount: %s %s) was archived and deleted by %s.",
            $transaction['transaction_id'],
            $detail,
            $transaction['currency'] ?? 'PHP',
            number_format($transaction['amount'], 2),
            $username
        );

        $logService->logEvent(
            $userId,
            $accountId,
            $username,
            "DELETE_TRANSACTION",
            $logDetails
        );

        // Commit changes
        $conn->commit();

        $_SESSION['flash_success'] = "Transaction moved to archives successfully!";
        header("Location: ../ledger.php?archived=success");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "<pre style='color:red;'>Error: " . $e->getMessage() . "</pre>";
        error_log("Archive error: " . $e->getMessage());
        exit();
    } finally {
        $conn->close();
    }

} else {
    header("Location: ../ledger.php?error=invalid_id");
    exit();
}
?>
