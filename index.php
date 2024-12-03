<?php include "functionn.php"; ?>
<?php
// Ambil data pasien dan lokasi puskesmas dari database
$query = "
    SELECT pasien.*, puskesmas.Nama_Puskesmas, puskesmas.Alamat AS alamat_puskesmas, puskesmas.latitude AS puskesmas_latitude, puskesmas.longitude AS puskesmas_longitude, puskesmas.idpuskesmas
    FROM pasien
    LEFT JOIN puskesmas ON pasien.asalfasyankes = puskesmas.idpuskesmas
";
$result = mysqli_query($conn, $query);
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
$totalPatients = count($patients);
$totalPuskesmas = count($puskesmasLocations);

// Ambil data jumlah koordinator
$koordinatorQuery = "SELECT COUNT(*) AS total_koordinator FROM koordinator";
$koordinatorResult = mysqli_query($conn, $koordinatorQuery);
if (!$koordinatorResult) {
    die('Query Error: ' . mysqli_error($conn));
}
$koordinatorData = mysqli_fetch_assoc($koordinatorResult);
$totalKoordinator = $koordinatorData['total_koordinator'];

// Ambil data jumlah pengguna
$penggunaQuery = "SELECT COUNT(*) AS total_pengguna FROM user";
$penggunaResult = mysqli_query($conn, $penggunaQuery);
if (!$penggunaResult) {
    die('Query Error: ' . mysqli_error($conn));
}
$penggunaData = mysqli_fetch_assoc($penggunaResult);
$totalPengguna = $penggunaData['total_pengguna'];

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU"></script>
<?php

// Ambil data infografis dari database
$query = "SELECT id, judul, deskripsi, foto1 FROM infografis";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}

$infografisData = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>
<?php
// Query untuk menghitung tren kasus per bulan
$trendQuery = "
    SELECT 
        MONTH(tanggalperiksa) AS bulan, 
        COUNT(*) AS jumlah_kasus
    FROM periksapasien
    GROUP BY MONTH(tanggalperiksa)
    ORDER BY bulan;
";
$trendResult = mysqli_query($conn, $trendQuery);

if (!$trendResult) {
    die('Query Error: ' . mysqli_error($conn));
}

// Siapkan array untuk grafik
$trendData = [];
while ($row = mysqli_fetch_assoc($trendResult)) {
    $trendData[(int)$row['bulan']] = (int)$row['jumlah_kasus'];
}

// Buat array untuk semua bulan (1-12) dengan nilai default 0
$trendFinalData = array_fill(1, 12, 0);
foreach ($trendData as $month => $cases) {
    $trendFinalData[$month] = $cases;
}
// Tutup koneksi
mysqli_close($conn);
?>

<?php include "header.php"; ?>


<!-- CSS -->
<style>
.container-fluid {
    position: relative;
    padding: 0 40px;
    overflow: hidden;
    margin-top: 10px;
}

.form-container {
    display: flex;
    gap: 20px;
    box-sizing: border-box;
    width: 100%;
    overflow-x: hidden;
    scroll-behavior: smooth;
    position: relative;
}

.form-card {
    flex: 0 0 220px;
    background-color: #f4f4f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.5s ease, opacity 0.5s ease;
    overflow: hidden;
}

.form-card img {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    max-height: 150px;
    object-fit: cover;
    width: 100%;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.description {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 15px;
}

.arrow-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    z-index: 10;
    padding: 10px;
}

.arrow-left {
    left: 10px;
}

.arrow-right {
    right: 10px;
}

/* Responsivitas */
@media (min-width: 1200px) {
    .form-card {
        flex: 0 0 calc(25% - 20px); /* Tampilkan 4 form di laptop */
    }
}

@media (max-width: 1199px) and (min-width: 768px) {
    .form-card {
        flex: 0 0 calc(50% - 20px); /* Tampilkan 2 form di tablet */
    }
}

