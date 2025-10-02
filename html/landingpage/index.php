<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Whitepace Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Material Symbols -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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
    <div class="text-lg font-bold">ChainLedger</div>

    <!-- Navbar Links -->
    <nav class="space-x-6 hidden md:flex">
      <a href="#home" class="hover:text-gray-300">Home</a>
      <a href="#about" class="hover:text-gray-300">Description</a>
      <a href="#demo" class="hover:text-gray-300">Demo</a>
      <a href="#features" class="hover:text-gray-300">Features</a>
     
    </nav>

    <div class="space-x-4">
      <a href="../../html/usercreation/signup.php" class="px-4 py-2 text-sm hover:bg-gray-100 text-white hover:text-black">Sign Up</a>
      <a href="../../html/usercreation/login.php" class="px-4 py-2 bg-white hover:bg-black text-indigo-900 hover:text-white rounded">Log In</a>
    </div>
  </div>
</header>


  <!-- Hero Section -->
<!-- Hero Section -->
<section id="home" class="bg-gradient-to-r from-violet-900 to-indigo-800 text-center text-white px-6 pt-40 pb-20">
  <h1 class="text-3xl md:text-5xl font-bold mb-4">ChainLedger</h1>
  <p class="text-lg md:text-xl mb-8">An E-wallet Transaction Monitoring System for Small Businesses.</p>
  
  <div class="space-x-4">
    <a href="../../html/usercreation/signup.php" class="bg-gray-900 hover:bg-gray-100 text-white hover:text-black font-bold px-6 py-3 rounded">Sign Up Now!</a>
    <a href="#about" class="bg-white hover:bg-black text-indigo-900 hover:text-white font-bold px-6 py-3 rounded">Learn More</a>
  </div>

  <!-- Side by Side Images -->
<div class="mt-12 flex justify-center items-center gap-8">
  <!-- Wider image -->
  <img src="../../images/img.png" alt="Dashboard" 
       class="h-[500px] w-[45%] object-contain rounded-lg shadow-lg">

  <!-- Taller image -->
  <img src="../../images/img1.png" alt="Dashboard" 
       class="h-[480px] w-[45%] object-contain rounded-lg shadow-lg">
</div>



</section>


  <section id="about" class="bg-white px-6 pt-32 pb-16">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <img src="https://dummyimage.com/500x300/eeeeee/000000&text=ARR+Preview" alt="ARR Example" class="rounded-lg shadow">
      </div>
      <div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Know more about ChainLedger</h2>
        <p class="text-gray-700 mb-4">E-wallet Transaction Management System.</p>
        <p class="text-gray-700 mb-4">Description about the system 2-3 sentences.</p>
      </div>
    </div>
  </section>


  <section id="demo" class="bg-white py-16 px-6">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Take a quick look!</h2>
        <p class="text-gray-700 mb-4">screenshots of the system with carousel transitions+.</p>
        <p class="text-gray-700 mb-4">Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit Duis aute irure dolor in reprehenderit.</p>
        <a href="#" class="text-teal-500 font-semibold hover:underline">See an example report</a>
      </div>
      <div class="flex justify-center">
        <img src="https://dummyimage.com/300x600/ffffff/000000&text=Subscribers+Graph" alt="Subscribers Report" class="rounded-lg shadow-lg">
      </div>
    </div>
  </section>


<section id="obj" class="bg-gradient-to-r from-violet-900 to-indigo-800 text-white py-20 px-6">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
    <!-- Text Content -->
    <div>
      <h2 class="text-4xl font-bold mb-6">System Objectives</h2>
      <p class="text-gray-200 mb-8">
        Duis aute irure dolor in reprehenderit, Duis aute irure dolor in reprehenderit. Duis aute irure dolor in reprehenderit. 
        Duis aute irure dolor in reprehenderitDuis aute irure dolor in reprehenderit.
      </p>
      <button class="bg-blue-500 text-white px-6 py-3 rounded-md flex items-center gap-2">
        Try Taskey <span class="material-symbols-outlined">arrow_forward</span>
      </button>
    </div>
    <!-- Illustration -->
    <div class="flex justify-center">
      <img src="https://via.placeholder.com/350x400?text=Illustration" alt="Work Anywhere" class="w-72 md:w-96">
    </div>
  </div>
</section>

<section id="features" class="bg-white py-40 px-6">
  <div class="max-w-6xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-12 -mt-20 text-brand-navy">Meet the Developers</h2>
    <div class="grid md:grid-cols-3 gap-12 text-brand-slate">

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">bar_chart</span>
        <h3 class="font-bold">Loriz Neil Carlos</h3>
        <p>Project Manager & Frontend Programmer.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">task_alt</span>
        <h3 class="font-bold">L.E Dela Pena</h3>
        <p>Systems ANalyst.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">show_chart</span>
        <h3 class="font-bold">Einjhel Jam Romanillos</h3>
        <p>Documentation Specialist.</p>
      </div>

      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">functions</span>
        <h3 class="font-bold">Trixie Valerie Bautista</h3>
        <p>Database Administrator.</p>
      </div>
      <div>
        <span class="material-symbols-outlined text-brand-blue text-4xl mb-4 mt-10">share</span>
        <h3 class="font-bold">Shairyl Limpahan</h3>
        <p>Duis aute irure dolor in reprehenderit.</p>
      </div>
    </div>
  </div>
</section>

<!-- Use as Extension -->
<section id="extension" class="bg-gradient-to-r from-violet-900 to-indigo-800 text-white py-20 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="text-3xl font-bold mb-4">sd daws <span class="text-yellow-400">jdfytf</span></h2>
      <p class="text-gray-200 mb-6">
       irure dolor in reprehenderit irure dolor in reprehenderitirure dolor in reprehenderitirure dolor in reprehenderit.
      </p>
      <button class="bg-white text-blue-900 px-6 py-3 rounded-md flex items-center gap-2">
        Let’s Go <span class="material-symbols-outlined">arrow_forward</span>
      </button>
    </div>
    <img src="https://via.placeholder.com/450x300?text=Extension+Illustration" alt="Use as Extension" class="rounded-lg">
  </div>
</section>


  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 text-center py-6">
    <p>&copy; <?php echo date("Y"); ?> ChainLedger. All rights reserved.</p>
  </footer>

</body>
</html>
