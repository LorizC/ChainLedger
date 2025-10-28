document.addEventListener("DOMContentLoaded", () => {
// =========================================================================================================================================== //
// =======================================================PASSWORD FIELD TOGGLES============================================================== //
// ========================================================================================================================================== //  
  document.querySelectorAll(".toggle-password").forEach(toggle => {

    // Single password toggle (eye icon)
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

// Toggle password visibility for each field (like in login page)
document.querySelectorAll('.toggle-password').forEach(icon => {
  icon.addEventListener('click', () => {
    const input = document.querySelector(icon.getAttribute('data-toggle'));
    const eyeIcon = icon.querySelector('i');

    if (input.type === 'password') {
      input.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      input.type = 'cpassword';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    }
  });
});


// =========================================================================================================================================== //
// ===========================================================Copy Account ID================================================================= //
// ========================================================================================================================================== // 
    function copyAccountID() {
      const accountID = document.getElementById('accountID').innerText;
      navigator.clipboard.writeText(accountID);
      alert('Account ID copied: ' + accountID);
    }

// =========================================================================================================================================== //
// ============================================================ FEATHER ICONS================================================================ //
// ========================================================================================================================================== // 
 document.addEventListener("DOMContentLoaded", () => {
  if (typeof feather !== "undefined") feather.replace();});

// =========================================================================================================================================== //
// =======================================================MERCHANT SELECT (Color + Logo)============================================================== //
// ========================================================================================================================================== //   
const merchantSelect = document.getElementById("merchantSelect");
const merchantLogo = document.getElementById("merchantLogo");

if (merchantSelect && merchantLogo) {
  merchantSelect.addEventListener("change", function () {
    const colors = {
      Gcash: "#0077FF",
      Googlepay: "#EA4335",
      Grabpay: "#01672fff",
      Maya: "#19926eff",
      Paypal: "#003087"
    };

    const logos = {
      Gcash: "../../images/ewallets/gcash1.jpg",
      Googlepay: "../../images/ewallets/googlepay1.png",
      Grabpay: "../../images/ewallets/grabpay.jpeg",
      Maya: "../../images/ewallets/maya1.png",
      Paypal: "../../images/ewallets/paypal1.jpg"
    };

    // Change select color
    this.style.color = colors[this.value] || "black";

    // Swap merchant logo
    merchantLogo.src = logos[this.value] || "../images/avatars/profile.jpg";
  });
}

// =========================================================================================================================================== //
// ===================================================Horizontal Scroll Helper (Dynamic)===================================================== //
// ========================================================================================================================================== // 
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

// =========================================================================================================================================== //
// =====================================================DASHBOARD/ANALYTICS SCROLL BUTTONS =================================================== //
// ========================================================================================================================================== // 

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

// =========================================================================================================================================== //
// ==================================================THEME PERSISTENCE (Checkbox + Button) ================================================== //
// ========================================================================================================================================== // 

//Saves the toggled theme in refresh
//Wag delete other style doesn't change theme
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

document.addEventListener("DOMContentLoaded", () => {
  const themeBtn = document.getElementById("themeBtn");
  const themeToggle = document.getElementById("theme-toggle"); // optional checkbox toggle

  if (localStorage.getItem("theme") === "dark") {
    document.documentElement.classList.add("dark");
    if (themeBtn) themeBtn.textContent = "dark_mode"; // 🌙
    if (themeToggle) themeToggle.checked = true;
  }
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

  //Tailwind change themes
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

// =========================================================================================================================================== //
// ============================================================SIDEBAR TOGGLE =============================================================== //
// ========================================================================================================================================== // 
document.addEventListener("DOMContentLoaded", () => {
  const burgerBtn = document.getElementById("burgerBtn");
  const sidebar = document.querySelector(".sidebar");
  const main = document.querySelector(".main");
  const header = document.querySelector("header");

  // Sidebar starts open
  sidebar.classList.add("show");

  burgerBtn.addEventListener("click", () => {
    if (sidebar.classList.contains("show")) {
      sidebar.classList.remove("show");
      sidebar.classList.add("hide");
      main.classList.add("full");
      header.classList.add("full");
    } else {
      sidebar.classList.remove("hide");
      sidebar.classList.add("show");
      main.classList.remove("full");
      header.classList.remove("full");
    }
  });
});

// =========================================================================================================================================== //
// ========================================================HEADER USER POPUP TOGGLE=========================================================== //
// ========================================================================================================================================== // 
document.addEventListener("DOMContentLoaded", () => {
  const userBtn = document.getElementById("userBtn");
  const userPopup = document.getElementById("userPopup");

  userBtn.addEventListener("click", () => {
    userPopup.style.display = userPopup.style.display === "block" ? "none" : "block";
  });

  document.addEventListener("click", (e) => {
    if (!userPopup.contains(e.target) && !userBtn.contains(e.target)) {
      userPopup.style.display = "none";
    }
  });

window.addEventListener("scroll", () => {
  userPopup.classList.remove("show");
});
});

  document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("settingsToggle");
    const drawer = document.getElementById("settingsDrawer");

    toggle.addEventListener("click", () => {
      drawer.classList.toggle("show");
    });
  });
// =========================================================================================================================================== //
// ==============================================================PAGINATION <>=============================================================== //
// ========================================================================================================================================== // 
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

// =========================================================================================================================================== //
// ==============================================================LOGOUT CONFIRMATION=============================================================== //
// ========================================================================================================================================== // 
document.addEventListener("DOMContentLoaded", () => {
  const logoutButtons = document.querySelectorAll('a[href*="logout.php"]');

  logoutButtons.forEach(button => {
    button.addEventListener("click", (e) => {
      e.preventDefault();

      // Prevent duplicates
      if (document.getElementById("logoutConfirmBox")) return;

      // Create confirmation popup
      const confirmBox = document.createElement("div");
      confirmBox.id = "logoutConfirmBox";
      confirmBox.className =
        "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";

      confirmBox.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center max-w-sm w-full transition transform scale-95 opacity-0 animate-fadeIn">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Confirm Logout</h2>
          <p class="text-gray-600 dark:text-gray-300 mb-5">Are you sure you want to log out?</p>
          <div class="flex justify-center gap-4">
            <button id="cancelLogout" class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-600">Cancel</button>
            <a id="confirmLogout" href="${button.href}" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white">Logout</a>
          </div>
        </div>
      `;

      document.body.appendChild(confirmBox);

      // Animate popup
      requestAnimationFrame(() => {
        const box = confirmBox.querySelector("div");
        box.classList.remove("scale-95", "opacity-0");
        box.classList.add("scale-100", "opacity-100");
      });

      // Cancel button closes popup
      document.getElementById("cancelLogout").addEventListener("click", () => {
        confirmBox.remove();
      });
    });
  });
});

// =========================================================================================================================================== //
// =============================================================EDIT PROFILE================================================================= //
// ========================================================================================================================================== // 
document.addEventListener("DOMContentLoaded", () => {
const modal = document.getElementById('editProfileModal');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const avatarInputs = document.querySelectorAll('input[name="avatar"]');
const avatarPreview = document.getElementById('avatarPreview');

if (modal && cancelEditBtn) {
  // Example: Open the modal (attach this to your "Edit Profile" button)
  function openEditProfile() {
    modal.classList.remove('hidden');
  }

  // Close modal
  cancelEditBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  // Live avatar preview
  avatarInputs.forEach(input => {
    input.addEventListener('change', () => {
      if (avatarPreview) avatarPreview.src = input.value;
    });
  });
}

});
  // =========================================================================================================================================== //
// ==========================================================DELETE ACCOUNT CONFIRMATION====================================================== //
// ========================================================================================================================================== //
document.addEventListener("DOMContentLoaded", () => {
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  const form = document.getElementById("deleteForm");

  if (!confirmDeleteBtn || !form) return; // safety check

  confirmDeleteBtn.addEventListener("click", () => {
    const securityAnswer = form.querySelector("input[name='security_answer']").value.trim();
    const password = form.querySelector("input[name='current_password']").value.trim();
    const confirmPassword = form.querySelector("input[name='confirm_password']").value.trim();

    // Basic input validation
    if (!securityAnswer || !password || !confirmPassword) {
      Swal.fire({
        icon: 'error',
        title: 'Missing Fields',
        text: 'Please fill in all fields before proceeding.',
        confirmButtonColor: '#3085d6'
      });
      return;
    }

    // Password match validation
    if (password !== confirmPassword) {
      Swal.fire({
        icon: 'error',
        title: 'Password Mismatch',
        text: 'Passwords do not match. Please re-enter them correctly.',
        confirmButtonColor: '#3085d6'
      });
      return;
    }

    // Show confirmation modal
    Swal.fire({
      title: 'Are you absolutely sure?',
      text: "Your account and all associated data will be permanently deleted.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete my account',
      cancelButtonText: 'Cancel',
      backdrop: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Deleting...',
          text: 'Please wait while we remove your account.',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
          didOpen: () => {
            form.submit();
          }
        });
      }
    });
  });
});

  // =========================================================================================================================================== //
// ==========================================================CHANGEPASSWORD SUCCESS====================================================== //
// ========================================================================================================================================== //
document.addEventListener('DOMContentLoaded', () => {
  const successAlert = document.getElementById('success-alert');
  const closeAlertBtn = document.getElementById('close-alert');

  if (successAlert) {
    // Auto-hide after 4 seconds
    setTimeout(() => {
      successAlert.classList.add('opacity-0');
      setTimeout(() => successAlert.remove(), 500);
    }, 4000);
  }

  if (closeAlertBtn) {
    closeAlertBtn.addEventListener('click', () => {
      successAlert.classList.add('opacity-0');
      setTimeout(() => successAlert.remove(), 500);
    });
  }
});
  // =========================================================================================================================================== //
// ==========================================================SORTING SCRIPT====================================================== //
// ========================================================================================================================================== //
document.addEventListener('DOMContentLoaded', () => {
    const timestampHeader = document.getElementById('timestampHeader');
    const table = document.getElementById('logsTable');
    const sortIcon = document.getElementById('sortIcon');
    let sortDirection = null; // null, 'asc', or 'desc'

    timestampHeader.addEventListener('click', () => {
      const tbody = table.querySelector('tbody');
      const rows = Array.from(tbody.querySelectorAll('tr'));

      const getTimestamp = (row) => {
        const cell = row.cells[2]; // Timestamp column index after removal
        return new Date(cell.textContent.trim());
      };

      if (sortDirection === 'asc') {
        sortDirection = 'desc';
        sortIcon.textContent = 'expand_more';
      } else if (sortDirection === 'desc') {
        sortDirection = 'asc';
        sortIcon.textContent = 'expand_less';
      } else {
        sortDirection = 'asc';
        sortIcon.textContent = 'expand_less';
      }

      rows.sort((a, b) => {
        const dateA = getTimestamp(a);
        const dateB = getTimestamp(b);
        return sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
      });

      tbody.innerHTML = '';
      rows.forEach(row => tbody.appendChild(row));
    });
  });
  // =========================================================================================================================================== //
// ==========================================================DELETEACC SUCCESS MESSANGE====================================================== //
// ========================================================================================================================================== //
