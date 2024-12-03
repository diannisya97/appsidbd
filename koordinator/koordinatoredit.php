<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id from URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id) || !is_numeric($id)) {
    echo "<script>alert('ID Koordinator tidak valid!'); window.location.href='koordinator.php';</script>";
    exit();
}

$id = intval($id); // Ensure the id is an integer

// Fetch data for the given id along with puskesmas name using JOIN
$query = "
    SELECT k.*, p.Nama_Puskesmas
    FROM koordinator k
    INNER JOIN puskesmas p ON k.asalfasyankes = p.idpuskesmas
    WHERE k.id = ?
";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data koordinator tidak ditemukan!'); window.location.href='koordinator.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

// Handle form submission
if (isset($_POST['updatekoordinator'])) {
    $namakoor = $_POST['namakoor'];
    $alamat = $_POST['alamat'];
    $nokontak = $_POST['nokontak'];
    $asalfasyankes = $_POST['asalfasyankes'];

    $updateQuery = "UPDATE koordinator SET namakoor = ?, alamat = ?, nokontak = ?, asalfasyankes = ? WHERE id = ?";
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("ssssi", $namakoor, $alamat, $nokontak, $asalfasyankes, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href='koordinator.php';</script>";
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
<?php
// Ambil ID Puskesmas untuk pengguna yang login dari sesi
$idpuskesmasLogin = $_SESSION['idpuskesmas'];

// Query untuk mengambil puskesmas sesuai dengan ID puskesmas login
$query = "SELECT * FROM puskesmas WHERE idpuskesmas = '$idpuskesmasLogin'";
$result = mysqli_query($conn, $query);

// Membuat opsi untuk dropdown
$puskesmasOptions = '';
while ($row = mysqli_fetch_assoc($result)) {
    $namafasyankes = htmlspecialchars($row['Nama_Puskesmas']);
    $idpuskesmas = htmlspecialchars($row['idpuskesmas']);
    $puskesmasOptions .= "<option value=\"$idpuskesmas\" " . ($idpuskesmas == $data['asalfasyankes'] ? 'selected' : '') . ">$namafasyankes</option>";
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
                <h1 class="mt-4">Edit Data Koordinator</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Ke Halaman Dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="koordinator.php" data-bs-toggle="tooltip" title="Ke Halaman Data Koordinator">Data Koordinator</a></li>
                        <li class="breadcrumb-item active">Edit Data Koordinator</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="namakoor" class="form-label">Nama Koordinator</label>
                                    <input type="text" class="form-control" id="namakoor" name="namakoor" value="<?php echo htmlspecialchars($data['namakoor']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nokontak" class="form-label">Nomor Kontak</label>
                                    <input type="text" class="form-control" id="nokontak" name="nokontak" value="<?php echo htmlspecialchars($data['nokontak']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="asalfasyankes" class="form-label">Asal Fasyankes</label>
                                    <select class="form-select" id="asalfasyankes" name="asalfasyankes" required>
                                                <?php echo $puskesmasOptions; ?>
                                    </select>
                                </div>
                                <div class="modal-footer"><button type="submit" class="btn btn-primary" name="updatekoordinator" data-bs-toggle="tooltip" title="Simpan Perubahan Data">Simpan Perubahan</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
