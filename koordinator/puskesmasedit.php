<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// get id
$idpuskesmas = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpuskesmas) || !is_numeric($idpuskesmas)) {
    echo "<script>alert('ID Puskesmas tidak valid!'); window.location.href='puskesmas.php';</script>";
    exit();
}

$idpuskesmas = intval($idpuskesmas); // Ensure the id is an integer

// Fetch data for the given id
$query = "SELECT * FROM puskesmas WHERE idpuskesmas = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idpuskesmas);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

// Menangani pengiriman form
if (isset($_POST['updatepuskesmas'])) {
    $Nama_Puskesmas = $_POST['Nama_Puskesmas'];
    $Nomor_Kontak = $_POST['Nomor_Kontak'];
    $Alamat = $_POST['Alamat'];
    $Longitude = $_POST['longitude'];
    $Latitude = $_POST['latitude'];
    $Jam_Operasional = $_POST['jam_operasional']; // Tambahan
    $Kepala_Puskesmas = $_POST['kepala_puskesmas']; // Tambahan

    $updateQuery = "UPDATE puskesmas SET Nama_Puskesmas = ?, Nomor_Kontak = ?, Alamat = ?, longitude = ?, latitude = ?, jam_operasional = ?, kepala_puskesmas = ? WHERE idpuskesmas = ?";
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("sssssssi", $Nama_Puskesmas, $Nomor_Kontak, $Alamat, $Longitude, $Latitude, $Jam_Operasional, $Kepala_Puskesmas, $idpuskesmas);
        if ($stmt->execute()) {
            echo "<script>
    alert('Data berhasil diperbarui!');
    window.location.href = 'puskesmaslihat.php?id=$idpuskesmas';
</script>";
        } else {
            echo "DATA GAGAL DIPERBARUI! Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "ERROR: Tidak dapat mempersiapkan query: " . $conn->error;
    }
    $conn->close();
}

?>
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
</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Data Faskes</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="puskesmas.php">Data Faskes</a></li>
                    <li class="breadcrumb-item"><a href="puskesmaslihat.php?id=<?php echo $idpuskesmas; ?>">Detail Faskes</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="POST">
                        <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; padding-right: 10px;">
                        <div class="mb-3">
                            <label for="Nama_Puskesmas" class="form-label">Fasilitas Kesehatan</label>
                            <input type="text" class="form-control" id="Nama_Puskesmas" name="Nama_Puskesmas" value="<?php echo htmlspecialchars($data['Nama_Puskesmas']); ?>" required>
                        </div>
                    </td>
                    <td style="width: 50%; padding-left: 10px;">
                        <div class="mb-3">
                            <label for="Nomor_Kontak" class="form-label">Nomor Kontak</label>
                            <input type="text" class="form-control" id="Nomor_Kontak" name="Nomor_Kontak" value="<?php echo htmlspecialchars($data['Nomor_Kontak']); ?>" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; padding-right: 10px;">
                        <div class="mb-3">
                            <label for="jam_operasional" class="form-label">Jam Operasional</label>
                            <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" value="<?php echo htmlspecialchars($data['jam_operasional']); ?>" required>
                        </div>
                    </td>
                    <td style="width: 50%; padding-left: 10px;">
                        <div class="mb-3">
                            <label for="kepala_puskesmas" class="form-label">Kepala Faskes</label>
                            <input type="text" class="form-control" id="kepala_puskesmas" name="kepala_puskesmas" value="<?php echo htmlspecialchars($data['kepala_puskesmas']); ?>" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; padding-right: 10px;">
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($data['latitude']); ?>" readonly required>
                        </div>
                    </td>
                    <td style="width: 50%; padding-left: 10px;">
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($data['longitude']); ?>" readonly required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; padding-right: 10px;">
                        <div class="mb-3">
                            <label for="Alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="Alamat" name="Alamat" required><?php echo htmlspecialchars($data['Alamat']); ?></textarea>
                        </div>
                    </td>
                    <td style="width: 50%; padding-right: 10px;">
                        <div class="mb-3">
                            <label for="map" class="form-label">Lokasi Peta</label>
                            <div id="map" style="height: 200px;"></div>
                        </div>
                    </td>
                </tr>
                </table>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="updatepuskesmas">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize map
        var map = L.map('map').setView([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>], {
            draggable: true
        }).addTo(map);

        function updateAddress(lat, lng) {
            // Reverse geocoding using Nominatim
            var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        var address = data.display_name;
                        document.getElementById('Alamat').value = address;
                    }
                })
                .catch(error => {
                    console.error('Error during reverse geocoding:', error);
                });
        }

        // Update latitude, longitude, and address on marker drag
        marker.on('dragend', function(event) {
            var position = event.target.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
            updateAddress(position.lat, position.lng);
        });

        // Update latitude, longitude, and address on map click
        map.on('click', function(event) {
            var position = event.latlng;
            marker.setLatLng(position);
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
            updateAddress(position.lat, position.lng);
        });

        // Initialize address based on the current marker position
        updateAddress(<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>);
    });
</script>

</body>
</html>
