<?php
session_start();
require_once __DIR__ . '/../db/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/PasswordService.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);
$passwordService = new PasswordService($userRepo);


?>