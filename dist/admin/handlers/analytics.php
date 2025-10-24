<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../database/dbconfig.php';

if (!isset($_SESSION['user'])) {
    header("Location: /ChainLedger-System-/index.php");
    exit();
}
$account_id = $_SESSION['user']['account_id'] ?? null;
$role = $_SESSION['user']['role'] ?? null;


$conn = Database::getConnection();
// --- CHECK IF THERE ARE ANY TRANSACTIONS ---
$total_transactions_query = "SELECT COUNT(*) AS total_txn FROM transactions";
$total_transactions_result = $conn->query($total_transactions_query);
$total_transactions = 0;

if ($total_transactions_result && $total_transactions_result->num_rows > 0) {
    $row = $total_transactions_result->fetch_assoc();
    $total_transactions = (int)$row['total_txn'];
}
$total_transactions_result->close();

$has_transactions = $total_transactions > 0;


// --- MERCHANTS ---
$merchants = [];
$sql_merchants = "
    SELECT 
        m.merchant,
        COALESCE(SUM(ABS(t.amount)), 0) AS total_amount,
        COUNT(t.transaction_id) AS count
    FROM (
        SELECT 'Gcash' AS merchant
        UNION SELECT 'Maya'
        UNION SELECT 'Grabpay'
        UNION SELECT 'Paypal'
        UNION SELECT 'Googlepay'
    ) AS m
    LEFT JOIN transactions t 
        ON t.merchant = m.merchant
    GROUP BY m.merchant
    ORDER BY total_amount DESC
";

$result_merchants = $conn->query($sql_merchants);
if ($result_merchants && $result_merchants->num_rows > 0) {
    while ($row = $result_merchants->fetch_assoc()) {
        $merchants[] = [
            'title' => $row['merchant'] ?? 'Unknown',
            'value' => '₱' . number_format($row['total_amount'], 2),
            'image' => getMerchantImage($row['merchant']),
            'count' => $row['count']
        ];
    }
} else {
    $merchants[] = [
        'title' => 'No merchants yet',
        'value' => '₱0.00',
        'image' => '../assets/images/ewallets/default.png',
        'count' => 0
    ];
}
$result_merchants->close();

// --- CATEGORIES ---
$categories = [];
$sql_categories = "
    SELECT 
        d.detail AS detail,
        COALESCE(SUM(ABS(t.amount)), 0) AS total_amount,
        COUNT(t.transaction_id) AS count
    FROM (
        SELECT 'Food' AS detail
        UNION ALL SELECT 'Equipment'
        UNION ALL SELECT 'Transportation'
        UNION ALL SELECT 'Health'
        UNION ALL SELECT 'Maintenance'
        UNION ALL SELECT 'Utilities'
    ) AS d
    LEFT JOIN transactions t 
        ON LOWER(t.detail) = LOWER(d.detail)
    GROUP BY d.detail
    ORDER BY total_amount DESC
";

$result_categories = $conn->query($sql_categories);
if ($result_categories && $result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $cat_title = ucfirst(strtolower($row['detail'] ?? 'Unknown'));

        $categories[] = [
            'title' => $cat_title,
            'value' => '₱' . number_format((float)$row['total_amount'], 2),
            'color' => getCategoryColor($cat_title),
            'icon' => getCategoryIcon($cat_title),
            'count' => (int)$row['count'],
            'numeric_value' => (float)$row['total_amount']
        ];
    }
} else {
    $categories[] = [
        'title' => 'No categories yet',
        'value' => '₱0.00',
        'color' => 'bg-gray-100 text-gray-600',
        'icon' => 'category',
        'count' => 0,
        'numeric_value' => 0
    ];
}

$result_categories->close();
// --- TRANSACTION TYPES ---
$transaction_types = [];
$sql_transaction_types = "
    SELECT 
        t.transaction_type AS type,
        COALESCE(SUM(ABS(t.amount)), 0) AS total_amount,
        COUNT(t.transaction_id) AS count
    FROM transactions t
    GROUP BY t.transaction_type
    ORDER BY total_amount DESC
";

$result_transaction_types = $conn->query($sql_transaction_types);
if ($result_transaction_types && $result_transaction_types->num_rows > 0) {
    while ($row = $result_transaction_types->fetch_assoc()) {
        $transaction_types[] = [
            'type' => ucfirst($row['type'] ?? 'Unknown'),
            'value' => '₱' . number_format((float)$row['total_amount'], 2),
            'count' => (int)$row['count'],
            'numeric_value' => (float)$row['total_amount']
        ];
    }
} else {
    $transaction_types[] = [
        'type' => 'No transactions yet',
        'value' => '₱0.00',
        'count' => 0,
        'numeric_value' => 0
    ];
}
$result_transaction_types->close();

// --- MONTHLY SUMMARY DESCRIPTION (COLOR-CODED) ---
$latest_month_label = isset($monthly_labels) && is_array($monthly_labels) && !empty($monthly_labels)
    ? end($monthly_labels)
    : date('M Y');

