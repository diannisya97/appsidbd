<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Ambil id dari URL dan validasi
$idjentik = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idjentik) {
    echo "<script>alert('ID Jentik tidak valid!'); window.location.href='jentik.php';</script>";
    exit();
}

// Ambil data untuk ID jentik yang diberikan
$query = "SELECT * FROM jentikperiksa WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idjentik);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "<script>alert('Data Jentik tidak ditemukan!'); window.location.href='jentik.php';</script>";
        exit();
    }
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ERROR: Tidak bisa mempersiapkan query: " . $conn->error;
    exit();
}

// Ambil data koordinator dari tabel koordinator
$query_koordinator = "SELECT id, namakoor FROM koordinator";
$koordinators = $conn->query($query_koordinator);

// Tangani pengajuan formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatejentik'])) {
    $Nama_Koordinator = $_POST['idkoordinator'];
    $Nama_Pemilik_Rumah = $_POST['namapasien'];
    $Tanggal_Pemeriksaan = $_POST['Tanggal_Pemeriksaan'];
    $Bak_Mandi = $_POST['Bak_Mandi'];
    $Dispenser = $_POST['Dispenser'];
    $Penampungan_Hujan = $_POST['Penampungan_Hujan'];
    $Pot_Bunga = $_POST['Pot_Bunga'];
    $Tempat_Minum_Hewan = $_POST['Tempat_Minum_Hewan'];
    $Ban_Bekas = $_POST['Ban_Bekas'];
    $Sampah = $_POST['Sampah'];
    $Pohon = $_POST['Pohon'];
    $Lainnya = $_POST['Lainnya'];

    // Ambil id dari tabel pasien berdasarkan nama pasien yang diberikan
    $query_pasien = "SELECT id FROM pasien WHERE namapasien = ?";
    if ($stmt_pasien = $conn->prepare($query_pasien)) {
        $stmt_pasien->bind_param("s", $Nama_Pemilik_Rumah);
        $stmt_pasien->execute();
        $result_pasien = $stmt_pasien->get_result();
        if ($result_pasien->num_rows > 0) {
            $row_pasien = $result_pasien->fetch_assoc();
            $Nama_Pemilik_Rumah_ID = $row_pasien['id']; // Ambil ID dari pasien
        } else {
            echo "<script>alert('Nama pasien tidak ditemukan!'); window.location.href='jentikedit.php?id={$idjentik}';</script>";
            exit();
        }
        $stmt_pasien->close();
    } else {
        echo "ERROR: Tidak bisa mempersiapkan query: " . $conn->error;
        exit();
    }

    // Query untuk memperbarui data jentik
    $updateQuery = "
        UPDATE jentikperiksa 
        SET namakoor = ?, namapemilik = ?, tanggalrekap = ?, bakmandi = ?, dispenser = ?, penampung = ?, potbunga = ?, tempatminumhewan = ?, banbekas = ?, sampah = ?, pohon = ?, lainnya = ? 
        WHERE id = ?
    ";

    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param(
            'ssssssssssssi',
            $Nama_Koordinator, $Nama_Pemilik_Rumah_ID, $Tanggal_Pemeriksaan, $Bak_Mandi, $Dispenser, $Penampungan_Hujan, $Pot_Bunga, $Tempat_Minum_Hewan, $Ban_Bekas, $Sampah, $Pohon, $Lainnya, $idjentik
        );
        if ($stmt->execute()) {
            echo "<script>alert('Data jentik berhasil diperbarui!'); window.location.href='jentiklihat.php?id={$idjentik}';</script>";
        } else {
            echo "DATA GAGAL DIPERBARUI! Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "ERROR: Tidak bisa mempersiapkan query: " . $conn->error;
    }
    $conn->close();
}
?>

<?php
// Misalkan $data['namakoor'] dan $data['namapemilik'] adalah ID dari database

// Ambil nama koordinator berdasarkan ID
$query_koordinator = "SELECT namakoor FROM koordinator WHERE id = ?";
if ($stmt_koor = $conn->prepare($query_koordinator)) {
    $stmt_koor->bind_param("i", $data['namakoor']);
    $stmt_koor->execute();
    $result_koor = $stmt_koor->get_result();
    if ($row_koor = $result_koor->fetch_assoc()) {
        $nama_koordinator = $row_koor['namakoor'];
    }
    $stmt_koor->close();
}

