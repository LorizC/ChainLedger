<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../../repositories/UserRepository.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$accountId = (int)$_SESSION['user']['account_id'];

//  Handle profile update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newAvatar   = trim($_POST['avatar']);
    
    // New fields for first and last name
    $newFirstName = trim($_POST['first_name'] ?? '');
    $newLastName  = trim($_POST['last_name'] ?? '');

    // Update query includes first_name and last_name now
    if ($newUsername !== '') {
        $stmt = $conn->prepare("
            UPDATE users 
            SET username = ?, profile_image = ?, first_name = ?, last_name = ?
            WHERE account_id = ?
        ");
        $stmt->bind_param("ssssi", $newUsername, $newAvatar, $newFirstName, $newLastName, $accountId);
        $stmt->execute();
        $stmt->close();
    }

    // Update session immediately
    $_SESSION['user']['username'] = $newUsername;
    $_SESSION['user']['profile_image'] = $newAvatar;
    $_SESSION['user']['first_name'] = $newFirstName;
    $_SESSION['user']['last_name'] = $newLastName;

    // Redirect back to profile page
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    header("Location: profile.php?page=$page");
    exit();
}
// FETCH USER INFO
$userData = $userRepo->findWithRoleByAccountId($accountId);
if (!$userData) {
    header("Location: ../../pages.php?error=user_not_found");
    exit();
}

$user = [
    'first_name'  => $userData['first_name'],
    'last_name'   => $userData['last_name'],
    'gender'      => $userData['gender'],
    'name'        => $userData['first_name'] . ' ' . $userData['last_name'],
    'account_id'  => $userData['account_id'],
    'role'        => $userData['company_role'] ?? 'Unassigned',
    'birthdate'   => date('m-d-Y', strtotime($userData['birthdate'] ?? '')),
    'registered'  => date('m-d-Y', strtotime($userData['date_registered'] ?? ''))
];

$currentAvatar = $userData['profile_image'] ?: '../../assets/images/user/profile.png';
// ==========================
// COUNT BUSINESS ROLES
// ==========================
$countQuery = $conn->prepare("
    SELECT 
        SUM(company_role = 'Business Owner') AS owners,
        SUM(company_role = 'Manager') AS managers,
        SUM(company_role = 'Staff') AS staffs
    FROM company_personnel
");
$countQuery->execute();
$counts = $countQuery->get_result()->fetch_assoc();

// Default to 0 if null
$ownersCount = $counts['owners'] ?? 0;
$managersCount = $counts['managers'] ?? 0;
$staffsCount = $counts['staffs'] ?? 0;

?>
