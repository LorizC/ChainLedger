<!--use include '../php/handlers/ledger.php'; ?> to connect php to html -->
<?php 
$ledger = [
  ["user" => "Loriz Carlos", "details" => "Food", "merchant" => "GrabPay", "amount" => "-₱2,255,555.55", "date" => "16-8"],
  ["user" => "Mii Lee", "details" => "Refund", "merchant" => "GCash", "amount" => "-₱900,000.00", "date" => "16-7"],
  ["user" => "Sarah Dicaya", "details" => "Equipment", "merchant" => "Maya", "amount" => "-₱10,000,000.00", "date" => "16-7"],
  ["user" => "Dave Smith", "details" => "Loan", "merchant" => "Paypal", "amount" => "+₱15,000.00", "date" => "16-6"],
  ["user" => "Jon Weak", "details" => "Loan", "merchant" => "Google Pay", "amount" => "+₱10,000.00", "date" => "16-5"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaction Ledger</title>
  <link rel="stylesheet" href="css/main.css">

  <!-- Alpine.js -->
  <script src="https://unpkg.com/alpinejs" defer></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>

  <!-- Icons & Charts -->
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

  <!-- Custom Theme Logic -->
  <script src="../../js/user.js"></script>  
</head>
<body>
  <!-- Sidebar -->
  <?php include './includes/sidebar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Header -->
    <?php include './includes/header.php'; ?>

    <!-- Title & Welcome -->
    <div class="title-block">
      <h1>ChainLedger History</h1>
      <p>Welcome to Transaction Ledger</p>
    </div>

    <section class="content">
      <div class="ledger-section">
        <div class="ledger-wrapper">
          <table class="ledger-table">
            <thead>
              <tr>
                <th>Transaction by</th>
                <th>Details</th>
                <th>Merchant</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($ledger as $row): ?>
              <tr>
                <td class="user-cell">
                  <img src="../../images/avatars/profile1.png" alt="Profile" class="user-icon">
                  <?= $row["user"] ?>
                </td>
                <td><?= $row["details"] ?></td>
                <td class="merchant <?= strtolower(str_replace(' ', '', $row['merchant'])) ?>">
                  <?= $row["merchant"] ?>
                </td>
                <td class="amount <?= strpos($row["amount"], '-') !== false ? 'negative' : 'positive' ?>">
                  <?= $row["amount"] ?>
                </td>
                <td><?= $row["date"] ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
