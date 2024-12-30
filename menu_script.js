// Add expand/collapse functionality for smaller screens
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            // Toggle the "active" class to show/hide submenu
            item.classList.toggle('active');
        });
    });
});
