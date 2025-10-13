<?php include('../handlers/success.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account Created - ChainLedger</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    body {
      background-image: url('../assets/images/img2.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
    .overlay {
      background: rgba(0, 0, 0, 0.55);
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    @keyframes fadeInDown {
      from { opacity: 0; transform: translate(-50%, -20px); }
      to { opacity: 1; transform: translate(-50%, 0); }
    }
    .animate-fadeInDown {
      animation: fadeInDown 0.3s ease-out;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen relative font-[Poppins] dark:bg-gray-900 dark:text-white">

  <div class="overlay"></div>

  <!-- 📌 Bigger Card -->
  <div class="bg-white dark:bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-3xl shadow-2xl w-full max-w-2xl p-14 text-center relative z-10 transition">
    <div class="flex justify-center mb-8">
      <div class="bg-green-100 dark:bg-green-800 rounded-full p-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    <h1 class="text-green-700 dark:text-green-400 text-4xl font-semibold mb-4">
      Account Created Successfully!
    </h1>
    <h2 class="text-gray-700 dark:text-gray-300 text-2xl font-medium mb-10">
      Account Details
    </h2>

    <div class="text-left space-y-6 text-xl">
      <p class="text-gray-800 dark:text-gray-200 font-semibold flex justify-between">
        <span>Username:</span>
        <span class="text-red-600 dark:text-red-400 font-bold ml-2"><?php echo $username; ?></span>
      </p>

      <div class="flex items-center justify-between">
        <p class="text-gray-800 dark:text-gray-200 font-semibold flex-1">
          Account ID:
          <span id="accountID" class="text-red-600 dark:text-red-400 font-bold ml-2"><?php echo $accountID; ?></span>
        </p>
        <button
          onclick="copyAccountID()"
          class="ml-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-lg flex items-center gap-2 text-base font-semibold transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2M8 16h8a2 2 0 002-2v-2M8 16v2a2 2 0 002 2h2m4-4v2a2 2 0 01-2 2h-2" />
          </svg>
          Copy
        </button>
      </div>
    </div>

    <button
      onclick="window.location.href='../../index.php'"
      class="mt-10 bg-blue-800 dark:bg-blue-700 text-white w-full py-4 rounded-lg hover:bg-blue-900 dark:hover:bg-blue-600 transition text-xl font-semibold"
    >
      Back to Login
    </button>

    <p class="text-gray-500 dark:text-gray-400 text-base mt-6 italic">
      Tip: Make sure to remember your credentials — they won’t be shown again!
    </p>
  </div>
<!-- Required Js -->
<script src="../assets/js/plugins/simplebar.min.js"></script>
<script src="../assets/js/plugins/popper.min.js"></script>
<script src="../assets/js/icon/custom-icon.js"></script>
<script src="../assets/js/plugins/feather.min.js"></script>
<script src="../assets/js/component.js"></script>
<script src="../assets/js/theme.js"></script>
<script src="../assets/js/script.js"></script>
  <script>
    function copyAccountID() {
      const accountIDElement = document.getElementById('accountID');
      if (!accountIDElement) return;
      const accountID = accountIDElement.textContent.trim();

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(accountID)
          .then(() => showToast('✅ Account ID copied!'))
          .catch(err => console.error('Clipboard error:', err));
      } else {
        const textarea = document.createElement('textarea');
        textarea.value = accountID;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('✅ Account ID copied!');
      }
    }

    // ✅ Toast now shows at the TOP center
    function showToast(message) {
      const toast = document.createElement('div');
      toast.textContent = message;
      toast.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg animate-fadeInDown text-lg font-semibold';
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => toast.remove(), 500);
      }, 2000);
    }
  </script>
</body>
</html>
