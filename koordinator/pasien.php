<?php
session_start();
require "../function.php";
require "../cek.php";
require "pasientambah.php";
require "header.php";
?>

<!-- Tambahkan link CSS dan JS untuk Leaflet Control Geocoder -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIAMmkbWxR5JcB5Ru5bixrtuuE7ofSnqU&callback=initMap" async defer></script>
<style>
    #step1, #step2, #step3 {
        margin-bottom: 15px;
    }

    /* CSS tambahan untuk kontrol pencarian */
    .leaflet-control-geocoder {
        z-index: 1000;
    }

    .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Aligns items to the left and right */
            font-weight: bold;
            font-size: 1.5em;
        }
    .card-header .header-title {
            text-align: center;
            flex: 1; /* Takes up remaining space */
        }
</style>
<style>
        #datatablesSimple th, #datatablesSimple td {
            text-align: center; /* Center-align text by default */
        }
        #datatablesSimple th:nth-child(1),
        #datatablesSimple td:nth-child(1) {
            width: 5%; /* Adjust width for No. column */
        }
        #datatablesSimple th:nth-child(2),
        #datatablesSimple td:nth-child(2) {
            width: 15%; /* Adjust width for Nama Pasien column */
            text-align: left; /* Align text to the left for Nama Pasien */
        }
        #datatablesSimple th:nth-child(3),
        #datatablesSimple td:nth-child(3) {
            width: 35%; /* Adjust width for Alamat column */
            text-align: left; /* Align text to the left for Alamat */
        }
        #datatablesSimple th:nth-child(4),
        #datatablesSimple td:nth-child(4) {
            width: 15%; /* Adjust width for Desa / Kelurahan column */
            text-align: left; /* Align text to the left for Desa / Kelurahan */
        }
        #datatablesSimple th:nth-child(5),
        #datatablesSimple td:nth-child(5) {
            width: 15%; /* Adjust width for Nomor Kontak column */
        }
        #datatablesSimple th:nth-child(6),
        #datatablesSimple td:nth-child(6) {
            width: 20%; /* Adjust width for Aksi column */
        }
    </style>
<style>
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
            background-color: #5a6268;
            border-color: #545b62;
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
                            window.location.href = `pasienlihat.php?id=${userId}`;
                        } else {
                            alert('ID Pasien tidak valid.');
                        }
                    }
                }
            });
        });
    </script>


