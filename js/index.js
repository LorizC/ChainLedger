// Dark Theme
function darkTheme() {
    return {
        darkMode: false,
        init() {
            this.darkMode = localStorage.getItem('darkMode') === 'true';
            this.$watch('darkMode', value => localStorage.setItem('darkMode', value));
        }
    }
}

// Chart.js config
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('balanceChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'Balance',
                data: [50,100,80,120,150,130],
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    feather.replace();
});
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.querySelector(".sidebar");
  const logo = document.querySelector(".logo");

  if (logo) {
    logo.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
    });
  }
});
