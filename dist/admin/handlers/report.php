<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/dbconfig.php';
$conn = Database::getConnection();

if (isset($_POST['submit'])) {
    // ✅ Get current user info
    $current_username = $_SESSION['user']['username'];

    // Fetch account_id from users table
    $sql_user = "SELECT account_id FROM users WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $current_username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    
    if ($result_user->num_rows === 0) {
        $_SESSION['flash_error'] = "User not found in database.";
        $stmt_user->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    $user_row = $result_user->fetch_assoc();
    $account_id = $user_row['account_id'];
    $stmt_user->close();

    // ✅ Sanitize inputs
    $category           = trim($_POST['category'] ?? '');          // For 'detail' column (e.g. Food, Utilities)
    $merchant           = trim($_POST['merchant'] ?? '');
    $amount             = floatval($_POST['amount'] ?? 0);
    $transaction_type   = trim($_POST['transaction_type'] ?? '');  // PAYMENT, REFUND, etc.
    $status_input       = trim($_POST['status'] ?? '');
    $date_input         = trim($_POST['date'] ?? '');

    // ✅ Date validation (strict YYYY-MM-DD)
    $date = null;
    if (!empty($date_input)) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_input)) {
            $_SESSION['flash_error'] = "Invalid date format. Use YYYY-MM-DD.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        $date_obj = DateTime::createFromFormat('Y-m-d', $date_input);
        if (!$date_obj || $date_obj->format('Y-m-d') !== $date_input) {
            $_SESSION['flash_error'] = "Invalid date provided.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        $date = $date_input;
    } else {
        $_SESSION['flash_error'] = "Date is required.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // ✅ Validation sets
    $valid_categories       = ['Food', 'Equipment', 'Transportation', 'Health', 'Maintenance', 'Utilities'];
    $valid_transaction_types= ['DEPOSIT', 'WITHDRAWAL', 'TRANSFER', 'PAYMENT', 'REFUND'];
    $valid_merchants        = ['Gcash', 'Maya', 'Grabpay', 'Paypal', 'Googlepay'];
    $valid_status           = ['PENDING', 'COMPLETED', 'FAILED', 'CANCELLED'];

    // ✅ Validation checks
    $errors = [];

    if (empty($category) || !in_array($category, $valid_categories)) {
        $errors[] = "Valid Category is required.";
    }

    if (empty($merchant) || !in_array($merchant, $valid_merchants)) {
        $errors[] = "Valid Merchant is required.";
    }

    if ($amount <= 0) {
        $errors[] = "Amount must be greater than 0.";
    }

    if (empty($transaction_type) || !in_array($transaction_type, $valid_transaction_types)) {
        $errors[] = "Valid Transaction Type is required.";
    }

    if (empty($status_input) || !in_array($status_input, $valid_status)) {
        $errors[] = "Valid Status is required.";
    }

    // ✅ Insert if no errors
    if (empty($errors)) {
        $sql = "INSERT INTO transactions 
            (account_id, username, detail, merchant, amount, currency, transaction_date, transaction_type, status)
            VALUES (?, ?, ?, ?, ?, 'PHP', ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                "isssdsss",
                $account_id,
                $current_username,
                $category,           // goes into `detail`
                $merchant,
                $amount,
                $date,
                $transaction_type,
                $status_input
            );

            if ($stmt->execute()) {
                $_SESSION['flash_success'] = "Transaction saved successfully! ₱" . number_format($amount, 2) . " ($transaction_type - $category) on $date.";
            } else {
                $_SESSION['flash_error'] = "Error saving transaction: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['flash_error'] = "Prepare statement error: " . $conn->error;
        }
    } else {
        $_SESSION['flash_error'] = "Validation errors: " . implode(", ", $errors);
    }

    $conn->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