@media (max-width: 767px) {
    .form-card {
        flex: 0 0 100%; /* Tampilkan 1 form di HP */
    }
}
/* Atur tampilan default untuk desktop */
.about-banner {
    height: 400px;
    margin-bottom: -90px;
}

.about-banner .about-content {
    position: relative;
    top: -10px;
    text-align: center;
}

/* Untuk mobile devices */
@media only screen and (max-width: 768px) {
    .about-banner {
        height: auto; /* Biarkan tinggi konten yang menentukan */
        margin-bottom: 0; /* Hilangkan margin negatif */
    }

    .about-banner .about-content {
        top: 0; /* Reset posisi */
        padding: 20px; /* Tambahkan padding untuk ruang */
    }

    .about-banner h1 {
        font-size: 24px; /* Sesuaikan ukuran font */
    }

    .about-banner p {
        font-size: 14px; /* Sesuaikan ukuran font */
        padding: 10px 0;
    }

    .primary-btn {
        font-size: 14px; /* Sesuaikan ukuran tombol */
        padding: 10px 20px;
    }
}
/* Default styles */
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
    font-size: 20px;
    font-weight: bold; /* Angka lebih tebal */
    color: #fff;
    margin-bottom: 5px;
}

.info-card .text .label {
    font-size: 10px; /* Ukuran label sedikit lebih kecil */
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


/* Peta styling */
#map {
    height: 470px;
    width: 100%;
    margin-top: 20px;
}

