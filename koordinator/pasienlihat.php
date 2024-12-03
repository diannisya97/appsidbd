<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id from URL
$idpasien = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpasien) || !is_numeric($idpasien)) {
    echo "<script>alert('ID Pasien tidak valid!'); window.location.href='pasien.php';</script>";
    exit();
}

$idpasien = intval($idpasien); // Ensure the id is an integer

// Fetch data for the given id
$query = "SELECT * FROM pasien WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idpasien);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary meta tags and stylesheets -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pasien</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .category-header {
    color: black;        /* Mengatur warna teks menjadi hitam */
    font-weight: bold;   /* Mengatur teks menjadi tebal (bold) */
}

    /* Custom CSS to style the table headers */
    table th.category-header {
        font-weight: bold;
    }
    table th, table td {
        font-weight: normal;
    }
    /* Menambahkan penekanan yang lebih halus pada elemen penting */
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: bold;
        font-size: 1.5em;
    }
    .card-header .header-title {
        text-align: center;
        flex: 1; /* Takes up remaining space */
        font-weight: 700;
    }
    /* Sedikit penekanan pada label data */
    table th {
        font-weight: 600;
    }
    /* Normal weight untuk data agar kontras dengan label */
    table td {
        font-weight: 400;
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
    if (document.referrer.includes('pasienedit.php')) {
        // Jika ya, arahkan ke puskesmas.php
        window.location.href = 'pasien.php';
    } else {
        // Jika tidak, gunakan history.back() untuk kembali ke halaman sebelumnya
        history.back();
    }
}
</script>
</head>
<body>
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
    <main>
    <div class="container-fluid px-4">
        <br>
        <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Kembali Ke Data Pasien"><a href="pasien.php">Data Pasien</a></li>
                    <li class="breadcrumb-item active">Detail Pasien</li>
                </ol>
        <div class="card mb-4">
            <div class="card-header">
                <a href="#" class="btn btn-secondary" onclick="handleBack(); return false;"data-bs-toggle="tooltip" title="Ke Halaman Sebelumnya"><i class="fas fa-arrow-left" ></i> Kembali</a>
                <div class="header-title">Detail Pasien</div>
                <a href="pasienedit.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-success" 
   data-bs-toggle="tooltip" title="Edit Data" hidden>
    <i class="fas fa-edit" style="color: #fff;"></i> Edit Data
</a>

            </div>
                <div class="card-body">
                <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="4" class="category-header">Informasi Pasien</th>
            </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Nama Pasien</th>
                            <td><?php echo htmlspecialchars($data['namapasien']); ?></td>
                            <th>NIK</th>
                            <td><?php echo htmlspecialchars($data['nik']); ?></td>
                        </tr>
                        <tr>
                            <th>Tempat Lahir</th>
                            <td><?php echo htmlspecialchars($data['tempatlahir']); ?></td>
                            <th>Tanggal Lahir</th>
                            <td><?php echo htmlspecialchars($data['tanggallahir']); ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?php echo htmlspecialchars($data['jeniskelamin']); ?></td>
                            <th>Domisili</th>
                            <td><?php echo htmlspecialchars($data['domisili'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th>Nomor Kontak</th>
                            <td><?php echo htmlspecialchars($data['nokontak']); ?></td>
                            <th>Asal Fasyankes</th>
                            <td>
                                <?php
                                $fasyankesQuery = "SELECT Nama_Puskesmas FROM puskesmas WHERE idpuskesmas = ?";
                                if ($fasyankesStmt = $conn->prepare($fasyankesQuery)) {
                                    $fasyankesStmt->bind_param("i", $data['asalfasyankes']);
                                    $fasyankesStmt->execute();
                                    $fasyankesResult = $fasyankesStmt->get_result();
                                    $fasyankesData = $fasyankesResult->fetch_assoc();
                                    echo htmlspecialchars($fasyankesData['Nama_Puskesmas']);
                                    $fasyankesStmt->close();
                                } else {
                                    echo "ERROR: Could not prepare query: " . $conn->error;
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th colspan="4" class="category-header">Informasi Geografis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                            <td colspan="2" rowspan="10">
                                <div id="map" style="width: 100%; height: 100%; min-height: 400px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <th>RT</th>
                            <td><?php echo htmlspecialchars($data['rt']); ?></td>
                        </tr>
                        <tr>
                            <th>RW</th>
                            <td><?php echo htmlspecialchars($data['rw']); ?></td>
                        </tr>
                        <tr>
                            <th>Desa/Kelurahan</th>
                            <td><?php echo htmlspecialchars($data['desakelurahan']); ?></td>
                        </tr>
                        <tr>
                            <th>Kecamatan</th>
                            <td><?php echo htmlspecialchars($data['kecamatan']); ?></td>
                        </tr>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <td><?php echo htmlspecialchars($data['kabkota']); ?></td>
                        </tr>
                        <tr>
                            <th>Kode Pos</th>
                            <td><?php echo htmlspecialchars($data['kodepos']); ?></td>
                        </tr>
                        <tr>
                            <th>Latitude</th>
                            <td><?php echo htmlspecialchars($data['latitude']); ?></td>
                        </tr>
                        <tr>
                            <th>Longitude</th>
                            <td><?php echo htmlspecialchars($data['longitude']); ?></td>
                        </tr>
                    </tbody>
                    <tbody>
                        <?php if (isset($data['created_at'])): ?>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td colspan="3"><?php echo htmlspecialchars($data['created_at']); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </main>
        <?php include 'footer.php'; ?>
    </div>
    <script>
    // Initialize and add the map
    function initMap() {
        var lat = <?php echo $data['latitude']; ?>;
        var lng = <?php echo $data['longitude']; ?>;
        
        // Inisialisasi Peta Google
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: lat, lng: lng },
            zoom: 15
        });

        // Definisikan ikon kustom
        var customIcon = {
            url: '../img/pasien.png', // URL gambar ikon
            scaledSize: new google.maps.Size(48, 48), // Ukuran ikon
            anchor: new google.maps.Point(19, 38), // Titik ikon yang akan sesuai dengan lokasi marker
            origin: new google.maps.Point(0, 0), // Titik asal ikon
            labelOrigin: new google.maps.Point(0, 0)
        };
        
        // Tambahkan marker dengan ikon kustom
        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            icon: customIcon,
            title: 'Lokasi Pasien'
        });

        // Tambahkan popup
        var infoWindow = new google.maps.InfoWindow({
            content: 'Lokasi Pasien'
        });
        
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });
    }

    // Menunggu hingga DOM siap sebelum memanggil initMap
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
</script>

<!-- Memuat library Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>


</body>
</html>
