<?php
class PasswordService {
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function verifySecurityAnswer(int $accountId, string $question, string $answer): bool {
        $record = $this->userRepo->findSecurityByAccountId($accountId);
        if (!$record) return false;

        $questionMatches = strtolower(trim($record['security_question'])) === strtolower(trim($question));
        $answerMatches   = password_verify(trim($answer), $record['security_answer']);

        return $questionMatches && $answerMatches;
    }


    
     // Set a password for a user (by account_id).
     // @throws Exception on failure.
    public function setPassword(int $accountId, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if (!$this->userRepo->updatePassword($accountId, $hashedPassword)) {
            throw new Exception("Failed to set password for account ID $accountId.");
        }
        return true;
    }

    
     // Change existing password
     // @throws Exception on failure.
    public function changePassword(int $accountId, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        if (!$this->userRepo->updatePassword($accountId, $hashedPassword)) {
            throw new Exception("Failed to change password for account ID $accountId.");
        }
        return true;
    }

    
     // Reset password (alias for changePassword)
    public function resetPassword(int $accountId, string $newPassword): bool {
        return $this->changePassword($accountId, $newPassword);
    }
}
