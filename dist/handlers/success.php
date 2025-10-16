<?php
// success.php
session_start();
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

$conn = Database::getConnection();
$userRepo = new UserRepository($conn);

$error = "";

$username = "N/A";
$accountID = "000000";

//  Use stored session info first
if (isset($_SESSION['success_account_id']) && isset($_SESSION['success_username'])) {
    $username = htmlspecialchars($_SESSION['success_username']);
    $accountID = htmlspecialchars($_SESSION['success_account_id']);
} else {
    // fallback if no session
    $query = "SELECT username, account_id FROM users ORDER BY user_id DESC LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = htmlspecialchars($user['username']);
        $accountID = htmlspecialchars($user['account_id']);
    }
}
$business_id = $_GET['business_id'] ?? null;
$business_name = '';
$business_industry = '';
$date_registered = '';

if ($business_id) {
    $stmt = $conn->prepare("SELECT business_name, business_industry, date_registered FROM registered_businesses WHERE business_id = ?");
    $stmt->bind_param("i", $business_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $business_name = htmlspecialchars($row['business_name']);
        $business_industry = htmlspecialchars($row['business_industry']);
        $date_registered = htmlspecialchars(date('F j, Y, g:i A', strtotime($row['date_registered'])));
    } else {
        $business_name = "Unknown Business";
        $business_industry = "N/A";
        $date_registered = "N/A";
    }

    $stmt->close();
} else {
    header("Location: ../../index.php");
    exit();
}
?>