<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Mendapatkan ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die('ID Pemeriksaan tidak valid!');
}

// Mengambil data dari database berdasarkan ID
$query = "
    SELECT periksapasien.*, pasien.namapasien AS nama_pasien, koordinator.namakoor
    FROM periksapasien
    LEFT JOIN pasien ON periksapasien.namapasien = pasien.id
    LEFT JOIN koordinator ON periksapasien.pjpemeriksa = koordinator.id
    WHERE periksapasien.id = '$id'
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query error: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    die('Data tidak ditemukan');
}

// Ambil ID pasien dan nama pasien dari data periksapasien
$id_pasien = $row['namapasien']; // ID pasien
$nama_pasien = $row['nama_pasien']; // Nama pasien
$id_koordinator = $row['pjpemeriksa']; // ID koordinator
$nama_koordinator = $row['namakoor']; // Nama koordinator

// Ambil data pasien dari tabel pasien
$query_pasien = "SELECT id, namapasien FROM pasien";
$result_pasien = mysqli_query($conn, $query_pasien);
$pasien_options = '';

while ($row_pasien = mysqli_fetch_assoc($result_pasien)) {
    $selected = ($row_pasien['id'] == $id_pasien) ? 'selected' : '';
    $pasien_options .= "<option value='{$row_pasien['id']}' $selected>{$row_pasien['namapasien']}</option>";
}

// Ambil data koordinator dari tabel koordinator
$query_koordinator = "SELECT id, namakoor FROM koordinator";
$result_koordinator = mysqli_query($conn, $query_koordinator);
$koordinator_options = '';