/* Mobile specific adjustments */
@media only screen and (max-width: 768px) {
    /* Cards stacked vertically */
    .row.justify-content-center {
        flex-direction: column;
        align-items: stretch; /* Stretch cards to full width */
    }

    .info-card {
        width: 100%; /* Full width on mobile */
        padding: 15px; /* Less padding for smaller screen */
        margin-bottom: 15px; /* Less space between cards */
    }

    .info-card .icon {
        font-size: 30px; /* Adjust icon size */
    }

    .info-card .text .number {
        font-size: 20px; /* Adjust number size */
    }

    .info-card .text .label {
        font-size: 14px; /* Adjust label size */
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }

    #map {
        height: 0px; /* Smaller map height for mobile */
    }

    h1.mb-10 {
        font-size: 22px; /* Adjust heading font size */
    }

    p {
        font-size: 14px; /* Adjust paragraph font size */
        padding: 0 10px; /* Add padding for readability */
    }

    /* Align cards and map neatly */
    .info-card {
        margin-left: auto;
        margin-right: auto;
    }

    /* Reduce font sizes for mobile readability */
    .text .number {
        font-size: 18px;
    }

    .text .label {
        font-size: 12px;
    }
}
.puskesmas-card {
    background: #fff;
    border-radius: 8px;
    transition: transform 0.3s ease;
    width: 220px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.puskesmas-card:hover {
    transform: translateY(-5px);
}
.puskesmas-name {
    font-weight: bold;
    color: #333;
}
.puskesmas-address {
    font-size: 14px;
    margin: 10px 0;
}
.card-title {
    font-weight: 600;
    margin-bottom: 20px;
}
.container-fluid {
    font-family: 'Arial', sans-serif;
    color: #333;
}

/* Menyesuaikan tampilan untuk tabel */
.table th,
.table td {
    word-wrap: break-word;  /* Memastikan kata yang panjang tidak keluar dari kolom */
    white-space: nowrap;    /* Menjaga teks tetap berada di dalam kolom */
    padding: 8px;           /* Menambah ruang di dalam sel tabel */
    text-align: left;       /* Menjaga teks di dalam tabel rata kiri */
}

/* Tabel dengan layout fixed agar lebih stabil */
.table {
    table-layout: fixed;    /* Memastikan kolom tidak melebar otomatis */
    width: 100%;            /* Lebar tabel 100% */
    margin-bottom: 0;       /* Menghilangkan margin bawah */
}

/* Mengatur tampilan di desktop */
@media (min-width: 769px) {

    /* Menyesuaikan lebar kolom No dan Status agar lebih kecil */
    .table th:nth-child(1),
    .table td:nth-child(1),
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 5%;            /* Lebar kolom No lebih kecil */
        max-width: 50px;      /* Membatasi lebar */
        text-align: center;   /* Menjaga teks di tengah */
    }

    /* Memastikan kolom Nama Puskesmas lebih lebar */
    .table th:nth-child(2),
    .table td:nth-child(2),
    .table th:nth-child(5),
    .table td:nth-child(5) {
        min-width: 200px;     /* Menyediakan ruang untuk Nama Puskesmas */
    }

    /* Kolom Status (kolom 3 dan 6) tetap proporsional */
    .table th:nth-child(3),
    .table td:nth-child(3),
    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 15%;           /* Menyesuaikan lebar kolom Status */
        text-align: center;   /* Menjaga status berada di tengah */
    }

    /* Pengaturan kolom untuk tampilan yang seimbang */
    .col-md-6 {
        width: 50%;           /* Membagi 2 kolom dengan lebar 50% */
        padding: 10px;        /* Memberikan jarak di dalam kolom */
    }

    /* Pengaturan agar card tetap rapi */
    .card {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Memberikan bayangan pada card */
        border: 1px solid #ddd; /* Membuat garis tipis di sekitar card */
    }

    /* Grafik dan tabel sama-sama berada dalam kolom 50% */
    .col-md-6 .card {
        height: 100%;         /* Memastikan card memenuhi kolom */
    }

    /* Tabel pada card */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9; /* Warna latar belakang setiap baris ganjil */
    }

    /* Pengaturan kolom untuk grafik */
    #trendChart {
        height: 100%;         /* Memastikan grafik memenuhi card */
    }
}
/* Pengaturan untuk tabel di perangkat mobile */
@media (max-width: 768px) {
    /* Membuat tabel responsif dengan scroll horizontal */
    .table-responsive {
        overflow-x: auto;  /* Menambahkan scroll horizontal jika tabel melewati batas */
        -webkit-overflow-scrolling: touch;  /* Untuk kelancaran scroll pada perangkat sentuh */
        margin-bottom: 15px; /* Menambahkan margin bawah */
    }

    /* Pengaturan tabel agar tidak melebihi lebar card */
    .table {
        width: 100%;       /* Memastikan tabel mengambil seluruh lebar card */
        table-layout: auto; /* Mengatur lebar kolom otomatis */
    }

    /* Pengaturan kolom untuk kolom pertama dan keempat (No) */
    .table th:nth-child(1),
    .table td:nth-child(1),
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 10%;          /* Menetapkan lebar kolom No menjadi lebih sempit */
    }

    /* Lebar kolom Nama Puskesmas (Kolom 2 dan 5) */
    .table th:nth-child(2),
    .table td:nth-child(2),
    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 35%;          /* Kolom 2 dan 5 mendapatkan lebih banyak ruang */
    }

    /* Lebar kolom Status (Kolom 3 dan 6) */
    .table th:nth-child(3),
    .table td:nth-child(3),
    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 20%;          /* Kolom 3 dan 6 mendapatkan lebar standar */
    }

    /* Mengatur agar card dan tabel mengisi seluruh kolom pada perangkat mobile */
    .col-md-6 {
        width: 100%;         /* Kolom menjadi 100% pada tampilan mobile */
        padding: 0 15px;     /* Menambahkan padding kiri dan kanan */
        margin-bottom: 15px; /* Menambahkan margin bawah agar jaraknya lebih lebar */
    }

    /* Mengatur tinggi grafik untuk perangkat mobile */
    #trendChart {
        height: 250px;       /* Mengatur agar grafik lebih kecil di perangkat mobile */
    }
}

