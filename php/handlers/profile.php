<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database and repository classes
require_once __DIR__ . '/../db/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

$accountId = (int)$_SESSION['user']['account_id'];
$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

// ✅ Handle profile update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newAvatar   = trim($_POST['avatar']);

    // Basic validation
    if ($newUsername !== '') {
        $stmt = $conn->prepare("UPDATE users SET username = ?, profile_image = ? WHERE account_id = ?");
        $stmt->bind_param("ssi", $newUsername, $newAvatar, $accountId);
        $stmt->execute();
    }

    // Update session to reflect changes immediately
    $_SESSION['user']['username'] = $newUsername;
    $_SESSION['user']['profile_image'] = $newAvatar;

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// =======================
// FETCH USER INFO
// =======================
$userData = $userRepo->findWithRoleByAccountId($accountId);
if (!$userData) {
    die("User not found.");
}

// Get spending total
$totalSpending = $userRepo->getTotalSpendingByAccountId($accountId);

// Transactions
$transactions = [];
$stmt = $conn->prepare("
    SELECT t.transaction_id, t.amount, t.merchant, t.transaction_date
    FROM transactions t
    WHERE t.account_id = ?
    ORDER BY t.transaction_date DESC
    LIMIT 20
");
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $transactions[] = [
        'name'     => $userData['first_name'] . ' ' . $userData['last_name'],
        'merchant' => $row['merchant'],
        'amount'   => '₱' . number_format(abs($row['amount']), 2),
        'transaction_date'     => date('m-d-Y', strtotime($row['transaction_date']))
    ];
}


// User display array
$user = [
    'name'        => $userData['first_name'] . ' ' . $userData['last_name'],
    'account_id'  => $userData['account_id'],
    'role'        => $userData['company_role'] ?? 'Unassigned',
    'birthdate'   => date('m-d-Y', strtotime($userData['birthdate'] ?? '')),
    'registered'  => date('m-d-Y', strtotime($userData['date_registered'] ?? '')),
    'spending'    => '₱' . number_format($totalSpending, 2),
];

$currentAvatar = $userData['profile_image'] ?: '../../images/avatars/profile.png';
$defaultAvatars = [
    "../../images/avatars/profile.png",
    "../../images/avatars/profile2.png",
    "../../images/avatars/profile3.png",
    "../../images/avatars/profile4.png",
    "../../images/avatars/profile5.png"
];
?>
