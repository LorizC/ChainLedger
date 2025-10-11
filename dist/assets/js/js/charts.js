// ../../js/charts.js
document.addEventListener("DOMContentLoaded", () => {
  // helper to detect dark theme (supports documentElement 'dark' + body 'dark-mode')
  const isDark = () =>
    document.documentElement.classList.contains("dark") ||
    document.body.classList.contains("dark-mode");

  function getChartOptions({ yTitle = "", xTitle = "", stacked = false } = {}) {
    const dark = isDark();
    const labelColor = dark ? "#FFFFFF" : "#000000";
    const gridColor = dark ? "rgba(255,255,255,0.06)" : "rgba(0,0,0,0.06)";

    return {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
          labels: { color: labelColor }
        },
        title: {
          display: false,
          color: labelColor
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          stacked,
          title: { display: !!yTitle, text: yTitle, color: labelColor },
          ticks: { color: labelColor },
          grid: { color: gridColor }
        },
        x: {
          stacked,
          title: { display: !!xTitle, text: xTitle, color: labelColor },
          ticks: { color: labelColor },
          grid: { color: gridColor }
        }
      }
    };
  }

  // safe getContext helper (avoids exceptions if canvas missing)
  function getCtxIfExists(id) {
    const el = document.getElementById(id);
    if (!el) return null;
    return el.getContext ? el.getContext("2d") : null;
  }

  // ------------------------------
  // Create charts (only once)
  // ------------------------------
  const txCtx = getCtxIfExists("transactionsChart");
  let transactionsChart = null;
  if (txCtx) {
    transactionsChart = new Chart(txCtx, {
      type: "bar",
      data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
          { label: "GCash", data: [80,120,40,130,60,40,70,60,90,60,80,100], backgroundColor: "#004ed5" },
          { label: "Maya",  data: [60,0,30,0,50,0,40,0,30,0,40,0], backgroundColor: "#9cffc5" },
          { label: "GrabPay", data: [0,0,20,0,30,0,0,0,20,0,30,0], backgroundColor: "#02ab4ee9" },
          { label: "Paypal", data: [0,0,20,0,30,0,0,0,20,0,30,0], backgroundColor: "#013eae" },
          { label: "GooglePay", data: [0,0,20,0,30,0,0,0,20,0,30,0], backgroundColor: "#fb5305c1" }
        ]
      },
      options: getChartOptions({ yTitle: "Transactions", xTitle: "Month" })
    });
  } else {
    console.warn("transactionsChart canvas not found (id='transactionsChart').");
  }

  const categoryCtx = getCtxIfExists("categoryChart");
  let categoryChart = null;
  if (categoryCtx) {
    categoryChart = new Chart(categoryCtx, {
      type: "bar",
      data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
          { label: "Equipments", data:[500,700,650,600,750,720,800,850,900,870,950,1000], backgroundColor: "#FFFF00" },
          { label: "Food",       data:[2000,2500,3000,2800,3100,2900,3300,3500,4000,3700,4200,4500], backgroundColor: "#712eb9ff" },
          { label: "Maintenance",data:[500,700,650,600,750,720,800,850,900,870,950,1000], backgroundColor: "#ff9800" },
          { label: "Health",     data:[500,700,650,600,750,720,800,850,900,870,950,1000], backgroundColor: "#1eff00ff" },
          { label: "Travel",     data:[800,900,950,880,1000,970,1050,1100,1200,1150,1250,1300], backgroundColor: "#3557c6ff" },
          { label: "Utilities",  data:[1500,1400,1600,1550,1700,1650,1800,1750,1900,1850,2000,2100], backgroundColor: "#d33d33ff" }
        ]
      },
      options: getChartOptions({ yTitle: "Amount (₱)", xTitle: "Month", stacked: true })
    });
  } else {
    console.warn("categoryChart canvas not found (id='categoryChart').");
  }

  // ------------------------------
  // Update chart colors (on theme change)
  // ------------------------------
  function updateChartColors(chart) {
    if (!chart) return;
    const opts = getChartOptions({
      yTitle: chart.options.scales?.y?.title?.text || "",
      xTitle: chart.options.scales?.x?.title?.text || "",
      stacked: !!chart.options.scales?.x?.stacked // some heuristic
    });

    // merge new colors into existing options and update
    chart.options.plugins = opts.plugins;
    chart.options.scales = opts.scales;
    chart.update();
  }

  // update immediately to match the initial theme
  updateChartColors(transactionsChart);
  updateChartColors(categoryChart);

  // If theme is toggled by adding/removing classes, observe class changes on root/body
  const observer = new MutationObserver(() => {
    updateChartColors(transactionsChart);
    updateChartColors(categoryChart);
  });
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ["class"] });
  observer.observe(document.body, { attributes: true, attributeFilter: ["class"] });

  // Also wire theme button (if you have one with id="themeBtn")
  const themeBtn = document.getElementById("themeBtn");
  if (themeBtn) {
    themeBtn.addEventListener("click", () => {
      // theme script should toggle classes; we update charts after a tiny delay
      setTimeout(() => {
        updateChartColors(transactionsChart);
        updateChartColors(categoryChart);
      }, 30);
    });
  }

  // helpful console message for debugging
  console.log("charts.js initialized", {
    transactionsChart: !!transactionsChart,
    categoryChart: !!categoryChart,
    darkMode: isDark()
  });
});
