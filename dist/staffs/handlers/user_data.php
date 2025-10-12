<?php
session_start();
require_once __DIR__ . '/../../database/dbconfig.php';

$db = new Database();
$conn = $db->getConnection();  // ✅ this ensures $conn exists


// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: html/usercreation/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch username, first name, and last name
$stmt = $conn->prepare("SELECT username, first_name, last_name FROM Users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    $user = ["username" => "guest", "first_name" => "Guest", "last_name" => ""];
}
$total_balance  = 432568;
$total_change   = 245860;
$total_expenses = 25.35;
$total_income   = 22.56;
$balance_trend  = 221478;

$monthly_expenses = [
    ["name" => "Food",       "amount" => 1200, "percent" => 20, "color" => "bg-orange-500"],
    ["name" => "Transport",  "amount" => 1200, "percent" => 15, "color" => "bg-yellow-500"],
    ["name" => "Healthcare", "amount" => 1200, "percent" => 18, "color" => "bg-red-400"],
    ["name" => "Education",  "amount" => 1200, "percent" => 22, "color" => "bg-green-500"],
    ["name" => "Clothes",    "amount" => 1200, "percent" => 10, "color" => "bg-blue-500"],
    ["name" => "Pets",       "amount" => 1200, "percent" => 15, "color" => "bg-purple-500"],
];

$menu = [
    ["icon" => "home",       "label" => "Home"],
    ["icon" => "credit-card","label" => "Wallet"],
    ["icon" => "dollar-sign","label" => "Budget"],
    ["icon" => "target",     "label" => "Goals"],
    ["icon" => "user",       "label" => "Profile"],
    ["icon" => "bar-chart-2","label" => "Analytics"],
    ["icon" => "file-text",  "label" => "Reports"],
];



