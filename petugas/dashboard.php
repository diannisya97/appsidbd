<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Ambil id puskesmas dari session pengguna yang login
$idpuskesmas = $_SESSION['idpuskesmas']; // Pastikan id puskesmas tersimpan di session saat login

// Ambil data pasien hanya untuk puskesmas user yang login
$query = "
    SELECT pasien.*, puskesmas.Nama_Puskesmas, puskesmas.Alamat AS alamat_puskesmas, puskesmas.latitude AS puskesmas_latitude, puskesmas.longitude AS puskesmas_longitude, puskesmas.idpuskesmas
    FROM pasien
    LEFT JOIN puskesmas ON pasien.asalfasyankes = puskesmas.idpuskesmas
    WHERE pasien.asalfasyankes = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idpuskesmas);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}
$patients = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Ambil data lokasi puskesmas untuk ditampilkan di peta
$puskesmasQuery = "SELECT * FROM puskesmas";
$puskesmasResult = mysqli_query($conn, $puskesmasQuery);
if (!$puskesmasResult) {
    die('Query Error: ' . mysqli_error($conn));
}
$puskesmasLocations = mysqli_fetch_all($puskesmasResult, MYSQLI_ASSOC);

// Hitung jumlah pasien dan puskesmas
$totalPatients = count($patients); // Jumlah pasien hanya dari puskesmas pengguna yang login
$totalPuskesmas = count($puskesmasLocations);

// Perbaiki query untuk koordinator
$koordinatorQuery = "
    SELECT COUNT(*) AS total_koordinator
    FROM koordinator
    WHERE asalfasyankes = ? -- Ubah ini sesuai dengan kolom yang benar
";
$koordinatorStmt = $conn->prepare($koordinatorQuery);
$koordinatorStmt->bind_param("i", $idpuskesmas);
$koordinatorStmt->execute();
$koordinatorResult = $koordinatorStmt->get_result();
if (!$koordinatorResult) {
    die('Query Error: ' . mysqli_error($conn));
}
$koordinatorData = mysqli_fetch_assoc($koordinatorResult);
$totalKoordinator = $koordinatorData['total_koordinator'];

// Ambil data jumlah pengguna hanya untuk puskesmas user yang login
$penggunaQuery = "
    SELECT COUNT(*) AS total_pengguna
    FROM user
    WHERE idpuskesmas = ?
";
$penggunaStmt = $conn->prepare($penggunaQuery);
$penggunaStmt->bind_param("i", $idpuskesmas);
$penggunaStmt->execute();
$penggunaResult = $penggunaStmt->get_result();
if (!$penggunaResult) {
    die('Query Error: ' . mysqli_error($conn));
}
$penggunaData = mysqli_fetch_assoc($penggunaResult);
$totalPengguna = $penggunaData['total_pengguna'];

?>
<!-- Modal Popup for Slideshow -->
<div class="modal fade" id="slideshowModal" tabindex="-1" aria-labelledby="slideshowModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="slideshowModalLabel">Petunjuk Aplikasi DBD</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Carousel Slideshow -->
        <div id="slideshowCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="../petunjuk/1.png" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
              <img src="../petunjuk/2.png" class="d-block w-100" alt="Slide 2">
            </div>
            <div class="carousel-item">
              <img src="../petunjuk/3.png" class="d-block w-100" alt="Slide 3">
            </div>
            <div class="carousel-item">
              <img src="../petunjuk/4.png" class="d-block w-100" alt="Slide 4">
            </div>
          </div>
          <!-- Controls for Manual Navigation -->
          <button class="carousel-control-prev" type="button" data-bs-target="#slideshowCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#slideshowCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script to Trigger Modal on Page Load -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var slideshowModal = new bootstrap.Modal(document.getElementById('slideshowModal'));
    slideshowModal.show();
  });
</script>

<!-- Additional CSS for Next/Previous Icons Visibility -->
<style>
  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5); /* Menambahkan background agar ikon terlihat */
    border-radius: 50%; /* Membuat ikon terlihat seperti tombol bulat */
    width: 2.5em;
    height: 2.5em;
  }
