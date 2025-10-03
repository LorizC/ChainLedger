<?php 
ob_start();
include 'confirm.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Confirm Password - ChainLedger</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-image: url('../../../images/logos/img2.jpg');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-black/50 backdrop-blur-sm">

  <div class="w-full max-w-5xl bg-white/90 shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
    <!-- Left side -->
    <div class="p-10 bg-gray-800 text-white flex flex-col justify-center">
      <h1 class="text-4xl font-bold mb-6">⚠️ Confirm Password</h1>
      <p class="text-lg leading-relaxed mb-8">
        For your security, please confirm your password to proceed with this action. 
        This ensures that only authorized users can make sensitive changes to the account. 
        <span class="font-semibold text-red-400">Do not share your password</span> with anyone.
      </p>
      <div class="mt-auto text-sm text-gray-400">
        © 2025 ChainLedger | All Rights Reserved
      </div>
    </div>

    <!-- Right side -->
    <div class="p-8 flex flex-col justify-center">
      <?php if (!empty($success)): ?>
        <p class="text-green-600 text-center mb-4"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="" class="space-y-5">
        <h3 class="text-2xl font-semibold text-center mb-2">Delete Confirmation</h3>

        <!-- Username -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            value="<?php echo htmlspecialchars($_SESSION['user']['username'] ?? ''); ?>" 
            readonly 
            class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 cursor-not-allowed text-gray-700"
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
          <input 
            type="password" 
            id="password" 
            name="new_password" 
            placeholder="Enter your password" 
            minlength="8" 
            required 
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-red-500 focus:outline-none"
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input 
            type="password" 
            id="confirm_password" 
            name="confirm_password" 
            placeholder="Confirm your password" 
            minlength="8" 
            required 
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-red-500 focus:outline-none"
          />
        </div>

        <p id="togglePassword" class="text-sm text-blue-600 cursor-pointer text-right">Show Passwords</p>

        <!-- Buttons -->
        <div class="flex flex-col gap-3 mt-6">
          <button 
            type="submit" 
            class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-semibold"
          >
            Delete Account
          </button>
          <a 
            href="../dashboard.php" 
            class="w-full text-center bg-gray-300 text-gray-800 py-2 rounded-lg hover:bg-gray-400 transition font-medium"
          >
            Back
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="../../../js/user.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
