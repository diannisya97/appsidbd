<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Ambil parameter pencarian dari query string
$name = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';
$address = isset($_GET['address']) ? mysqli_real_escape_string($conn, $_GET['address']) : '';
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// Buat query dasar dengan join tambahan untuk tabel pengendalianvektor
$query = "SELECT per.id, p.namapasien, p.nik, p.tanggallahir, p.tempatlahir, p.jeniskelamin, p.alamat, p.domisili, p.desakelurahan,
                 per.tanggalperiksa, per.tanggalgejala, per.trombositturun, per.mimisan, per.nyeriperut, per.perasaansembuh,
                 per.suhutubuh, per.sakitkepala, per.muntah, per.nyerisendi, per.mual, per.ptkie, per.infeksitenggorok, 
                 per.sakitbolamata, per.lainnya, per.iggm, per.igm, per.ns1, per.tombosit, per.hematokrit, per.hb, 
                 per.leukosit, per.eritrosit, per.pernahranap, per.namars, per.tanggalmasuk, per.ruangrawat, per.namarssebelum,
                 per.statuspasienakhir, per.periksajentik, per.pjpemeriksa, per.diagnosislab, per.diagnosisklinis,
                 pv.penyuluhan, pv.psn3m, pv.larvasidasi, pv.fogging,
                 IF((SELECT COUNT(*) FROM periksapasien p2 WHERE p2.namapasien = p.id) > 0, 'Ada', 'Tidak ada') AS ada_periksapasien,
                 IF((SELECT COUNT(*) FROM jentikperiksa j WHERE j.namapemilik = p.id) > 0, 'Ada', 'Tidak ada') AS ada_jentikperiksa
          FROM periksapasien per
          JOIN pasien p ON p.id = per.namapasien
          LEFT JOIN pengendalianvektor pv ON pv.id = p.id
          WHERE 1=1";

// Tambahkan filter berdasarkan parameter pencarian
if (!empty($name)) {
    $query .= " AND (p.namapasien LIKE '%$name%' OR p.nik LIKE '%$name%')";
}

if (!empty($address)) {
    $query .= " AND (p.alamat LIKE '%$address%' OR p.desakelurahan LIKE '%$address%')";
}

if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND per.tanggalperiksa BETWEEN '$start_date' AND '$end_date'";
} elseif (!empty($start_date)) {
    $query .= " AND per.tanggalperiksa >= '$start_date'";
} elseif (!empty($end_date)) {
    $query .= " AND per.tanggalperiksa <= '$end_date'";
}

// Jalankan query
$ambildatapasien = mysqli_query($conn, $query);

// Cek jika ada error
if (!$ambildatapasien) {
    die('Query Error: ' . mysqli_error($conn));
}
?>
<?php

// Periksa apakah ada pesan pemberitahuan di session
if (isset($_SESSION['message'])) {
    // Tampilkan pesan pemberitahuan
    echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";

    // Hapus pesan setelah ditampilkan untuk mencegah muncul berulang kali
    unset($_SESSION['message']);
}
?>

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
/* Style umum untuk desktop */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}

.form-inline .form-group {
    margin-bottom: 10px;
}

.btn-custom {
    width: auto;
    margin-left: 10px;
}

.btn-primary, .btn-success {
    padding: 10px 15px;
    font-size: 14px;
}

