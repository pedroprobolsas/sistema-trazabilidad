document.addEventListener('DOMContentLoaded', function() {
    // Load components
    loadComponent('sidebar-container', 'components/sidebar.html');
    loadComponent('topbar-container', 'components/topbar.html');
    loadComponent('main-content', 'components/dashboard.html', initializeCharts);
    
    // Set up navigation
    setupNavigation();
});

// Function to load HTML components
function loadComponent(containerId, componentPath, callback) {
    fetch(componentPath)
        .then(response => response.text())
        .then(html => {
            document.getElementById(containerId).innerHTML = html;
            if (callback) callback();
        })
        .catch(error => console.error('Error loading component:', error));
}

// Function to set up navigation
function setupNavigation() {
    document.addEventListener('click', function(e) {
        // Find closest anchor with nav-link class
        const navLink = e.target.closest('.nav-link');
        if (!navLink) return;
        
        e.preventDefault();
        const targetId = navLink.getAttribute('href').substring(1);
        
        // Load the appropriate component
        switch(targetId) {
            case 'dashboard':
                loadComponent('main-content', 'components/dashboard.html', initializeCharts);
                break;
            case 'service-orders':
                loadComponent('main-content', 'components/orders.html');
                break;
            case 'providers':
                loadComponent('main-content', 'components/providers.html');
                break;
            case 'products':
                loadComponent('main-content', 'components/products.html');
                break;
            case 'service-types':
                loadComponent('main-content', 'components/service-types.html');
                break;
            case 'reports':
                loadComponent('main-content', 'components/reports.html');
                break;
            case 'receptions':
                loadComponent('main-content', 'components/receptions.html');
                break;
        }
        
        // Update active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });
        navLink.parentElement.classList.add('active');
    });
}

// Function to initialize charts
function initializeCharts() {
    // Efficiency Chart
    if (document.getElementById('efficiencyChart')) {
        var efficiencyCtx = document.getElementById('efficiencyChart').getContext('2d');
        var efficiencyChart = new Chart(efficiencyCtx, {
            type: 'bar',
            data: {
                labels: ["Proveedor A", "Proveedor B", "Proveedor C", "Proveedor D"],
                datasets: [{
                    label: "Eficiencia (%)",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: [96.2, 92.8, 94.5, 93.1],
                }],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
    
    // Service Type Chart
    if (document.getElementById('serviceTypeChart')) {
        var serviceTypeCtx = document.getElementById('serviceTypeChart').getContext('2d');
        var serviceTypeChart = new Chart(serviceTypeCtx, {
            type: 'doughnut',
            data: {
                labels: ["Impresión", "Laminado", "Corte"],
                datasets: [{
                    data: [55, 30, 15],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
            }
        });
    }
}