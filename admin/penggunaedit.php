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
        echo "<script>
    alert('Data berhasil diperbarui!');
    window.location.href = 'penggunalihat.php?id=$idpengguna';
</script>";
    } else {
        echo "DATA GAGAL DIPERBARUI! Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
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
    .breadcrumb {
    background-color: #f8f9fa; /* Warna latar belakang terang */
    border: 1px solid #dee2e6; /* Border abu-abu terang */
    border-radius: 0.25rem; /* Membuat sudut membulat */
    padding: 8px 15px; /* Padding dalam breadcrumb */
    margin-bottom: 15px; /* Jarak bawah breadcrumb */
}

.breadcrumb-item a {
    color: #4e73df; /* Warna biru utama */
    text-decoration: none; /* Hilangkan garis bawah */
    font-weight: 500; /* Menambah ketebalan teks */
}

.breadcrumb-item a:hover {
    text-decoration: underline; /* Tambahkan garis bawah saat hover */
    color: #2e59d9; /* Warna biru lebih gelap saat hover */
}

.breadcrumb-item.active {
    color: #6c757d; /* Warna abu-abu untuk item aktif */
    font-weight: bold; /* Teks tebal untuk item aktif */
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const formControls = document.querySelectorAll('.form-control');
        
        formControls.forEach(function(control) {
            control.addEventListener('focus', function() {
                this.style.backgroundColor = '#e9f5ff';
            });

            control.addEventListener('blur', function() {
                this.style.backgroundColor = 'white';
            });
        });
    });
</script>

<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Data Pengguna</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Dashboard"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="pengguna.php" data-bs-toggle="tooltip" title="Ke Halaman Data Pengguna">Data Pengguna</a></li>
                    <li class="breadcrumb-item"><a href="penggunalihat.php?id=<?php echo $idpengguna; ?>" data-bs-toggle="tooltip" title="Ke Halaman Detail Data">Detail Pengguna</a></li>
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
                                    <select id="asalpkm" name="asalpkm" class="form-control" required>
                                        <?php
                                        $puskesmasQuery = "SELECT idpuskesmas, Nama_Puskesmas FROM puskesmas";
                                        $puskesmasResult = $conn->query($puskesmasQuery);

                                        while ($row = $puskesmasResult->fetch_assoc()) {
                                            $selected = ($data['idpuskesmas'] == $row['idpuskesmas']) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($row['idpuskesmas']) . "' $selected>" . htmlspecialchars($row['Nama_Puskesmas']) . "</option>";
                                        }
                                        ?>
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
                                        <option value="admin" <?php echo ($data['level'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="petugas" <?php echo ($data['level'] == 'Petugas') ? 'selected' : ''; ?>>Petugas</option>
                                        <option value="koordinator" <?php echo ($data['level'] == 'Koordinator') ? 'selected' : ''; ?>>Koordinator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="updatepengguna" data-bs-toggle="tooltip" title="Simpan Perubahan">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
