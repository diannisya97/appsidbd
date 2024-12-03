<?php
session_start();
require "../function.php";
require "puskesmastambah.php";
require "header.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Puskesmas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        #step1, #step2, #step3 {
            margin-bottom: 15px;
        }
        .leaflet-control-geocoder {
            z-index: 1000;
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
        #datatablesSimple th:nth-child(1),
        #datatablesSimple td:nth-child(1) {
            width: 5%;
        }
        #datatablesSimple th:nth-child(2),
        #datatablesSimple td:nth-child(2) {
            width: 20%;
        }
        #datatablesSimple th:nth-child(3),
        #datatablesSimple td:nth-child(3) {
            width: 50%;
        }
        #datatablesSimple th:nth-child(4),
        #datatablesSimple td:nth-child(4) {
            width: 15%;
        }
        #datatablesSimple th:nth-child(5),
        #datatablesSimple td:nth-child(5) {
            width: 10%;
        }
        .modal-footer {
            display: flex;
            justify-content: center;
        }
        .modal-footer button {
            width: 350px;
            margin: 10px;
            border-radius: 5px;
            font-size: 16px;
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
        .btn-secondary {
            background-color: #4e73df;
            border-color: #6c757d;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
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
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.querySelector('#datatablesSimple');

            table.addEventListener('click', function (event) {
                const row = event.target.closest('tr');
                
                if (row) {
                    const link = row.querySelector('a');
                    
                    if (link) {
                        // Ambil parameter ID dari URL tautan
                        const userId = new URL(link.href).searchParams.get('id');
                        
                        if (userId) {
                            // Redirect ke halaman penggunalihat.php dengan ID yang diambil
                            window.location.href = `puskesmaslihat.php?id=${userId}`;
                        } else {
                            alert('ID Puskesmas tidak valid.');
                        }
                    }
                }
            });
        });
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
                <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Faskes</li>
                </ol>
                <div class="card mb-4">
                <div class="card-header">
                        <div class="header-title">Data Fasilitas Kesehatan</div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-bs-toggle="tooltip" title="Tambah Data" hidden>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" data-bs-toggle="tooltip" title="Klik untuk melihat detail data">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Faskes</th>
                                    <th>Alamat</th>
                                    <th>Nomor Kontak</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $ambildatapuskesmas = mysqli_query($conn, "SELECT * FROM puskesmas");
                            while ($row = mysqli_fetch_array($ambildatapuskesmas)) {
                                $Nama_Puskesmas = $row['Nama_Puskesmas'];
                                $Alamat = $row['Alamat'];
                                $Nomor_Kontak = $row['Nomor_Kontak'];
                            ?>
                            <tr data-id="<?php echo $id; ?>">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $Nama_Puskesmas; ?></td>
                                <td><?php echo $Alamat; ?></td>
                                <td><?php echo $Nomor_Kontak; ?><div>
                                        <a href="puskesmasdelete.php?id=<?php echo $row['idpuskesmas'] ?>"  onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"></a>
                                    </div></td>
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Fasilitas Kesehatan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="POST" action="puskesmastambah.php">
                <div class="modal-body">
                    <!-- Langkah 1: Informasi Puskesmas -->
                    <div id="step1">
                        <div class="mb-3">
                            <label for="Nama_Puskesmas">Fasilitas Kesehatan</label>
                            <input name="Nama_Puskesmas" id="Nama_Puskesmas" type="text" class="form-control" placeholder="Puskesmas Kahuripan/RS TMC" required>
                        </div>
                        <div class="mb-3">
                            <label for="Nomor_Kontak">Nomor Kontak</label>
                            <input name="Nomor_Kontak" id="Nomor_Kontak" type="text" class="form-control" placeholder="Nomor Kontak" required>
                        </div>
                        <div class="mb-3">
                            <label for="kepala_puskesmas">Kepala Faskes</label>
                            <input name="kepala_puskesmas" id="kepala_puskesmas" type="text" class="form-control" placeholder="Nama Kepala Faskes" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_operasional">Jam Operasional</label>
                            <input name="jam_operasional" id="jam_operasional" type="text" class="form-control" placeholder="Jam Operasional" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="nextStep" data-bs-toggle="tooltip" title="Ke Tahap Selanjutnya">Selanjutnya</button>
                        </div>
                    </div>

                    <!-- Langkah 3: Peta Lokasi -->
                    <div id="step3" style="display: none;">
                        <div class="row">
                            <!-- Kolom untuk Input Alamat, Longitude, dan Latitude -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="Alamat">Alamat</label>
                                    <input name="Alamat" id="Alamat" type="text" class="form-control" placeholder="Alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="longitude">Longitude</label>
                                    <input name="longitude" id="longitude" type="text" class="form-control" placeholder="Longitude" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="latitude">Latitude</label>
                                    <input name="latitude" id="latitude" type="text" class="form-control" placeholder="Latitude" readonly>
                                </div>
                            </div>

                            <!-- Kolom untuk Peta -->
                            <div class="col-md-6">
                                <div id="mapid" style="height: 400px;"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="prevStep" class="btn btn-secondary" data-bs-toggle="tooltip" title="Ke Tahap Sebelumnya">Sebelumnya</button>
                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Simpan Data">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var currentStep = 1;
    var step1 = document.getElementById('step1');
    var step3 = document.getElementById('step3');
    var nextStepBtn = document.getElementById('nextStep');
    var prevStepBtn = document.getElementById('prevStep');
    var form = document.querySelector('form');
    var map, marker, geocoder;

    // Fungsi untuk validasi input
    function validateInputs(inputIds) {
        var isValid = true;
        inputIds.forEach(function(id) {
            var input = document.getElementById(id);
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add("is-invalid"); // Menambahkan gaya untuk menandai input tidak valid
            } else {
                input.classList.remove("is-invalid");
            }
        });
        return isValid;
    }

    // Event klik tombol "Selanjutnya"
    nextStepBtn.addEventListener('click', function () {
        if (validateInputs(['Nama_Puskesmas', 'Nomor_Kontak', 'kepala_puskesmas', 'jam_operasional'])) {
            step1.style.display = 'none';
            step3.style.display = 'block';
            currentStep++;
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        } else {
            alert('Mohon isi semua data yang wajib sebelum melanjutkan.');
        }
    });

    // Event klik tombol "Sebelumnya"
    prevStepBtn.addEventListener('click', function () {
        step3.style.display = 'none';
        step1.style.display = 'block';
        currentStep--;
    });

    // Event submit untuk validasi semua input
    form.addEventListener('submit', function (e) {
        if (!validateInputs(['Nama_Puskesmas', 'Nomor_Kontak', 'kepala_puskesmas', 'jam_operasional', 'Alamat', 'longitude', 'latitude'])) {
            e.preventDefault(); // Mencegah pengiriman form jika ada input yang tidak terisi
            alert('Mohon isi semua data dengan lengkap.');
        }
    });
});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>

