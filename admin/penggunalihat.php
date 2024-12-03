<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Get id from URL
$idpengguna = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpengguna) || !is_numeric($idpengguna)) {
    echo "<script>alert('ID Pengguna tidak valid!'); window.location.href='pengguna.php';</script>";
    exit();
}

$idpengguna = intval($idpengguna); // Ensure the id is an integer

// Fetch data for the given id
$query = "SELECT u.*, p.Nama_Puskesmas FROM user u LEFT JOIN puskesmas p ON u.idpuskesmas = p.idpuskesmas WHERE u.id = ?";
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
?>
<style>
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
    .btn-success {
        background-color: #4e73df; /* Warna latar belakang tombol */
        border-color: #4e73df;     /* Warna border tombol */
        color: #fff;               /* Warna teks tombol */
        padding: 0.5rem 1rem;      /* Padding tombol */
        border-radius: 0.25rem;    /* Sudut melengkung tombol */
        font-size: 0.875rem;       /* Ukuran font */
    }
    .btn-success:hover {
        background-color: #2e59d9; /* Warna latar belakang saat hover */
        border-color: #2653d4;     /* Warna border saat hover */
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
                    <br>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Dashboard"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="pengguna.php" data-bs-toggle="tooltip" title="Ke Halaman Data Pengguna">Data Pengguna</a></li>
                        <li class="breadcrumb-item active">Detail Pengguna</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                        <a href="#" class="btn btn-secondary" onclick="handleBack(); return false;"data-bs-toggle="tooltip" title="Ke Halaman Sebelumnya"><i class="fas fa-arrow-left" ></i> Kembali</a>
                            <div class="header-title">Detail Pengguna</div>
                        <a href="penggunaedit.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-success"data-bs-toggle="tooltip" title="Edit Data"><i class="fas fa-edit" style="color: #fff;" ></i> Edit Data</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Username</th>
                                    <td><?php echo htmlspecialchars($data['username']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td><?php echo htmlspecialchars($data['contact']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($data['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <td><?php echo htmlspecialchars($data['password']); ?></td>
                                </tr>
                                <tr>
                                    <th>Level</th>
                                    <td><?php echo htmlspecialchars($data['level']); ?></td>
                                </tr>
                                <tr>
                                    <th>Asal Fasyankes</th>
                                    <td><?php echo htmlspecialchars($data['Nama_Puskesmas']); ?></td>
                                </tr>
                                <?php if (isset($data['created_at'])): ?>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td><?php echo htmlspecialchars($data['created_at']); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
<script>
function handleBack() {
    // Periksa apakah halaman sebelumnya adalah penggunaedit.php
    if (document.referrer.includes('penggunaedit.php')) {
        // Jika ya, arahkan ke pengguna.php
        window.location.href = 'pengguna.php';
    } else {
        // Jika tidak, gunakan history.back() untuk kembali ke halaman sebelumnya
        history.back();
    }
}
</script>