/* Untuk desktop, tetap menjaga lebar kolom agar tidak terpengaruh oleh perubahan di mobile */
@media (min-width: 769px) {
    .table th, .table td {
        padding: 12px;       /* Padding yang lebih besar di desktop */
    }

    /* Menjaga lebar kolom pada tampilan desktop */
    .table th:nth-child(1),
    .table td:nth-child(1),
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 8%;
    }

    .table th:nth-child(2),
    .table td:nth-child(2),
    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 35%;
    }

    .table th:nth-child(3),
    .table td:nth-child(3),
    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 22%;
    }
}

</style>

<section class="about-banner relative" style="height: 400px; margin-bottom: -90px;">
  <div class="overlay overlay-bg"></div>
  <div class="container">
    <div class="row d-flex align-items-start justify-content-center">
      <div class="about-content col-lg-12" style="position: relative; top: -10px;">
        <h1 class="text-white">
            Aplikasi Sistem Informasi Demam Berdarah
        </h1>
        <p class="text-white link-nav">Sistem ini memungkinkan visualisasi lokasi pasien DBD serta faskes yang menangani mereka, seperti Puskesmas dan Rumah Sakit. Peta ini akan membantu dalam memantau sebaran kasus dan memfasilitasi koordinasi penanganan DBD secara lebih efisien.</p>
        <br><a href="#maps" class="primary-btn text-uppercase">Lihat Sebaran DBD</a>
      </div>
    </div>
  </div>
</section>


<main id="main">
  <section class="about-info-area section-gap">
<!-- Konten Utama -->
<div class="container-fluid mt-5">
    <div class="row justify-content-center mb-4 form-container">
        <?php foreach ($infografisData as $info) { ?>
        <div class="form-card">
            <img src="uploads/<?php echo $info['foto1']; ?>" alt="Gambar Infografis">
            <div class="p-3">
                <h5 class="card-title"><?php echo $info['judul']; ?></h5>
                <p class="card-text description"><?php echo $info['deskripsi']; ?></p>
                <a href="detailinfografis.php?id=<?php echo $info['id']; ?>" class="btn btn-primary">Selengkapnya</a>
            </div>
        </div>
        <?php } ?>
    </div>
    <button class="arrow-button arrow-left" onclick="slideLeft()">&#9664;</button>
    <button class="arrow-button arrow-right" onclick="slideRight()">&#9654;</button>
