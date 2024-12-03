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
                        <a href="#" class="btn btn-secondary" onclick="history.back(); return false;"data-bs-toggle="tooltip" title="Ke Halaman Sebelumnya"><i class="fas fa-arrow-left" ></i> Kembali</a>
                            <div class="header-title">Detail Koordnator</div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nama Koordinator</th>
                                    <td><?php echo htmlspecialchars($data['namakoor']); ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor Kontak</th>
                                    <td><?php echo htmlspecialchars($data['nokontak']); ?></td>
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
