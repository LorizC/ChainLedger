<?php
class PasswordService {
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    /**
     * Set a password for a user.
     * @throws Exception on failure.
     */
    public function setPassword(int $userId, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if (!$this->userRepo->updatePassword($userId, $hashedPassword)) {
            throw new Exception("Failed to set password for user ID $userId.");
        }
        return true;
    }

    /**
     * Change existing password
     * @throws Exception on failure.
     */
    public function changePassword(int $userId, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        if (!$this->userRepo->updatePassword($userId, $hashedPassword)) {
            throw new Exception("Failed to change password for user ID $userId.");
        }
        return true;
    }

    /**
     * Reset password (alias for changePassword)
     */
    public function resetPassword(int $userId, string $newPassword): bool {
        return $this->changePassword($userId, $newPassword);
    }
}
