document.addEventListener("DOMContentLoaded", () => {
  // ==========================================================================================================
  // ✅ FULL SCREEN GALLERY
  // ==========================================================================================================
  const galleryOverlay = document.getElementById('fullscreen-gallery');
  const galleryImg = document.getElementById('gallery-img');
  const openGalleryBtn = document.getElementById('open-fullscreen-gallery');
  const closeGalleryBtn = document.getElementById('close-gallery');
  const prevGalleryBtn = document.getElementById('prev-gallery');
  const nextGalleryBtn = document.getElementById('next-gallery');

  const galleryImages = Array.from(document.querySelectorAll('#carousel img'));
  let galleryIndex = 0;

  function showGalleryImage(index) {
    galleryImg.src = galleryImages[index].src;
  }

  if (openGalleryBtn) {
    openGalleryBtn.addEventListener('click', () => {
      galleryIndex = 0;
      showGalleryImage(galleryIndex);
      galleryOverlay.classList.remove('hidden');
    });
  }

  if (closeGalleryBtn) {
    closeGalleryBtn.addEventListener('click', () => {
      galleryOverlay.classList.add('hidden');
    });
  }

  if (prevGalleryBtn) {
    prevGalleryBtn.addEventListener('click', () => {
      galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
      showGalleryImage(galleryIndex);
    });
  }

  if (nextGalleryBtn) {
    nextGalleryBtn.addEventListener('click', () => {
      galleryIndex = (galleryIndex + 1) % galleryImages.length;
      showGalleryImage(galleryIndex);
    });
  }

  // Keyboard navigation + ESC close
  document.addEventListener('keydown', (e) => {
    if (galleryOverlay.classList.contains('hidden')) return;

    if (e.key === 'Escape') {
      galleryOverlay.classList.add('hidden');
    } else if (e.key === 'ArrowLeft') {
      galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
      showGalleryImage(galleryIndex);
    } else if (e.key === 'ArrowRight') {
      galleryIndex = (galleryIndex + 1) % galleryImages.length;
      showGalleryImage(galleryIndex);
    }
  });

  // Close when clicking outside image
  if (galleryOverlay) {
    galleryOverlay.addEventListener('click', (e) => {
      if (e.target === galleryOverlay) {
        galleryOverlay.classList.add('hidden');
      }
    });
  }

  // ==========================================================================================================
  // ✅ PHOTO CAROUSEL
  // ==========================================================================================================
  const carousel = document.getElementById('carousel');
  const dotsContainer = document.getElementById('carousel-dots');
  let index = 0;
  let interval;

  if (carousel && dotsContainer) {
    const slides = carousel.children;
    const totalSlides = slides.length;

    // Create navigation dots
    for (let i = 0; i < totalSlides; i++) {
      const dot = document.createElement('button');
      dot.className = 'w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-500 transition';
      dot.addEventListener('click', () => moveToSlide(i));
      dotsContainer.appendChild(dot);
    }

    const dots = dotsContainer.children;
    if (dots[0]) dots[0].classList.add('bg-gray-800');

    function updateDots() {
      for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove('bg-gray-800');
      }
      dots[index].classList.add('bg-gray-800');
    }

    function moveToSlide(i) {
      index = (i + totalSlides) % totalSlides;
      carousel.style.transform = `translateX(-${index * 100}%)`;
      updateDots();
      restartAutoplay();
    }

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (prevBtn) prevBtn.addEventListener('click', () => moveToSlide(index - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => moveToSlide(index + 1));

    function autoplay() {
      interval = setInterval(() => moveToSlide(index + 1), 4000);
    }

    function restartAutoplay() {
      clearInterval(interval);
      autoplay();
    }

    autoplay();

    // Clean up interval when leaving page
    window.addEventListener('beforeunload', () => clearInterval(interval));
  }

  // ==========================================================================================================
  // ✅ LANDING PAGE SCROLL LOCK
  // ==========================================================================================================
  const body = document.body;
  const homeSection = document.getElementById('home');
  const learnMoreBtn = document.querySelector('a[href="#about"]');

  // Lock scroll on load
  window.addEventListener('load', () => {
    body.classList.add('lock-scroll');
  });

  // Unlock scroll if "Learn More" is clicked
  learnMoreBtn.addEventListener('click', () => {
    body.classList.remove('lock-scroll');
  });

  // Optional: also unlock when the user manually scrolls down
  window.addEventListener('scroll', () => {
    const homeBottom = homeSection.offsetHeight;
    if (window.scrollY > homeBottom - 100) {
      body.classList.remove('lock-scroll');
    }
  });
});