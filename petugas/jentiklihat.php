<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id from URL
$idjentik = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idjentik) || !is_numeric($idjentik)) {
    echo "<script>alert('ID jentik tidak valid!'); window.location.href='jentik.php';</script>";
    exit();
}

$idjentik = intval($idjentik); // Ensure the id is an integer

// Fetch data for the given id with JOIN to get the actual names and additional fields
$query = "
    SELECT jentikperiksa.*, 
           koordinator.namakoor AS nama_koordinator, 
           pasien.namapasien AS nama_pemilik, 
           pasien.rt, 
           pasien.rw, 
           pasien.desakelurahan 
    FROM jentikperiksa
    LEFT JOIN koordinator ON jentikperiksa.namakoor = koordinator.id
    LEFT JOIN pasien ON jentikperiksa.namapemilik = pasien.id
    WHERE jentikperiksa.id = ?
";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idjentik);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

// Use $data as needed, for example:
echo "Nama Pemilik: " . htmlspecialchars($data['nama_pemilik']);
echo "RT: " . htmlspecialchars($data['rt']);
echo "RW: " . htmlspecialchars($data['rw']);
echo "Desa/Kelurahan: " . htmlspecialchars($data['desakelurahan']);
?>

    <style>
        /* CSS untuk lebar tabel */
        .table {
            width: 100%; /* Atur tabel untuk mengambil 100% lebar kontainer */
        }
        .table th,
        .table td {
            vertical-align: middle; /* Agar konten sel tabel rata tengah secara vertikal */
        }
        .table td {
            padding: 10px; /* Menambahkan padding pada sel tabel */
        }
    </style>
    <style>
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: bold;
        font-size: 1.5em;
    }
    .card-header .header-title {
        text-align: center;
        flex: 1;
    }
</style>
<style>
    .btn-success {
        background-color: #4e73df; /* Warna latar belakang tombol */
        border-color: #4e73df;     /* Warna border tombol */
        color: #fff;               /* Warna teks tombol */
        padding: 0.5rem 1rem;      /* Padding tombol */
        border-radius: 0.25rem;    /* Sudut melengkung tombol */
        font-size: 0.875rem;       /* Ukuran font */
    }
    .btn-success:hover {
        background-color: #2e59d9; /* Warna latar belakang saat hover */
        border-color: #2653d4;     /* Warna border saat hover */
    }
    
</style>
<script>
function handleBack() {
    // Periksa apakah halaman sebelumnya adalah puskesmasedit.php
    if (document.referrer.includes('jentikedit.php')) {
        // Jika ya, arahkan ke puskesmas.php
        window.location.href = 'jentik.php';
    } else {
        // Jika tidak, gunakan history.back() untuk kembali ke halaman sebelumnya
        history.back();
    }
}
</script>

<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="jentik.php" data-bs-toggle="tooltip" title="Ke Halaman Data pemeriksaan Jentik">Data Pemeriksaan Jentik</a></li>
                    <li class="breadcrumb-item active">Detail Pemeriksaan Jentik</li>
                </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <a href="#" class="btn btn-secondary" onclick="handleBack(); return false;"data-bs-toggle="tooltip" title="Ke Halaman Sebelumnya"><i class="fas fa-arrow-left" ></i> Kembali</a>
                            <div class="header-title">Detail Pemeriksaan Jentik</div>
                            <a href="jentikedit.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Edit Data"><i class="fas fa-edit" style="color: #fff;"></i> Edit Data</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" style="font-weight: bold; text-align: center;">Data Penduduk</th> <!-- Menggabungkan th pertama dan kedua -->
                                    <th colspan="2" style="font-weight: bold; text-align: center;">Data Pemeriksaan Jentik</th> <!-- Menggabungkan th ketiga dan keempat -->
                                </tr>
                                <tr>
                                    <th>Nama Koordinator</th>
                                    <td><?php echo htmlspecialchars($data['nama_koordinator']); ?></td>
                                    <th>Bak Mandi</th>
                                    <td><?php echo $data['bakmandi'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Pemilik Rumah</th>
                                    <td><?php echo htmlspecialchars($data['nama_pemilik']); ?></td>
                                    <th>Dispenser</th>
                                    <td><?php echo $data['dispenser'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <th>RT</th>
                                    <td><?php echo htmlspecialchars($data['rt']); ?></td>
                                    <th>Penampungan Hujan</th>
                                    <td><?php echo $data['penampung'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <th>RW</th>
                                    <td><?php echo htmlspecialchars($data['rw']); ?></td>
                                    <th>Pot Bunga</th>
                                    <td><?php echo $data['potbunga'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <th>Kelurahan</th>
                                    <td><?php echo htmlspecialchars($data['desakelurahan']); ?></td>
                                    <th>Tempat Minum Hewan</th>
                                    <td><?php echo $data['tempatminumhewan'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pemeriksaan</th>
                                    <td><?php echo htmlspecialchars($data['tanggalrekap']); ?></td>
                                    <th>Ban Bekas</th>
                                    <td><?php echo $data['banbekas'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <th>Pohon</th>
                                    <td><?php echo $data['pohon'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <th>Sampah</th>
                                    <td><?php echo $data['sampah'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <th>Lainnya</th>
                                    <td><?php echo $data['lainnya'] == 'ada' ? 'Ada' : 'Tidak Ada'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script>
        // JavaScript untuk inisialisasi DataTable
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('.table');
            if (table) {
                new DataTable(table);
            }
        });
    </script>
</body>
</html>
