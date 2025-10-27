<?php
class AuthService {
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    
      //Login using account_id + password
     
    public function login(string $accountId, string $password): array {
        //  fetch user with role included
        $user = $this->userRepo->findWithRoleByAccountId($accountId);

        if (!$user || empty($user['password'])) {
            return ["error" => "Invalid credentials."]; // don’t leak info
        }

        if (!password_verify($password, $user['password'])) {
            return ["error" => "Invalid credentials."];
        }

        //  remove sensitive hash
        unset($user['password']);

        //  ensure a fallback role
        $user['company_role'] = $user['company_role'] ?? 'User';

        return ["user" => $user];
    }
}
