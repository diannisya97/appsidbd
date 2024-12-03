<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id
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

// Fetch puskesmas data for dropdown
$puskesmasQuery = "SELECT idpuskesmas, Nama_Puskesmas FROM puskesmas";
$puskesmasResult = $conn->query($puskesmasQuery);
$puskesmasOptions = "";
while ($row = $puskesmasResult->fetch_assoc()) {
    $selected = ($data['asalfasyankes'] == $row['idpuskesmas']) ? 'selected' : '';
    $puskesmasOptions .= "<option value=\"{$row['idpuskesmas']}\" $selected>{$row['Nama_Puskesmas']}</option>";
}

// Handle form submission
if (isset($_POST['updatepasien'])) {
    $namapasien = $_POST['namapasien'];
    $nik = $_POST['nik'];
    $tanggallahir = $_POST['tanggallahir'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $alamat = $_POST['alamat'];
    $rtrw = $_POST['rtrw'];
    $desakelurahan = $_POST['desakelurahan'];
    $kecamatan = $_POST['kecamatan'];
    $kabkota = $_POST['kabkota'];
    $nokontak = $_POST['nokontak'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $asalfasyankes = $_POST['asalfasyankes'];

    $updateQuery = "UPDATE pasien SET 
                    namapasien = ?, 
                    nik = ?, 
                    tanggallahir = ?, 
                    jeniskelamin = ?, 
                    alamat = ?, 
                    rtrw = ?, 
                    desakelurahan = ?, 
                    kecamatan = ?, 
                    kabkota = ?, 
                    nokontak = ?, 
                    longitude = ?, 
                    latitude = ?, 
                    asalfasyankes = ? 
                    WHERE id = ?";
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("sssssssssssdii", $namapasien, $nik, $tanggallahir, $jeniskelamin, $alamat, $rtrw, $desakelurahan, $kecamatan, $kabkota, $nokontak, $longitude, $latitude, $asalfasyankes, $idpasien);
        if ($stmt->execute()) {
            echo "<script>alert('Data Pasien berhasil diperbarui!'); window.location.href='pasien.php';</script>";
        } else {
            echo "DATA GAGAL DIPERBARUI! Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "ERROR: Could not prepare query: " . $conn->error;
    }
    
    $conn->close();
}
?>

<style>
    #map {
        height: 400px;
    }
    .table-borderless td, .table-borderless th {
        border: 0;
    }
    .btn-custom {
        float: right;
        width: 150px;
    }
</style>
</head>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Data Pasien</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="pasien.php">Pasien</a></li>
                    <li class="breadcrumb-item active">Edit Data Pasien</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="post" action="pasienedit.php?id=<?php echo $idpasien; ?>">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th colspan="4">Informasi Pasien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><label for="namapasien" class="form-label">Nama Pasien</label></td>
                                        <td><input type="text" class="form-control" id="namapasien" name="namapasien" value="<?php echo htmlspecialchars($data['namapasien']); ?>" required /></td>
                                        <td><label for="nik" class="form-label">NIK</label></td>
                                        <td><input type="text" class="form-control" id="nik" name="nik" value="<?php echo htmlspecialchars($data['nik']); ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="tanggallahir" class="form-label">Tanggal Lahir</label></td>
                                        <td><input type="date" class="form-control" id="tanggallahir" name="tanggallahir" value="<?php echo htmlspecialchars($data['tanggallahir']); ?>" required /></td>
                                        <td><label for="jeniskelamin" class="form-label">Jenis Kelamin</label></td>
                                        <td>
                                            <select class="form-select" id="jeniskelamin" name="jeniskelamin" required>
                                                <option value="L" <?php echo ($data['jeniskelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                                <option value="P" <?php echo ($data['jeniskelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="nokontak" class="form-label">No. Kontak</label></td>
                                        <td><input type="text" class="form-control" id="nokontak" name="nokontak" value="<?php echo htmlspecialchars($data['nokontak']); ?>" required /></td>
                                        <td><label for="asalfasyankes" class="form-label">Asal Fasyankes</label></td>
                                        <td>
                                            <select class="form-select" id="asalfasyankes" name="asalfasyankes" required>
                                                <?php echo $puskesmasOptions; ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="4">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><label for="alamat" class="form-label">Alamat</label></td>
                                        <td colspan="3"><input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="rtrw" class="form-label">RT/RW</label></td>
                                        <td><input type="text" class="form-control" id="rtrw" name="rtrw" value="<?php echo htmlspecialchars($data['rtrw']); ?>" required /></td>
                                        <td><label for="desakelurahan" class="form-label">Desa/Kelurahan</label></td>
                                        <td><input type="text" class="form-control" id="desakelurahan" name="desakelurahan" value="<?php echo htmlspecialchars($data['desakelurahan']); ?>" required /></td>
                                        
                                    </tr>
                                    <tr>
                                        <td><label for="kecamatan" class="form-label">Kecamatan</label></td>
                                        <td><input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?php echo htmlspecialchars($data['kecamatan']); ?>" required /></td>
                                        <td><label for="kabkota" class="form-label">Kabupaten/Kota</label></td>
                                        <td><input type="text" class="form-control" id="kabkota" name="kabkota" value="<?php echo htmlspecialchars($data['kabkota']); ?>" required /></td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="4">Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">
                                            <div id="map"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="longitude" class="form-label">Longitude</label></td>
                                        <td><input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($data['longitude']); ?>" required /></td>
                                        <td><label for="latitude" class="form-label">Latitude</label></td>
                                        <td><input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($data['latitude']); ?>" required /></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary btn-custom" name="updatepasien">Perbarui Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>]).addTo(map);

    function onMapClick(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    }

    map.on('click', onMapClick);
</script>
</html>
