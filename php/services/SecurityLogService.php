<?php
class SecurityLogService {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function logEvent($userId, $accountId, $username, $action) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
        $deviceInfo = php_uname(); // snapshot of server-side device info

        $stmt = $this->conn->prepare("
            INSERT INTO security_logs (user_id, account_id, username, action, ip_address, device_info, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssss", $userId, $accountId, $username, $action, $ip, $deviceInfo, $userAgent);
        $stmt->execute();
        $stmt->close();
    }
}
?>