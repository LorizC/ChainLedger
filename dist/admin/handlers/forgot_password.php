<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accountId = trim($_POST['account_id'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $question  = trim($_POST['security_question'] ?? '');
    $answer    = trim($_POST['security_answer'] ?? '');

    if ((empty($accountId) && empty($username)) || empty($question) || empty($answer)) {
        $error = "Please provide either Account ID or Username and answer the security question.";
    } else {
        // ✅ If username is provided, get account_id from it
        if (!empty($username)) {
            $stmt = $conn->prepare("SELECT account_id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $error = "Invalid Username.";
                $stmt->close();
            } else {
                $row = $result->fetch_assoc();
                $accountId = (int) $row['account_id'];
                $stmt->close();
            }
        } else {
            // Convert to int if provided directly
            $accountId = (int) $accountId;
        }

        if (empty($error)) {
            // ✅ Check if security record exists
            $stmt2 = $conn->prepare("SELECT account_id FROM security WHERE account_id = ? LIMIT 1");
            $stmt2->bind_param("i", $accountId);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows === 0) {
                $error = "No security question found for this user.";
            } else {
                // ✅ Verify security question + answer
                if ($passwordService->verifySecurityAnswer($accountId, $question, $answer)) {
                    $_SESSION['reset_account_id'] = $accountId;
                    header("Location: change_password.php");
                    exit;
                } else {
                    $error = "Invalid security question or answer.";
                }
            }

            $stmt2->close();
        }
    }
}
?>