// Ambil nama pasien berdasarkan ID
$query_pasien = "SELECT namapasien FROM pasien WHERE id = ?";
if ($stmt_pasien = $conn->prepare($query_pasien)) {
    $stmt_pasien->bind_param("i", $data['namapemilik']);
    $stmt_pasien->execute();
    $result_pasien = $stmt_pasien->get_result();
    if ($row_pasien = $result_pasien->fetch_assoc()) {
        $nama_pasien = $row_pasien['namapasien'];
    }
    $stmt_pasien->close();
}
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
                <h1 class="mt-4">Edit Data Pemeriksaan Jentik</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="jentik.php" data-bs-toggle="tooltip" title="Ke Halaman Data pemeriksaan Jentik">Data Pemeriksaan Jentik</a></li>
                    <li class="breadcrumb-item"><a  href="jentiklihat.php?id=<?php echo $idjentik; ?>" data-bs-toggle="tooltip" title="Ke Halaman Detail Data">Detail Pemeriksaan Jentik</a></li>
                    <li class="breadcrumb-item active">Edit Data Pemeriksaan Jentik</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="post">
                            <h5>Data Penduduk</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="searchKoor">Nama Koordinator</label>
                                    <div class="dropdown-container position-relative">
                                        <input type="text" id="searchKoor" name="namakoor" class="form-control" placeholder="Cari Koordinator..." autocomplete="off" value="<?php echo htmlspecialchars($nama_koordinator); ?>">
                                        <div id="dropdownKoorMenu" class="dropdown-menu w-100" style="display: none;">
                                            <!-- Daftar koordinator akan diisi oleh JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" id="koordinatorId" name="idkoordinator" value="<?php echo htmlspecialchars($data['namakoor']); ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="searchInput">Nama Pemilik Rumah</label>
                                    <div class="dropdown-container position-relative">
                                        <input type="text" id="searchInput" name="namapasien" class="form-control" placeholder="Cari Pasien..." autocomplete="off" value="<?php echo htmlspecialchars($nama_pasien); ?>">
                                        <div id="dropdownMenu" class="dropdown-menu w-100" style="display: none;">
                                            <!-- Daftar pasien akan diisi oleh JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" id="patientId" name="id" value="<?php echo htmlspecialchars($data['namapemilik']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="Tanggal_Pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                                <input type="date" class="form-control" id="Tanggal_Pemeriksaan" name="Tanggal_Pemeriksaan" value="<?php echo $data['tanggalrekap']; ?>" required>
                            </div>
                        </div>

                        <h5>Pemeriksaan Jentik</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Bak_Mandi" class="form-label">Bak Mandi</label>
                                <select class="form-control" id="Bak_Mandi" name="Bak_Mandi" required>
                                    <option value="ada" <?php if ($data['bakmandi'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['bakmandi'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Dispenser" class="form-label">Dispenser</label>
                                <select class="form-control" id="Dispenser" name="Dispenser" required>
                                    <option value="ada" <?php if ($data['dispenser'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['dispenser'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Penampungan_Hujan" class="form-label">Penampungan Hujan</label>
                                <select class="form-control" id="Penampungan_Hujan" name="Penampungan_Hujan" required>
                                    <option value="ada" <?php if ($data['penampung'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['penampung'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Pot_Bunga" class="form-label">Pot Bunga</label>
                                <select class="form-control" id="Pot_Bunga" name="Pot_Bunga" required>
                                    <option value="ada" <?php if ($data['potbunga'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['potbunga'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Tempat_Minum_Hewan" class="form-label">Tempat Minum Hewan</label>
                                <select class="form-control" id="Tempat_Minum_Hewan" name="Tempat_Minum_Hewan" required>
                                    <option value="ada" <?php if ($data['tempatminumhewan'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['tempatminumhewan'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Ban_Bekas" class="form-label">Ban Bekas</label>
                                <select class="form-control" id="Ban_Bekas" name="Ban_Bekas" required>
                                    <option value="ada" <?php if ($data['banbekas'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['banbekas'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Sampah" class="form-label">Sampah</label>
                                <select class="form-control" id="Sampah" name="Sampah" required>
                                    <option value="ada" <?php if ($data['sampah'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['sampah'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Pohon" class="form-label">Pohon</label>
                                <select class="form-control" id="Pohon" name="Pohon" required>
                                    <option value="ada" <?php if ($data['pohon'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['pohon'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Lainnya" class="form-label">Lainnya</label>
                                <select class="form-control" id="Lainnya" name="Lainnya" required>
                                    <option value="ada" <?php if ($data['lainnya'] == 'ada') echo 'selected'; ?>>Ada</option>
                                    <option value="tidak_ada" <?php if ($data['lainnya'] == 'tidak_ada') echo 'selected'; ?>>Tidak Ada</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="updatejentik" data-bs-toggle="tooltip" title="Simpan Perubahan Data">Simpan Perubahan</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </main>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Koordinator
    var searchKoor = document.getElementById('searchKoor');
    var dropdownKoorMenu = document.getElementById('dropdownKoorMenu');
    var koordinatorIdInput = document.getElementById('koordinatorId');

    function loadKoordinators() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_koordinators.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                var koordinators = JSON.parse(this.responseText);
                var html = '';
                koordinators.forEach(function(koordinator) {
                    html += '<div class="dropdown-item" data-id="' + koordinator.id + '">' + koordinator.namakoor + '</div>';
                });
                dropdownKoorMenu.innerHTML = html;
            }
        };
        xhr.send();
    }

    loadKoordinators();

    searchKoor.addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var items = dropdownKoorMenu.querySelectorAll('div[data-id]');
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

        dropdownKoorMenu.style.display = visibleItems ? 'block' : 'none';
    });

    dropdownKoorMenu.addEventListener('click', function(event) {
        var target = event.target;
        if (target.dataset.id) {
            searchKoor.value = target.textContent;
            koordinatorIdInput.value = target.dataset.id; // Set hidden ID value
            dropdownKoorMenu.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdownKoorMenu.contains(event.target) && event.target !== searchKoor) {
            dropdownKoorMenu.style.display = 'none';
        }
    });

    // Pasien
    var searchInput = document.getElementById('searchInput');
    var dropdownMenu = document.getElementById('dropdownMenu');
    var patientIdInput = document.getElementById('patientId');

    function loadPatients() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_patient.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                var patients = JSON.parse(this.responseText);
                var html = '';
                patients.forEach(function(patient) {
                    html += '<div class="dropdown-item" data-id="' + patient.id + '">' + patient.namapasien + '</div>';
                });
                dropdownMenu.innerHTML = html;
            }
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
            patientIdInput.value = target.dataset.id; // Set hidden ID value
            dropdownMenu.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdownMenu.contains(event.target) && event.target !== searchInput) {
            dropdownMenu.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
