<?php include '../../php/handlers/user_data.php'; ?>


<!DOCTYPE html>
<html lang="en" x-data="darkTheme()" x-init="init()" x-bind:class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <title>ChainLedger</title>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>

    <!-- Icons & Charts -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Theme Logic -->
    <script src="../js/user.js"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100 flex">

    <!-- ======================
         Sidebar
    ======================= -->
    <aside x-data="{ expanded: false }"
           :class="expanded ? 'w-64' : 'w-20'"
           class="bg-indigo-700 dark:bg-indigo-900 h-screen flex flex-col py-6 space-y-6 transition-all duration-300">

        <!-- Profile / Logo -->
<!-- Profile / Logo -->
<div @click="expanded = !expanded"
     class="flex items-center cursor-pointer"
     :class="expanded ? 'justify-start px-4 space-x-3' : 'justify-center'">
    <img src="images/logo.png" alt="Logo" class="w-12 h-12 rounded-full">

    <span x-show="expanded"
          x-cloak
          x-transition
          class="text-white font-semibold">
        <?= htmlspecialchars($user['username']) ?>
    </span>
</div>


        <!-- Navigation -->
        <nav class="flex flex-col space-y-8 text-white w-full">
            <?php
            $menu = [
                ["icon" => "home",       "label" => "Home"],
                ["icon" => "credit-card","label" => "Transaction Ledger"],
                ["icon" => "bar-chart-2","label" => "Analytics"],
                ["icon" => "file-text",  "label" => "Transaction Reports"],
                ["icon" => "user",       "label" => "Profile"],
            ];
            foreach ($menu as $item): ?>
                <a href="#"
                   class="flex items-center hover:text-indigo-300"
                   :class="expanded ? 'justify-start px-4 space-x-3' : 'justify-center'">
                    <i data-feather="<?= $item['icon'] ?>"></i>
                    <span x-show="expanded" x-cloak x-transition><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- ======================
         Main Content
    ======================= -->
    <main class="flex-1 p-6">

        <!-- Topbar -->
        <div class="flex justify-between items-center mb-6 relative" x-data="{ open: false }">
            <input type="text"
                   placeholder="Search here"
                   class="border px-4 py-2 rounded-lg w-1/3 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">

            <div class="flex space-x-6 items-center text-gray-600 dark:text-gray-300">
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" class="hover:text-indigo-600">
                    <!-- Light Mode Icon -->
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v2m0 14v2m9-9h-2M5 
                                 12H3m15.364-6.364l-1.414 1.414M6.05 
                                 17.95l-1.414 1.414M17.95 17.95l1.414 
                                 1.414M6.05 6.05L4.636 4.636M12 
                                 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Dark Mode Icon -->
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6" fill="currentColor"
                         viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1111.21 
                                 3a7 7 0 109.79 9.79z"/>
                    </svg>
                </button>

                <button class="hover:text-indigo-600"><i data-feather="bell"></i></button>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="open = !open"
                            class="w-10 h-10 bg-indigo-700 rounded-full flex items-center justify-center text-white">
                        <img src="images/profile2.jpg" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                    </button>

                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border py-3 z-50">

                        <!-- User Info -->
                        <div class="flex items-center space-x-3 px-4 pb-3 border-b dark:border-gray-700">
                            <img src="images/profile2.jpg" alt="Profile"
                                 class="w-10 h-10 rounded-full object-cover">
                            <div>
                                 <?= htmlspecialchars($user['username']) ?>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">
                                    <?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?>
                                </p>

                            </div>
                        </div>

                        <!-- Links -->
                        <ul class="mt-2">
                            <li><a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"><i data-feather="user" class="mr-3 text-indigo-600"></i> Profile</a></li>
                            <li><a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"><i data-feather="credit-card" class="mr-3 text-indigo-600"></i> Transactions</a></li>
                            <li><a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"><i data-feather="settings" class="mr-3 text-indigo-600"></i> Settings</a></li>
                        </ul>

                        <!-- Logout -->
                        <div class="border-t mt-2 pt-2 dark:border-gray-700">
                            <a href="login_html.php"
                               class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600">
                                <i data-feather="log-out" class="mr-3"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Title -->
        <h1 class="text-2xl font-bold mb-2">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Welcome to ChainLedger: E-wallet Transaction Management</p>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Balance</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_balance) ?></h2>
                <p class="text-green-600 text-sm">▲ 2.47% Last month ₱24,478</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Period Change</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_change) ?></h2>
                <p class="text-green-600 text-sm">▲ 2.47% Last month ₱24,478</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Period Expenses</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_expenses, 2) ?></h2>
                <p class="text-red-600 text-sm">▼ 2.47% Last month ₱24,478</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400">Total Period Income</p>
                <h2 class="text-2xl font-bold">₱<?= number_format($total_income, 2) ?></h2>
                <p class="text-green-600 text-sm">▲ 2.47% Last month ₱24,478</p>
            </div>
        </div>

        <!-- Balance Trend & Expenses Breakdown -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Balance Trends -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-2">Balance Trends</h3>
                <h2 class="text-xl font-bold mb-2">₱<?= number_format($balance_trend) ?></h2>
                <canvas id="balanceChart"></canvas>
            </div>

            <!-- Expenses Breakdown -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Monthly Expenses Breakdown</h3>

                <!-- Colored Line -->
                <div class="w-full h-2 flex rounded overflow-hidden mb-4">
                    <?php foreach ($monthly_expenses as $expense): ?>
                        <div class="<?= $expense['color'] ?>"
                             style="width: <?= $expense['percent'] ?>%"></div>
                    <?php endforeach; ?>
                </div>

                <!-- Expense List -->
                <ul class="space-y-3">
                    <?php foreach ($monthly_expenses as $expense): ?>
                        <li class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 <?= $expense['color'] ?> rounded-full"></span>
                                <span><?= $expense['name'] ?></span>
                            </div>
                            <span>₱<?= number_format($expense['amount']) ?> (<?= $expense['percent'] ?>%)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>

    <!-- ======================
         Scripts
    ======================= -->
<script src="../js/index.js"></script>

</html>