</style>

    <style>
        .popup-content {
            max-width: 200px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .popup-content h4 {
            margin: 0;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }
        .popup-content p {
            margin: 5px 0;
            font-size: 12px;
            color: #555;
        }
        .popup-content a {
            display: block;
            margin-top: 10px;
            padding: 3px 8px;
            font-size: 12px;
            color: #fff;
            background-color: #007bff;
            border-radius: 3px;
            text-decoration: none;
            text-align: center;
        }
        .popup-content a:hover {
            background-color: #0056b3;
        }
        .info-card {
    background: linear-gradient(135deg, #004aad 0%, #0072ff 100%); /* Warna biru pilihan */
    border-radius: 15px; /* Sudut melengkung */
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Bayangan lebih dalam */
    color: #fff; /* Warna teks putih */
    display: flex;
    align-items: center;
    transition: all 0.4s ease; /* Transisi halus untuk semua properti */
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.info-card:hover {
    transform: translateY(-10px) scale(1.05); /* Sedikit perbesar dan angkat kartu saat hover */
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3); /* Bayangan lebih dalam saat hover */
}

.info-card .icon {
    font-size: 40px;
    color: rgba(255, 255, 255, 0.85); /* Ikon putih dengan transparansi */
    margin-right: 20px;
    z-index: 1; /* Ikon di atas */
}

.info-card:hover .icon {
    color: #fff; /* Ikon lebih cerah saat hover */
}

.info-card .text {
    display: flex;
    flex-direction: column;
    z-index: 1;
}

.info-card .text .number {
    font-size: 30px;
    font-weight: bold; /* Angka lebih tebal */
    color: #fff;
    margin-bottom: 5px;
}

.info-card .text .label {
    font-size: 14px; /* Ukuran label sedikit lebih kecil */
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 1.2px;
}

/* Variasi efek highlight */
.info-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255, 255, 255, 0.15);
    transform: rotate(45deg);
    transition: all 0.6s ease;
}

.info-card:hover::before {
    top: -30%;
    left: -30%;
    width: 160%;
    height: 160%;
    background: rgba(255, 255, 255, 0.2);
}

/* Variasi warna kartu */
.info-card.red {
    background: linear-gradient(135deg, #dd3c3c 0%, #ff6b6b 100%); /* Warna merah pilihan */
}

.info-card.blue {
    background: linear-gradient(135deg, #004aad 0%, #00a2ff 100%); /* Warna biru pilihan */
}

.info-card.dark {
    background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%); /* Warna abu-abu kehitaman */
}

.info-card.green {
    background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%); /* Warna hijau-biru */
}
.card-header {
    font-size: 20px;
    font-weight: bold;
    color: #fff; /* Warna teks putih */
    background: linear-gradient(135deg, #004aad, #0072ff); /* Gradasi warna biru tua ke biru cerah */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 15px;
    text-align: center; /* Teks berada di tengah */
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1); /* Efek bayangan */
    text-transform: uppercase; /* Huruf kapital untuk tampilan yang lebih formal */
    letter-spacing: 1.5px; /* Spasi antar huruf untuk efek elegan */
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: "";
    position: absolute;
    top: 0;
    left: -50%;
    width: 200%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transform: skewX(-20deg);
    transition: all 0.5s ease;
}

.card-header:hover::before {
    left: 100%;
}

.card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

/* Tambahan estetika pada card-body */
.card-body {
    background: #f9f9fb;
    padding: 20px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    font-size: 16px;
    color: #444;
    line-height: 1.6;
    position: relative;
}


    /* Ukuran peta agar setara dengan dua grafik di sebelah kanan */
    #map {
        height: 380px; /* Tinggi disetarakan dengan dua grafik */
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
    }
    .chart-wrapper {
        position: relative;
        height: 300px; /* Atur tinggi grafik */
    }
    .chart-wrapper {
        margin-bottom: 20px;
    }

    /* Ukuran Chart agar setara dengan tinggi peta */
    #chartBar, #chartLine {
        height: 240px;
        width: 100%;
        background-color: #f4f4f9;
        border-radius: 15px;
        padding: 15px;
    }

    .chart-container {
        display: flex;
        flex-direction: column; /* Kolom agar grafik atas dan bawah */
        justify-content: space-between;
        height: 500px; /* Tinggi total setara dengan peta */
    }
