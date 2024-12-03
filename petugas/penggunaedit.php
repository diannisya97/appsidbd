<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

$idpengguna = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpengguna) || !is_numeric($idpengguna)) {
    echo "<script>alert('ID Pengguna tidak valid!'); window.location.href='pengguna.php';</script>";
    exit();
}

$idpengguna = intval($idpengguna);

$query = "SELECT * FROM user WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $idpengguna);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data pengguna tidak ditemukan!'); window.location.href='pengguna.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

if (isset($_POST['updatepengguna'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];
    $nama = $_POST['nama'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $idpuskesmas = $_POST['asalpkm'];

    if (!empty($password)) {
        $updateQuery = "UPDATE user SET username = ?, password = ?, level = ?, nama = ?, contact = ?, email = ?, idpuskesmas = ? WHERE id = ?";
        if ($stmt = $conn->prepare($updateQuery)) {
            $stmt->bind_param("ssssssii", $username, $password, $level, $nama, $contact, $email, $idpuskesmas, $idpengguna);
        } else {
            echo "ERROR: Could not prepare query: " . $conn->error;
            exit();
        }
    } else {
        $updateQuery = "UPDATE user SET username = ?, level = ?, nama = ?, contact = ?, email = ?, idpuskesmas = ? WHERE id = ?";
        if ($stmt = $conn->prepare($updateQuery)) {
            $stmt->bind_param("ssssssi", $username, $level, $nama, $contact, $email, $idpuskesmas, $idpengguna);
        } else {
            echo "ERROR: Could not prepare query: " . $conn->error;
            exit();
        }
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='pengguna.php';</script>";
    } else {
        echo "DATA GAGAL DIPERBARUI! Error: " . $stmt->error;
    }
    $stmt->close();
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
    .form-group {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    .form-group label {
        width: 100%;
    }
    .form-group .col {
        flex: 1;
        min-width: 200px;
        padding: 0 10px;
    }
</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Data Pengguna</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="pengguna.php">Pengguna</a></li>
                        <li class="breadcrumb-item active">Edit Data Pengguna</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="post">
                                <div class="form-group">
                                    <div class="col">
                                        <label for="nama" class="form-label">Nama Pengguna</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="contact" class="form-label">Nomor Kontak</label>
                                        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($data['contact']); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="asalpkm" class="form-label">Asal Fasyankes</label>
                                        <select class="form-select" id="asalpkm" name="asalpkm" required>
                                                <?php echo $puskesmasOptions; ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="password" class="form-label">Password (Isi jika ingin diubah)</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col">
                                        <label for="level" class="form-label">Level</label>
                                        <select id="level" name="level" class="form-control" required>
                                            <option value="Petugas" <?php echo ($data['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer"><button type="submit" class="btn btn-primary" name="updatepengguna">Simpan Perubahan</button></div>
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
