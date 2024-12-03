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
    $rt = $_POST['rt'];
    $rw = $_POST['rw'];
    $desakelurahan = $_POST['desakelurahan'];
    $kecamatan = $_POST['kecamatan'];
    $kabkota = $_POST['kabkota'];
    $kodepos = $_POST['kodepos'];
    $nokontak = $_POST['nokontak'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $asalfasyankes = $_POST['asalfasyankes'];
    $tempatlahir = $_POST['tempatlahir'];
    $domisili = $_POST['domisili'];

    $updateQuery = "UPDATE pasien SET 
                    namapasien = ?, 
                    nik = ?, 
                    tanggallahir = ?, 
                    jeniskelamin = ?, 
                    alamat = ?, 
                    rt = ?, 
                    rw = ?, 
                    desakelurahan = ?, 
                    kecamatan = ?, 
                    kabkota = ?, 
                    kodepos = ?, 
                    nokontak = ?, 
                    longitude = ?, 
                    latitude = ?, 
                    asalfasyankes = ?, 
                    tempatlahir = ?, 
                    domisili = ? 
                    WHERE id = ?";
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("sssssssssssssssssi", $namapasien, $nik, $tanggallahir, $jeniskelamin, $alamat, $rt, $rw, $desakelurahan, $kecamatan, $kabkota, $kodepos, $nokontak, $longitude, $latitude, $asalfasyankes, $tempatlahir, $domisili, $idpasien);
        if ($stmt->execute()) {
            echo "<script>alert('Data Pasien berhasil diperbarui!'); window.location.href='pasienlihat.php?id=" . $data['id'] . "';</script>";
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
        height: 250px;
    }
    .table-borderless td, .table-borderless th {
        border: 0;
    }
</style>
<?php
$idpuskesmas = $_GET['id']; // Pastikan ID diambil dari query string atau dari konteks yang sesuai
?>
<style>
   body {
        background-color: #f7f9fc;
        font-family: Arial, sans-serif;
        color: #333;
    }

    .container-fluid {
        max-width: 1000px; /* Lebarkan form */
        margin: auto;
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background-color: #fff;
        padding: 20px;
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .form-group .col {
        flex: 0 0 48%;
    }

    .form-group label {
        font-weight: bold;
        color: #555;
    }

    .form-control {
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        font-size: 18px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .modal-footer {
        display: flex;
        justify-content: center;
    }

    .modal-footer button {
        width: 100%;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-group {
            flex-direction: column;
        }

        .form-group .col {
            flex: 1 1 100%;
        }
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
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

</head>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Data Pasien</h1>
                <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Dashboard"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Kembali Ke Data Pasien"><a href="pasien.php">Data Pasien</a></li>
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Ke Halaman Sebelumnya"><a href="javascript:history.back()">Detail Pasien</a></li>
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
                                    <td><label for="tempatlahir" class="form-label">Tempat Lahir</label></td>
                                    <td><input type="text" class="form-control" id="tempatlahir" name="tempatlahir" value="<?php echo htmlspecialchars($data['tempatlahir']); ?>" required /></td>
                                </tr>
                                <tr>
                                    <td><label for="jeniskelamin" class="form-label">Jenis Kelamin</label></td>
                                    <td>
                                        <select class="form-select" id="jeniskelamin" name="jeniskelamin" required>
                                            <option value="Laki-laki" <?php echo ($data['jeniskelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php echo ($data['jeniskelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </td>
                                    <td><label for="domisili" class="form-label">Domisili</label></td>
                                    <td><input type="text" class="form-control" id="domisili" name="domisili" value="<?php echo htmlspecialchars($data['domisili']); ?>" /></td>
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
                                    <th colspan="4">Informasi Geografis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <div id="map" data-bs-toggle="tooltip" title="Klik Lokasi Yang Sesuai Pada Peta"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="alamat" class="form-label">Alamat</label></td>
                                    <td colspan="3"><input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required /></td>
                                </tr>
                                <tr>
                                    <td><label for="rt" class="form-label">RT</label></td>
                                    <td><input type="text" class="form-control" id="rt" name="rt" value="<?php echo htmlspecialchars($data['rt']); ?>" required /></td>
                                    <td><label for="rw" class="form-label">RW</label></td>
                                    <td><input type="text" class="form-control" id="rw" name="rw" value="<?php echo htmlspecialchars($data['rw']); ?>" required /></td>
                                </tr>
                                <tr>
                                    <td><label for="desakelurahan" class="form-label">Desa/Kelurahan</label></td>
                                    <td><input type="text" class="form-control" id="desakelurahan" name="desakelurahan" value="<?php echo htmlspecialchars($data['desakelurahan']); ?>" required /></td>
                                    <td><label for="kodepos" class="form-label">Kode Pos</label></td>
                                    <td><input type="text" class="form-control" id="kodepos" name="kodepos" value="<?php echo htmlspecialchars($data['kodepos']); ?>" required /></td>
                                </tr>
                                <tr>
                                    <td><label for="kecamatan" class="form-label">Kecamatan</label></td>
                                    <td><input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?php echo htmlspecialchars($data['kecamatan']); ?>" required /></td>
                                    <td><label for="kabkota" class="form-label">Kabupaten/Kota</label></td>
                                    <td><input type="text" class="form-control" id="kabkota" name="kabkota" value="<?php echo htmlspecialchars($data['kabkota']); ?>" required /></td>
                                </tr>
                                <tr>
                                    <td><label for="longitude" class="form-label">Longitude</label></td>
                                    <td><input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($data['longitude']); ?>" required /></td>
                                    <td><label for="latitude" class="form-label">Latitude</label></td>
                                    <td><input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($data['latitude']); ?>" required /></td>
                                </tr>
                            </tbody>
                        </table>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="updatepasien" data-bs-toggle="tooltip" title="Simpan Perubahan Data">Simpan Perubahan</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>
<script>
    var map, marker;

    function initMap() {
    // Inisialisasi Peta Google
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: <?php echo $data['latitude']; ?>, lng: <?php echo $data['longitude']; ?> },
        zoom: 14
    });

    // Membuat marker pada posisi awal
    marker = new google.maps.Marker({
        position: { lat: <?php echo $data['latitude']; ?>, lng: <?php echo $data['longitude']; ?> },
        map: map,
        icon: {
            url: '../img/pasien.png',
            scaledSize: new google.maps.Size(40, 40) // Menentukan ukuran ikon (40x40 piksel)
        }
    });

    // Event listener untuk menarik marker
    google.maps.event.addListener(marker, 'dragend', function(event) {
        var latlng = event.latLng;
        updateAddress(latlng);
        document.getElementById('latitude').value = latlng.lat();
        document.getElementById('longitude').value = latlng.lng();
    });

    // Event listener untuk klik pada peta
    google.maps.event.addListener(map, 'click', function(event) {
        marker.setPosition(event.latLng);
        document.getElementById('latitude').value = event.latLng.lat();
        document.getElementById('longitude').value = event.latLng.lng();
        updateAddress(event.latLng); // Update alamat berdasarkan koordinat
    });

    // Update alamat awal berdasarkan posisi marker
    updateAddress(marker.getPosition());
}

    // Update address based on coordinates using Google Maps Geocoder
    function updateAddress(latlng) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'location': latlng }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK && results[0]) {
                // Filter address components to remove Plus Code (e.g. J6QR+GH4)
                var filteredAddress = results[0].address_components
                    .filter(c => !c.types.includes('plus_code')) // Remove 'plus_code' type if exists
                    .map(c => c.long_name)
                    .join(', ');

                var addressComponents = results[0].address_components;

                // Set filtered address in the 'alamat' field
                document.getElementById('alamat').value = filteredAddress;

                // Handle RT and RW if available, or set to null
                var rt = addressComponents.find(c => c.types.includes('neighborhood'))?.long_name || null;
                var rw = addressComponents.find(c => c.types.includes('suburb'))?.long_name || null;

                // Handle Desa/Kelurahan (level 4)
                var desakelurahan = addressComponents.find(c => 
                    c.types.includes('administrative_area_level_4')
                )?.long_name || '';

                // Set values for other fields based on address components
                document.getElementById('desakelurahan').value = desakelurahan;
                document.getElementById('kabkota').value = addressComponents.find(c => c.types.includes('administrative_area_level_2'))?.long_name || '';
                document.getElementById('kodepos').value = addressComponents.find(c => c.types.includes('postal_code'))?.long_name || '';
                document.getElementById('kecamatan').value = addressComponents.find(c => c.types.includes('administrative_area_level_3'))?.long_name || '';
                document.getElementById('provinsi').value = addressComponents.find(c => c.types.includes('administrative_area_level_1'))?.long_name || '';
            } else {
                console.error('Geocoder failed due to: ' + status);
                document.getElementById('alamat').value = 'Alamat tidak ditemukan';
            }
        });
    }
</script>



</html>
