<?php
class AuthService {
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    /**
     * Login using account_id + password
     */
    public function login(string $accountId, string $password): array {
        $user = $this->userRepo->findByAccountId($accountId);

        if (!$user || empty($user['password'])) {
            return ["error" => "Invalid credentials."]; // don’t leak info
        }

        if (!password_verify($password, $user['password'])) {
            return ["error" => "Invalid credentials."];
        }

        // ✅ never return password hash in response
        unset($user['password']);

        return ["user" => $user];
    }
}
