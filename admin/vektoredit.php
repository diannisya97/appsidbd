<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id from URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id) || !is_numeric($id)) {
    echo "<script>alert('ID Pengendalian Vektor tidak valid!'); window.location.href='vektor.php';</script>";
    exit();
}

$id = intval($id); // Ensure the id is an integer

// Fetch data for the given id
$query = "SELECT * FROM pengendalianvektor WHERE idpengendalian = ?";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data pengendalian vektor tidak ditemukan!'); window.location.href='vektor.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}
// Handle form submission
if (isset($_POST['updatevektor'])) {
    // Debug: Print POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    $penyuluhan = $_POST['penyuluhan'];
    $psn3m = $_POST['psn3m'];
    $larvasidasi = $_POST['larvasidasi'];
    $fogging = $_POST['fogging'];

    $updateQuery = "UPDATE pengendalianvektor SET penyuluhan = ?, psn3m = ?, larvasidasi = ?, fogging = ? WHERE idpengendalian = ?";
    
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("ssssi", $penyuluhan, $psn3m, $larvasidasi, $fogging, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href='vektor.php';</script>";
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
/* Styling untuk tampilan desktop */
.form-label {
    margin-left: 220px;
    font-size: 16px;
}

.radio-group {
    margin-right: 100px;
}

.radio-option {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.radio-option .radio-pernah {
    margin-right: 30px;
}

.radio-option label {
    margin-left: 5px;
}

/* Styling tambahan untuk tampilan mobile */
@media (max-width: 768px) {
    .form-label {
        margin-left: 0;
        font-size: 14px;
        text-align: left;
        margin-bottom: 5px;
        padding: 0 10px;
    }

    .radio-group {
        margin-right: 0;
        padding: 0 10px;
    }

    .radio-option {
        flex-direction: row;
        align-items: center;
        justify-content: space-between; /* Menjaga jarak antara "Pernah" dan "Tidak Pernah" */
        margin-bottom: 10px;
        width: 100%;
    }

    .radio-option .radio-pernah {
        margin-right: 0;
        margin-bottom: 0;
    }

    .radio-option label {
        margin-left: 5px;
        font-size: 14px;
    }

    .radio-option input[type="radio"] {
        transform: scale(1.2);
        margin-right: 5px;
    }
    
    body {
        font-size: 14px;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 15px;
    }
}

</style>
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
                <h1 class="mt-4">Edit Data Pengendalian Vektor</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Ke Halaman Dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="vektor.php" data-bs-toggle="tooltip" title="Ke Halaman Data Pengendalian Vektor">Pengendalian Vektor</a></li>
                    <li class="breadcrumb-item active">Edit Data Pengendalian Vektor</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                    <form method="post">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><label for="penyuluhan" class="form-label">Penyuluhan</label></td>
                                    <td>
                                        <div class="radio-group">
                                            <div class="radio-option">
                                                <input type="radio" id="penyuluhan_pernah" name="penyuluhan" value="Pernah" <?php echo ($data['penyuluhan'] == 'Pernah') ? 'checked' : ''; ?>>
                                                <label for="penyuluhan_pernah" class="radio-pernah">Pernah</label>
                                                <input type="radio" id="penyuluhan_tidak_pernah" name="penyuluhan" value="Tidak Pernah" <?php echo ($data['penyuluhan'] == 'Tidak Pernah') ? 'checked' : ''; ?>>
                                                <label for="penyuluhan_tidak_pernah">Tidak Pernah</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="psn3m" class="form-label">PSN 3M</label></td>
                                    <td>
                                        <div class="radio-group">
                                            <div class="radio-option">
                                                <input type="radio" id="psn3m_pernah" name="psn3m" value="Pernah" <?php echo ($data['psn3m'] == 'Pernah') ? 'checked' : ''; ?>>
                                                <label for="psn3m_pernah" class="radio-pernah">Pernah</label>
                                                <input type="radio" id="psn3m_tidak_pernah" name="psn3m" value="Tidak Pernah" <?php echo ($data['psn3m'] == 'Tidak Pernah') ? 'checked' : ''; ?>>
                                                <label for="psn3m_tidak_pernah">Tidak Pernah</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="larvasidasi" class="form-label">Larvasidasi</label></td>
                                    <td>
                                        <div class="radio-group">
                                            <div class="radio-option">
                                                <input type="radio" id="larvasidasi_pernah" name="larvasidasi" value="Pernah" <?php echo ($data['larvasidasi'] == 'Pernah') ? 'checked' : ''; ?>>
                                                <label for="larvasidasi_pernah" class="radio-pernah">Pernah</label>
                                                <input type="radio" id="larvasidasi_tidak_pernah" name="larvasidasi" value="Tidak Pernah" <?php echo ($data['larvasidasi'] == 'Tidak Pernah') ? 'checked' : ''; ?>>
                                                <label for="larvasidasi_tidak_pernah">Tidak Pernah</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="fogging" class="form-label">Fogging</label></td>
                                    <td>
                                        <div class="radio-group">
                                            <div class="radio-option">
                                                <input type="radio" id="fogging_pernah" name="fogging" value="Pernah" <?php echo ($data['fogging'] == 'Pernah') ? 'checked' : ''; ?>>
                                                <label for="fogging_pernah" class="radio-pernah">Pernah</label>
                                                <input type="radio" id="fogging_tidak_pernah" name="fogging" value="Tidak Pernah" <?php echo ($data['fogging'] == 'Tidak Pernah') ? 'checked' : ''; ?>>
                                                <label for="fogging_tidak_pernah">Tidak Pernah</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="updatevektor" data-bs-toggle="tooltip" title="Simpan Perubahan">Simpan Perubahan</button>
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
