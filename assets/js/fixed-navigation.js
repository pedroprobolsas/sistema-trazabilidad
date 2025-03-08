// Navigation logic for the fixed version
document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation links in the sidebar
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    
    // Add click event listener to each link
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the target component from the href attribute
            const target = this.getAttribute('href').substring(1); // Remove the # character
            
            // Hide all content sections
            document.querySelectorAll('.container-fluid').forEach(section => {
                section.classList.add('d-none');
            });
            
            // Show the target section
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.classList.remove('d-none');
                
                // Remove active class from all nav items
                document.querySelectorAll('.sidebar .nav-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Add active class to the clicked nav item
                this.closest('.nav-item').classList.add('active');
            } else {
                console.error('Target section not found:', target);
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
});
