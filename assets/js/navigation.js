// Navigation logic
function setupNavigation() {
    // Get all navigation links in the sidebar
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    
    // Add click event listener to each link
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the target component from the href attribute
            const target = this.getAttribute('href').substring(1); // Remove the # character
            
            // Remove active class from all nav items
            document.querySelectorAll('.sidebar .nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to the clicked nav item
            this.closest('.nav-item').classList.add('active');
            
            // Load the appropriate component based on the target
            switch(target) {
                case 'dashboard':
                    loadComponent('main-content', 'components/dashboard.html', initializeCharts);
                    break;
                case 'service-orders':
                    loadComponent('main-content', 'components/orders.html');
                    break;
                case 'receptions':
                    loadComponent('main-content', 'components/receptions.html');
                    break;
                case 'providers':
                    loadComponent('main-content', 'components/providers.html');
                    break;
                case 'products':
                    // If you have a products component, add it here
                    alert('Módulo de Productos en desarrollo');
                    break;
                case 'service-types':
                    // If you have a service types component, add it here
                    alert('Módulo de Tipos de Servicio en desarrollo');
                    break;
                case 'reports':
                    // If you have a reports component, add it here
                    alert('Módulo de Reportes en desarrollo');
                    break;
                default:
                    console.error('Unknown component:', target);
            }
        });
    });
    
    // Check if there's a hash in the URL and navigate to that component
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        const link = document.querySelector(`.sidebar .nav-link[href="#${hash}"]`);
        if (link) {
            link.click();
        }
    }
}
