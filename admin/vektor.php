<?php
session_start();
require "../function.php";
require "vektortambah.php";
require "../cek.php";
require "header.php";
?>
<style>
    .radio-options {
        display: flex;
        gap: 10px; /* Atur jarak sesuai kebutuhan */
        align-items: center; /* Mengatur sejajar vertikal jika diperlukan */
        margin-left: 100px;
    }

    .radio-options input[type="radio"] {
        margin-right: -10px; /* Jarak antara radio button dan label */
    }

    .radio-options label {
        margin-right: 30px; /* Jarak antara label yang satu dengan yang lain */
    }
</style>
<style>
    .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Aligns items to the left and right */
            font-weight: bold;
            font-size: 1.5em;
        }
    .card-header .header-title {
            text-align: center;
            flex: 1; /* Takes up remaining space */
        }
    .modal-footer {
        display: flex;
        justify-content: center;
    }
    .modal-footer button {
        width: 350px; /* Atur ukuran lebar tombol sesuai keinginan Anda */
        margin: 10px;
        border-radius: 5px; /* Membuat sudut tombol menjadi bundar */
        font-size: 16px; /* Atur ukuran font tombol */
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #007bff;
        color: white;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #4e73df;
        border-color: #004085;
    }

    /* Table Styling */
    table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff; /* White background */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Enhanced shadow for depth */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
            font-size: 0.95em; /* Slightly smaller font for a refined look */
        }
        table thead {
            background-color: #343a40; /* Dark grey background for header */
            color: #ffffff; /* White text */
            text-align: left;
            font-size: 1.1em; /* Larger font size for headers */
        }
        table th, table td {
            padding: 15px 20px; /* Increased padding for better spacing */
            border-bottom: 1px solid #dee2e6;
        }
        table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease; /* Smooth transition */
        }
        table tbody tr:nth-child(even) {
            background-color: #f8f9fc; /* Light blue-grey for alternate rows */
        }
        table tbody tr:hover {
            background-color: #e2e6ea; /* Slightly darker hover effect */
            transform: scale(1.02); /* Subtle scaling effect */
            cursor: pointer;
        }

        /* Plus Icon Button Styling */
        .card-header .btn-primary {
            background-color: #4e73df; /* Warna biru gelap */
            border-color: #4e73df; /* Warna biru gelap */
        }

        .card-header .btn-primary:hover {
            background-color: #2e59d9; /* Warna biru lebih gelap saat hover */
            border-color: #2653a0;
        }
        table tbody tr {
            cursor: pointer;
        }
        /* Tampilan pada perangkat seluler */
@media (max-width: 767.98px) {
    .table {
        width: 100%; /* Membuat tabel mengambil lebar penuh */
    }

    .no-border-table td {
        padding: 0.5rem; /* Mengatur padding sel tabel */
        vertical-align: top; /* Menyusun sel agar rata di atas */
    }

    .radio-options {
        display: flex; /* Mengatur radio-options menggunakan flexbox */
        flex-wrap: wrap; /* Membungkus item radio jika tidak muat dalam satu baris */
        gap: 0.5rem; /* Jarak antar item radio */
        align-items: center; /* Menyusun item agar sejajar secara vertikal */
        margin: 0; /* Menghapus margin default */
    }

    .radio-options input[type="radio"] {
        margin-right: 0.25rem; /* Jarak antara radio button dan label */
    }

    .radio-options label {
        margin: 0; /* Menghapus margin default dari label */
        font-size: 0.875rem; /* Ukuran font label lebih kecil */
        line-height: 1.2; /* Jarak antar baris label */
        display: flex; /* Menyusun label dan radio button dalam satu baris */
        align-items: center; /* Menyusun label secara vertikal dengan radio button */
    }

    .no-border-table td {
        padding: 0.25rem; /* Mengurangi padding untuk sel tabel agar lebih kompak */
    }
}

