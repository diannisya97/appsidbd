<!-- nav.php -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Logo -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars" data-bs-toggle="tooltip" title="Toggle Sidebar"></i>
    </button>
    <div class="navbar-brand ps-3" style="text-align: left;">
        <a href="dashboard.php" data-bs-toggle="tooltip" title="Ke Dashboard">
            <img src="../travelista-master/img/logo.png" alt="Logo" class="img-fluid" style="max-width: 80%; height: auto;">
        </a>
    </div>
    
    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
    
    <!-- Navbar User Menu -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <!-- History Icon -->
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#historyModal" 
               data-bs-toggle="tooltip" title="Lihat Histori">
                <i class="fas fa-history"></i>
            </a>
        </li>
        
        <!-- User Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" 
               data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip" title="Akun Anda">
                <?php
                if (isset($_SESSION["username"]) && $_SESSION["username"] != "") {
                    echo $_SESSION["username"];
                } else {
                    echo "Username tidak tersedia";
                }
                ?>
                <i class="fas fa-user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="profil.php?id=<?php echo $_SESSION['id']; ?>" 
                       data-bs-toggle="tooltip" title="Lihat Profil Anda">
                        Profil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="logout.php" data-bs-toggle="tooltip" title="Keluar dari Sistem">
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Modal for History -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Histori Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="historyContent">
    <p class="text-center">Memuat data...</p>
</div>

<div class="modals-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>

<style>
    /* Menetapkan posisi sticky untuk footer modal */
    .modals-footer {
        position: sticky;
        bottom: 0;
        background-color: white; /* Bisa sesuaikan dengan warna background modal */
        padding: 10px;
        z-index: 10; /* Untuk memastikan tombol tetap di atas konten jika ada elemen lain */
        border-top: 1px solid #ddd; /* Menambahkan garis pemisah jika diinginkan */
    }

    /* Menambahkan margin dan padding agar tombol tidak terganggu */
    .modal-body {
        max-height: 700px; /* Atur tinggi sesuai dengan kebutuhan */
        overflow-y: auto;
    }
</style>

        </div>
    </div>
</div>


<!-- Bootstrap core JavaScript-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<style>
    /* Mengatur logo tetap di kiri */
    .navbar-brand {
        margin-right: auto;
        padding-left: 15px;
    }

    /* Menghapus padding default pada elemen ul */
    .navbar-nav {
        margin-left: auto;
    }

    /* Samakan warna background dengan sidebar */
    .navbar {
        background-color: #343a40; /* Sama dengan warna sidebar */
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const historyModal = document.getElementById('historyModal');
        const historyContent = document.getElementById('historyContent');

        historyModal.addEventListener('show.bs.modal', function () {
            // Tampilkan loading state
            historyContent.innerHTML = "<p class='text-center'>Memuat data...</p>";

            // Kirim AJAX request
            fetch('function.php')
                .then(response => response.text())
                .then(data => {
                    historyContent.innerHTML = data;
                })
                .catch(error => {
                    historyContent.innerHTML = "<p class='text-danger text-center'>Gagal memuat data.</p>";
                    console.error("Error:", error);
                });
        });
    });
</script>