<script>
  var map, marker;

  function initMap() {
      // Inisialisasi Peta
      map = new google.maps.Map(document.getElementById('mapid'), {
          center: { lat: -7.349107651179965, lng: 108.21763336441607 },
          zoom: 13
      });

      // URL icon kustom
      var puskesmasIconUrl = '../img/puskesmas.png';

      // Ukuran icon kustom
      var iconSize = new google.maps.Size(50, 50); // Sesuaikan ukuran di sini (width, height)

      // Inisialisasi Marker dengan icon kustom dan ukuran yang diatur
      marker = new google.maps.Marker({
          position: { lat: -7.349107651179965, lng: 108.21763336441607 },
          map: map,
          icon: {
              url: puskesmasIconUrl,
              scaledSize: iconSize // Ukuran ikon
          }
      });

      // Inisialisasi Geocoder
      var geocoder = new google.maps.Geocoder();

      // Event Listener untuk klik peta
      google.maps.event.addListener(map, 'click', function(event) {
          var latlng = event.latLng;
          updateCoordinates(latlng);
      });

      // Fungsi untuk mengupdate koordinat
      function updateCoordinates(latlng) {
          marker.setPosition(latlng);
          document.getElementById('longitude').value = latlng.lng().toFixed(6);
          document.getElementById('latitude').value = latlng.lat().toFixed(6);
          updateAddress(latlng);
      }

      // Fungsi untuk mengupdate alamat berdasarkan koordinat
      function updateAddress(latlng) {
          geocoder.geocode({ 'location': latlng }, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                  if (results[0]) {
                      var address = results[0].formatted_address || '';

                      // Menggunakan regex untuk mendeteksi dan menghilangkan Plus Code (contoh M69H+XRP)
                      var plusCodeRegex = /[A-Za-z0-9]{4}\+[A-Za-z0-9]{4}/; // Pola untuk mencocokkan Plus Code
                      address = address.replace(plusCodeRegex, '').trim(); // Menghapus Plus Code jika ada

                      document.getElementById('Alamat').value = address;
                  }
              }
          });
      }

      // Geocoding berdasarkan alamat (Jika ada input geocode)
      var input = document.getElementById('Alamat');
      var autocomplete = new google.maps.places.Autocomplete(input);

      autocomplete.addListener('place_changed', function() {
          var place = autocomplete.getPlace();
          if (place.geometry) {
              updateCoordinates(place.geometry.location);
          }
      });
  }
</script>
</body>
</html>
