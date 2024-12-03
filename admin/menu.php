<?php
// Dapatkan nama file yang sedang diakses
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark bg-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Menu</div>
                    <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'pasien.php') ? 'active' : ''; ?>" href="pasien.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Pasien
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'periksa.php') ? 'active' : ''; ?>" href="periksa.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-notes-medical"></i></div>
                        Pemeriksaan Klinis
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'jentik.php') ? 'active' : ''; ?>" href="jentik.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-microscope"></i></div>
                        Pemeriksaan Jentik
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'vektor.php') ? 'active' : ''; ?>" href="vektor.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-bug"></i></div>
                        Pengendalian Vektor
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'puskesmas.php') ? 'active' : ''; ?>" href="puskesmas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-clinic-medical"></i></div>
                        Fasilitas Kesehatan
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'koordinator.php') ? 'active' : ''; ?>" href="koordinator.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                        Koordinator
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'pengguna.php') ? 'active' : ''; ?>" href="pengguna.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                        Pengguna
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'infografis.php') ? 'active' : ''; ?>" href="infografis.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-pie"></i></div>
                        Infografis
                    </a>
                    <a class="nav-link <?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>" href="logout.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                        Logout
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:
                    <?php
                    if (isset($_SESSION["username"]) && $_SESSION["username"] != "") {
                        echo $_SESSION["username"];
                    } else {
                        echo "Username tidak tersedia";
                    }
                    ?>
                </div>
            </div>
        </nav>
</div>


<script>
    document.getElementById("sidebarToggle").addEventListener("click", function() {
        var sidenav = document.getElementById("layoutSidenav_nav");
        sidenav.classList.toggle("sb-sidenav-toggled");
    });
</script>

<style>
    /* Memastikan ikon tetap muncul saat ditoggle */
    #layoutSidenav_nav.sb-sidenav-toggled .sb-sidenav-menu .nav-link {
        justify-content: left;
        text-align: center;
        padding: 0.75rem;
    }

    #layoutSidenav_nav.sb-sidenav-toggled .sb-nav-link-icon {
        margin-right: 0;
    }

    /* Mengatur lebar sidebar saat ditoggle */
    #layoutSidenav_nav.sb-sidenav-toggled {
        width: 80px;
    }

    /* Menyembunyikan teks menu saat ditoggle */
    #layoutSidenav_nav.sb-sidenav-toggled .sb-sidenav-menu .nav-link span {
        display: none;
    }

    /* Perbaikan padding dan penataan elemen */
    .sb-sidenav-menu .nav-link {
        display: flex;
        align-items: center;
    }

    .sb-sidenav-menu .sb-nav-link-icon {
        margin-right: 10px;
    }

    /* Menyembunyikan teks di footer saat ditoggle */
    #layoutSidenav_nav.sb-sidenav-toggled .sb-sidenav-footer .small {
        display: none;
    }

    /* Memastikan footer tetap terpusat */
    #layoutSidenav_nav.sb-sidenav-toggled .sb-sidenav-footer {
        text-align: center;
        font-size: 0.8rem;
    }

    /* Samakan warna sidebar dengan navbar */
    .sb-sidenav {
        background-color: #343a40; /* Sama dengan warna navbar */
    }
</style>
