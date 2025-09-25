<?php
class AuthService {
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function login(string $accountId, string $password): array {
        $user = $this->userRepo->findByAccountId($accountId);

        if (!$user) {
            return ["error" => "Invalid credentials."]; // avoid leaking info
        }

        if (!password_verify($password, $user['password'])) {
            return ["error" => "Invalid credentials."]; // same message
        }

        return ["user" => $user];
    }
}
