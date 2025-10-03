<?php 
ob_start();
include 'delete.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delete Account - ChainLedger</title>
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
  <h1 class="text-4xl font-bold mb-6">Account Deletion</h1>
  <p class="text-lg leading-relaxed mb-8">
    Deleting this account is 
    <span class="font-semibold text-red-400">permanent</span> 
    and cannot be undone. All data associated with this account will be 
    <span class="font-semibold">permanently removed</span>. 
    Please make sure this is what you want before proceeding.
  </p>
  <div class="mt-auto text-sm text-gray-400">
    © 2025 ChainLedger | All Rights Reserved
  </div>
</div>


    <!-- Right side -->
    <div class="p-8">
      <?php if (!empty($success)): ?>
        <p class="text-green-600 text-center mb-4"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="" class="space-y-5">
        <h3 class="text-2xl font-semibold text-center mb-2">Delete Account</h3>

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

        <!-- Security Question -->
        <div>
          <label for="security-question" class="block text-sm font-medium text-gray-700 mb-1">Security Question</label>
          <select 
            id="security-question" 
            name="security_question" 
            required 
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-red-500 focus:outline-none"
          >
            <option value="" disabled selected>Select a question</option>
            <option value="First Pet">What is the name of your first pet?</option>
            <option value="Elementary School">What is the name of your elementary school?</option>
            <option value="Favorite Food">What is your favorite food?</option>
            <option value="Childhood Nickname">What was your childhood nickname?</option>
          </select>
        </div>

        <!-- Security Answer -->
        <div>
          <label for="security-answer" class="block text-sm font-medium text-gray-700 mb-1">Your Answer</label>
          <input 
            type="text" 
            placeholder="Enter your answer" 
            id="security-answer" 
            name="security_answer" 
            required 
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-red-500 focus:outline-none"
          />
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-3 mt-6">
          <button 
            type="submit" 
            class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-semibold"
          >
            Confirm Account
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
