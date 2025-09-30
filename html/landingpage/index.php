<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChainLedger: E-wallet Transaction Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <!-- Navbar -->
  <header class="bg-gradient-to-r from-indigo-900 to-purple-800 text-white">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <div class="text-lg font-bold">ChainLedger</div>
      <nav class="space-x-6 hidden md:flex">
        <a href="#" class="hover:text-gray-300">For Businesses</a>
        <a href="#" class="hover:text-gray-300">For Investors</a>
        <a href="#" class="hover:text-gray-300">Pricing</a>
        <a href="#" class="hover:text-gray-300">Learn</a>
      </nav>
      <div class="space-x-4">
        <a href="#" class="px-4 py-2 text-sm">Sign Up</a>
        <a href="#" class="px-4 py-2 bg-white text-indigo-900 rounded">Log In</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-indigo-900 to-purple-800 text-center text-white py-20 px-6">
    <h1 class="text-3xl md:text-5xl font-bold mb-4">See all your metrics in one place</h1>
    <p class="text-lg md:text-xl mb-8">Understand, share and improve the numbers that matter to your business</p>
    <div class="space-x-4">
      <a href="#" class="bg-teal-400 hover:bg-teal-500 text-black font-bold px-6 py-3 rounded">Sign Up For Free!</a>
      <a href="#" class="bg-white hover:bg-gray-100 text-indigo-900 font-bold px-6 py-3 rounded">Learn More</a>
    </div>
    <div class="mt-12 flex justify-center">
      <img src="https://dummyimage.com/800x400/ffffff/000000&text=Dashboard+Preview" alt="Dashboard" class="rounded-lg shadow-lg">
    </div>
  </section>

  <!-- Features Section -->
  <section class="bg-white py-16 px-6">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <img src="https://dummyimage.com/500x300/eeeeee/000000&text=ARR+Preview" alt="ARR Example" class="rounded-lg shadow">
      </div>
      <div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Get a single picture of your business</h2>
        <p class="text-gray-700 mb-4">
          Stop wrangling metrics in multiple places.
        </p>
        <p class="text-gray-700 mb-4">
          One Metric helps you get everything together in one simple and focused tool so you can better identify trends and improve decision-making.
        </p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 text-center py-6">
    <p>&copy; <?php echo date("Y"); ?> OneMetric.io. All rights reserved.</p>
  </footer>

</body>
</html>
