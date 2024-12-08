// Sidebar Animation
const sidebarToggle = document.querySelector("#sidebar-toggle");
sidebarToggle.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
});

// Active Sidebar
// Fungsi untuk menetapkan kelas 'active' berdasarkan URL yang tersimpan di localStorage
function setActiveLink() {
    // Ambil path tersimpan di localStorage
    const savedPath = localStorage.getItem('activeSidebarLink');
    const openDropdownId = localStorage.getItem('openDropdownId');

    // Loop melalui setiap link di sidebar
    document.querySelectorAll('.sidebar-nav li.sidebar-item a').forEach(function(link) {
        const linkPath = link.getAttribute('href');

        // Hapus kelas 'active' dari semua item awalnya
        link.closest('li.sidebar-item').classList.remove('active');

        // Jika path link cocok dengan path tersimpan, tambahkan kelas 'active'
        if (window.location.href === linkPath) {
            link.closest('li.sidebar-item').classList.add('active');

            // Jika link ini berada di dalam dropdown, buka dropdown tersebut
            const dropdown = link.closest('.sidebar-dropdown');
            if (dropdown) {
                const dropdownToggle = dropdown.previousElementSibling;
                dropdown.classList.add('show');
                dropdownToggle.classList.remove('collapsed');
                dropdownToggle.parentElement.classList.add('active');
                dropdownToggle.setAttribute('aria-expanded', 'true');
            }
        }
    });

    // // Jika ada dropdown yang tersimpan, buka dropdown tersebut
    // if (openDropdownId) {
    //     const dropdown = document.getElementById(openDropdownId);
    //     if (dropdown) {
    //         const dropdownToggle = dropdown.previousElementSibling;
    //         dropdown.classList.add('show');
    //         // dropdownToggle.classList.remove('collapsed');
    //         dropdownToggle.classList.add('meong');
    //         // dropdownToggle.closest('.list-except').classList.add('active');
    //         // console.log(dropdownToggle.closest('.list-except'));
    //         dropdownToggle.setAttribute('aria-expanded', 'true');
    //     }
    // }
}

// Event listener untuk setiap link di sidebar
document.querySelectorAll('.sidebar-nav li.sidebar-item a').forEach(function(link) {
    link.addEventListener('click', function(event) {
        // Simpan path URL yang diklik ke localStorage
        if (!this.parentElement.classList.contains('list-except')) {
            localStorage.setItem('activeSidebarLink', this.getAttribute('href'));
        }

        if (!this.parentElement.classList.contains('active')) {
            if (this.closest('.list-except') && this.closest('.list-except').classList.contains('active')) {
                document.querySelectorAll('.sidebar-nav li.sidebar-item.active')
                .forEach(function(activeItem) {
                    if (!activeItem.classList.contains('list-except')) {
                        activeItem.classList.remove('active');
                    }
                });
            } else {
                document.querySelectorAll('.sidebar-nav li.sidebar-item.active')
                .forEach(function(activeItem) {
                    activeItem.classList.remove('active');
                });
            }
        }
        // Hapus kelas 'active' dari semua item di sidebar
        // document.querySelectorAll('.sidebar-nav li.sidebar-item.active')
        // .forEach(function(activeItem) {
        //     if (!activeItem.classList.contains('list-except')) {
        //         activeItem.classList.remove('active');
        //     }
        // });


        // Tambahkan kelas 'active' ke item yang diklik
        this.closest('li.sidebar-item').classList.add('active');

        // Jika klik pada dropdown toggle, simpan ID dropdown ke localStorage
        if (this.classList.contains('collapsed')) {
            const targetDropdown = this.getAttribute('data-bs-target').substring(1); // Ambil ID tanpa '#'
            localStorage.setItem('openDropdownId', targetDropdown);
        } else {
            // Hapus ID dropdown dari localStorage jika item di dalam dropdown diklik
            localStorage.removeItem('openDropdownId');
        }
    });
});

// Saat halaman dimuat, panggil fungsi untuk menetapkan link aktif
window.addEventListener('load', setActiveLink);




//tabel


