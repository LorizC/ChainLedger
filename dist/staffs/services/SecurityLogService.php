<?php
// services/SecurityLogService.php
class SecurityLogService {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function logEvent(int $userId, int $accountId, string $username, string $action): void {
        try {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
            $deviceInfo = $this->detectDevice($userAgent);

            $stmt = $this->conn->prepare("
                INSERT INTO security_logs 
                    (user_id, account_id, username, action, ip_address, device_info, user_agent, timestamp)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            if (!$stmt) {
                error_log("SecurityLogService: Prepare failed - " . $this->conn->error);
                return;
            }

            $stmt->bind_param('iisssss', $userId, $accountId, $username, $action, $ipAddress, $deviceInfo, $userAgent);
            $stmt->execute();
            $stmt->close();

        } catch (mysqli_sql_exception $e) {
            error_log("SecurityLogService: MySQL error - " . $e->getMessage());
        } catch (Exception $e) {
            error_log("SecurityLogService: General error - " . $e->getMessage());
        }
    }

    private function detectDevice(string $userAgent): string {
        $ua = strtolower($userAgent);
        if (strpos($ua, 'mobile') !== false) return 'Mobile';
        if (strpos($ua, 'tablet') !== false) return 'Tablet';
        if (strpos($ua, 'windows') !== false || strpos($ua, 'macintosh') !== false) return 'Desktop';
        return 'Unknown Device';
    }

    public function logFailedLogin(string $username): void {
    try {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
        $deviceInfo = $this->detectDevice($userAgent);

        $stmt = $this->conn->prepare("
            INSERT INTO security_logs 
                (user_id, account_id, username, action, ip_address, device_info, user_agent, timestamp)
            VALUES (NULL, NULL, ?, 'FAILED_LOGIN', ?, ?, ?, NOW())
        ");

        if (!$stmt) {
            error_log("SecurityLogService: Prepare failed for failed_login - " . $this->conn->error);
            return;
        }

        $stmt->bind_param('ssss', $username, $ipAddress, $deviceInfo, $userAgent);
        $stmt->execute();
        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        error_log("SecurityLogService: MySQL error (failed_login) - " . $e->getMessage());
    } catch (Exception $e) {
        error_log("SecurityLogService: General error (failed_login) - " . $e->getMessage());
    }
}

}

