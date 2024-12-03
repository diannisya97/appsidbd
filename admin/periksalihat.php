<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";
?>
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die('ID Pemeriksaan tidak valid!');
}

// Kode untuk mengambil dan menampilkan data berdasarkan ID
?>
<?php
// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die('ID Pemeriksaan tidak valid!');
}

// Fetch data for the given id from periksapasien table
$query = "SELECT * FROM periksapasien WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

// Fetch patient name for display
$ambildatapasien = mysqli_query($conn, "SELECT p.namapasien FROM periksapasien u JOIN pasien p ON p.id = u.namapasien WHERE u.id = $id");
$row = mysqli_fetch_array($ambildatapasien);
$namapasien = $row['namapasien'];

// Fetch koordinator name
$koordinator_id = $data['pjpemeriksa'];
$query_koordinator = "SELECT namakoor FROM koordinator WHERE id = ?";
if ($stmt_koordinator = $conn->prepare($query_koordinator)) {
    $stmt_koordinator->bind_param("i", $koordinator_id);
    $stmt_koordinator->execute();
    $result_koordinator = $stmt_koordinator->get_result();
    $row_koordinator = $result_koordinator->fetch_assoc();
    $nama_koordinator = $row_koordinator['namakoor'];
    $stmt_koordinator->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemeriksaan Pasien</title>
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
    <style>
        /* Custom CSS for table layout */
        .table {
            width: 100%;
            margin: 0 auto;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table .title-col {
            width: 25%;
            font-weight: bold;
        }
        .table .value-col {
            width: 25%;
        }
        .category-title {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .text-start {
            text-align: left;
        }
        .fw-bold {
            font-weight: bold;
        }
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
        .breadcrumb {
    background-color: #f8f9fa; /* Warna latar belakang terang */
    border: 1px solid #dee2e6; /* Border abu-abu terang */
    border-radius: 0.25rem; /* Membuat sudut membulat */
    padding: 8px 15px; /* Padding dalam breadcrumb */
    margin-bottom: 15px; /* Jarak bawah breadcrumb */
}

.breadcrumb-item a {
    color: #4e73df; /* Warna biru utama */
    text-decoration: none; /* Hilangkan garis bawah */
    font-weight: 500; /* Menambah ketebalan teks */
}

.breadcrumb-item a:hover {
    text-decoration: underline; /* Tambahkan garis bawah saat hover */
    color: #2e59d9; /* Warna biru lebih gelap saat hover */
}

.breadcrumb-item.active {
    color: #6c757d; /* Warna abu-abu untuk item aktif */
    font-weight: bold; /* Teks tebal untuk item aktif */
}
    </style>
    <script>
        function handleBack() {
            // Periksa apakah halaman sebelumnya adalah puskesmasedit.php
            if (document.referrer.includes('periksaedit.php')) {
                // Jika ya, arahkan ke puskesmas.php
                window.location.href = 'periksa.php';
            } else {
                // Jika tidak, gunakan history.back() untuk kembali ke halaman sebelumnya
                history.back();
            }
        }
</script>
</head>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
    <main>
    <div class="container-fluid px-4">
        <br>
        <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Dashboard"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="periksa.php" data-bs-toggle="tooltip" title="Data Pemeriksaan">Data Pemeriksaan</a></li>
                        <li class="breadcrumb-item active">Detail Data Pemeriksaan</li>
                    </ol>
        <div class="card mb-4">
            <div class="card-header">
                <a href="#" class="btn btn-secondary" onclick="handleBack(); return false;"data-bs-toggle="tooltip" title="Kembali Ke Halaman Sebelumnya"><i class="fas fa-arrow-left" ></i> Kembali</a>
                <div class="header-title">Detail Pemeriksaan Pasien</div>
                <a href="periksaedit.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Edit Data"> <i class="fas fa-edit" style="color: #fff;"></i> Edit Data</a>
            </div>
                    
            <div class="card-body">
                <!-- Informasi Pasien -->
                <div class="category-title">Informasi Pasien</div>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Pasien</th>
                        <td><?php echo htmlspecialchars($namapasien); ?></td>
                        <th>Tanggal Pemeriksaan</th>
                        <td><?php echo htmlspecialchars($data['tanggalperiksa']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Gejala</th>
                        <td><?php echo htmlspecialchars($data['tanggalgejala']); ?></td>
                        <th>Suhu Tubuh</th>
                        <td><?php echo htmlspecialchars($data['suhutubuh']) . ' °C'; ?></td>
                    </tr>
                    <tr>
                        <th>Mimisan</th>
                        <td><?php echo htmlspecialchars($data['mimisan']); ?></td>
                        <th>Nyeri Perut</th>
                        <td><?php echo htmlspecialchars($data['nyeriperut']); ?></td>
                    </tr>
                    <tr>
                        <th>Perasaan Sembuh</th>
                        <td><?php echo htmlspecialchars($data['perasaansembuh']); ?></td>
                        <th>Trombosit Turun</th>
                        <td><?php echo htmlspecialchars($data['trombositturun']); ?></td>
                    </tr>
                    <tr>
                        <th>Sakit Kepala</th>
                        <td><?php echo htmlspecialchars($data['sakitkepala']); ?></td>
                        <th>Muntah</th>
                        <td><?php echo htmlspecialchars($data['muntah']); ?></td>
                    </tr>
                    <tr>
                        <th>Nyeri Sendi</th>
                        <td><?php echo htmlspecialchars($data['nyerisendi']); ?></td>
                        <th>Mual</th>
                        <td><?php echo htmlspecialchars($data['mual']); ?></td>
                    </tr>
                    <tr>
                        <th>PTKIE</th>
                        <td><?php echo htmlspecialchars($data['ptkie']); ?></td>
                        <th>Infeksi Tenggorokan</th>
                        <td><?php echo htmlspecialchars($data['infeksitenggorok']); ?></td>
                    </tr>
                    <tr>
                        <th>Sakit Bola Mata</th>
                        <td><?php echo htmlspecialchars($data['sakitbolamata']); ?></td>
                        <th>Lainnya</th>
                        <td><?php echo htmlspecialchars($data['lainnya']); ?></td>
                    </tr>
                </table>

                <!-- Pemeriksaan Lab Darah -->
                <div class="category-title">Pemeriksaan Lab Darah</div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nilai IGGM</th>
                            <td><?php echo htmlspecialchars($data['iggm']); ?></td>
                            <th>Nilai IGM</th>
                            <td><?php echo htmlspecialchars($data['igm']); ?></td>
                            <th>Nilai NS-1</th>
                            <td><?php echo htmlspecialchars($data['ns1']); ?></td>
                        </tr>
                        <tr>
                            <th>Nilai Trombosit</th>
                            <td><?php echo htmlspecialchars($data['tombosit']); ?></td>
                            <th>Nilai Hematokrit</th>
                            <td><?php echo htmlspecialchars($data['hematokrit']); ?></td>
                            <th>Nilai Hemaglobin</th>
                            <td><?php echo htmlspecialchars($data['hb']); ?></td>
                        </tr>
                        <tr>
                            <th>Nilai Leukosit</th>
                            <td><?php echo htmlspecialchars($data['leukosit']); ?></td>
                            <th>Nilai Eritrosit</th>
                            <td><?php echo htmlspecialchars($data['eritrosit']); ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Diagnosis -->
                <div class="category-title">Diagnosis</div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Diagnosis Laboratorium</th>
                            <td><?php echo htmlspecialchars($data['diagnosislab']); ?></td>
                            <th>Diagnosis Klinis</th>
                            <td><?php echo htmlspecialchars($data['diagnosisklinis']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Hasil Pemeriksaan Perawatan Sebelumnya -->
                <div class="category-title">Hasil Pemeriksaan Perawatan Sebelumnya</div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Pernah Rawat Inap</th>
                            <td><?php echo htmlspecialchars($data['pernahranap']); ?></td>
                            <th>Nama Rumah Sakit</th>
                            <td><?php echo htmlspecialchars($data['namars']); ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Rawat Inap</th>
                            <td><?php echo htmlspecialchars($data['tanggalmasuk']); ?></td>
                            <th>Ruang Rawat Inap</th>
                            <td><?php echo htmlspecialchars($data['ruangrawat']); ?></td>
                        </tr>
                        <tr>
                            <th>Nama RS Sebelum di Rawat</th>
                            <td><?php echo htmlspecialchars($data['namarssebelum']); ?></td>
                            <th>Status Pasien Keluar</th>
                            <td><?php echo htmlspecialchars($data['statuspasienakhir']); ?></td>
                        </tr>
                        <tr>
                            <th>Pemeriksaan Jentik Nyamuk</th>
                            <td><?php echo htmlspecialchars($data['periksajentik']); ?></td>
                            <th>Penanggung Jawab Pemeriksaan</th>
                            <td><?php echo htmlspecialchars($nama_koordinator); ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Keluar Perawatan</th>
                            <td><?php echo htmlspecialchars($data['tgl_keluar_perawatan']); ?></td>
                            <th>File Pemeriksaan KDRS</th>
                            <td>
                                <?php if (!empty($data['upload_file_kdrs'])): ?>
                                    <a href="../KDRS/<?php echo htmlspecialchars($data['upload_file_kdrs']); ?>" class="btn btn-primary" download data-bs-toggle="tooltip" title="Unduh File KDRS">Unduh KDRS
                                    <i class="fas fa-download" ></i> 
                                    </a>
                                <?php else: ?>
                                    <span>Tidak ada file yang diunggah</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
function handleBack() {
    window.history.back();
}
</script>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>