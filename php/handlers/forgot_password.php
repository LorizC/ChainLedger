<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accountId = trim($_POST['account_id'] ?? '');
    $question  = trim($_POST['security_question'] ?? '');
    $answer    = trim($_POST['security_answer'] ?? '');

    if (empty($accountId) || empty($question) || empty($answer)) {
        $error = "All fields are required.";
    } else {
        // ✅ Check if account_id exists
        $stmt = $conn->prepare("SELECT account_id FROM security WHERE account_id = ? LIMIT 1");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // No such account_id in security table
            $error = "Invalid Account ID.";
        } else {
            // ✅ Verify security question + answer
            if ($passwordService->verifySecurityAnswer((int)$accountId, $question, $answer)) {
                $_SESSION['reset_account_id'] = $accountId;
                header("Location: change_password.php");
                exit;
            } else {
                $error = "Invalid security question or answer.";
            }
        }

        $stmt->close();
    }
}
?>
