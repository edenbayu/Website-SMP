// Sidebar Animation
const sidebarToggle = document.querySelector("#sidebar-toggle");
sidebarToggle.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
});

// Active Sidebar
// Menambahkan event listener untuk semua link di dalam item sidebar
document.querySelectorAll('.sidebar-nav li.sidebar-item a').forEach(function(link) {
    link.addEventListener('click', function(event) {
        // Check if the clicked element does not have the 'collapsed' class (i.e., it is not a dropdown toggle)
        if (!this.classList.contains('collapsed')) {
            // Remove the 'active' class from all items in the sidebar
            document.querySelectorAll('.sidebar-nav li.sidebar-item.active').forEach(function(activeItem) {
                activeItem.classList.remove('active');
            });

            // Add the 'active' class to the clicked item
            this.closest('li.sidebar-item').classList.add('active');
        } else {
            // If it's a dropdown toggle, prevent the default link action
            event.preventDefault();
        }
    });
});

//tabel


