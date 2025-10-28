<?php
require_once __DIR__ . '/../services/AuthGuard.php';
require_once __DIR__ . '/handlers/dashboard.php';

// Only allow logged-in users who are Staff
auth_guard(['Staff']);

$role = strtolower(trim($_SESSION['user']['company_role'] ?? ''));

// Restrict to staff only
if ($role !== 'staff') {
    header("Location: /ChainLedger/pages.php");
    exit;
}
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr">
<head>
    <title>ChainLedger | Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Transaction summary and breakdown" />
    <meta name="author" content="Sniper 2025" />

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
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
    <!-- [ Pre-loader ] end -->

    <!-- [ Sidebar Menu ] start -->
    <?php include '../includes/sidebar.php'; ?>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <?php include '../includes/header.php'; ?>
    <!-- [ Header Topbar ] end -->

    <!-- [ Main Content ] start -->
    <main class="pc-container">
        <div class="pc-content">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../staffs/dashboard.php">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Transactions</li>
                    </ul>
                </div>
            </div>

            <!-- Flash & Status Messages -->
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 truncate" 
                     title="<?= htmlspecialchars($_SESSION['flash_success']); ?>">
                    <?= htmlspecialchars($_SESSION['flash_success']); ?>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <!-- Summary Cards -->
            <section class="relative mt-6 mb-8">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="card p-6 text-center">
                            <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">receipt_long</span>
                            <h6 class="text-gray-500 mb-2">Total Transactions</h6>
                            <h3 class="text-2xl font-semibold text-blue-600"><?= number_format($totalTransactions) ?></h3>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="card p-6 text-center">
                            <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">trending_up</span>
                            <h6 class="text-gray-500 mb-2">Total Gains</h6>
                            <h3 class="text-2xl font-semibold text-green-600">₱<?= number_format($totalGains, 2) ?></h3>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="card p-6 text-center">
                            <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">trending_down</span>
                            <h6 class="text-gray-500 mb-2">Total Costs</h6>
                            <h3 class="text-2xl font-semibold text-red-600">₱<?= number_format($totalCosts, 2) ?></h3>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="card p-6 text-center">
                            <span class="material-icons-outlined text-gray-500 mb-1 block text-lg">account_balance_wallet</span>
                            <h6 class="text-gray-500 mb-2">Net Balance</h6>
                            <h3 class="text-2xl font-semibold <?= $netBalance >= 0 ? 'text-green-600' : 'text-red-600' ?> mb-0">
                                ₱<?= number_format($netBalance, 2) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </section>

            <!-- My Transactions Table -->
            <section class="content mt-8">
                <div class="card table-card">
                    <div class="card-header flex justify-between items-center">
                        <h5>My Transactions</h5>
                        <a href="#" onclick="exportTableToText('myTransactionsTable', 'my_transactions.txt')" 
                           class="text-primary-500 text-sm flex items-center">
                            <i data-feather='download' class="w-4 h-4 mr-1"></i> Export
                        </a>
                    </div>

                    <div class="card-body overflow-x-auto">
                        <table id="myTransactionsTable" class="table table-hover w-full">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Details</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-gray-500 italic">No Transactions Yet</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $tx): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tx['detail'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($tx['merchant'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                                    <?= htmlspecialchars($tx['transaction_type'] ?? 'Unknown') ?>
                                                </span>
                                            </td>
                                            <td class="<?= ($tx['amount'] < 0 || in_array($tx['transaction_type'], ['WITHDRAWAL', 'TRANSFER', 'PAYMENT']))
                                                ? 'text-red-500 font-semibold'
                                                : 'text-green-500 font-semibold' ?>">
                                                <?= ($tx['currency'] === 'PHP' ? '₱' : $tx['currency']) . number_format(abs($tx['amount']), 2) ?>
                                            </td>
                                            <td>
                                                <span class="px-2 py-1 rounded text-xs 
                                                    <?= $tx['status'] === 'COMPLETED' ? 'bg-green-100 text-green-800' :
                                                        ($tx['status'] === 'PENDING' ? 'bg-yellow-100 text-yellow-800' :
                                                        ($tx['status'] === 'FAILED' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                                    <?= ($tx['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $tx['formatted_date'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Recent Transactions Table -->
            <section class="content mt-8">
                <div class="card table-card">
                    <div class="card-header flex justify-between items-center">
                        <h5>Recent Transactions</h5>
                        <a href="#" onclick="exportTableToText('transactionsTable', 'transactions.txt')" 
                           class="text-primary-500 text-sm flex items-center">
                            <i data-feather='download' class="w-4 h-4 mr-1"></i> Export
                        </a>
                    </div>

                    <div class="card-body overflow-x-auto">
                        <table id="transactionsTable" class="table table-hover w-full">
                            <thead>
                                <tr>
                                    <th>Transaction By</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentTransactions)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500 italic">No Transactions Yet</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentTransactions as $tx): ?>
                                        <tr>
                                            <td class="max-w-[180px] truncate" title="<?= htmlspecialchars($tx['fullname']) ?>">
                                                <?= htmlspecialchars($tx['fullname']) ?>
                                            </td>
                                            <td><?= $tx['detail'] ?></td>
                                            <td>
                                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                                    <?= $tx['category'] ?>
                                                </span>
                                            </td>
                                            <td class="<?= $tx['is_negative'] ? 'text-red-500 font-semibold' : 'text-green-500 font-semibold' ?>">
                                                <?= $tx['formatted_amount'] ?>
                                            </td>
                                            <td>
                                                <span class="px-2 py-1 rounded text-xs 
                                                    <?= $tx['status'] === 'COMPLETED' ? 'bg-green-100 text-green-800' :
                                                        ($tx['status'] === 'FAILED' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') ?>">
                                                    <?= ($tx['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $tx['formatted_date'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

    <!-- Export Function -->
    <script>
        function exportTableToText(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) return alert("Table not found!");

            let text = "";
            const rows = table.querySelectorAll("tr");
            rows.forEach((row) => {
                const cols = row.querySelectorAll("th, td");
                const rowText = Array.from(cols)
                    .map(col => col.innerText.trim())
                    .join(" | ");
                text += rowText + "\n";
            });

            const blob = new Blob([text], { type: "text/plain" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        }
    </script>

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

    <script>
        // Pagination Function
        function paginateTable(tableId, rowsPerPage = 5) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            if (totalRows <= rowsPerPage) return;

            let currentPage = 1;

            const paginationContainer = document.createElement("div");
            paginationContainer.className = "flex items-center justify-center gap-3 mt-4";

            const prevBtn = document.createElement("button");
            prevBtn.textContent = "Previous";
            prevBtn.className = "px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed";

            const nextBtn = document.createElement("button");
            nextBtn.textContent = "Next";
            nextBtn.className = "px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed";

            const pageInfo = document.createElement("span");
            pageInfo.className = "text-sm text-gray-600";

            paginationContainer.appendChild(prevBtn);
            paginationContainer.appendChild(pageInfo);
            paginationContainer.appendChild(nextBtn);
            table.parentElement.appendChild(paginationContainer);

            function renderTable() {
                tbody.innerHTML = "";
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                rows.slice(start, end).forEach(row => tbody.appendChild(row));

                pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
            }

            prevBtn.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });

            nextBtn.addEventListener("click", () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });

            renderTable();
        }

        document.addEventListener("DOMContentLoaded", () => {
            paginateTable("myTransactionsTable", 5);
        });
    </script>
</body>
</html>
