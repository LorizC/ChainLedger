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

  burgerBtn.addEventListener("click", () => {
    sidebar.classList.toggle("hidden");
    main.classList.toggle("full");
    header.classList.toggle("full");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const themeBtn = document.getElementById("themeBtn");

  // Apply saved theme on load
  if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
    themeBtn.textContent = "dark_mode"; // 🌙
  }

  themeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
      themeBtn.textContent = "dark_mode"; // 🌙
      localStorage.setItem("theme", "dark");
    } else {
      themeBtn.textContent = "light_mode"; // ☀️
      localStorage.setItem("theme", "light");
    }
  });
});


// theme.js
document.addEventListener("DOMContentLoaded", () => {
  const themeBtn = document.getElementById("themeBtn");
  const themeToggle = document.getElementById("theme-toggle"); // optional checkbox toggle

  // Apply saved theme on load
  if (localStorage.getItem("theme") === "dark") {
    document.documentElement.classList.add("dark");
    if (themeBtn) themeBtn.textContent = "dark_mode"; // 🌙
    if (themeToggle) themeToggle.checked = true;
  }

  // Icon button toggle
  if (themeBtn) {
    themeBtn.addEventListener("click", () => {
      document.documentElement.classList.toggle("dark");

      if (document.documentElement.classList.contains("dark")) {
        themeBtn.textContent = "dark_mode"; // 🌙
        localStorage.setItem("theme", "dark");
        if (themeToggle) themeToggle.checked = true;
      } else {
        themeBtn.textContent = "light_mode"; // ☀️
        localStorage.setItem("theme", "light");
        if (themeToggle) themeToggle.checked = false;
      }
    });
  }

  // Checkbox toggle
  if (themeToggle) {
    themeToggle.addEventListener("change", () => {
      if (themeToggle.checked) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
        if (themeBtn) themeBtn.textContent = "dark_mode";
      } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
        if (themeBtn) themeBtn.textContent = "light_mode";
      }
    });
  }
});


  document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll(".ledger-row");
    const prevBtn = document.getElementById("prev-page");
    const nextBtn = document.getElementById("next-page");
    const pageInfo = document.getElementById("page-info");

    const rowsPerPage = 9;
    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    function renderPage() {
      // hide all rows
      rows.forEach((row, index) => {
        row.style.display = "none";
        if (
          index >= (currentPage - 1) * rowsPerPage &&
          index < currentPage * rowsPerPage
        ) {
          row.style.display = "";
        }
      });

      // update page info
      pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

      // disable buttons when needed
      prevBtn.disabled = currentPage === 1;
      nextBtn.disabled = currentPage === totalPages;
    }

    prevBtn.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        renderPage();
      }
    });

    nextBtn.addEventListener("click", () => {
      if (currentPage < totalPages) {
        currentPage++;
        renderPage();
      }
    });

    // initial render
    renderPage();
  });