while ($row_koordinator = mysqli_fetch_assoc($result_koordinator)) {
    $selected = ($row_koordinator['id'] == $id_koordinator) ? 'selected' : '';
    $koordinator_options .= "<option value='{$row_koordinator['id']}' $selected>{$row_koordinator['namakoor']}</option>";
}
?>


    <style>
        /* Container for the entire content */
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px; /* Padding yang lebih kecil */
        }

        /* Category form styling */
        .form-category {
            margin-bottom: 20px; /* Margin yang lebih kecil */
            padding: 15px; /* Padding yang lebih kecil */
            background-color: #f9f9f9; /* Warna latar belakang untuk kategori form */
            border-radius: 6px; /* Menambahkan border-radius yang lebih kecil */
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); /* Menambahkan shadow yang lebih ringan */
        }

        /* Heading styling for form category */
        .form-category h4 {
            margin-bottom: 15px; /* Margin yang lebih kecil */
            font-weight: 600;
            font-size: 1.125rem; /* Ukuran font yang lebih kecil */
            color: #333; /* Warna teks yang lebih gelap */
        }

        /* Form row styling for alignment */
        .form-row {
            display: flex;
            gap: 10px; /* Jarak antar elemen di dalam baris form */
            flex-wrap: wrap; /* Membolehkan elemen untuk membungkus ke baris berikutnya */
        }

        /* Form group styling for each input */
        .form-group {
            flex: 1;
            min-width: 180px; /* Lebar minimum untuk menjaga tampilan responsif */
        }

        /* Form control styling for inputs and textareas */
        .form-control {
            width: 100%;
            padding: 8px 10px; /* Padding yang lebih kecil untuk input */
            border: 1px solid #ddd; /* Border yang lebih ringan */
            border-radius: 4px; /* Sudut border yang lebih lembut */
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1); /* Shadow inset yang lebih ringan */
            font-size: 0.875rem; /* Ukuran font yang lebih kecil */
            color: #555; /* Warna teks yang lebih terang */
            transition: border-color 0.3s, box-shadow 0.3s; /* Transisi halus untuk interaksi */
        }

        /* Form control focus state */
        .form-control:focus {
            border-color: #007bff; /* Warna border saat fokus */
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25); /* Shadow untuk menonjolkan input saat fokus */
            outline: none; /* Menghilangkan outline default */
        }

        /* Label styling */
        .form-group label {
            display: block;
            margin-bottom: 4px; /* Jarak yang lebih kecil antara label dan input */
            font-weight: 500; /* Menambah ketebalan font label */
            color: #666; /* Warna teks label yang lebih terang */
            font-size: 0.875rem; /* Ukuran font yang lebih kecil */
        }

        /* Add hover effect for form controls */
        .form-control:hover {
            border-color: #0056b3; /* Warna border saat hover */
            background-color: #f1f1f1; /* Warna latar belakang saat hover */
        }

        .form-category {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-category h4 {
            margin-bottom: 15px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .modal-footer {
        display: flex;
        justify-content: center;
    }
    .modal-footer button {
        width: 700px; /* Atur ukuran lebar tombol sesuai keinginan Anda */
        margin: 10px;
        border-radius: 5px; /* Membuat sudut tombol menjadi bundar */
        font-size: 16px; /* Atur ukuran font tombol */
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
    <style>
.dropdown-container {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    background-color: #fff;
    z-index: 1000;
}

.dropdown-menu div {
    padding: 8px;
    cursor: pointer;
}

.dropdown-menu div:hover {
    background-color: #f1f1f1;
}
</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Data Pemeriksaan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="periksa.php" data-bs-toggle="tooltip" title="Ke Halaman Data Pemeriksaan">Pemeriksaan</a></li>
                        <li class="breadcrumb-item"><a href="javascript:history.back()" data-bs-toggle="tooltip" title="Ke Halaman Detail Pemeriksaan">Detail Pemeriksaan</a></li>
                        <li class="breadcrumb-item active">Edit Data Pemeriksaan</li>
                    </ol>
                    <form action="periksaeditfunction.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <div class="form-category">
                            <h4>Informasi Pasien</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="searchInput">Nama Pasien:</label>
                                    <div class="dropdown-container">
                                        <input type="text" id="searchInput" name="namapasien" class="form-control" 
                                            value="<?php echo htmlspecialchars($nama_pasien); ?>" autocomplete="off">
                                        <div id="dropdownMenu" class="dropdown-menu">
                                            <!-- Daftar pasien akan diisi oleh JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" id="patientId" name="namapasien" value="<?php echo htmlspecialchars($id_pasien); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tanggalperiksa">Tanggal Periksa:</label>
                                    <input type="date" name="tanggalperiksa" class="form-control" value="<?php echo $row['tanggalperiksa']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tanggalgejala">Tanggal Gejala:</label>
                                    <input type="date" name="tanggalgejala" class="form-control" value="<?php echo $row['tanggalgejala']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="suhutubuh">Suhu Tubuh:</label>
                                    <input type="text" name="suhutubuh" class="form-control" value="<?php echo $row['suhutubuh']; ?>">
                                </div>
                               
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mimisan">Mimisan:</label>
                                    <select name="mimisan" class="form-control">
                                        <option value="Ya" <?php echo ($row['mimisan'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['mimisan'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nyeriperut">Nyeri Perut:</label>
                                    <select name="nyeriperut" class="form-control">
                                        <option value="Ya" <?php echo ($row['nyeriperut'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['nyeriperut'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="perasaansembuh">Perasaan Sembuh:</label>
                                    <select name="perasaansembuh" class="form-control">
                                        <option value="Ya" <?php echo ($row['perasaansembuh'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['perasaansembuh'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="trombositturun">Trombosit Turun:</label>
                                    <select name="trombositturun" class="form-control">
                                        <option value="Ya" <?php echo ($row['trombositturun'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['trombositturun'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sakitkepala">Sakit Kepala:</label>
                                    <select name="sakitkepala" class="form-control">
                                        <option value="Ya" <?php echo ($row['sakitkepala'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['sakitkepala'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="muntah">Muntah:</label>
                                    <select name="muntah" class="form-control">
                                        <option value="Ya" <?php echo ($row['muntah'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['muntah'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nyerisendi">Nyeri Sendi:</label>
                                    <select name="nyerisendi" class="form-control">
                                        <option value="Ya" <?php echo ($row['nyerisendi'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['nyerisendi'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mual">Mual:</label>
                                    <select name="mual" class="form-control">
                                        <option value="Ya" <?php echo ($row['mual'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['mual'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="ptkie">PTKIE:</label>
                                    <select name="ptkie" class="form-control">
                                        <option value="Ya" <?php echo ($row['ptkie'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['ptkie'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="infeksitenggorok">Infeksi Tenggorok:</label>
                                    <select name="infeksitenggorok" class="form-control">
                                        <option value="Ya" <?php echo ($row['infeksitenggorok'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['infeksitenggorok'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sakitbolamata">Sakit Bola Mata:</label>
                                    <select name="sakitbolamata" class="form-control">
                                        <option value="Ya" <?php echo ($row['sakitbolamata'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                        <option value="Tidak" <?php echo ($row['sakitbolamata'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="lainnya">Lainnya:</label>
                                    <input type="text" name="lainnya" class="form-control" value="<?php echo $row['lainnya']; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Kategori Pemeriksaan Lab Darah -->
                        <div class="form-category">
                            <h4>Pemeriksaan Lab Darah</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="iggm">Nilai IGG/M:</label>
                                    <select name="iggm" class="form-control">
                                        <option value="Positif" <?php echo ($row['iggm'] == 'Positif') ? 'selected' : ''; ?>>Positif</option>
                                        <option value="Negatif" <?php echo ($row['iggm'] == 'Negatif') ? 'selected' : ''; ?>>Negatif</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="igm">Nilai IGM:</label>
                                    <select name="igm" class="form-control">
                                        <option value="Positif" <?php echo ($row['igm'] == 'Positif') ? 'selected' : ''; ?>>Positif</option>
                                        <option value="Negatif" <?php echo ($row['igm'] == 'Negatif') ? 'selected' : ''; ?>>Negatif</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ns1">Nilai NS-1:</label>
                                    <select name="ns1" class="form-control">
                                        <option value="Positif" <?php echo ($row['ns1'] == 'Positif') ? 'selected' : ''; ?>>Positif</option>
                                        <option value="Negatif" <?php echo ($row['ns1'] == 'Negatif') ? 'selected' : ''; ?>>Negatif</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tombosit">Nilai Trombosit:</label>
                                    <input type="text" name="tombosit" class="form-control" value="<?php echo $row['tombosit']; ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hematokrit">Nilai Hematokrit:</label>
                                    <input type="text" name="hematokrit" class="form-control" value="<?php echo $row['hematokrit']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="hb">Nilai Hemoglobin:</label>
                                    <input type="text" name="hb" class="form-control" value="<?php echo $row['hb']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="leukosit">Nilai Leukosit:</label>
                                    <input type="text" name="leukosit" class="form-control" value="<?php echo $row['leukosit']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="eritrosit">Nilai Eritrosit:</label>
                                    <input type="text" name="eritrosit" class="form-control" value="<?php echo $row['eritrosit']; ?>">
                                </div>
                            </div>
                        </div>

                         <!-- Kategori Hasil Pemeriksaan Perawatan Sebelumnya -->
                         <div class="form-category">
                            <h4>Hasil Pemeriksaan Perawatan Sebelumnya</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="diagnosislab">Diagnosis Laboratorium:</label>
                                    <input type="text" name="diagnosislab" class="form-control" value="<?php echo $row['diagnosislab']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="diagnosisklinis">Diagnosis Klinis:</label>
                                    <input type="text" name="diagnosisklinis" class="form-control" value="<?php echo $row['diagnosisklinis']; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Kategori Hasil Pemeriksaan Perawatan Sebelumnya -->
                        <div class="form-category">
                            <h4>Hasil Pemeriksaan Perawatan Sebelumnya</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="pernahranap">Pernah Dirawat:</label>
                                    <select name="pernahranap" class="form-control">
                                        <option value="Pernah" <?php echo ($row['pernahranap'] == 'Pernah') ? 'selected' : ''; ?>>Pernah</option>
                                        <option value="Tidak Pernah" <?php echo ($row['pernahranap'] == 'Tidak Pernah') ? 'selected' : ''; ?>>Tidak Pernah</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="namars">Nama RS:</label>
                                    <input type="text" name="namars" class="form-control" value="<?php echo htmlspecialchars($row['namars']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tanggalmasuk">Tanggal Rawat Inap:</label>
                                    <input type="date" name="tanggalmasuk" class="form-control" value="<?php echo htmlspecialchars($row['tanggalmasuk']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ruangrawat">Ruang Rawat:</label>
                                    <input type="text" name="ruangrawat" class="form-control" value="<?php echo htmlspecialchars($row['ruangrawat']); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="namarssebelum">Nama RS Sebelum:</label>
                                    <input type="text" name="namarssebelum" class="form-control" value="<?php echo htmlspecialchars($row['namarssebelum']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="statuspasienakhir">Status Pasien Keluar:</label>
                                    <input type="text" name="statuspasienakhir" class="form-control" value="<?php echo htmlspecialchars($row['statuspasienakhir']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="periksajentik">Periksa Jentik:</label>
                                    <input type="text" name="periksajentik" class="form-control" value="<?php echo htmlspecialchars($row['periksajentik']); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="searchInputKoor">Penanggung Jawab Pemeriksaan:</label>
                                    <div class="dropdown-container">
                                        <input type="text" id="searchInputKoor" name="pjpemeriksa" class="form-control" 
                                            value="<?php echo htmlspecialchars($row['namakoor']); ?>" autocomplete="off">
                                        <div id="dropdownMenuKoor" class="dropdown-menu">
                                            <!-- Daftar koordinator akan diisi oleh JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" id="coordinatorId" name="pjpemeriksa" value="<?php echo htmlspecialchars($id_koordinator); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tgl_keluar_perawatan">Tanggal Keluar Perawatan:</label>
                                    <input type="date" name="tgl_keluar_perawatan" class="form-control" 
                                        value="<?php echo htmlspecialchars($row['tgl_keluar_perawatan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="upload_file_kdrs">Upload File KDRS:</label>
                                    <input type="file" name="upload_file_kdrs" class="form-control" 
                                        accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.jpg,.jpeg,.png,.gif">
                                    <small class="form-text text-muted">Jika tidak ingin mengubah, biarkan kosong.</small>
                                </div>
                            </div>
                        </div>
                        <!-- Tombol Submit -->
                        <div class="modal-footer"><button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Simpan Perubahan Data">Simpan</button></div>
                    </form>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('searchInput');
        var dropdownMenu = document.getElementById('dropdownMenu');
        var patientIdInput = document.getElementById('patientId');

        function loadPatients() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_patients.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var patients = JSON.parse(this.responseText);
                    var html = '';
                    patients.forEach(function(patient) {
                        html += '<div data-id="' + patient.id + '">' + patient.namapasien + '</div>';
                    });
                    dropdownMenu.innerHTML = html;
                } else {
                    console.error('Gagal memuat pasien:', this.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Request error...');
            };
            xhr.send();
        }

        loadPatients();

        searchInput.addEventListener('input', function() {
            var filter = this.value.toUpperCase();
            var items = dropdownMenu.querySelectorAll('div[data-id]');
            var visibleItems = false;

            items.forEach(function(item) {
                var text = item.textContent || item.innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    item.style.display = '';
                    visibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });

            dropdownMenu.style.display = visibleItems ? 'block' : 'none';
        });

        dropdownMenu.addEventListener('click', function(event) {
            var target = event.target;
            if (target.dataset.id) {
                searchInput.value = target.textContent;
                patientIdInput.value = target.dataset.id;
                dropdownMenu.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            if (!dropdownMenu.contains(event.target) && event.target !== searchInput) {
                dropdownMenu.style.display = 'none';
            }
        });

        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                var selectedItem = dropdownMenu.querySelector('div:not([style*="display: none"])');
                if (selectedItem) {
                    searchInput.value = selectedItem.textContent;
                    patientIdInput.value = selectedItem.dataset.id;
                    dropdownMenu.style.display = 'none';
                }
                event.preventDefault();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var searchInputKoor = document.getElementById('searchInputKoor');
        var dropdownMenuKoor = document.getElementById('dropdownMenuKoor');
        var coordinatorIdInput = document.getElementById('coordinatorId');

        function loadCoordinators() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_koordinators.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var coordinators = JSON.parse(this.responseText);
                    var html = '';
                    coordinators.forEach(function(coordinator) {
                        html += '<div data-id="' + coordinator.id + '">' + coordinator.namakoor + '</div>';
                    });
                    dropdownMenuKoor.innerHTML = html;
                } else {
                    console.error('Gagal memuat koordinator:', this.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Request error...');
            };
            xhr.send();
        }

        loadCoordinators();

        searchInputKoor.addEventListener('input', function() {
            var filter = this.value.toUpperCase();
            var items = dropdownMenuKoor.querySelectorAll('div[data-id]');
            var visibleItems = false;

            items.forEach(function(item) {
                var text = item.textContent || item.innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    item.style.display = '';
                    visibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });

            dropdownMenuKoor.style.display = visibleItems ? 'block' : 'none';
        });

        dropdownMenuKoor.addEventListener('click', function(event) {
            var target = event.target;
            if (target.dataset.id) {
                searchInputKoor.value = target.textContent;
                coordinatorIdInput.value = target.dataset.id;
                dropdownMenuKoor.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            if (!dropdownMenuKoor.contains(event.target) && event.target !== searchInputKoor) {
                dropdownMenuKoor.style.display = 'none';
            }
        });

        searchInputKoor.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                var selectedItem = dropdownMenuKoor.querySelector('div:not([style*="display: none"])');
                if (selectedItem) {
                    searchInputKoor.value = selectedItem.textContent;
                    coordinatorIdInput.value = selectedItem.dataset.id;
                    dropdownMenuKoor.style.display = 'none';
                }
                event.preventDefault();
            }
        });
    });
</script>
</html>
