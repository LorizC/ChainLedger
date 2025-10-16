<?php
// ==========================
// registerbusiness.php
// ==========================
require_once __DIR__ . '/../database/dbconfig.php';
require_once __DIR__ . '/../repositories/UserRepository.php';

$conn = Database::getConnection();
$error = ''; // Default error message holder

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_name = trim($_POST['business_name'] ?? '');
    $business_industry = trim($_POST['business_industry'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ===========================
    // Validation
    // ===========================
    if (empty($business_name) || empty($business_industry) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // ===========================
        // Check for duplicate business
        // ===========================
        $check_query = "SELECT * FROM registered_businesses WHERE business_name = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $business_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Business name already exists. Please choose another.";
        } else {
            // ===========================
            // Generate Unique Business ID
            // ===========================
            $business_id = random_int(100000, 999999);

            // Make sure ID is unique
            $check_id_query = "SELECT business_id FROM registered_businesses WHERE business_id = ?";
            $check_stmt = $conn->prepare($check_id_query);
            $check_stmt->bind_param("i", $business_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            while ($check_result->num_rows > 0) {
                $business_id = random_int(100000, 999999);
                $check_stmt->bind_param("i", $business_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
            }

            $check_stmt->close();

            // ===========================
            // Hash Password
            // ===========================
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ===========================
            // Insert Business Record
            // ===========================
            $insert_query = "
                INSERT INTO registered_businesses (business_id, business_name, business_industry, password)
                VALUES (?, ?, ?, ?)
            ";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isss", $business_id, $business_name, $business_industry, $hashed_password);

            if ($stmt->execute()) {
                    header("Location: registration_success.php?business_id=" . urlencode($business_id));
                exit();
            } else {
                $error = "Database error: Could not register business.";
            }

            $stmt->close();
        }
    }
}
?>
