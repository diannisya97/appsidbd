<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Ambil ID dari URL
$idpuskesmas = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpuskesmas) || !is_numeric($idpuskesmas)) {
    echo "<script>alert('ID Puskesmas tidak valid!'); window.location.href='puskesmas.php';</script>";
    exit();
}

$idpuskesmas = intval($idpuskesmas); // Pastikan ID adalah integer

// Ambil data untuk ID yang diberikan
$query = "SELECT * FROM puskesmas WHERE idpuskesmas = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idpuskesmas);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ERROR: Tidak dapat menyiapkan query: " . $conn->error;
    exit();
}
?>
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
    if (document.referrer.includes('puskesmasedit.php')) {
        // Jika ya, arahkan ke puskesmas.php
        window.location.href = 'puskesmas.php';
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
                <br>
                <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Ke Halaman Data Faskes"><a href="puskesmas.php">Data Faskes</a></li>
                    <li class="breadcrumb-item active">Detail Faskes</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                    <a href="#" class="btn btn-secondary" onclick="handleBack(); return false;" data-bs-toggle="tooltip" title="Ke Halaman Sebelumnya"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <div class="header-title">Detail Fasilitas Kesehatan</div>
                        <?php
// Mendapatkan idpuskesmas dari session pengguna yang login
$idpuskesmas_login = $_SESSION['idpuskesmas'];

// Mendapatkan idpuskesmas dari data yang sedang ditampilkan
$idpuskesmas_data = $data['idpuskesmas'];

// Cek apakah idpuskesmas login sama dengan idpuskesmas data
if ($idpuskesmas_login == $idpuskesmas_data) {
    ?>
    <a href="puskesmasedit.php?id=<?php echo $data['idpuskesmas']; ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Edit Data"> 
        <i class="fas fa-edit" style="color: #fff;"></i> Edit Data
    </a>
    <?php
}
?>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Fasilitas Kesehatan</th>
                                        <td><?php echo htmlspecialchars($data['Nama_Puskesmas']); ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Nomor Kontak</th>
                                        <td><?php echo htmlspecialchars($data['Nomor_Kontak']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jam Operasional</th>
                                        <td><?php echo htmlspecialchars($data['jam_operasional']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kepala Faskes</th>
                                        <td><?php echo htmlspecialchars($data['kepala_puskesmas']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td><?php echo htmlspecialchars($data['Alamat']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Latitude</th>
                                        <td><?php echo htmlspecialchars($data['latitude']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Longitude</th>
                                        <td><?php echo htmlspecialchars($data['longitude']); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div id="map" style="height: 300px;"></div>
                                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>
                                <script>
                                    function initMap() {
                                        // Inisialisasi peta menggunakan koordinat latitude dan longitude
                                        const latitude = <?php echo $data['latitude']; ?>;
                                        const longitude = <?php echo $data['longitude']; ?>;

                                        // Membuat peta dengan pusat koordinat yang ditentukan
                                        const map = new google.maps.Map(document.getElementById('map'), {
                                            center: { lat: latitude, lng: longitude },
                                            zoom: 15
                                        });

                                        // Menyiapkan icon untuk marker
                                        const puskesmasIcon = {
                                            url: '../img/puskesmas.png', // Path gambar ikon
                                            scaledSize: new google.maps.Size(52, 52), // Ukuran ikon
                                            anchor: new google.maps.Point(16, 32), // Titik acuan ikon
                                        };

                                        // Membuat marker di peta dengan koordinat yang ditentukan dan ikon puskesmas
                                        const marker = new google.maps.Marker({
                                            position: { lat: latitude, lng: longitude },
                                            map: map,
                                            title: '<?php echo htmlspecialchars($data['Nama_Puskesmas']); ?>',
                                            icon: puskesmasIcon // Menetapkan ikon marker
                                        });

                                        // Menambahkan info window untuk marker
                                        const infoWindow = new google.maps.InfoWindow({
                                            content: `<b><?php echo htmlspecialchars($data['Nama_Puskesmas']); ?></b>`
                                        });

                                        // Menampilkan info window saat marker diklik
                                        marker.addListener('click', function() {
                                            infoWindow.open(map, marker);
                                        });
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</div>
</body>
</html>