</style>
<style>
    /* Umum: buat card dan map lebih responsif */
    .card {
        margin: 10px 0;
        padding: 15px;
    }

    .info-card {
        margin: 10px 0;
        padding: 15px;
        font-size: 14px;
    }

    /* Media query untuk tampilan mobile */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0 15px;
        }

        .info-card {
            font-size: 12px;
            padding: 10px;
        }

        .info-card .text .number {
            font-size: 24px;
        }

        .info-card .text .label {
            font-size: 12px;
        }

        .card-header {
            font-size: 18px;
        }

        #map {
            height: 250px;
        }

        .chart-wrapper {
            height: 180px;
        }

        .card-body {
            padding: 10px;
        }

        .chart-container {
            height: auto; /* Menyesuaikan tinggi untuk mobile */
        }
    }

    /* Media query untuk tampilan tablet */
    @media (min-width: 768px) and (max-width: 1024px) {
        .container-fluid {
            padding: 0 30px;
        }

        .info-card {
            font-size: 14px;
        }

        .card-header {
            font-size: 20px;
        }

        #map {
            height: 300px;
        }

        .chart-wrapper {
            height: 220px;
        }

    /* Global Styling for Mobile */
    @media (max-width: 768px) {
    /* Mengatur ukuran font dan padding untuk tampilan lebih kecil */
    h1, .card-header, .label, .number {
        font-size: 1rem;
    }

    /* Mengatur ulang tata letak card agar satu baris hanya memuat satu card */
    .info-card, .card {
        margin-bottom: 1rem;
        text-align: center;
    }

    /* Mengatur ulang tampilan card dalam satu kolom di perangkat mobile */
    .row > .col-md-3, .row > .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    /* Modal ukuran layar mobile */
    .modal-lg {
        max-width: 95%;
    }

    /* Menyesuaikan lebar dan tinggi gambar carousel di modal */
    #slideshowCarousel img {
        max-height: 250px;
        object-fit: cover;
    }

    /* Mengatur ulang tinggi textarea peta */
    #map {
        height: 200px;
    }

    /* Grafik ukuran layar mobile */
    .chart-wrapper canvas {
        height: 200px !important;
    }

    /* Spacing adjustments */
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Mengatur ulang tata letak alamat dan peta */
@media (max-width: 576px) {
    /* Mengubah tinggi textarea alamat */
    .address-textarea {
        height: 150px;
    }

    /* Alamat dan peta tampil dalam satu kolom di layar kecil */
    .address-section, .map-section {
        width: 100%;
    }
}

</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
        <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4"></h1>
                        <div class="row">
                        <!-- Card Informasi -->
                            <div class="col-md-3">
                                <div class="info-card" title="Total jumlah pasien terdaftar">
                                    <div class="icon">
                                        <i class="fas fa-procedures"></i>
                                    </div> <!-- Ikon pasien -->
                                    <div class="text">
                                        <div class="number"><?php echo $totalPatients; ?> Orang</div>
                                        <div class="label">Pasien</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card" title="Total jumlah fasilitas kesehatan">
                                    <div class="icon">
                                        <i class="fas fa-hospital"></i>
                                    </div> <!-- Ikon puskesmas -->
                                    <div class="text">
                                        <div class="number"><?php echo $totalPuskesmas; ?> Faskes</div>
                                        <div class="label">Faskes</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card" title="Total jumlah koordinator">
                                    <div class="icon">
                                        <i class="fas fa-users-cog"></i>
                                    </div> <!-- Ikon koordinator -->
                                    <div class="text">
                                        <div class="number"><?php echo $totalKoordinator; ?> Orang</div>
                                        <div class="label">Koordinator</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card" title="Total jumlah pengguna sistem">
                                    <div class="icon">
                                        <i class="fas fa-user-cog"></i>
                                    </div> <!-- Ikon pengguna -->
                                    <div class="text">
                                        <div class="number"><?php echo $totalPengguna; ?> Orang</div>
                                        <div class="label">Pengguna</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Row untuk card baru dan peta -->
                            <div class="row">
                                <!-- Card di atas peta -->
                                <div class="col-md-6">
                                    <div class="card" style="height: 350px;"data-bs-toggle="tooltip" title="Selamat datang di aplikasi APP-SIDBD!">
                                        <div class="card-header">SELAMAT DATANG</div>
                                        <div class="card-body">
                                            <p>Aplikasi Sistem Informasi Demam Berdarah (APP-SIDBD) merupakan sebuah sistem t </p>
                                        </div>
                                    </div>
<p>
                                    <!-- Peta -->
                                    <div class="card" data-bs-toggle="tooltip" title="Peta sebaran kasus DBD ">
                                        <div class="card-header">Peta Sebaran DBD</div>
                                        <div class="card-body">
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Grafik Batang dan Grafik Garis -->
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="card" data-bs-toggle="tooltip" title="Grafik jumlah pemeriksaan">
                                                <div class="card-header">Grafik Jumlah Pemeriksaan Pasien</div>
                                                <div class="card-body">
                                                    <div class="chart-wrapper">
                                                        <canvas id="myBarChart" style="height: 300px;"></canvas> <!-- Grafik batang -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                    <div class="card mt-3" data-bs-toggle="tooltip" title="Grafik data pengendalian vektor">
                                        <div class="card-header">Grafik Data Pengendalian Vektor</div>
                                        <div class="card-body">
                                            <div class="chart-wrapper">
                                                <canvas id="myVektorChart" style="height: 300px;"></canvas> <!-- Grafik batang -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>