</div>
<div class="container-fluid mt-5">
    <div class="row">
        <!-- Grafik Tren Bulanan -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm bg-light h-100">
                <h4 class="card-title text-center">Grafik Tren Kasus DBD Tiap Bulan</h4>
                <canvas id="trendChart" class="w-100" style="height: 100%;"></canvas>
            </div>
        </div>

        <!-- Tabel Nama Puskesmas -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm bg-light h-100">
                <h4 class="card-title text-center">Daftar Faskes Melayani Kasus DBD</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Puskesmas</th>
                                <th scope="col">Status</th>
                                <th scope="col">No</th>
                                <th scope="col">Nama Puskesmas</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no1 = 1; // Nomor untuk kolom pertama
                            $no4 = 6; // Nomor untuk kolom keempat
                            $total = count($puskesmasLocations); 
                            $max_data = ceil($total / 2); // Membatasi data untuk setiap kolom
                            for ($i = 0; $i < $max_data; $i++) { 
                            ?>
                            <tr>
                                <!-- Kolom pertama (No) -->
                                <td><?php echo $no1++; ?></td>
                                <td><?php echo $puskesmasLocations[$i]['Nama_Puskesmas']; ?></td>
                                <td><span class="badge badge-success">Tersedia</span></td>

                                <!-- Kolom keempat (No) -->
                                <?php if (isset($puskesmasLocations[$i + $max_data])) { ?>
                                <td><?php echo $no4++; ?></td>
                                <td><?php echo $puskesmasLocations[$i + $max_data]['Nama_Puskesmas']; ?></td>
                                <td><span class="badge badge-success">Tersedia</span></td>
                                <?php } else { ?>
                                <!-- Jika kolom keempat tidak ada data -->
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

    <div class="container">
      <div class="row justify-content-center mb-4">
        <div class="col-md-3">
          <div class="info-card">
            <div class="icon"><i class="fas fa-procedures"></i></div> <!-- Ikon pasien -->
            <div class="text">
              <div class="number"><?php echo $totalPatients; ?> Orang</div>
              <div class="label">Jumlah Pasien</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-card">
            <div class="icon"><i class="fas fa-hospital"></i></div> <!-- Ikon puskesmas -->
            <div class="text">
              <div class="number"><?php echo $totalPuskesmas; ?> Faskes</div>
              <div class="label">Jumlah Faskes</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-card">
            <div class="icon"><i class="fas fa-users-cog"></i></div> <!-- Ikon koordinator -->
            <div class="text">
              <div class="number"><?php echo $totalKoordinator; ?> Orang</div>
              <div class="label">Jumlah Koordinator</div>
            </div>
          </div>
        </div>
        <div id="maps" class="col-md-3">
          <div class="info-card">
            <div class="icon"><i class="fas fa-user-cog"></i></div> <!-- Ikon pengguna -->
            <div class="text">
              <div class="number"><?php echo $totalPengguna; ?> Orang</div>
              <div class="label">Jumlah Pengguna</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <center><h1 class="mb-10">Peta Sebaran Demam Berdarah (DBD)</h1></center>
          <p>Aplikasi pemetaan Penyakit Demam Berdarah di Kota Tasikmalaya ini memuat informasi dan lokasi dari kasus Demam Berdarah yang terjadi di Kota Tasikmalaya. Selain itu, aplikasi ini juga menampilkan lokasi fasilitas kesehatan (faskes) yang menangani kasus Demam Berdarah, seperti puskesmas, rumah sakit, dan klinik yang siap memberikan layanan penanganan dan perawatan terhadap pasien yang terjangkit. </p>
          <div id="mapid" style="height: 470px; width: 100%;"></div>
        </div>
      </div>
    </div>


<!-- Modal -->
<div id="puskesmasModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <table>
            <tr>
                <th>Nama Puskesmas:</th>
                <td id="modalTitle"></td>
            </tr>
            <tr>
                <th>Alamat:</th>
                <td id="modalAddress"></td>
            </tr>
            <tr>
                <th>Nomor Kontak:</th>
                <td id="modalContact"></td>
            </tr>
        </table>
    </div>
</div>

</div>
  </section>
</main>
<?php include "footer.php"; ?>
<!-- Modal -->

<script>
    const trendData = <?php echo json_encode(array_values($trendFinalData)); ?>;
    const trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trendChart').getContext('2d');
const trendChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Jumlah Kasus DBD',
            data: trendData,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                beginAtZero: true
            },
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});


    function showPuskesmasModal(name, address) {
        document.getElementById('modalTitle').innerText = name;
        document.getElementById('modalAddress').innerText = address;
        document.getElementById('puskesmasModal').style.display = 'block';
    }
</script>
<style>
.custom-popup {
    font-family: Arial, sans-serif;
    font-size: 12px; /* Ukuran teks */
    line-height: 1.5; /* Tinggi baris */
    padding: 10px; /* Padding */
    max-width: 220px; /* Lebar maksimal */
    border: 1px solid #ddd; /* Garis tepi */
    border-radius: 8px; /* Sudut melengkung */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan */
    background-color: #ffffff; /* Latar belakang putih */
    color: #333; /* Warna teks */
}

.custom-popup h4 {
    margin: 0 0 8px; /* Spasi bawah */
    font-size: 14px; /* Ukuran font judul */
    font-weight: bold;
    color: #007bff; /* Warna teks judul */
}

