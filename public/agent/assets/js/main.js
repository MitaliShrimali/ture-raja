// Sidebar open/close for mobile
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) sidebar.classList.remove('-translate-x-full');
    if (backdrop) backdrop.classList.remove('hidden');
    document.body.classList.add('sidebar-open');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) sidebar.classList.add('-translate-x-full');
    if (backdrop) backdrop.classList.add('hidden');
    document.body.classList.remove('sidebar-open');
}

// Global Dropdown Toggle
window.toggleDropdown = function(e, id) {
    e.stopPropagation();
    const dropdown = document.getElementById('dropdown-' + id);
    const allDropdowns = document.querySelectorAll('.dropdown-menu');
    allDropdowns.forEach(d => {
        if (d.id !== 'dropdown-' + id) d.classList.add('hidden');
    });
    if (dropdown) dropdown.classList.toggle('hidden');
};

// Global click listener to close dropdowns
window.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
});

document.addEventListener('DOMContentLoaded', function() {
    // Close sidebar on lg+ resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeSidebar();
            const backdrop = document.getElementById('sidebarBackdrop');
            if (backdrop) backdrop.classList.add('hidden');
        }
    });

    // Active state handling
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('bg-white', 'text-primary', 'shadow-sm', 'shadow-orange-100');
            link.classList.remove('text-gray-400');
            const iconContainer = link.querySelector('div');
            if (iconContainer) {
                iconContainer.classList.add('bg-primary', 'text-white', 'shadow-md', 'shadow-orange-200');
                iconContainer.classList.remove('bg-transparent');
            }
        }
    });

    // Toastr
    window.showSuccess = (msg) => toastr.success(msg);
    window.showError = (msg) => toastr.error(msg);

    // SweetAlert global helper
    window.confirmAction = (title, text, callback) => {
        Swal.fire({
            title, text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F0642F',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            borderRadius: '2rem'
        }).then((result) => {
            if (result.isConfirmed) callback();
        });
    };
});
