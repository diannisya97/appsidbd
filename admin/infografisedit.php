<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id from URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id) || !is_numeric($id)) {
    echo "<script>alert('ID Infografis tidak valid!'); window.location.href='infografis.php';</script>";
    exit();
}

$id = intval($id); // Ensure the id is an integer

// Fetch data for the given id
$query = "SELECT * FROM infografis WHERE id = ?";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data infografis tidak ditemukan!'); window.location.href='infografis.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

// Handle form submission
if (isset($_POST['updateinfografis'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    
    // Handle file uploads
    $foto1 = $_FILES['foto1']['name'];
    $uploadDir = '../uploads/';
    
    // Check if a new file was uploaded
    if (!empty($foto1)) {
        $foto1Path = $uploadDir . basename($foto1);
        move_uploaded_file($_FILES['foto1']['tmp_name'], $foto1Path);
        // Remove old photo if a new one is uploaded
        if ($data['foto1'] && file_exists($uploadDir . $data['foto1'])) {
            unlink($uploadDir . $data['foto1']);
        }
    } else {
        // Check if the user opted to remove the file
        $foto1 = isset($_POST['remove_foto1']) ? '' : $data['foto1'];
        if (isset($_POST['remove_foto1']) && $data['foto1']) {
            unlink($uploadDir . $data['foto1']);
        }
    }
    
    // Update query
    $updateQuery = "UPDATE infografis SET judul = ?, deskripsi = ?, foto1 = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("sssi", $judul, $deskripsi, $foto1, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href='infografis.php';</script>";
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

<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Data Infografis</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="infografis.php">Data Infografis</a></li>
                    <li class="breadcrumb-item active">Edit Data Infografis</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                            <tbody>
                            <tr>
    <th><label for="judul" class="form-label">Judul</label></th>
    <td><input type="text" id="judul" name="judul" class="form-control" value="<?php echo htmlspecialchars($data['judul']); ?>" required></td>
</tr>
<tr>
    <th><label for="deskripsi" class="form-label">Deskripsi</label></th>
    <td><textarea id="deskripsi" name="deskripsi" class="form-control" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea></td>
</tr>
<tr>
    <th><label for="foto1" class="form-label">Foto 1</label></th>
    <td>
        <input type="file" id="foto1" name="foto1" class="form-control">
        <?php if ($data['foto1']) { ?>
            <img src="../uploads/<?php echo htmlspecialchars($data['foto1']); ?>" width="100" class="mt-2">
            <input type="checkbox" id="remove_foto1" name="remove_foto1">
            <label for="remove_foto1">Hapus Foto 1</label>
        <?php } ?>
    </td>
</tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="updateinfografis">Simpan Perubahan</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
