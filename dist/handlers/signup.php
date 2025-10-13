<?php
session_start();
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/SignupService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$signupService = new SignupService($userRepo);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $postData = [
            'first_name' => ucfirst(trim($_POST['first_name'] ?? '')),
            'last_name' => ucfirst(trim($_POST['last_name'] ?? '')),
            'birthdate' => trim($_POST['birthdate'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'security_question' => trim($_POST['security_question'] ?? ''),
            'security_answer' => trim($_POST['security_answer'] ?? '')
        ];

        $result = $signupService->registerUser($postData);

        $_SESSION['temp_user_id'] = $result['user_id'];
        $_SESSION['account_id'] = $result['account_id'];
        $_SESSION['temp_username'] = $result['username'];

        header("Location: set_password.php");
        exit;

    } catch (Exception $e) {
        error_log("Signup Error: " . $e->getMessage());
        $error = "Signup failed: " . htmlspecialchars($e->getMessage());
    }
}
