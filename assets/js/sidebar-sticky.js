// Custom JavaScript for enhanced sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('accordionSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggleTop = document.getElementById('sidebarToggleTop');
    const body = document.body;

    // Enhanced toggle functionality for sticky sidebar
    function toggleSidebar() {
        body.classList.toggle('sidebar-toggled');

        // On mobile, add show class for slide effect
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('show');
        }
    }

    // Add event listeners
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (sidebarToggleTop) {
        sidebarToggleTop.addEventListener('click', toggleSidebar);
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            // Desktop: remove mobile classes
            sidebar.classList.remove('show');
            if (!body.classList.contains('sidebar-toggled')) {
                // Show sidebar on desktop by default
            }
        } else {
            // Mobile: hide sidebar by default
            if (!sidebar.classList.contains('show')) {
                body.classList.add('sidebar-toggled');
            }
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = (sidebarToggle && sidebarToggle.contains(event.target)) ||
                (sidebarToggleTop && sidebarToggleTop.contains(event.target));

            if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                body.classList.add('sidebar-toggled');
                sidebar.classList.remove('show');
            }
        }
    });

    // Smooth scroll for sidebar navigation
    const navLinks = sidebar.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add click effect
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Initialize mobile state
    if (window.innerWidth <= 768) {
        body.classList.add('sidebar-toggled');
        sidebar.classList.remove('show');
    }
});