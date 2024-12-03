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
                <br><br>
                <div class="card mb-4">
                    <div class="card-header">
                        <a href="#" class="btn btn-secondary" onclick="handleBack(); return false;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <div class="header-title">Detail Fasilitas Kesehatan</div>
                        <a href="puskesmasedit.php?id=<?php echo $data['idpuskesmas']; ?>" class="btn btn-sm btn-success"> <i class="fas fa-edit" style="color: #fff;"></i> Edit Data</a>
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
                                <script>
                                    // Inisialisasi peta
                                    const latitude = <?php echo $data['latitude']; ?>;
                                    const longitude = <?php echo $data['longitude']; ?>;
                                    const map = L.map('map').setView([latitude, longitude], 15);

                                    // Tambahkan lapisan peta dari OpenStreetMap
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '© OpenStreetMap'
                                    }).addTo(map);

                                    // Tambahkan penanda (marker)
                                    L.marker([latitude, longitude]).addTo(map)
                                        .bindPopup('<b><?php echo htmlspecialchars($data['Nama_Puskesmas']); ?></b>')
                                        .openPopup();
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
