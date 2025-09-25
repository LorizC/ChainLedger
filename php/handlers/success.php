<?php
session_start();
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$user = null;
$account_id = $_SESSION['account_id'] ?? '';
$username   = $_SESSION['username'] ?? '';

if (isset($_SESSION['user_id'])) {
    $user = $userRepo->getUserById($_SESSION['user_id']);
}
