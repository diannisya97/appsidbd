<?php
require "../function.php";

// Pastikan session login telah diatur
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href='../login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambahvektor'])) {
    // Ambil data dari form
    $id = mysqli_real_escape_string($conn, $_POST['id']); // ID pasien
    $penyuluhan = mysqli_real_escape_string($conn, $_POST['penyuluhan']);
    $psn3m = mysqli_real_escape_string($conn, $_POST['psn3m']);
    $larvasidasi = mysqli_real_escape_string($conn, $_POST['larvasidasi']);
    $fogging = mysqli_real_escape_string($conn, $_POST['fogging']);

    // Validasi input tidak boleh kosong
    if (empty($id) || empty($penyuluhan) || empty($psn3m) || empty($larvasidasi) || empty($fogging)) {
        echo "<script>alert('Semua field harus diisi.'); window.location.href='vektor.php';</script>";
        exit();
    }

    // Query untuk menyimpan data ke dalam tabel `pengendalianvektor`
    $query = "INSERT INTO pengendalianvektor (id, penyuluhan, psn3m, larvasidasi, fogging) 
              VALUES ('$id', '$penyuluhan', '$psn3m', '$larvasidasi', '$fogging')";

    if (mysqli_query($conn, $query)) {
        // Catat aktivitas
        date_default_timezone_set('Asia/Jakarta');
        $userId = $_SESSION['id'];
        $activity = "Menambah data pengendalian vektor untuk ID pasien: $id";
        $currentTime = date('Y-m-d H:i:s'); // Format datetime

        // Query untuk mengambil aktivitas sebelumnya
        $activityQuery = "SELECT activity FROM user WHERE id = ?";
        $stmt = $conn->prepare($activityQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $currentActivities = !empty($data['activity']) ? $data['activity'] . ',' . $activity : $activity;

            // Update aktivitas ke tabel user
            $updateQuery = "UPDATE user SET activity = ?, created_at = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param("ssi", $currentActivities, $currentTime, $userId);

            if ($stmtUpdate->execute()) {
                echo "<script>alert('Berhasil Tambah Pengendalian Vektor dan Aktivitas Tercatat'); window.location.href='vektor.php';</script>";
            } else {
                echo "<script>alert('Gagal mencatat aktivitas: " . $conn->error . "'); window.location.href='vektor.php';</script>";
            }
            $stmtUpdate->close();
        } else {
            echo "<script>alert('Data user tidak ditemukan.'); window.location.href='vektor.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Gagal menambahkan data pengendalian vektor: " . mysqli_error($conn) . "'); window.location.href='vektor.php';</script>";
    }

    // Tutup koneksi
    mysqli_close($conn);
}
?>
