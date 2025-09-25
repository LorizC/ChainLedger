<?php
class UserRepository {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function createUser(
        string $first,
        string $last,
        string $birthdate,
        string $gender,
        string $username,
        int $accountId
    ): int {
        $stmt = $this->conn->prepare("
            INSERT INTO users (first_name, last_name, birthdate, gender, username, account_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssi", $first, $last, $birthdate, $gender, $username, $accountId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to create user: " . $stmt->error);
        }

        return $this->conn->insert_id; // ✅ correct way to get auto-increment user_id
    }

    public function findByAccountId(string $accountId): ?array {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.first_name, u.last_name, u.birthdate, u.gender, 
                   u.username, u.account_id,
                   s.password
            FROM users u
            LEFT JOIN security s ON u.user_id = s.user_id
            WHERE u.account_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->conn->prepare("
            SELECT user_id, username, account_id
            FROM users
            WHERE username = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function accountIdExists(int $accountId): bool {
        $stmt = $this->conn->prepare("
            SELECT user_id FROM users WHERE account_id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // ⚠️ password should NOT be in users table! Move it to `security`.
public function updatePassword(int $userId, string $hashedPassword): bool {
    // Try to update first
    $stmt = $this->conn->prepare("
        UPDATE security SET password = ? WHERE user_id = ?
    ");
    $stmt->bind_param("si", $hashedPassword, $userId);
    $stmt->execute();

    // ✅ If no rows updated, insert a new one
    if ($stmt->affected_rows === 0) {
        $user = $this->findByUserId($userId);
        if (!$user) {
            throw new Exception("User not found for password insert.");
        }

        $stmt = $this->conn->prepare("
            INSERT INTO security (user_id, account_id, username, password)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE password = VALUES(password)
        ");
        $stmt->bind_param(
            "iiss",
            $user['user_id'],
            $user['account_id'],
            $user['username'],
            $hashedPassword
        );
        return $stmt->execute();
    }

    return true;}

    // Helper: find user by ID
    public function findByUserId(int $userId): ?array {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.account_id, u.username, u.first_name, u.last_name
            FROM users u
            WHERE u.user_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    // Helper: insert into security table
    public function addSecurity(int $userId, string $hashedPassword, string $securityQ, string $securityA): bool {
        $user = $this->findByUserId($userId);
        if (!$user) {
            throw new Exception("User not found for security insert.");
        }

        $stmt = $this->conn->prepare("
            INSERT INTO security (user_id, account_id, username, security_question, security_answer, password)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iissss",
            $user['user_id'],
            $user['account_id'],
            $user['username'],
            $securityQ,
            $securityA,
            $hashedPassword
        );

        return $stmt->execute();
    }
public function assignRole(array $user, string $role): bool {
    $roleTables = [
        "Business Owner" => ["company_personnel", "owners"],
        "Staff"          => ["company_personnel", "staffs"],
        "Manager"        => ["company_personnel", "managers"],
    ];

    // Normalize role
    $companyRole = ucwords(strtolower(trim($role)));
    if (!isset($roleTables[$companyRole])) {
        throw new Exception("Invalid role: " . $companyRole);
    }

    foreach ($roleTables[$companyRole] as $table) {
        $stmt = $this->conn->prepare("
            INSERT INTO $table (account_id, username, company_role)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE company_role = VALUES(company_role)
        ");
        $stmt->bind_param("iss", $user['account_id'], $user['username'], $companyRole);

        if (!$stmt->execute()) {
            throw new Exception("Failed to assign role in $table: " . $stmt->error);
        }
        $stmt->close();
    }

    return true;
}
}
