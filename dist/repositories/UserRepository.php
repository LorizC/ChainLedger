<?php
class UserRepository {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // ===============================
    // Create User
    // ===============================
    public function createUser(
        string $first,
        string $last,
        string $birthdate,
        string $gender,
        string $username,
        int $accountId,
        string $profileImage = '../../assets/images/user/profile.png'
    ): int {
        $stmt = $this->conn->prepare("
            INSERT INTO users (first_name, last_name, birthdate, gender, username, account_id, profile_image)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssis", $first, $last, $birthdate, $gender, $username, $accountId, $profileImage);

        if (!$stmt->execute()) {
            throw new Exception("Failed to create user: " . $stmt->error);
        }

        return $this->conn->insert_id;
    }

    // ===============================
    // Finders
    // ===============================
    public function findSecurityByAccountId(int $accountId): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM security WHERE account_id = ? LIMIT 1");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function findByAccountId(int $accountId): ?array {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.first_name, u.last_name, u.birthdate, u.gender, 
                   u.username, u.account_id, u.profile_image,
                   s.password
            FROM users u
            LEFT JOIN security s ON u.account_id = s.account_id
            WHERE u.account_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $accountId);
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

    public function findWithRoleByAccountId(int $accountId): ?array {
        $sql = "
            SELECT 
                u.user_id,
                u.account_id,
                u.username,
                u.first_name,
                u.last_name,
                u.birthdate,
                u.gender,
                u.profile_image,
                u.date_registered,
                s.password,
                c.company_role
            FROM users u
            LEFT JOIN security s ON u.account_id = s.account_id
            LEFT JOIN company_personnel c ON u.account_id = c.account_id
            WHERE u.account_id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $accountId);
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

    // ===============================
    // Password Management
    // ===============================
    public function updatePassword(int $accountId, string $hashedPassword): bool {
        $stmt = $this->conn->prepare("
            UPDATE security SET password = ? WHERE account_id = ?
        ");
        $stmt->bind_param("si", $hashedPassword, $accountId);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $user = $this->findByAccountId($accountId);
            if (!$user) {
                throw new Exception("User not found for password insert.");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO security (account_id, username, password, security_question, security_answer)
                VALUES (?, ?, ?, '', '')
                ON DUPLICATE KEY UPDATE password = VALUES(password)
            ");
            $stmt->bind_param(
                "iss",
                $user['account_id'],
                $user['username'],
                $hashedPassword
            );
            return $stmt->execute();
        }

        return true;
    }

    // ===============================
    // Helpers
    // ===============================
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

    public function addSecurity(int $accountId, string $username, ?string $hashedPassword, string $securityQ, string $hashedSecurityA): bool {
        $stmt = $this->conn->prepare("
           INSERT INTO security (account_id, username, security_question, security_answer, password)
           VALUES (?, ?, ?, ?, ?)
           ON DUPLICATE KEY UPDATE 
            security_question = VALUES(security_question),
            security_answer = VALUES(security_answer),
            password = COALESCE(VALUES(password), password)
            ");

             // Note: $hashedSecurityA is already hashed before calling this method
            $stmt->bind_param("issss", $accountId, $username, $securityQ, $hashedSecurityA, $hashedPassword);
            return $stmt->execute();
     }

    public function assignRole(array $user, string $role): bool {
        $role = ucwords(strtolower(trim($role)));

        $stmt = $this->conn->prepare("
            INSERT INTO company_personnel (account_id, username, company_role)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE company_role = VALUES(company_role)
        ");
        $stmt->bind_param("iss", $user['account_id'], $user['username'], $role);
        if (!$stmt->execute()) {
            throw new Exception("Failed to assign role in company_personnel: " . $stmt->error);
        }

        if ($role === "Business Owner") {
            $stmt = $this->conn->prepare("
                INSERT INTO company_owners (account_id, username, company_role)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE company_role = VALUES(company_role)
            ");
            $stmt->bind_param("iss", $user['account_id'], $user['username'], $role);
            if (!$stmt->execute()) {
                throw new Exception("Failed to assign role in company_owners: " . $stmt->error);
            }
        }

        return true;
    }

    // ===============================
    // Total Spending
    // ===============================
    public function getTotalSpendingByAccountId(int $accountId): float {
        $sql = "
            SELECT COALESCE(SUM(amount), 0) AS total_spending
            FROM transactions
            WHERE account_id = ? AND amount < 0
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return abs((float)$row['total_spending']);
    }
}
