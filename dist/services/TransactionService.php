<?php
class TransactionService {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // ===============================
    //  Create Transaction
    // ===============================
    public function addTransaction(
        int $accountId,
        string $username,
        string $detail,
        string $merchant,
        float $amount,
        string $transactionType,
        string $status,
        string $transactionDate
    ): array {
        $validation = $this->validateTransactionFields($detail, $merchant, $amount, $transactionType, $status, $transactionDate);
        if ($validation !== true) {
            return ['error' => $validation];
        }

        $sql = "
            INSERT INTO transactions 
                (account_id, username, detail, merchant, amount, currency, transaction_date, transaction_type, status)
            VALUES (?, ?, ?, ?, ?, 'PHP', ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['error' => "Database error: " . $this->conn->error];
        }

        $stmt->bind_param(
            "isssdsss",
            $accountId,
            $username,
            $detail,
            $merchant,
            $amount,
            $transactionDate,
            $transactionType,
            $status
        );

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['error' => "Failed to insert transaction: $error"];
        }
    }

    // ===============================
    //  Update Transaction
    // ===============================
    public function updateTransaction(
        int $transactionId,
        string $detail,
        string $merchant,
        float $amount,
        string $transactionType,
        string $status,
        string $transactionDate
    ): array {
        $validation = $this->validateTransactionFields($detail, $merchant, $amount, $transactionType, $status, $transactionDate);
        if ($validation !== true) {
            return ['error' => $validation];
        }

        $sql = "
            UPDATE transactions 
            SET detail = ?, merchant = ?, amount = ?, transaction_date = ?, transaction_type = ?, status = ?
            WHERE transaction_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['error' => "Database error: " . $this->conn->error];
        }

        $stmt->bind_param(
            "ssdsssi",
            $detail,
            $merchant,
            $amount,
            $transactionDate,
            $transactionType,
            $status,
            $transactionId
        );

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['error' => "Failed to update transaction: $error"];
        }
    }

    // ===============================
    //  Archive + Delete Transaction
    // ===============================
    public function archiveAndDeleteTransaction(int $transactionId): array {
        $this->conn->begin_transaction();

        try {
            // Copy to archivedtransactions
            $archiveSql = "
                INSERT INTO archivedtransactions 
                (account_id, old_account_id, old_username, detail, merchant, amount, currency, transaction_date, entry_date, transaction_type, status)
                SELECT account_id, account_id AS old_account_id, username AS old_username, detail, merchant, amount, currency, transaction_date, entry_date, transaction_type, status
                FROM transactions
                WHERE transaction_id = ?
            ";
            $archiveStmt = $this->conn->prepare($archiveSql);
            if (!$archiveStmt) {
                throw new Exception("Archive prepare error: " . $this->conn->error);
            }
            $archiveStmt->bind_param("i", $transactionId);
            if (!$archiveStmt->execute()) {
                throw new Exception("Archive insert failed: " . $archiveStmt->error);
            }
            $archiveStmt->close();

            // Delete from transactions
            $deleteSql = "DELETE FROM transactions WHERE transaction_id = ?";
            $deleteStmt = $this->conn->prepare($deleteSql);
            if (!$deleteStmt) {
                throw new Exception("Delete prepare error: " . $this->conn->error);
            }
            $deleteStmt->bind_param("i", $transactionId);
            if (!$deleteStmt->execute()) {
                throw new Exception("Delete failed: " . $deleteStmt->error);
            }
            $deleteStmt->close();

            $this->conn->commit();
            return ['success' => true];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['error' => $e->getMessage()];
        }
    }

    // ===============================
    //  Field Validator
    // ===============================
    private function validateTransactionFields(
        string $detail,
        string $merchant,
        float $amount,
        string $transactionType,
        string $status,
        string $transactionDate
    ) {
        $validDetails = ['Food', 'Equipment', 'Transportation', 'Health', 'Maintenance', 'Utilities'];
        $validMerchants = ['Gcash', 'Maya', 'Grabpay', 'Paypal', 'Googlepay'];
        $validTypes = ['DEPOSIT', 'WITHDRAWAL', 'TRANSFER', 'PAYMENT', 'REFUND'];
        $validStatuses = ['PENDING', 'COMPLETED', 'FAILED', 'CANCELLED'];

        if (!in_array($detail, $validDetails)) return "Invalid detail/category.";
        if (!in_array($merchant, $validMerchants)) return "Invalid merchant.";
        if ($amount <= 0) return "Amount must be greater than 0.";
        if (!in_array($transactionType, $validTypes)) return "Invalid transaction type.";
        if (!in_array($status, $validStatuses)) return "Invalid status.";

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $transactionDate)) return "Invalid date format.";
        $dateObj = DateTime::createFromFormat('Y-m-d', $transactionDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $transactionDate) return "Invalid date.";

        return true;
    }
}
