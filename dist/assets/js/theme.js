'use strict';

var rtl_flag = false;
var dark_flag = false;

document.addEventListener('DOMContentLoaded', function () {

// -------------------------
// Theme persistence & toggle
// -------------------------
document.addEventListener("DOMContentLoaded", () => {
  const themeBtn = document.getElementById("themeBtn");
  const themeToggle = document.getElementById("theme-toggle"); // optional checkbox toggle

  // Apply saved theme on load
  if (localStorage.getItem("theme") === "dark") {
    document.documentElement.classList.add("dark");
    if (themeBtn) themeBtn.textContent = "dark_mode";
    if (themeToggle) themeToggle.checked = true;
  }

  // Theme button click
  if (themeBtn) {
    themeBtn.addEventListener("click", () => {
      document.documentElement.classList.toggle("dark");
      if (document.documentElement.classList.contains("dark")) {
        themeBtn.textContent = "dark_mode";
        localStorage.setItem("theme", "dark");
        if (themeToggle) themeToggle.checked = true;
      } else {
        themeBtn.textContent = "light_mode";
        localStorage.setItem("theme", "light");
        if (themeToggle) themeToggle.checked = false;
      }
    });
  }

  // Optional checkbox toggle
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

// -------------------------
// Scroll arrows for cards
// -------------------------
function setupScrollArrows(leftId, rightId, scrollId) {
    const leftBtn = document.getElementById(leftId);
    const rightBtn = document.getElementById(rightId);
    const scrollArea = document.getElementById(scrollId);

    if (!scrollArea || !leftBtn || !rightBtn) return;

    function updateArrows() {
        const scrollWidth = scrollArea.scrollWidth;
        const clientWidth = scrollArea.clientWidth;
        const scrollLeft = scrollArea.scrollLeft;

        if (scrollWidth > clientWidth) {
            leftBtn.classList.remove('hidden');
            rightBtn.classList.remove('hidden');

            leftBtn.style.opacity = scrollLeft <= 0 ? '0.3' : '1';
            rightBtn.style.opacity = scrollLeft + clientWidth >= scrollWidth ? '0.3' : '1';
        } else {
            leftBtn.classList.add('hidden');
            rightBtn.classList.add('hidden');
        }
    }

    leftBtn.addEventListener('click', () => {
        scrollArea.scrollBy({ left: -250, behavior: 'smooth' });
    });

    rightBtn.addEventListener('click', () => {
        scrollArea.scrollBy({ left: 250, behavior: 'smooth' });
    });

    scrollArea.addEventListener('scroll', updateArrows);
    window.addEventListener('resize', updateArrows);

    updateArrows();
}

// Initialize both carousels
setupScrollArrows('wallet-left', 'wallet-right', 'wallet-scroll');
setupScrollArrows('cat-left', 'cat-right', 'cat-scroll');

//Confirmation dialog for password change

  // -------------------------
  // Preset color buttons
  // -------------------------
  const presetButtons = document.querySelectorAll('.preset-color > a');
  presetButtons.forEach(btn => {
    btn.addEventListener('click', function (e) {
      let target = e.target;
      if (target.tagName === 'I' || target.tagName === 'SPAN') target = target.parentNode;
      preset_change(target.getAttribute('data-value'));
    });
  });

  // Reset layout button
  const layout_reset = document.querySelector('#layoutreset');
  if (layout_reset) {
    layout_reset.addEventListener('click', function () {
      localStorage.clear();
      location.reload();
    });
  }

  // Initialize other UI color changes
  const colorTypes = ['header', 'navbar', 'logo', 'caption', 'drp-menu-icon', 'drp-menu-link-icon', 'navbar-img'];
  colorTypes.forEach(type => {
    const buttons = document.querySelectorAll(`.${type} > a`);
    buttons.forEach(btn => {
      btn.addEventListener('click', function (e) {
        let target = e.target;
        if (target.tagName === 'SPAN' || target.tagName === 'I') target = target.parentNode;
        window[`${type}_change`](target.getAttribute('data-value'));
      });
    });
  });

  // -------------------------
  // Scroll arrows for cards
  // -------------------------
  function setupScrollArrows(leftId, rightId, scrollId) {
    const leftBtn = document.getElementById(leftId);
    const rightBtn = document.getElementById(rightId);
    const scrollArea = document.getElementById(scrollId);

    function updateArrows() {
      if (!scrollArea) return;
      // Show arrows only if content overflows
      if (scrollArea.scrollWidth > scrollArea.clientWidth) {
        leftBtn.classList.remove('hidden');
        rightBtn.classList.remove('hidden');
      } else {
        leftBtn.classList.add('hidden');
        rightBtn.classList.add('hidden');
      }

      // Dim arrows if at start or end
      leftBtn.style.opacity = scrollArea.scrollLeft <= 0 ? '0.3' : '1';
      rightBtn.style.opacity = scrollArea.scrollLeft + scrollArea.clientWidth >= scrollArea.scrollWidth ? '0.3' : '1';
    }

    leftBtn.onclick = () => scrollArea.scrollBy({ left: -250, behavior: 'smooth' });
    rightBtn.onclick = () => scrollArea.scrollBy({ left: 250, behavior: 'smooth' });

    scrollArea.addEventListener('scroll', updateArrows);
    window.addEventListener('resize', updateArrows);
    updateArrows(); // Initial
  }

  setupScrollArrows('wallet-left','wallet-right','wallet-scroll');
  setupScrollArrows('cat-left','cat-right','cat-scroll');

});

// ====================
// Layout change functions
// ====================
function layout_change(theme) {
  const html = document.documentElement;
  html.setAttribute('data-pc-theme', theme);
  dark_flag = theme === 'dark';

  // Tailwind dark class toggling
  if (dark_flag) html.classList.add('dark');
  else html.classList.remove('dark');

  // Update logos
  const logoSrc = dark_flag ? '../assets/images/logo-white.svg' : '../assets/images/logo-dark.svg';
  ['.navbar-brand .logo-lg', '.auth-logo', '.footer-logo'].forEach(selector => {
    const el = document.querySelector(selector);
    if (el) el.setAttribute('src', logoSrc);
  });

  // Update button states
  const buttons = document.querySelectorAll('.theme-layout .btn');
  buttons.forEach(btn => btn.classList.remove('active'));
  const activeBtn = document.querySelector(`.theme-layout .btn[data-value='${dark_flag ? 'false' : 'true'}']`);
  if (activeBtn) activeBtn.classList.add('active');
}

function change_box_container(value) {
  const content = document.querySelector('.pc-content');
  const footer = document.querySelector('.footer-wrapper');
  if (!content || !footer) return;

  if (value === 'true') {
    content.classList.add('container');
    footer.classList.add('container');
    footer.classList.remove('container-fluid');
  } else {
    content.classList.remove('container');
    footer.classList.remove('container');
    footer.classList.add('container-fluid');
  }

  const activeBtn = document.querySelector('.theme-container .btn.active');
  if (activeBtn) activeBtn.classList.remove('active');
  const newBtn = document.querySelector(`.theme-container .btn[data-value='${value}']`);
  if (newBtn) newBtn.classList.add('active');
}

// ====================
// RTL, captions, presets
// ====================
function layout_rtl_change(value) {
  const html = document.documentElement;
  if (value === 'true') {
    rtl_flag = true;
    html.setAttribute('data-pc-direction', 'rtl');
    html.setAttribute('dir', 'rtl');
    html.setAttribute('lang', 'ar');
  } else {
    rtl_flag = false;
    html.setAttribute('data-pc-direction', 'ltr');
    html.setAttribute('dir', 'ltr');
    html.removeAttribute('lang');
  }
}

function layout_caption_change(value) {
  document.documentElement.setAttribute('data-pc-sidebar-caption', value);
  const btns = document.querySelectorAll('.theme-nav-caption .btn');
  btns.forEach(b => b.classList.remove('active'));
  const newBtn = document.querySelector(`.theme-nav-caption .btn[data-value='${value}']`);
  if (newBtn) newBtn.classList.add('active');
}

function preset_change(value) {
  document.documentElement.setAttribute('class', value);
  const active = document.querySelector('.preset-color > a.active');
  if (active) active.classList.remove('active');
  const newActive = document.querySelector(`.preset-color > a[data-value='${value}']`);
  if (newActive) newActive.classList.add('active');
}

// ====================
// Other helpers (header, navbar, logo, etc.)
// ====================
function header_change(value) { document.documentElement.setAttribute('data-pc-header', value); }
function navbar_change(value) { document.documentElement.setAttribute('data-pc-navbar', value); }
function logo_change(value) { document.documentElement.setAttribute('data-pc-logo', value); }
function caption_change(value) { document.documentElement.setAttribute('data-pc-caption', value); }
function drp_menu_icon_change(value) { document.documentElement.setAttribute('data-pc-drp-menu-icon', value); }
function drp_menu_link_icon_change(value) { document.documentElement.setAttribute('data-pc-drp-menu-link-icon', value); }
function nav_image_change(value) { document.documentElement.setAttribute('data-pc-navimg', value); }
function layout_theme_sidebar_change(value) {
  document.documentElement.setAttribute('data-pc-sidebar_theme', value);
  const logo = document.querySelector('.pc-sidebar .m-header .logo-lg');
  if (logo) logo.setAttribute('src', value === 'true' ? '../assets/images/logo-dark.svg' : '../assets/images/logo-white.svg');
}

