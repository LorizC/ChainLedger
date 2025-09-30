<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChainLedger: E-wallet Transaction Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<style>
  .material-symbols-outlined {
    font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 48;
  }
</style>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <!-- Navbar -->
  <header class="bg-gradient-to-r from-indigo-900 to-purple-800 text-white">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <div class="text-lg font-bold">ChainLedger</div>
      <nav class="space-x-6 hidden md:flex">
        <a href="#" class="hover:text-gray-300">About</a>
        <a href="#" class="hover:text-gray-300">Features</a>
      </nav>
      <div class="space-x-4">
        <a href="#" class="px-4 py-2 text-sm">Sign Up</a>
        <a href="#" class="px-4 py-2 bg-white text-indigo-900 rounded">Log In</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-indigo-900 to-purple-800 text-center text-white py-20 px-6">
    <h1 class="text-3xl md:text-5xl font-bold mb-4">Lorem ipsum dolor sit amet</h1>
    <p class="text-lg md:text-xl mb-8">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    <div class="space-x-4">
      <a href="#" class="bg-teal-400 hover:bg-teal-500 text-black font-bold px-6 py-3 rounded">Sign Up Now!</a>
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
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Duis aute irure dolor in reprehenderit</h2>
        <p class="text-gray-700 mb-4">Excepteur sint occaecat cupidatat non.</p>
        <p class="text-gray-700 mb-4">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium.</p>
      </div>
    </div>
  </section>

  <!-- Ask better questions Section -->
  <section class="bg-gray-50 py-16 px-6">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Duis aute irure dolor in reprehenderit</h2>
        <p class="text-gray-700 mb-4">Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit.</p>
        <p class="text-gray-700 mb-4">Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit.</p>
        <a href="#" class="text-teal-500 font-semibold hover:underline">See an example report</a>
      </div>
      <div class="flex justify-center">
        <img src="https://dummyimage.com/300x600/ffffff/000000&text=Subscribers+Graph" alt="Subscribers Report" class="rounded-lg shadow-lg">
      </div>
    </div>
  </section>

<!-- Why you'll love Section -->
<section class="bg-white py-20 px-6">
  <div class="max-w-6xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-12 text-brand-navy">Duis aute irure dolor in reprehenderit</h2>
    <div class="grid md:grid-cols-3 gap-12 text-brand-slate">

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">bar_chart</span>
        <h3 class="font-bold">Duis aute irure dolor in reprehenderit</h3>
        <p>Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">task_alt</span>
        <h3 class="font-bold">Duis aute irure dolor in reprehenderit</h3>
        <p>Duis aute irure dolor in reprehenderit, Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">show_chart</span>
        <h3 class="font-bold">Duis aute irure</h3>
        <p>Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">functions</span>
        <h3 class="font-bold">Duis aute irure dolor in reprehenderit</h3>
        <p>Duis aute irure dolor in reprehenderit, Duis aute irure dolor in reprehenderit</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">share</span>
        <h3 class="font-bold">Duis aute irure dolor in</h3>
        <p>Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">chat</span>
        <h3 class="font-bold">Duis aute irure dolor in reprehenderit</h3>
        <p>Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">cloud_sync</span>
        <h3 class="font-bold">Duis aute irure dolor in reprehenderit</h3>
        <p>Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">notifications_active</span>
        <h3 class="font-bold">Duis aute irure</h3>
        <p>Duis aute irure dolor in reprehenderit.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4">group</span>
        <h3 class="font-bold">Duis aute irure dolor in reprehenderit</h3>
        <p>Duis aute irure dolor in reprehenderit.</p>
      </div>

    </div>
  </div>
</section>


  <!-- Quote Section -->
  <section class="bg-gray-50 py-16 px-6 text-center">
    <div class="max-w-4xl mx-auto">
      <p class="text-xl italic mb-6">
        “I believe that being honest and transparent with your results and asking for help when you need it is key to the success of any business.”
      </p>
      <p class="text-gray-600 mb-4">Developer's Name</p>
      <a href="#" class="text-teal-500 font-semibold hover:underline">Why we built ChainLedger</a>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="bg-indigo-900 text-center text-white py-20 px-6">
    <h2 class="text-3xl font-bold mb-4">Improve how you track and share your metrics</h2>
    <p class="mb-8">Use ChainLedger</p>
    <a href="#" class="bg-teal-400 hover:bg-teal-500 text-black font-bold px-6 py-3 rounded">Sign up Now!</a>
  </section>


  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 text-center py-6">
    <p>&copy; <?php echo date("Y"); ?> ChainLedger. All rights reserved.</p>
  </footer>

</body>
</html>
