<?php
// ------------------------------
// I-enable ang error reporting (para sa debugging)
// ------------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ------------------------------
// Simulan ang session (❗MAHALAGA para gumana ang $_SESSION)
// ------------------------------
session_start();

// ------------------------------
// Isama ang database connection file
// ------------------------------
// Gumamit tayo ng absolute path para siguradong mahanap kahit saan tawagin
require_once __DIR__ . '/../../database/dbconfig.php';

// ------------------------------
// Kunin ang database connection
// ------------------------------
$conn = Database::getConnection();

// ------------------------------
// Suriin kung na-submit ang form
// ------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kunin lahat ng data galing sa form
    $details = $_POST['details'];
    $category = $_POST['category'];
    $merchant = $_POST['merchant'];
    $amount = $_POST['amount'];
    $transaction_date = $_POST['date']; // user input (mm/dd/yyyy format)
    $transaction_type = $_POST['details']; // depende sa "details" kung PAYMENT, etc.
    
    // ✅ Na-update: Kunin ang status mula sa form (default to 'completed' if empty/not selected)
    $status = !empty($_POST['status']) ? $_POST['status'] : 'completed';

    // ✅ Kunin mula sa nested session array
    $username = $_SESSION['user']['username'] ?? 'guest';
    $account_id = $_SESSION['user']['account_id'] ?? 0;

    // ------------------------------
    // I-convert ang date format (from mm/dd/yyyy to yyyy-mm-dd hh:mm:ss)
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
    // Status is already included in the INSERT and will be saved to the database.
    // ------------------------------
    $sql = "INSERT INTO transactions 
                (account_id, username, detail, merchant, amount, transaction_date, currency, transaction_type, status)
            VALUES (?, ?, ?, ?, ?, ?, 'PHP', ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ SQL Prepare Failed: " . $conn->error);
    }

    // Bind parameters (status is the last one, already bound correctly)
    $stmt->bind_param("isssdsss", $account_id, $username, $category, $merchant, $amount, $transaction_date, $transaction_type, $status);

    // Execute and check if success
    if ($stmt->execute()) {
        // Optional: I-log ang inserted status para sa debugging (tanggalin sa production)
        error_log("Transaction saved with status: " . $status);
        
        header("Location: ../../forms/main/report.php?success=1");
        exit;
    } else {
        echo "❌ Error saving transaction: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
