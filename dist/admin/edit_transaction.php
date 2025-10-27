<?php
require_once __DIR__ . '/../services/AuthGuard.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../database/dbconfig.php';

// Restrict access
auth_guard(['Business Owner', 'Manager']);
$conn = Database::getConnection();
// Initialize UserRepository
$userRepo = new UserRepository($conn);

// FETCH USER INFO
$accountId = $_SESSION['user']['account_id'] ?? null;
$userData = $userRepo->findWithRoleByAccountId($accountId);
if (!$userData) {
    header("Location: /ChainLedger/pages.php?error=user_not_found");
    exit();
}

// Fetch transaction by ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id = (int)$_GET['id'];

  $stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $transaction = $result->fetch_assoc();
  } else {
    header("Location: dashboard.php?error=not_found");
    exit();
  }
  $stmt->close();
} else {
  header("Location: dashboard.php?error=invalid_id");
  exit();
}
?>

<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr">

<head>
  <title>ChainLedger | Edit Transaction</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Transaction summary and breakdown" />
  <meta name="author" content="Sniper 2025" />

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">  
  <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
  <link rel="stylesheet" href="../assets/fonts/feather.css" />
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../assets/fonts/material.css" />

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />
  <!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
    <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
      <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <!-- [ Sidebar Menu ] start -->
  <?php include '../includes/sidebar.php'; ?>
  <!-- [ Sidebar Menu ] end -->

  <!-- [ Header Topbar ] start -->
  <?php include '../includes/header.php'; ?>
  <!-- [ Header Topbar ] end -->

  <div class="pc-container">
    <div class="pc-content">
      <div class="page-header">
        <div class="page-block">
          <div class="page-header-title">
            <h5 class="mb-0 font-medium">Edit Transaction</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Edit Transaction</li>
          </ul>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
          <div class="card">
            <div class="card-header">
              <h5>Update Transaction Details (<span style="color:red;font-weight:bold">*</span> Required)</h5>
            </div>
            <div class="card-body">
              <form action="handlers/edit_transaction.php" method="POST" class="form-horizontal">
                <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">

                <!-- Transaction Type -->
                <div class="mb-3">
                  <label for="transaction_type" class="form-label">Transaction Type <span style="color:red">*</span></label>
                  <select name="transaction_type" id="transaction_type" class="form-control" required>
                    <option value="">Select Transaction Type</option>
                    <?php
                      $types = ['PAYMENT', 'REFUND', 'WITHDRAWAL', 'DEPOSIT', 'TRANSFER'];
                      foreach ($types as $type) {
                        $selected = ($transaction['transaction_type'] === $type) ? 'selected' : '';
                        echo "<option value='$type' $selected>$type</option>";
                      }
                    ?>
                  </select>
                </div>

                <!-- Category -->
                <div class="mb-3">
                  <label for="category" class="form-label">Category <span style="color:red">*</span></label>
                  <select name="category" id="category" class="form-control" required>
                    <?php
                      $categories = ['Equipment', 'Food', 'Health', 'Maintenance', 'Utilities', 'Transportation'];
                      foreach ($categories as $cat) {
                        $selected = ($transaction['detail'] === $cat) ? 'selected' : '';
                        echo "<option value='$cat' $selected>$cat</option>";
                      }
                    ?>
                  </select>
                </div>

                <!-- Merchant -->
                <div class="mb-3">
                  <label for="merchant" class="form-label">Merchant <span style="color:red">*</span></label>
                  <select name="merchant" id="merchant" class="form-control" required>
                    <?php
                      $merchants = ['Gcash', 'Googlepay', 'Grabpay', 'Maya', 'Paypal'];
                      foreach ($merchants as $m) {
                        $selected = ($transaction['merchant'] === $m) ? 'selected' : '';
                        echo "<option value='$m' $selected>$m</option>";
                      }
                    ?>
                  </select>
                </div>

                <!-- Amount -->
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount <span style="color:red">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" step="0.01" min="0" name="amount" id="amount"
                      value="<?= htmlspecialchars($transaction['amount']) ?>" class="form-control" required>
                  </div>
                </div>

                <!-- Date -->
                <div class="mb-3">
                  <label for="transaction_date" class="form-label">Date <span style="color:red">*</span></label>
                  <input type="date" name="transaction_date" id="transaction_date"
                    value="<?= htmlspecialchars($transaction['transaction_date']) ?>" class="form-control"min="<?= date('1900-m-d') ?>" max="<?= date('Y-m-d') ?>"  required>
                </div>


                <!-- Buttons -->
                <div class="flex mt-1 justify-between items-center flex-wrap">
                  <div class="form-check">
                    <a href="../admin/dashboard.php" class="btn btn-danger mx-auto shadow-2xl">Cancel</a>
                    <button type="submit" name="submit_edit" class="btn btn-success mx-auto shadow-2xl">Update Transaction</button>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <?php include '../includes/footer.php'; ?>
    <!-- Required JS -->
  <script src="../assets/js/plugins/simplebar.min.js"></script>
  <script src="../assets/js/plugins/popper.min.js"></script>
  <script src="../assets/js/icon/custom-icon.js"></script>
  <script src="../assets/js/plugins/feather.min.js"></script>
  <script src="../assets/js/component.js"></script>
  <script src="../assets/js/theme.js"></script>
  <script src="../assets/js/script.js"></script>

  <script>
    layout_change('false');
    layout_theme_sidebar_change('dark');
    change_box_container('false');
    layout_caption_change('true');
    layout_rtl_change('false');
    preset_change('preset-1');
    main_layout_change('vertical');
    feather.replace();
  </script>
</body>
</html>
