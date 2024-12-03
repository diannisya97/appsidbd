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
   /* CSS untuk tampilan desktop */
   @media (min-width: 768px) {
            .form-container {
                display: flex;
            }

            .form-container > div {
                width: 50%;
                padding-right: 10px;
            }

            #Alamat {
                height: 300px;
            }
        }

        /* CSS untuk tampilan mobile */
        @media (max-width: 767px) {
            .form-container {
                display: block;
            }

            .form-container > div {
                width: 100%;
                padding-right: 0;
            }

            #Alamat {
                height: 150px; /* Tinggi textarea pada tampilan mobile */
            }

            #map {
                height: 300px; /* Tinggi peta pada tampilan mobile */
            }
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
                <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Ke Halaman Data Faskes"><a href="puskesmas.php">Data Faskes</a></li>
                    <li class="breadcrumb-item"><a href="puskesmaslihat.php?id=<?php echo $idpuskesmas; ?>" data-bs-toggle="tooltip" title="Ke Halaman Detail Faskes">Detail Faskes</a></li>
                    <li class="breadcrumb-item active">Edit Data Faskes</li>
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
            <td colspan="2">
                <div class="form-container">
                    <div class="mb-3">
                        <label for="Alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="Alamat" name="Alamat" required><?php echo htmlspecialchars($data['Alamat']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="map" class="form-label">Lokasi Peta</label>
                        <div id="map" style="height: 200px;" data-bs-toggle="tooltip" title="Klik Lokasi Yang Sesuai Pada Peta"></div>
                    </div>
                </div>
            </td>
        </tr>
                </table>
                <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="updatepuskesmas" data-bs-toggle="tooltip" title="Simpan Perubahan Data">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize map
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: <?php echo $data['latitude']; ?>, lng: <?php echo $data['longitude']; ?> },
            zoom: 13
        });

        // Create a custom marker icon
        var puskesmasIcon = {
            url: '../img/puskesmas.png', // Path to your puskesmas.png icon
            scaledSize: new google.maps.Size(32, 32), // Adjust the size of the icon (optional)
            anchor: new google.maps.Point(16, 32) // Anchor point for the icon (optional)
        };

        // Create marker with custom icon
        var marker = new google.maps.Marker({
            position: { lat: <?php echo $data['latitude']; ?>, lng: <?php echo $data['longitude']; ?> },
            map: map,
            draggable: true,
            icon: puskesmasIcon // Use the custom icon here
        });

        // Initialize geocoder
        var geocoder = new google.maps.Geocoder();

        function updateAddress(lat, lng) {
            var latLng = new google.maps.LatLng(lat, lng);
            
            // Reverse geocoding using Google Maps API
            geocoder.geocode({ 'location': latLng }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var address = results[0].formatted_address;
                        document.getElementById('Alamat').value = address;
                    }
                } else {
                    console.error('Geocoder failed due to: ' + status);
                }
            });
        }

        // Update latitude, longitude, and address on marker drag
        google.maps.event.addListener(marker, 'dragend', function(event) {
            var position = event.latLng;
            document.getElementById('latitude').value = position.lat().toFixed(6);
            document.getElementById('longitude').value = position.lng().toFixed(6);
            updateAddress(position.lat(), position.lng());
        });

        // Update latitude, longitude, and address on map click
        google.maps.event.addListener(map, 'click', function(event) {
            var position = event.latLng;
            marker.setPosition(position);
            document.getElementById('latitude').value = position.lat().toFixed(6);
            document.getElementById('longitude').value = position.lng().toFixed(6);
            updateAddress(position.lat(), position.lng());
        });

        // Initialize address based on the current marker position
        updateAddress(<?php echo $data['latitude']; ?>, <?php echo $data['longitude']; ?>);
    });
</script>

</body>
</html>
