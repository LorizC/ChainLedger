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
    <!-- [ Pre-loader ] -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full absolute top-0 overflow-hidden">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
        </div>
    </div>

    <!-- Sidebar & Header -->
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/header.php'; ?>

    <!-- Main Content -->
    <main class="pc-container">
        <div class="pc-content">

            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../admin/dashboard.php">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Transactions</li>
                    </ul>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 truncate" title="<?= htmlspecialchars($_SESSION['flash_success']); ?>">
                    <?= htmlspecialchars($_SESSION['flash_success']); ?>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 truncate" title="<?= htmlspecialchars($_SESSION['flash_error']); ?>">
                    <?= htmlspecialchars($_SESSION['flash_error']); ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
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
                            <h3 class="text-2xl font-semibold <?= $netBalance >= 0 ? 'text-green-600' : 'text-red-600' ?>">₱<?= number_format($netBalance, 2) ?></h3>
                        </div>
                    </div>
                </div>
            </section>

            <!-- My Transactions Table -->
            <section class="content mt-8">
                <div class="card table-card">
                    <div class="card-header flex justify-between items-center">
                        <h5>My Transactions</h5>
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
                            <tbody id="myTransactionsTableBody">
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-gray-500 italic">No Transactions Yet</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $index => $tx): ?>
                                        <tr data-index="<?= $index ?>">
                                            <td><?= htmlspecialchars($tx['detail'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($tx['merchant'] ?? 'N/A') ?></td>
                                            <td><span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"><?= htmlspecialchars($tx['transaction_type'] ?? 'Unknown') ?></span></td>
                                            <td class="<?= ($tx['amount'] < 0 || in_array($tx['transaction_type'], ['WITHDRAWAL', 'TRANSFER', 'PAYMENT'])) ? 'text-red-500 font-semibold' : 'text-green-500 font-semibold' ?>">
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
                        <div id="myTransactionsPagination" class="mt-4 flex justify-center space-x-2"></div>
                    </div>
                </div>
            </section>


            <!-- Recent Transactions Table -->
            <section class="content mt-8">
                <div class="card table-card">
                    <div class="card-header flex justify-between items-center">
                        <h5>Recent Transactions</h5>
                        <a href="#" onclick="exportTableToText('transactionsTable', 'transactions.txt')" class="text-primary-500 text-sm flex items-center">
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
                                    <th>Action</th>
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
                                            <td class="max-w-[180px] truncate" title="<?= htmlspecialchars($tx['fullname']) ?>"><?= htmlspecialchars($tx['fullname']) ?></td>
                                            <td><?= $tx['detail'] ?></td>
                                            <td><span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800"><?= $tx['category'] ?></span></td>
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
                                            <td class="flex items-center space-x-3">
                                                <a href="edit_transaction.php?id=<?= $tx['transaction_id'] ?>" class="flex items-center text-blue-600 hover:text-blue-800">
                                                    <span class="material-icons-outlined text-base mr-1">edit</span> Edit
                                                </a>
                                                <a href="handlers/delete_transaction.php?id=<?= $tx['transaction_id'] ?>"
                                                   onclick="return confirm('Are you sure you want to delete this transaction?')"
                                                   class="flex items-center text-red-600 hover:text-red-800">
                                                    <span class="material-icons-outlined text-base mr-1">delete</span> Delete
                                                </a>
                                            </td>
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
document.addEventListener("DOMContentLoaded", () => {
    const perPage = 5;

    // --- Generic Pagination Function ---
    function paginateTable(tableBodyId, paginationContainerId) {
        const rows = document.querySelectorAll(`#${tableBodyId} tr`);
        const totalPages = Math.ceil(rows.length / perPage) || 1;
        let currentPage = 1;
        const paginationContainer = document.getElementById(paginationContainerId);

        function renderPage(page) {
            currentPage = page;
            rows.forEach((row, index) => {
                row.style.display = (index >= (page - 1) * perPage && index < page * perPage) ? "" : "none";
            });
            renderPagination();
        }

        function renderPagination() {
            paginationContainer.innerHTML = "";

            const createButton = (text, disabled, callback, isActive = false) => {
                const btn = document.createElement("button");
                btn.textContent = text;
                btn.disabled = disabled;
                btn.className = `px-3 py-1 border rounded ${isActive ? 'bg-blue-500 text-white' : 'bg-white text-blue-500'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
                btn.addEventListener("click", callback);
                return btn;
            }

            // Prev button
            paginationContainer.appendChild(createButton("Prev", currentPage === 1, () => renderPage(currentPage - 1)));

            // Page buttons
            for (let i = 1; i <= totalPages; i++) {
                paginationContainer.appendChild(createButton(i, false, () => renderPage(i), i === currentPage));
            }

            // Next button
            paginationContainer.appendChild(createButton("Next", currentPage === totalPages, () => renderPage(currentPage + 1)));
        }

        renderPage(1); // Initial render
    }

    // Initialize pagination for both tables
    paginateTable("myTransactionsTableBody", "myTransactionsPagination");
    paginateTable("transactorsTableBody", "paginationControls");
});
</script>

</body>
</html>