</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('#datatablesSimple');

        table.addEventListener('click', function (event) {
            const row = event.target.closest('tr');
            
            if (row) {
                const link = row.querySelector('a');
                
                if (link) {
                    // Ambil parameter ID dari URL tautan
                    const userId = new URL(link.href).searchParams.get('id');
                    
                    if (userId) {
                        // Redirect ke halaman vektorlihat.php dengan ID yang diambil
                        window.location.href = `vektoredit.php?id=${userId}`;
                    } else {
                        alert('ID Pengendalian Vektor tidak valid.');
                    }
                }
            }
        });
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <br>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Ke Halaman Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Pengendalian Vektor</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="header-title">Data Pengendalian Vektor</div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-bs-toggle="tooltip" title="Tambah Data"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" data-bs-toggle="tooltip" title="Klik untuk melihat detail data">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pasien</th>
                                    <th>Penyuluhan</th>
                                    <th>PSN 3M</th>
                                    <th>Larvasidasi</th>
                                    <th>Fogging</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1; // Inisialisasi di luar loop
                                $ambildatavektor = mysqli_query($conn, "SELECT v.*, p.namapasien FROM pengendalianvektor v JOIN pasien p ON p.id = v.id");
                                while ($row = mysqli_fetch_array($ambildatavektor)) {
                                    $namapasien = $row['namapasien'];
                                    $penyuluhan = $row['penyuluhan'];
                                    $psn3m = $row['psn3m'];
                                    $larvasidasi = $row['larvasidasi'];
                                    $fogging = $row['fogging'];
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $namapasien; ?></td>
                                    <td><?php echo $penyuluhan; ?></td>
                                    <td><?php echo $psn3m; ?></td>
                                    <td><?php echo $larvasidasi; ?></td>
                                    <td><?php echo $fogging; ?></td>
                                    <td>
                                        <div>
                                            <a href="vektorhapus.php?id=<?php echo $row['idpengendalian'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-bs-toggle="tooltip" title="Hapus Data"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
<!-- The Modal -->
<style>
.dropdown-container {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    background-color: #fff;
    z-index: 1000;
}

.dropdown-menu div {
    padding: 8px;
    cursor: pointer;
}

.dropdown-menu div:hover {
    background-color: #f1f1f1;
}
</style>
<!-- Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pengendalian Vektor</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Body Modal -->
            <form method="post" action="tambahvektor.php">
                <div class="modal-body">
                    <label for="searchInput">Nama Pasien</label>
                    <div class="dropdown-container">
                        <input type="text" id="searchInput" name="namapasien" class="form-control" placeholder="Cari Pasien..." autocomplete="off">
                        <div id="dropdownMenu" class="dropdown-menu">
                            <!-- Daftar pasien akan diisi oleh JavaScript -->
                        </div>
                    </div>
                    <!-- Input tersembunyi untuk ID pasien -->
                    <input type="hidden" id="patientId" name="id">
                    <p>
                    <table class="table no-border-table">
                        <tbody>
                            <tr>
                                <td>Penyuluhan</td>
                                <td>
                                    <div class="radio-options">
                                        <input name="penyuluhan" type="radio" value="Pernah" required id="penyuluhan_pernah">
                                        <label for="penyuluhan_pernah">Pernah</label>
                                        <input name="penyuluhan" type="radio" value="Tidak Pernah" required id="penyuluhan_tidak_pernah">
                                        <label for="penyuluhan_tidak_pernah">Tidak Pernah</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>PSN 3M</td>
                                <td>
                                    <div class="radio-options">
                                        <input name="psn3m" type="radio" value="Pernah" required id="psn3m_pernah">
                                        <label for="psn3m_pernah">Pernah</label>
                                        <input name="psn3m" type="radio" value="Tidak Pernah" required id="psn3m_tidak_pernah">
                                        <label for="psn3m_tidak_pernah">Tidak Pernah</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Larvasidasi</td>
                                <td>
                                    <div class="radio-options">
                                        <input name="larvasidasi" type="radio" value="Pernah" required id="larvasidasi_pernah">
                                        <label for="larvasidasi_pernah">Pernah</label>
                                        <input name="larvasidasi" type="radio" value="Tidak Pernah" required id="larvasidasi_tidak_pernah">
                                        <label for="larvasidasi_tidak_pernah">Tidak Pernah</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Fogging</td>
                                <td>
                                    <div class="radio-options">
                                        <input name="fogging" type="radio" value="Pernah" required id="fogging_pernah">
                                        <label for="fogging_pernah">Pernah</label>
                                        <input name="fogging" type="radio" value="Tidak Pernah" required id="fogging_tidak_pernah">
                                        <label for="fogging_tidak_pernah">Tidak Pernah</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="tambahvektor" data-bs-toggle="tooltip" title="Simpan Data">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInput');
    var dropdownMenu = document.getElementById('dropdownMenu');
    var patientIdInput = document.getElementById('patientId');

    function loadPatients() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_patients.php', true); // Ganti dengan URL endpoint yang benar
        xhr.onload = function() {
            if (this.status === 200) {
                var patients = JSON.parse(this.responseText);
                var html = '';
                patients.forEach(function(patient) {
                    html += '<div data-id="' + patient.id + '">' + patient.namapasien + '</div>';
                });
                dropdownMenu.innerHTML = html;
            }
        };
        xhr.send();
    }

    loadPatients();

    searchInput.addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var items = dropdownMenu.querySelectorAll('div[data-id]');
        var visibleItems = false;

        items.forEach(function(item) {
            var text = item.textContent || item.innerText;
            if (text.toUpperCase().indexOf(filter) > -1) {
                item.style.display = '';
                visibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        dropdownMenu.style.display = visibleItems ? 'block' : 'none';
    });

    dropdownMenu.addEventListener('click', function(event) {
        var target = event.target;
        if (target.dataset.id) {
            searchInput.value = target.textContent;
            patientIdInput.value = target.dataset.id; // Set ID pasien ke input tersembunyi
            dropdownMenu.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdownMenu.contains(event.target) && event.target !== searchInput) {
            dropdownMenu.style.display = 'none';
        }
    });

    searchInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            var selectedItem = dropdownMenu.querySelector('div:not([style*="display: none"])');
            if (selectedItem) {
                searchInput.value = selectedItem.textContent;
                patientIdInput.value = selectedItem.dataset.id; // Set ID pasien ke input tersembunyi
                dropdownMenu.style.display = 'none';
            }
            event.preventDefault(); // Mencegah pengiriman form saat menekan enter
        }
    });
});
</script>