/* Style khusus untuk mobile */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: stretch;
    }

    .form-inline {
        flex-direction: column;
        align-items: stretch;
    }

    .form-inline .form-group {
        width: 100%;
        margin: 0 auto 10px;
    }

    .form-control {
        width: 100%;
    }

    .btn-primary, .btn-success {
        width: 100%;
        margin-bottom: 10px;
    }

    .header-title {
        text-align: center;
        margin-bottom: 10px;
    }

    .btn-custom {
        width: 100%;
        margin-top: 10px;
    }

    /* Khusus tampilan mobile, input berdampingan */
    .form-inline .form-group {
        width: 100%;
    }

    .d-flex-mobile {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .form-group-mobile {
        flex: 1;
        margin-right: 5px;
    }

    .form-group-mobile:last-child {
        margin-right: 0;
    }
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#datatablesSimplee');

    table.addEventListener('click', function (event) {
        const row = event.target.closest('tr');

        if (row) {
            const link = row.querySelector('a');

            if (link) {
                console.log('Tautan diklik:', link.href); // Debugging URL tautan
                const userId = new URL(link.href).searchParams.get('id');
                
                console.log('ID yang diambil:', userId); // Debugging ID yang diambil

                if (userId) {
                    window.location.href = `periksalihat.php?id=${userId}`;
                } else {
                    alert('ID Periksa tidak valid.');
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
                        <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Dashboard"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pemeriksaan</li>
                    </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="header-title">Data Pemeriksaan Pasien</div>
                        <a class="btn btn-primary" href="periksatambah.php" role="button" data-bs-toggle="tooltip" title="Tambah Data Pemeriksaan">
                            <i class="fas fa-plus"></i>
                        </a>
                        <button type="button" class="btn btn-success ms-1" id="exportButton"  data-bs-toggle="tooltip" title="Export Data"
                            onclick="window.location.href='exportexcel.php?name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&address=<?php echo isset($_GET['address']) ? urlencode($_GET['address']) : ''; ?>&start_date=<?php echo isset($_GET['start_date']) ? urlencode($_GET['start_date']) : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? urlencode($_GET['end_date']) : ''; ?>'">
                            <i class="fas fa-file-download"></i> Export Excel
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 justify-content-center">
                            <!-- Filter Pencarian -->
                            <div class="col-md-8">
                                <form action="" method="get" class="form-inline d-flex align-items-center justify-content-center" >
                                    <input type="hidden" name="page" value="data-member">
                                    
                                    <!-- Filter Nama Pasien dan NIK -->
                                    <div class="form-group mx-2">
                                        <label for="name" class="sr-only">Nama Pasien/NIK</label>
                                        <input type="text" data-bs-toggle="tooltip" title="Cari Berdasarkan Nama/NIK" class="form-control" id="name" name="name" placeholder="Nama Pasien/NIK" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
                                    </div>
                                    
                                    <!-- Filter Alamat dan Desa/Kelurahan -->
                                    <div class="form-group mx-2">
                                        <label for="address" class="sr-only">Alamat</label>
                                        <input type="text" data-bs-toggle="tooltip" title="Cari Berdasarkan Alamat" class="form-control" id="address" name="address" placeholder="Alamat" value="<?php echo isset($_GET['address']) ? htmlspecialchars($_GET['address']) : ''; ?>">
                                    </div>
                                    
                                    <!-- Filter Tanggal Awal dan Akhir -->
                                    <div class="form-group mx-2">
                                        <label for="start_date" class="sr-only">Tanggal Awal Periksa</label>
                                        <input type="date" data-bs-toggle="tooltip" title="Dari" class="form-control" id="start_date" name="start_date" placeholder="Tanggal Awal" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                                    </div>
                                    <div class="form-group mx-2">
                                        <label for="end_date" class="sr-only">Tanggal Akhir Periksa</label>
                                        <input type="date" data-bs-toggle="tooltip" title=" Sampai" class="form-control" id="end_date" name="end_date" placeholder="Tanggal Akhir" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-custom" data-bs-toggle="tooltip" title="Cari Data">Cari</button>
                                </form>
                            </div>
                        </div>

                        <table id="datatablesSimplee" data-bs-toggle="tooltip" title="Klik untuk melihat detail pemeriksaan">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIK</th>
                                    <th>Nama Pasien</th>
                                    <th>Tanggal Periksa</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1; // Inisialisasi di luar loop
                                while ($row = mysqli_fetch_array($ambildatapasien)) {
                                    $id = $row['id'];
                                    $namapasien = $row['namapasien'];
                                    $tanggalperiksa = $row['tanggalperiksa'];
                                    $alamat = $row['alamat'];
                                    $nik = $row['nik'];
                                    $tanggallahir = $row['tanggallahir'];
                                    ?>
                                    <tr data-bs-toggle="tooltip" title="Klik Lihat Detail Data">
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $nik; ?></td>
                                        <td><?php echo $namapasien; ?></td>
                                        <td><?php echo $tanggalperiksa; ?></td>
                                        <td><?php echo $alamat; ?></td>
                                        <td><?php echo $tanggallahir; ?></td>
                                        <td>
                                            <div>
                                                <a href="periksahapus.php?id=<?php echo $id ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Data" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
                </script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"></script>

<script>
window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimplee = document.getElementById('datatablesSimplee');
    if (datatablesSimplee) {
        new simpleDatatables.DataTable(datatablesSimplee, {
            searchable: false, // Menonaktifkan pencarian
            perPageSelect: false // Menonaktifkan dropdown pemilihan jumlah entri per halaman
        });
    }
});

</script>


</html>