// Define consistent Tailwind text colors for highlights
$colors = [
    'Food' => 'text-purple-600',
    'Utilities' => 'text-red-600',
    'Transportation' => 'text-blue-600',
    'Equipment' => 'text-yellow-600',
    'Health' => 'text-green-600',
    'Maintenance' => 'text-orange-600',
    'Merchant' => 'text-indigo-600',
    'Type' => 'text-pink-600'
];

$highest_merchant = $merchants[0]['title'] ?? 'N/A';
$highest_category = $categories[0]['title'] ?? 'N/A';
$highest_type = $transaction_types[0]['type'] ?? 'N/A';

$top_merchant_value = $merchants[0]['value'] ?? '₱0.00';
$top_category_value = $categories[0]['value'] ?? '₱0.00';
$top_type_value = $transaction_types[0]['value'] ?? '₱0.00';

// Get color for category dynamically
$category_color = $colors[$highest_category] ?? 'text-gray-600';

if ($has_transactions) {
    // When there ARE transactions
    $monthly_summary = "
      In <span class='font-semibold text-gray-900 dark:text-gray-100'>{$latest_month_label}</span>, 
      the highest recorded transaction merchant was 
      <strong class='{$colors['Merchant']}'>{$highest_merchant}</strong> 
      with a total of 
      <strong class='{$colors['Merchant']}'>{$top_merchant_value}</strong>. 
      The leading category was 
      <strong class='{$category_color}'>{$highest_category}</strong> 
      totaling 
      <strong class='{$category_color}'>{$top_category_value}</strong>, 
      while the most common transaction type was 
      <strong class='{$colors['Type']}'>{$highest_type}</strong> 
      with 
      <strong class='{$colors['Type']}'>{$top_type_value}</strong> 
      in value.<br> 
      Overall, ChainLedger recorded steady financial activity across all categories, merchants, and transaction types.
    ";
} else {
    // When there are NO transactions
    $monthly_summary = "
      <span class='text-gray-600 dark:text-gray-400 italic'>
        No monthly transactions yet. Summary will appear once data is available.
      </span>
    ";
}



// --- MONTHLY LINE CHART (LAST 6 MONTHS) ---
$monthly_labels = [];
$monthly_values = [];
$sql_monthly = "SELECT DATE_FORMAT(transaction_date, '%Y-%m') as month, SUM(ABS(amount)) as total 
                FROM transactions 
                WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month 
                ORDER BY month ASC";
$result_monthly = $conn->query($sql_monthly);
if ($result_monthly && $result_monthly->num_rows > 0) {
    while ($row = $result_monthly->fetch_assoc()) {
        $monthly_labels[] = date('M Y', strtotime($row['month'] . '-01'));
        $monthly_values[] = (float)$row['total'];
    }
} else {
    // Fallback: last 6 months labels with 0 values
    for ($i=5; $i>=0; $i--) {
        $month = date('M Y', strtotime("-$i month"));
        $monthly_labels[] = $month;
        $monthly_values[] = 0;
    }
}
$result_monthly->close();

// --- PIE CHART ---
$pie_labels = array_column($categories, 'title');
$pie_values = array_column($categories, 'numeric_value');

$conn->close();



// --- HELPER FUNCTIONS ---
function getMerchantImage($merchant) {
    $images = [
        'Gcash' => '../assets/images/ewallets/gcash1.jpg',
        'Googlepay' => '../assets/images/ewallets/googlepay.png',
        'Grabpay' => '../assets/images/ewallets/grabpay.jpeg',
        'Maya' => '../assets/images/ewallets/maya.png',
        'Paypal' => '../assets/images/ewallets/paypal.png'
    ];
    $path = $images[$merchant] ?? '../assets/images/ewallets/default.png';
    return file_exists($path) ? $path : '../assets/images/ewallets/default.png';
}

function getCategoryColor($category) {
    $colors = [
        'Food' => 'bg-purple-100 text-purple-600',
        'Utilities' => 'bg-red-100 text-red-600',
        'Transportation' => 'bg-blue-100 text-blue-600',
        'Equipment' => 'bg-yellow-100 text-yellow-600',
        'Health' => 'bg-green-100 text-green-600',
        'Maintenance' => 'bg-orange-100 text-orange-600'
    ];
    $category = ucfirst(strtolower($category));
    return $colors[$category] ?? 'bg-gray-100 text-gray-600';
}

function getCategoryIcon($category) {
    $icons = [
        'Food' => 'restaurant',
        'Utilities' => 'bolt',
        'Transportation' => 'flight',
        'Equipment' => 'build',
        'Health' => 'healing',
        'Maintenance' => 'engineering'
    ];
    $category = ucfirst(strtolower($category));
    return $icons[$category] ?? 'category';
}
?>