.custom-popup p {
    margin: 0;
    font-size: 12px; /* Ukuran font deskripsi */
    color: #666; /* Warna teks deskripsi */
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi peta dengan titik lokasi pertama pasien
    var map = new google.maps.Map(document.getElementById('mapid'), {
        center: { lat: <?php echo $patients[0]['latitude']; ?>, lng: <?php echo $patients[0]['longitude']; ?> },
        zoom: 13
    });

    // Inisialisasi InfoWindow global
    var globalInfoWindow = new google.maps.InfoWindow();

    // Fungsi untuk membuat marker dan event listener
    function createMarker(map, position, title, content, iconUrl) {
        var marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: {
                url: iconUrl,
                scaledSize: new google.maps.Size(50, 50) // Ukuran ikon
            },
            title: title
        });

        marker.addListener('click', function () {
            globalInfoWindow.setContent(content);
            globalInfoWindow.open(map, marker);
        });

        return marker;
    }

    // Tambahkan marker untuk setiap pasien dan puskesmas
    <?php foreach ($patients as $patient): ?>
    createMarker(
        map,
        { lat: <?php echo $patient['latitude']; ?>, lng: <?php echo $patient['longitude']; ?> },
        "<?php echo $patient['namapasien']; ?>",
        `
            <div class="custom-popup">
                <h4><?php echo $patient['namapasien']; ?></h4>
                <p><strong>Puskesmas:</strong> <?php echo $patient['Nama_Puskesmas']; ?></p>
            </div>
        `,
        'img/pasien.png'
    );

    createMarker(
        map,
        { lat: <?php echo $patient['puskesmas_latitude']; ?>, lng: <?php echo $patient['puskesmas_longitude']; ?> },
        "<?php echo $patient['Nama_Puskesmas']; ?>",
        `
            <div class="custom-popup">
                <h4><?php echo $patient['Nama_Puskesmas']; ?></h4>
            </div>
        `,
        'img/puskesmas.png'
    );
    <?php endforeach; ?>
});

</script>




<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.form-container');
    const formCards = document.querySelectorAll('.form-card');
    let currentIndex = 0;
    const cardsToShowDesktop = 4; // Jumlah form yang ditampilkan di laptop
    const cardsToShowMobile = 1;  // Jumlah form yang ditampilkan di HP
    const cardsToShow = window.innerWidth >= 1200 ? cardsToShowDesktop : cardsToShowMobile;

    function updateVisibleCards() {
        formCards.forEach((card, index) => {
            if (index >= currentIndex && index < currentIndex + cardsToShow) {
                card.style.display = 'block';
                card.style.transform = 'translateX(0)';
                card.style.opacity = '1';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function slideRight() {
        currentIndex = (currentIndex + 1) % formCards.length;
        updateVisibleCards();
    }

    function slideLeft() {
        currentIndex = (currentIndex - 1 + formCards.length) % formCards.length;
        updateVisibleCards();
    }

    // Fungsi untuk memastikan jumlah form yang tampil disesuaikan saat ukuran layar berubah
    window.addEventListener('resize', () => {
        const newCardsToShow = window.innerWidth >= 1200 ? cardsToShowDesktop : cardsToShowMobile;
        if (cardsToShow !== newCardsToShow) {
            cardsToShow = newCardsToShow;
            updateVisibleCards();
        }
    });

    document.querySelector('.arrow-left').addEventListener('click', slideLeft);
    document.querySelector('.arrow-right').addEventListener('click', slideRight);

    updateVisibleCards(); // Inisialisasi tampilan form
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const scrollToMapBtn = document.querySelector('a[href="#maps"]');
    
    if (scrollToMapBtn) {
        scrollToMapBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default dari tautan

            // Mengambil elemen peta
            const mapSection = document.getElementById('maps');

            if (mapSection) {
                // Menggunakan scrollIntoView dengan opsi smooth
                mapSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});

</script>