<script>
    function initMap() {
        // Inisialisasi peta dengan lokasi pasien pertama
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: <?php echo $patients[0]['latitude']; ?>, lng: <?php echo $patients[0]['longitude']; ?> },
            zoom: 13
        });

        // Membuat satu instance InfoWindow untuk digunakan ulang
        var infoWindow = new google.maps.InfoWindow();

        <?php foreach ($patients as $patient): ?>
            // Membuat marker untuk pasien
            var markerPasien = new google.maps.Marker({
                position: { lat: <?php echo $patient['latitude']; ?>, lng: <?php echo $patient['longitude']; ?> },
                map: map,
                icon: {
                    url: '../img/pasien.png', // Path ikon pasien
                    scaledSize: new google.maps.Size(40, 40) // Ukuran ikon
                }
            });

            // Fungsi untuk menampilkan InfoWindow pasien
            (function(markerPasien, namaPasien, namaPuskesmas, pasienId) {
                google.maps.event.addListener(markerPasien, 'click', function() {
                    // Menutup InfoWindow yang terbuka sebelumnya dan menetapkan konten baru
                    infoWindow.setContent(`
                        <div class="popup-content">
                            <h4>${namaPasien}</h4>
                            <p>${namaPuskesmas}</p>
                            <a href="pasienlihat.php?id=${pasienId}">Lihat Detail</a>
                        </div>
                    `);
                    infoWindow.open(map, markerPasien);
                });
            })(markerPasien, "<?php echo $patient['namapasien']; ?>", "<?php echo $patient['Nama_Puskesmas']; ?>", "<?php echo $patient['id']; ?>");

            // Membuat marker untuk puskesmas
            var markerPuskesmas = new google.maps.Marker({
                position: { lat: <?php echo $patient['puskesmas_latitude']; ?>, lng: <?php echo $patient['puskesmas_longitude']; ?> },
                map: map,
                icon: {
                    url: '../img/puskesmas.png', // Path ikon puskesmas
                    scaledSize: new google.maps.Size(40, 40) // Ukuran ikon
                }
            });

            // Fungsi untuk menampilkan InfoWindow puskesmas
            (function(markerPuskesmas, namaPuskesmas, puskesmasId) {
                google.maps.event.addListener(markerPuskesmas, 'click', function() {
                    // Menutup InfoWindow yang terbuka sebelumnya dan menetapkan konten baru
                    infoWindow.setContent(`
                        <div class="popup-content">
                            <h4>${namaPuskesmas}</h4>
                            <a href="puskesmaslihat.php?id=${puskesmasId}">Lihat Detail</a>
                        </div>
                    `);
                    infoWindow.open(map, markerPuskesmas);
                });
            })(markerPuskesmas, "<?php echo $patient['Nama_Puskesmas']; ?>", "<?php echo $patient['idpuskesmas']; ?>");

        <?php endforeach; ?>
    }
</script>
<script>
    // Ambil data dari data_vektor.php
    fetch('data_vektor.php')
        .then(response => response.json())
        .then(data => {
            console.log('Data Vektor:', data); // Debugging output

            // Grafik Pengendalian Vektor
            var ctxVektor = document.getElementById('myVektorChart').getContext('2d');
            new Chart(ctxVektor, {
                type: 'bar',
                data: {
                    labels: ['Fogging', 'Larvasidasi', 'PSN3M', 'Penyuluhan'],
                    datasets: [
                        {
                            label: 'Pernah',
                            data: [
                                data.fogging_pernah,
                                data.larvasidasi_pernah,
                                data.psn3m_pernah,
                                data.penyuluhan_pernah
                            ],
                            backgroundColor: 'rgba(0, 123, 255, 0.2)',
                            borderColor: 'rgba(0, 123, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Tidak Pernah',
                            data: [
                                data.fogging_tidak_pernah,
                                data.larvasidasi_tidak_pernah,
                                data.psn3m_tidak_pernah,
                                data.penyuluhan_tidak_pernah
                            ],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Lompatan dari 0 ke 1, 1 ke 2, dst.
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error:', error)); // Error handling

    // Ambil data dari data_pemeriksaan.php
    fetch('data_pemeriksaan.php')
        .then(response => response.json())
        .then(data => {
            // Grafik Pemeriksaan Pasien per Hari/Tanggal
            var ctxPemeriksaan = document.getElementById('myBarChart').getContext('2d');
            new Chart(ctxPemeriksaan, {
                type: 'bar',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Jumlah Pasien per Tanggal',
                        data: data.counts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Lompatan dari 0 ke 1, 1 ke 2, dst.
                            }
                        }
                    }
                }
            });
        });
</script>

</body>
</html>
