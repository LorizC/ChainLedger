<?php
class SecurityLogService {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function logEvent($userId, $accountId, $username, $action) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO security_logs (user_id, account_id, username, action, timestamp)
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            if (!$stmt) {
                // silently fail if preparation failed
                error_log("SecurityLogService: Failed to prepare statement: " . $this->conn->error);
                return;
            }

            $stmt->bind_param('iiss', $userId, $accountId, $username, $action);
            $stmt->execute();

        } catch (mysqli_sql_exception $e) {
            // Catch and log (but don’t break execution)
            error_log("SecurityLogService: Log insert failed - " . $e->getMessage());
        } catch (Exception $e) {
            // Generic fallback
            error_log("SecurityLogService: Unexpected error - " . $e->getMessage());
        }
    }
}
