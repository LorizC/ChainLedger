<?php
class SignupService {
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    private function generateAccountId(): int {
        $maxAttempts = 100;
        $attempts = 0;

        do {
            $accountId = random_int(100000, 999999);
            $exists = $this->userRepo->accountIdExists($accountId);
            $attempts++;
            if ($attempts >= $maxAttempts) {
                throw new Exception("Failed to generate a unique account ID after $maxAttempts attempts.");
            }
        } while ($exists);

        return $accountId;
    }

    private function generateUsername(string $firstName, string $lastName): string {
        // Use "First Last" format
        $baseUsername = $firstName . ' ' . $lastName;
        $username = $baseUsername;
        $suffix = 1;
        $maxAttempts = 100;

        // Ensure uniqueness
        while ($this->userRepo->findByUsername($username) !== null) {
            $username = $baseUsername . $suffix;
            $suffix++;
            if ($suffix > $maxAttempts) {
                throw new Exception("Failed to generate a unique username after $maxAttempts attempts.");
            }
        }

        return $username;
    }

    public function registerUser(array $data): array {
        $firstName = trim($data['first_name'] ?? '');
        $lastName  = trim($data['last_name'] ?? '');
        $birthdate = $data['birthdate'] ?? '';
        $gender    = $data['gender'] ?? '';
        $securityQ = $data['security_question'] ?? '';
        $securityA = $data['security_answer'] ?? '';

        if ($firstName === '' || $lastName === '' || $birthdate === '' || $gender === '' || $securityQ === '' || $securityA === '') {
            throw new Exception("All required fields must be filled.");
        }

        // Generate unique username & account_id
        $username  = $this->generateUsername($firstName, $lastName);
        $accountId = $this->generateAccountId();

        // Assign a default profile image
        $defaultProfile = '../assets/images/user/profile.png';

        // Create user row with default profile image
        $userId = $this->userRepo->createUser(
            $firstName,
            $lastName,
            $birthdate,
            $gender,
            $username,
            $accountId,
            $defaultProfile
        );

        // Insert security (leave password NULL for now)
        if (!$this->userRepo->addSecurity($accountId, $username, null, $securityQ, $securityA)) {
            throw new Exception("Failed to add security info for account ID $accountId.");
        }

        return [
            "user_id"    => $userId,
            "account_id" => $accountId,
            "username"   => $username
        ];
    }
}
