<?php
// ------------------------------
// Enable error reporting (for debugging)
// ------------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ------------------------------
// Start session (❗IMPORTANT para gumana $_SESSION)
// ------------------------------
session_start();

// ------------------------------
// Include the database connection file
// ------------------------------
// Gumamit tayo ng absolute path para siguradong mahanap kahit saan tawagin
include $_SERVER['DOCUMENT_ROOT'] . '/ChainLedger/html/mainpages/includes/db_connect.php';

// ------------------------------
// Get database connection
// ------------------------------
$conn = Database::getConnection();

// ------------------------------
// Check if form is submitted
// ------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kunin lahat ng data galing sa form
    $details = $_POST['details'];
    $category = $_POST['category'];
    $merchant = $_POST['merchant'];
    $amount = $_POST['amount'];
    $transaction_date = $_POST['date']; // user input (mm/dd/yyyy format)
    $transaction_type = $_POST['details']; // depende sa "details" kung PAYMENT, etc.
    $status = 'COMPLETED'; // default value

    // ✅ Kunin mula sa nested session array
    $username = $_SESSION['user']['username'] ?? 'guest';
    $account_id = $_SESSION['user']['account_id'] ?? 0;

    // ------------------------------
    // Convert date format (from mm/dd/yyyy to yyyy-mm-dd hh:mm:ss)
    // ------------------------------
    $transaction_date = DateTime::createFromFormat('m/d/Y', $transaction_date);
    if ($transaction_date) {
        $transaction_date = $transaction_date->format('Y-m-d H:i:s');
    } else {
        // fallback: kung invalid date, gamitin current date
        $transaction_date = date('Y-m-d H:i:s');
    }

    // ------------------------------
    // SQL Query - INSERT transaction
    // Note: 'entry_date' ay may DEFAULT CURRENT_TIMESTAMP sa database
    // kaya hindi na kailangang isama dito.
    // ------------------------------
    $sql = "INSERT INTO transactions 
                (account_id, username, detail, merchant, amount, transaction_date, currency, transaction_type, status)
            VALUES (?, ?, ?, ?, ?, ?, 'PHP', ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ SQL Prepare Failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("isssdsss", $account_id, $username, $category, $merchant, $amount, $transaction_date, $transaction_type, $status);

    // Execute and check if success
    if ($stmt->execute()) {
        header("Location: ../../html/mainpages/report.php?success=1");
        exit;
    } else {
        echo "❌ Error saving transaction: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
