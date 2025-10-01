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
  </style>
</head>
<body class="font-sans text-gray-800">

  <!-- Navbar -->
  <header class="bg-blue-900 text-white">
    <div class="max-w-7xl mx-auto flex items-center justify-between py-4 px-6">
      <div class="flex items-center gap-2 font-bold text-xl">
        <span class="material-symbols-outlined">workspace_premium</span>
        whitepace
      </div>
      <nav class="hidden md:flex gap-6 text-sm">
        <a href="#" class="hover:text-yellow-400">Product</a>
        <a href="#" class="hover:text-yellow-400">Solutions</a>
        <a href="#" class="hover:text-yellow-400">Resources</a>
        <a href="#" class="hover:text-yellow-400">Pricing</a>
      </nav>
      <div class="flex items-center gap-4">
        <button class="text-sm hover:text-yellow-400">Login</button>
        <button class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-md text-sm font-medium">Try Whitepace Free</button>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="bg-blue-900 text-white py-20 px-6">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl font-bold mb-4">Get More Done with whitepace</h1>
        <p class="mb-6 text-lg">Project management software that enables your team to collaborate, plan, and achieve together.</p>
        <button class="bg-yellow-400 text-blue-900 px-6 py-3 rounded-md font-medium">Try Whitepace Free</button>
      </div>
      <img src="https://via.placeholder.com/500x350" alt="Hero Illustration" class="rounded-lg">
    </div>
  </section>

  <!-- Project Management + Work Together -->
  <section class="py-20 px-6 bg-white">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
      <!-- Project Management -->
      <div>
        <h2 class="text-3xl font-bold mb-4">Project Management</h2>
        <p class="text-gray-600 mb-6">
          Images, videos, PDFs and audio files are supported. Create math expressions and 
          diagrams directly from the app. Take photos with the mobile app and save them to a note.
        </p>
        <button class="bg-blue-900 text-white px-6 py-3 rounded-md flex items-center gap-2">
          Get Started <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </div>
      <img src="https://via.placeholder.com/500x350" alt="Project Management" class="rounded-lg">
    </div>

    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center mt-20">
      <img src="https://via.placeholder.com/400x300" alt="Work Together" class="rounded-lg">
      <div>
        <h2 class="text-3xl font-bold mb-4">Work Together</h2>
        <p class="text-gray-600 mb-6">
          With whitepace, share your notes with your colleagues and collaborate on them. 
          You can also publish a note to the internet and share the URL with others.
        </p>
        <button class="bg-blue-900 text-white px-6 py-3 rounded-md flex items-center gap-2">
          Try it now <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </div>
    </div>
  </section>



<section class="bg-white py-20 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <img src="https://via.placeholder.com/450x300?text=Customization+Illustration" alt="Customise" class="rounded-lg">
    <div>
      <h2 class="text-3xl font-bold mb-4">Customise it to <span class="text-blue-900">your needs</span></h2>
      <p class="text-gray-600 mb-6">
        Customise the app with plugins, custom themes and multiple text editors (Rich Text or Markdown), 
        or create your own scripts and plugins using the Extension API.
      </p>
      <button class="bg-blue-500 text-white px-6 py-3 rounded-md flex items-center gap-2">
        Let’s Go <span class="material-symbols-outlined">arrow_forward</span>
      </button>
    </div>
  </div>
</section>

<!-- Use as Extension -->
<section class="bg-blue-900 text-white py-20 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="text-3xl font-bold mb-4">Use as <span class="text-yellow-400">Extension</span></h2>
      <p class="text-gray-200 mb-6">
        Use the web clipper extension, available on Chrome and Firefox, to save web pages or take screenshots as notes.
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
