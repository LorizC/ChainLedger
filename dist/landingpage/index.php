<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChainLedger Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Material Symbols -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script src="../assets/js/js/index.js"></script>
  <style>
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 48;
    }
    html{
      scroll-behavior: smooth;
    }
  </style>
</head>
<body class="font-sans text-gray-800">

<!-- Navbar -->
<header class="fixed top-0 left-0 w-full bg-gradient-to-r from-violet-900 to-indigo-800 text-white shadow-md z-50">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
    
    <!-- Logo + Brand -->
    <div class="flex items-center space-x-3">
      <img src="../assets/images/logos/logo.png" alt="ChainLedger Logo" class="h-8 w-8 object-contain">
      <h1 class="text-xl font-bold tracking-wide">ChainLedger</h1>
    </div>

    <!-- Navbar Links -->
    <nav class="hidden md:flex space-x-6">
      <a href="#home" class="hover:text-gray-300">Home</a>
      <a href="#about" class="hover:text-gray-300">About</a>
      <a href="#obj" class="hover:text-gray-300">Objectives</a>
      <a href="#features" class="hover:text-gray-300">Features</a>
      <a href="#purpose" class="hover:text-gray-300">Purpose</a>
    </nav>

    <!-- Buttons -->
    <div class="space-x-4">
      <a href="../forms/signup.php" class="px-4 py-2 text-sm hover:bg-gray-100 text-white hover:text-black rounded">Sign Up</a>
      <a href="../../index.php" class="px-4 py-2 bg-white hover:bg-black text-indigo-900 hover:text-white rounded text-sm">Log In</a>
    </div>
  </div>
</header>

  <!-- Hero Section -->
<section id="home" class="bg-gradient-to-r from-violet-900 to-indigo-800 text-center text-white px-6 pt-40 pb-20">
  <h1 class="text-3xl md:text-5xl font-bold mb-4">ChainLedger</h1>
  <p class="text-lg md:text-xl mb-8">An E-wallet Transaction Monitoring System for Small Businesses.</p>
  
  <div class="space-x-4">
    <a href="../forms/signup.php" class="bg-gray-900 hover:bg-gray-100 text-white hover:text-black font-bold px-6 py-3 rounded">Sign Up Now!</a>
    <a href="#about" class="bg-white hover:bg-black text-indigo-900 hover:text-white font-bold px-6 py-3 rounded">Learn More</a>
  </div>

  <!-- Side by Side Images -->
<div class="mt-12 flex justify-center items-center gap-8">
  <!-- Wider image -->
  <img src="../assets/images/index/demo0.png" alt="Dashboard" 
       class="h-[500px] w-[45%] object-contain rounded-lg shadow-lg">

  <!-- Taller image -->
  <img src="../assets/images/index/img1.png" alt="Dashboard" 
       class="h-[480px] w-[45%] object-contain rounded-lg shadow-lg">
</div>
</section>

  <section id="about" class="bg-white px-6 pt-32 pb-16">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <img src="../assets/images/index/cl.png" alt="ARR Example" class="rounded-lg shadow">
      </div>
      <div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Know more about ChainLedger</h2>
        <p class="text-gray-700 mb-4">ChainLedger is an e-wallet transaction monitoring system for small businesses that handles e-wallets or digital wallets.</p>
        <p class="text-gray-700 mb-4">It allows users to record and categorize income and expenses, generate reports, and view visual summaries.
           With role-based access, it helps ensure accurate and organized financial management.</p>
      </div>
    </div>
  </section>

<section id="demo" class="bg-white py-16 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <!-- Text Side -->
    <div>
      <h2 class="text-2xl md:text-3xl font-bold mb-4">Take a quick look!</h2>
      <p class="text-gray-700 mb-4">Screenshots of the system.</p>
      <p class="text-gray-700 mb-4">Easily browse through different sections of ChainLedger to get a feel of the interface.</p>
      <button id="open-fullscreen-gallery" type="button" class="text-teal-500 font-semibold hover:underline">
  See it fullscreen
</button>
    </div>
    <!-- Fullscreen Gallery -->
<div id="fullscreen-gallery" class="hidden fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-[1000]">
  <button id="close-gallery" 
  class="absolute top-12 right-6 w-12 h-12 flex items-center justify-center 
         bg-white text-black text-4xl font-bold rounded-full 
         shadow-lg hover:text-white hover:bg-indigo-800 transition">
  &times;
</button>

<!-- Navigation Arrows (Black on White Circle) -->
<button id="prev-gallery" 
  class="absolute left-6 top-1/2 -translate-y-1/2 
         bg-white text-black text-4xl font-bold 
         w-12 h-12 flex items-center justify-center rounded-full 
         shadow-lg hover:text-white hover:bg-indigo-800 transition">
  &#10094;
</button>

<button id="next-gallery" 
  class="absolute right-6 top-1/2 -translate-y-1/2 
         bg-white text-black text-4xl font-bold 
         w-12 h-12 flex items-center justify-center rounded-full 
         shadow-lg hover:text-white hover:bg-indigo-800 transition">
  &#10095;
</button>

  <img id="gallery-img" src="" alt="Fullscreen Gallery" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
</div>

    <!-- Carousel Side -->
    <div class="relative w-full overflow-hidden rounded-lg shadow-lg">
      <!-- Carousel Container -->
