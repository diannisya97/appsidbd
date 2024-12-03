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
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <br>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">
                        <a href="#" class="btn btn-secondary" onclick="history.back(); return false;"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <div class="header-title">Detail Pengguna</div>
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
