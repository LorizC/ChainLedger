<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$accountId = (int)$_SESSION['user']['account_id'];

// ✅ Handle profile update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newAvatar   = trim($_POST['avatar']);

    if ($newUsername !== '') {
        $stmt = $conn->prepare("UPDATE users SET username = ?, profile_image = ? WHERE account_id = ?");
        $stmt->bind_param("ssi", $newUsername, $newAvatar, $accountId);
        $stmt->execute();
        $stmt->close();
    }

    // Update session immediately
    $_SESSION['user']['username'] = $newUsername;
    $_SESSION['user']['profile_image'] = $newAvatar;

    // Redirect back to profile page
    header("Location: profile.php");
    exit();
}

// FETCH USER INFO
$userData = $userRepo->findWithRoleByAccountId($accountId);
if (!$userData) {
    die("User not found.");
}

$totalSpending = $userRepo->getTotalSpendingByAccountId($accountId);

// Transactions
$transactions = [];
// Pagination setup
$limit = 9; // Max transactions per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Count total transactions for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM transactions WHERE account_id = ?");
$countStmt->bind_param("i", $accountId);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'] ?? 0;
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// Fetch paginated transactions
$stmt = $conn->prepare("
    SELECT t.transaction_id, t.amount, t.merchant, t.transaction_date
    FROM transactions t
    WHERE t.account_id = ?
    ORDER BY t.transaction_date DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $accountId, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = [
        'name' => $userData['first_name'] . ' ' . $userData['last_name'],
        'merchant' => $row['merchant'],
        'amount' => '₱' . number_format(abs($row['amount']), 2),
        'transaction_date' => date('m-d-Y', strtotime($row['transaction_date']))
    ];
}

$stmt->close();

$user = [
    'name'        => $userData['first_name'] . ' ' . $userData['last_name'],
    'account_id'  => $userData['account_id'],
    'role'        => $userData['company_role'] ?? 'Unassigned',
    'birthdate'   => date('m-d-Y', strtotime($userData['birthdate'] ?? '')),
    'registered'  => date('m-d-Y', strtotime($userData['date_registered'] ?? '')),
    'spending'    => '₱' . number_format($totalSpending, 2),
];

$currentAvatar = $userData['profile_image'] ?: '../../assets/images/user/profile.png';
?>