<div id="carousel" class="flex transition-transform duration-700 ease-in-out">
  <img src="../assets/images/index/demo0.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 1">
  <img src="../assets/images/index/demo.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 1">
  <img src="../assets/images/index/demo1.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 2">
  <img src="../assets/images/index/demo2.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 3">
  <img src="../assets/images/index/demo3.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 4">
  <img src="../assets/images/index/demo4.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 5">
  <img src="../assets/images/index/demo5.png" class="w-full flex-shrink-0 object-contain h-[300px] fullscreen-trigger" alt="Demo 6">
</div>


      <!-- Prev Button -->
      <button id="prevBtn" class="absolute top-1/2 left-3 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black transition">
        <span class="material-symbols-outlined">chevron_left</span>
      </button>

      <!-- Next Button -->
      <button id="nextBtn" class="absolute top-1/2 right-3 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black transition">
        <span class="material-symbols-outlined">chevron_right</span>
      </button>

      <!-- Dots -->
      <div id="carousel-dots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2"></div>
    </div>
  </div>
</section>

<!-- System Objectives -->
<section id="obj" class="bg-gradient-to-r from-violet-900 to-indigo-800 text-white py-20 px-6">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
    <!-- Text Content -->
    <div>
      <h2 class="text-4xl font-bold mb-6">System Objectives</h2>
      <p class="text-gray-200 mb-6">
        ChainLedger aims to achieve the following objectives:
      </p>
      <ul class="text-gray-200 space-y-3 mb-8 list-disc list-inside">
        <li><strong>Simplify transaction recording</strong> — allow users to easily log income and expenses from various e-wallet platforms.</li>
        <li><strong>Automate report generation</strong> — provide clear, accurate financial summaries and visual analytics.</li>
        <li><strong>Ensure data accuracy and security</strong> — protect financial information through role-based access and encrypted storage.</li>
        <li><strong>Support informed decision-making</strong> — give business owners the insights they need to manage finances efficiently.</li>
      </ul>
      <button onclick="window.location.href='../../index.php'" 
        class="bg-blue-500 text-white px-6 py-3 rounded-md flex items-center gap-2 
               transition duration-300 hover:bg-blue-700 hover:scale-105">
        Try ChainLedger 
        <span class="material-symbols-outlined">arrow_forward</span>
      </button>
    </div>
    <!-- Illustration -->
    <div class="flex justify-center">
      <img src="../assets/images/index/img1.webp" alt="ChainLedger" class="w-72 md:w-96">
    </div>
  </div>
</section>

<section id="features" class="bg-white py-40 px-6">
  <div class="max-w-6xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-12 -mt-20 text-brand-navy">Features</h2>
    <div class="grid md:grid-cols-3 gap-12 text-brand-slate">

    
      <div class="pt-10 text-center">
        <!-- Analytics Viewer -->
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">analytics</span>
        <h3 class="font-bold">Analytics Viewer</h3>
        <p>View insights and performance analytics in real-time.</p>
      </div>

      <div class="pt-10 text-center">
        <!-- Overall Dashboard -->
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">dashboard</span>
        <h3 class="font-bold">Overall Dashboard</h3>
        <p>A complete overview of your transactions and reports.</p>
      </div>

      <div class="pt-10 text-center">
        <!-- Transaction Ledger -->
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">receipt_long</span>
        <h3 class="font-bold">Transaction Ledger</h3>
        <p>Keep track of all your financial transactions seamlessly.</p>
      </div>

      <div class="pt-10 text-center">
        <!-- Reports -->
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">summarize</span>
        <h3 class="font-bold">Reports</h3>
        <p>Generate clear and organized financial reports.</p>
      </div>

      <div class="pt-10 text-center">
        <!-- Archives Section -->
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">archive</span>
        <h3 class="font-bold">Archives</h3>
        <p>View and manage stored or past transactions or logs easily.</p>
      </div>

      <div class="pt-10 text-center">
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">lock</span>
        <h3 class="font-bold">Secure Access</h3>
        <p>Protect your data with authentication and encrypted storage.</p>
      </div>

    </div>
  </div>
</section>

<!-- System's Purpose -->
<section id="purpose" class="bg-gradient-to-r from-violet-900 to-indigo-800 text-white py-12 px-6">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
    <!-- Text Content -->
    <div>
      <h2 class="text-4xl font-bold mb-6">The System's <span class="text-yellow-400">Purpose</span></h2>
      <p class="text-gray-200 mb-8">
        An E-wallet Transaction Monitoring System designed to help small business owners record, organize, and analyse their digital financial transactions.
      </p>
      <button onclick="window.location.href='../../index.php'" 
        class="bg-white text-blue-900 px-6 py-3 rounded-md flex items-center gap-2 
               transition duration-300 hover:bg-black hover:text-white hover:scale-105">
        Let’s Go 
        <span class="material-symbols-outlined">arrow_forward</span>
      </button>
    </div>

    <!-- Image -->
    <div class="flex justify-center">
      <img src="../assets/images/index/purpose.webp" alt="System Purpose Illustration" class="w-72 md:w-96 rounded-lg">
    </div>
  </div>
</section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 text-center py-6">
    <p>@ 2025 ChainLedger. All rights reserved.</p>
  </footer>
</body>
</html>
