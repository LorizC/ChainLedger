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
        int $accountId,
        string $profileImage = '../../images/avatars/profile.png' 
    ): int {
        $stmt = $this->conn->prepare("
            INSERT INTO users (first_name, last_name, birthdate, gender, username, account_id, profile_image)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssis", $first, $last, $birthdate, $gender, $username, $accountId, $profileImage);

        if (!$stmt->execute()) {
            throw new Exception("Failed to create user: " . $stmt->error);
        }

        return $this->conn->insert_id; // auto-increment user_id
    }

public function findSecurityByAccountId(int $accountId): ?array {
    $stmt = $this->conn->prepare("SELECT * FROM security WHERE account_id = ? LIMIT 1");
    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
}
    
public function findByAccountId(string $accountId): ?array {
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
public function findWithRoleByAccountId(string $accountId): ?array {
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
    $stmt->bind_param('s', $accountId);
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

    // ✅ Update password using account_id instead of user_id
    public function updatePassword(int $accountId, string $hashedPassword): bool {
        $stmt = $this->conn->prepare("
            UPDATE security SET password = ? WHERE account_id = ?
        ");
        $stmt->bind_param("si", $hashedPassword, $accountId);
        $stmt->execute();

        // ✅ If no rows updated, insert new
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

    // ✅ Insert into security table without user_id
public function addSecurity(int $accountId, string $username, ?string $hashedPassword, string $securityQ, string $securityA): bool {
    $stmt = $this->conn->prepare("
        INSERT INTO security (account_id, username, security_question, security_answer, password)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            security_question = VALUES(security_question),
            security_answer = VALUES(security_answer),
            password = COALESCE(VALUES(password), password)
    ");
    // Allow NULL password
    $stmt->bind_param("issss", $accountId, $username, $securityQ, $securityA, $hashedPassword);

    return $stmt->execute();
}


    // ✅ Roles simplified to match schema
    public function assignRole(array $user, string $role): bool {
        $role = ucwords(strtolower(trim($role)));

        // Always insert into company_personnel
        $stmt = $this->conn->prepare("
            INSERT INTO company_personnel (account_id, username, company_role)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE company_role = VALUES(company_role)
        ");
        $stmt->bind_param("iss", $user['account_id'], $user['username'], $role);
        if (!$stmt->execute()) {
            throw new Exception("Failed to assign role in company_personnel: " . $stmt->error);
        }

        // If Business Owner → also insert into company_owners
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
}