<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <br>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Kembali Ke Data Pasien"><a href="pasien.php">Data Pasien</a></li>
                    <li class="breadcrumb-item active">Data Pasien</li>
                </ol>
                <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">Data Pasien</div>
                    <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" 
                            data-bs-toggle="tooltip" title="Tambah Data Pasien" hidden>
                        <i class="fas fa-plus"></i>
                    </button>
                    </div>
                </div>
                    <div class="card-body">
                        <table id="datatablesSimple" data-bs-toggle="tooltip" title="Klik untuk melihat detail pasien">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>Desa / Kelurahan</th>
                                    <th>Nomor Kontak</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    $i = 1;
                                    $idpuskesmas = $_SESSION['idpuskesmas'];
                                    $ambildatakoor = mysqli_query($conn, "SELECT * FROM pasien WHERE asalfasyankes = '$idpuskesmas'");
                                    while ($row = mysqli_fetch_array($ambildatakoor)) {
                                        $namapasien = $row['namapasien'];
                                        $alamat = $row['alamat'];
                                        $desakelurahan = $row['desakelurahan'];
                                        $nokontak = $row['nokontak'];
                                        ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $namapasien; ?></td>
                                        <td><?php echo $alamat; ?></td>
                                        <td><?php echo $desakelurahan; ?></td>
                                        <td><?php echo $nokontak; ?><div>
                                                <a href="pasienhapus.php?id=<?php echo $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus Pasien ini?');" data-bs-toggle="tooltip" 
                                                title="Hapus Data"></a>
                                            </div>                                            
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pasien</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="POST" action="pasientambah.php">
                <div class="modal-body">
                    <!-- Langkah 1 dan 2: Informasi Pasien dan Alamat -->
                    <div id="step1">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="mb-3">
                                            NIK
                                            <input name="nik" type="text" class="form-control" placeholder="NIK" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-3">
                                            Nama Pasien
                                            <input name="namapasien" type="text" class="form-control" placeholder="Nama Pasien" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <div class="mb-3">
                                            Domisili
                                            <input name="domisili" type="text" class="form-control" placeholder="Domisili" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-3">
                                            Tempat Lahir
                                            <input name="tempatlahir" type="text" class="form-control" placeholder="Tempat Lahir" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mb-3">
                                            Tanggal Lahir
                                            <input type="date" name="tanggallahir" class="form-control" placeholder="Tanggal Lahir Pasien" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-3">
                                            Jenis Kelamin
                                            <select name="jeniskelamin" class="form-control">
                                                <option value="Laki Laki">Laki Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="mb-3">
                                            Nomor Kontak
                                            <input name="nokontak" type="text" class="form-control" placeholder="Nomor Kontak" required>
                                        </div>
                                    </td>
                                    <td>
                                    <div class="mb-3">
                                        Asal Fasyankes
                                        <select name="asalfasyankes" class="form-control" id="asalfasyankes">
                                            <option value="">Pilih Puskesmas</option>
                                            <?php
                                            // Mendapatkan idpuskesmas dari session pengguna yang login
                                            $idpuskesmas = $_SESSION['idpuskesmas'];
                                            
                                            // Query untuk mendapatkan puskesmas yang sesuai dengan idpuskesmas pengguna yang login
                                            $ambilasalfasyankes = mysqli_query($conn, "SELECT * FROM puskesmas WHERE idpuskesmas = '$idpuskesmas'");
                                            
                                            while ($fetcharray = mysqli_fetch_array($ambilasalfasyankes)) {
                                                $namafasyankes = $fetcharray['Nama_Puskesmas'];
                                                $idpuskesmas = $fetcharray['idpuskesmas'];
                                                $latitude = $fetcharray['latitude'];
                                                $longitude = $fetcharray['longitude'];
                                            ?>
                                                <option value="<?php echo $idpuskesmas; ?>" data-lat="<?php echo $latitude; ?>" data-lng="<?php echo $longitude; ?>">
                                                    <?php echo $namafasyankes; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" title="Menuju Tahap Selanjutnya" id="nextStep">Selanjutnya</button>
                        </div>
                    </div>

                    <!-- Langkah 3: Peta Lokasi -->
                    <div id="step3" style="display: none;">
                        <!-- Baris Peta -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <strong>Pilih melalui peta</strong> <!-- Teks di atas peta -->
                                </div>
                                <div id="mapid" style="height: 250px; width: 100%;" data-bs-toggle="tooltip" title="Klik Lokasi Yang Sesuai Pada Peta"></div> <!-- Atur tinggi dan lebar peta di sini -->
                            </div>
                        </div>

                        <!-- Kolom 1: Input -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    Alamat
                                    <input name="alamat" id="alamat" type="text" class="form-control" placeholder="Alamat" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            RT
                                            <input name="rt" id="rt" type="text" class="form-control form-control-sm" placeholder="RT" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            RW
                                            <input name="rw" id="rw" type="text" class="form-control form-control-sm" placeholder="RW" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    Kode Pos
                                    <input name="kodepos" id="kodepos" type="text" class="form-control" placeholder="Kode Pos" required>
                                </div>
                                <div class="mb-3">
                                    Desa/Kelurahan
                                    <input name="desakelurahan" id="desakelurahan" type="text" class="form-control" placeholder="Desa/Kelurahan" required>
                                </div>
                            </div>
                            
                            <!-- Kolom 2: Input -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    Kecamatan
                                    <input name="kecamatan" id="kecamatan" type="text" class="form-control" placeholder="Kecamatan" required>
                                </div>
                                <div class="mb-3">
                                    Kab/Kota
                                    <input name="kabkota" id="kabkota" type="text" class="form-control" placeholder="Kabupaten/Kota" required>
                                </div>
                                <div class="mb-3">
                                    Longitude
                                    <input name="longitude" id="longitude" type="text" class="form-control" placeholder="Longitude" readonly>
                                </div>
                                <div class="mb-3">
                                    Latitude
                                    <input name="latitude" id="latitude" type="text" class="form-control" placeholder="Latitude" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" id="prevStep" class="btn btn-secondary" data-bs-toggle="tooltip" title="Kembali Ke Tahap Sebelumnya">Sebelumnya</button>
                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Simpan Data">Simpan</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var currentStep = 1;
    var step1 = document.getElementById('step1');
    var step3 = document.getElementById('step3');
    var nextStepBtn = document.getElementById('nextStep');
    var prevStepBtn = document.getElementById('prevStep');
    var map, puskesmasMarker, pasienMarker;
    var puskesmasIconUrl = '../img/puskesmas.png';
    var pasienIconUrl = '../img/pasien.png';

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message) {
        alert(message);  // Untuk notifikasi sederhana, bisa diganti dengan modal notifikasi atau lainnya
    }

    // Fungsi untuk melakukan validasi data pada Langkah 1
    function validateStep1() {
        var nik = document.querySelector('input[name="nik"]').value;
        var namapasien = document.querySelector('input[name="namapasien"]').value;
        var domisili = document.querySelector('input[name="domisili"]').value;
        var tempatlahir = document.querySelector('input[name="tempatlahir"]').value;
        var tanggallahir = document.querySelector('input[name="tanggallahir"]').value;
        var jeniskelamin = document.querySelector('select[name="jeniskelamin"]').value;
        var nokontak = document.querySelector('input[name="nokontak"]').value;
        var asalfasyankes = document.querySelector('select[name="asalfasyankes"]').value;

        if (!nik || !namapasien || !domisili || !tempatlahir || !tanggallahir || !jeniskelamin || !nokontak || !asalfasyankes) {
            return false;
        }
        return true;
    }

    nextStepBtn.addEventListener('click', function () {
        // Cek apakah semua data pada langkah 1 sudah diisi
        if (!validateStep1()) {
            showNotification("Harap lengkapi semua data.");
            return;
        }

        step1.style.display = 'none';
        step3.style.display = 'block';
        currentStep++;

        // Refresh peta setelah langkah 3 ditampilkan
        setTimeout(function() {
            google.maps.event.trigger(map, "resize");

            // Mendapatkan opsi yang dipilih
            var puskesmasSelect = document.getElementById('asalfasyankes');
            var selectedOption = puskesmasSelect.options[puskesmasSelect.selectedIndex];
            var lat = selectedOption.getAttribute('data-lat');
            var lng = selectedOption.getAttribute('data-lng');

            // Jika lat dan lng valid, atur marker puskesmas pada peta
            if (lat && lng) {
                var latlng = new google.maps.LatLng(lat, lng);
                if (puskesmasMarker) {
                    puskesmasMarker.setPosition(latlng);
                } else {
                    puskesmasMarker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        icon: {
                            url: puskesmasIconUrl,
                            scaledSize: new google.maps.Size(32, 32), // Mengatur ukuran ikon Puskesmas (width, height)
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(16, 32)
                        }
                    });
                }
                map.setCenter(latlng);
                map.setZoom(15);
            }
        }, 100);
    });

    prevStepBtn.addEventListener('click', function () {
        step3.style.display = 'none';
        step1.style.display = 'block';
        currentStep--;
    });

    // Inisialisasi Peta Google Maps
    function initMap() {
        map = new google.maps.Map(document.getElementById('mapid'), {
            center: { lat: -7.349107651179965, lng: 108.21763336441607 },
            zoom: 13
        });

        var geocoder = new google.maps.Geocoder();

        google.maps.event.addListener(map, 'click', function(e) {
            var latlng = e.latLng;
            setPasienMarker(latlng); // Gunakan ikon pasien
        });

        // Menambahkan fungsi pencarian alamat menggunakan geocoder
        var input = document.createElement("input");
        input.setAttribute('placeholder', 'Cari alamat...');
        document.body.appendChild(input); // Bisa disesuaikan posisinya

        google.maps.event.addDomListener(input, 'keyup', function(e) {
            geocoder.geocode({ 'address': input.value }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latlng = results[0].geometry.location;
                    setPasienMarker(latlng); // Gunakan ikon pasien
                    map.setCenter(latlng);
                    map.setZoom(15);
                }
            });
        });
    }

    // Menambahkan marker pasien
    function setPasienMarker(latlng) {
        if (pasienMarker) {
            pasienMarker.setPosition(latlng);
        } else {
            pasienMarker = new google.maps.Marker({
                position: latlng,
                map: map,
                icon: {
                    url: pasienIconUrl,
                    scaledSize: new google.maps.Size(32, 32), // Mengatur ukuran ikon Pasien (width, height)
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(16, 32)
                }
            });
        }
        document.getElementById('longitude').value = latlng.lng().toFixed(6);
        document.getElementById('latitude').value = latlng.lat().toFixed(6);
        updateAddress(latlng);
    }

    // Update alamat berdasarkan koordinat
    function updateAddress(latlng) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'location': latlng }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK && results[0]) {
            // Filter komponen alamat lengkap untuk menghapus Plus Code (misalnya J6QR+GH4)
            var filteredAddress = results[0].address_components
                .filter(c => !c.types.includes('plus_code')) // Hapus jika ada tipe 'plus_code'
                .map(c => c.long_name)
                .join(', ');

            var addressComponents = results[0].address_components;

            // Set alamat lengkap tanpa Plus Code
            document.getElementById('alamat').value = filteredAddress;

            // Menangani data RT dan RW jika ada, jika tidak, set ke null
            var rt = addressComponents.find(c => c.types.includes('neighborhood'))?.long_name || null;
            var rw = addressComponents.find(c => c.types.includes('suburb'))?.long_name || null;

            // Ambil desa/kelurahan hanya dari level 4
            var desakelurahan = addressComponents.find(c => 
                c.types.includes('administrative_area_level_4')
            )?.long_name || '';

            document.getElementById('rt').value = rt;
            document.getElementById('rw').value = rw;
            document.getElementById('desakelurahan').value = desakelurahan;
            document.getElementById('kabkota').value = addressComponents.find(c => c.types.includes('administrative_area_level_2'))?.long_name || '';
            document.getElementById('kodepos').value = addressComponents.find(c => c.types.includes('postal_code'))?.long_name || '';
            document.getElementById('kecamatan').value = addressComponents.find(c => c.types.includes('administrative_area_level_3'))?.long_name || '';
            document.getElementById('provinsi').value = addressComponents.find(c => c.types.includes('administrative_area_level_1'))?.long_name || '';
        }
    });
}


    // Inisialisasi peta ketika halaman siap
    initMap();
});
</script>
