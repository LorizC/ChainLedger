document.addEventListener("DOMContentLoaded", () => {

  // =============================
  // Password Field Toggles
  // =============================
  document.querySelectorAll(".toggle-password").forEach(toggle => {
    toggle.addEventListener("click", function () {
      const input = document.querySelector(this.dataset.toggle);
      if (!input) return;
      input.type = input.type === "password" ? "text" : "password";
      const icon = this.querySelector("i");
      if (icon) {
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
      }
    });
  });

  const togglePasswordBtn = document.getElementById("togglePassword");
  if (togglePasswordBtn) {
    togglePasswordBtn.addEventListener("click", () => {
      const fields = [
        document.getElementById("password"),
        document.getElementById("confirm_password")
      ];
      if (!fields[0] || !fields[1]) return;
      const isHidden = fields.every(f => f.type === "password");
      fields.forEach(f => f.type = isHidden ? "text" : "password");
      togglePasswordBtn.textContent = isHidden ? "Hide Passwords" : "Show Passwords";
    });
  }

  // =============================
  // Feather Icons
  // =============================
  if (typeof feather !== "undefined") feather.replace();

  // =============================
  // Theme Persistence
  // =============================
  const themeToggle = document.getElementById("theme-toggle");
  if (themeToggle) {
    if (localStorage.getItem("theme") === "dark") {
      themeToggle.checked = true;
      document.body.classList.add("dark-mode");
    }
    themeToggle.addEventListener("change", () => {
      if (themeToggle.checked) {
        localStorage.setItem("theme", "dark");
        document.body.classList.add("dark-mode");
      } else {
        localStorage.setItem("theme", "light");
        document.body.classList.remove("dark-mode");
      }
    });
  }

  // =============================
  // Copy Account ID
  // =============================
  window.copyAccountId = function () {
    const accountId = document.getElementById("accountId").textContent;
    navigator.clipboard.writeText(accountId)
      .then(() => alert("✅ Account ID copied to clipboard!"))
      .catch(() => alert("❌ Failed to copy. Please try again."));
  };

  // =============================
  // Chart.js (Balance Chart)
  // =============================
  const ctx = document.getElementById("balanceChart");
  if (ctx) {
    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        datasets: [{
          label: "Balance",
          data: [50, 100, 80, 120, 150, 130],
          borderColor: "#4F46E5",
          backgroundColor: "rgba(79,70,229,0.2)",
          fill: true,
          tension: 0.4
        }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
  }

  // =============================
  // Horizontal Scroll Helper (Dynamic)
  // =============================
  function initCardScroll(containerSelector, leftBtnSelector, rightBtnSelector) {
    const container = document.querySelector(containerSelector);
    const leftBtn = document.querySelector(leftBtnSelector);
    const rightBtn = document.querySelector(rightBtnSelector);
    if (!container || !leftBtn || !rightBtn) return;

    function getCardWidth() {
      const card = container.querySelector(".card");
      if (!card) return 0;
      const style = window.getComputedStyle(card);
      const gap = parseFloat(style.marginRight) || 0;
      return card.offsetWidth + gap;
    }

    function updateButtons() {
      const maxScroll = container.scrollWidth - container.clientWidth;
      leftBtn.style.display = container.scrollLeft > 0 ? "flex" : "none";
      rightBtn.style.display = container.scrollLeft < maxScroll - 1 ? "flex" : "none"; 
    }

    // Initial update (slight delay for layout)
    setTimeout(updateButtons, 50);

    container.addEventListener("scroll", updateButtons);
    window.addEventListener("resize", updateButtons);

    leftBtn.addEventListener("click", () => {
      container.scrollBy({ left: -getCardWidth(), behavior: "smooth" });
    });

    rightBtn.addEventListener("click", () => {
      container.scrollBy({ left: getCardWidth(), behavior: "smooth" });
    });
  }

// =============================
// Merchant Select: Color + Logo
// =============================
const merchantSelect = document.getElementById("merchantSelect");
const merchantLogo = document.getElementById("merchantLogo");

if (merchantSelect && merchantLogo) {
  merchantSelect.addEventListener("change", function () {
    const colors = {
      gcash: "#0077FF",
      googlepay: "#EA4335",
      grabpay: "#01672fff",
      maya: "#19926eff",
      paypal: "#003087"
    };

    const logos = {
      gcash: "../../images/ewallets/gcash1.jpg",
      googlepay: "../../images/ewallets/googlepay1.png",
      grabpay: "../../images/ewallets/grabpay.jpeg",
      maya: "../../images/ewallets/maya1.png",
      paypal: "../../images/ewallets/paypal1.jpg"
    };

    // Change select color
    this.style.color = colors[this.value] || "black";

    // Swap merchant logo
    merchantLogo.src = logos[this.value] || "../images/avatars/profile.jpg";
  });
}

// Avatar Preview Script
  function previewAvatar(event) {
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('avatar-preview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }


  // =============================
  // Dashboard Scroll Buttons
  // =============================
  const totalScroll = document.getElementById("total-scroll");
  const leftBtn = document.getElementById("total-left");
  const rightBtn = document.getElementById("total-right");

  if (totalScroll && leftBtn && rightBtn) {
    const scrollAmount = 250; // adjust based on card width

    leftBtn.addEventListener("click", () => {
      totalScroll.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    rightBtn.addEventListener("click", () => {
      totalScroll.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });

    // Show/hide buttons based on scroll position
    const toggleButtons = () => {
      leftBtn.style.visibility = totalScroll.scrollLeft > 0 ? "visible" : "hidden";
      rightBtn.style.visibility =
        totalScroll.scrollLeft + totalScroll.clientWidth < totalScroll.scrollWidth
          ? "visible"
          : "hidden";
    };

    toggleButtons(); // Initial check
    totalScroll.addEventListener("scroll", toggleButtons);
    window.addEventListener("resize", toggleButtons);
  }

  // =============================
  // Initialize Scrollable Sections
  // =============================

initCardScroll("#total-scroll", "#total-left", "#total-right"); // Dashboard
initCardScroll("#summary-scroll", ".summary-wrapper .scroll-btn.left", ".summary-wrapper .scroll-btn.right"); // Analytics Summary
initCardScroll("#category-scroll", "#category-left", "#category-right"); // Categories


});


document.addEventListener("DOMContentLoaded", () => {
  const burgerBtn = document.getElementById("burgerBtn");
  const sidebar = document.querySelector(".sidebar");
  const main = document.querySelector(".main");
  const header = document.querySelector("header");

  if (burgerBtn && sidebar && main && header) {
    burgerBtn.addEventListener("click", () => {
      sidebar.classList.toggle("hidden");
      main.classList.toggle("full");
      header.classList.toggle("full");
    });
  }
});






