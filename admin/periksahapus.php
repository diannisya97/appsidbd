<?php
// Mulai sesi
session_start();

// Include file koneksi database
include '../function.php';
// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');
// Cek apakah ada parameter id yang dikirimkan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Pastikan id adalah integer untuk keamanan
    $id = intval($id);

    // Query untuk mendapatkan data pemeriksaan klinis yang akan dihapus
    $queryGetPeriksa = "SELECT namapasien FROM periksapasien WHERE id = ?";
    if ($stmt = $conn->prepare($queryGetPeriksa)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($namapasien);
        $stmt->fetch();
        $stmt->close();

        // Jika pasien ditemukan, lanjutkan dengan menghapus data
        if ($namapasien) {
            // Query untuk menghapus data dari tabel periksapasien
            $query = "DELETE FROM periksapasien WHERE id = ?";

            // Prepare statement untuk menghapus data
            if ($stmt = $conn->prepare($query)) {
                // Bind parameter
                $stmt->bind_param('i', $id);
                // Eksekusi query
                if ($stmt->execute()) {
                    // Hapus data berhasil
                    echo "<script>alert('Data Pasien berhasil dihapus'); window.location.href='periksa.php';</script>";

                    // Ambil id user yang sedang login dari session
                    $userId = $_SESSION['id']; // Pastikan session sudah ter-set

                    // Catat aktivitas menghapus data pemeriksaan klinis
                    $activity = "Menghapus pemeriksaan klinis pasien";
                    $currentTime = date('Y-m-d H:i:s'); // Waktu sekarang

                    // Query untuk mengambil aktivitas pengguna
                    $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
                    $result = mysqli_query($conn, $activityQuery);
                    $data = mysqli_fetch_assoc($result);

                    // Ambil aktivitas dan waktu lama jika ada
                    $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
                    $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

                    // Tambahkan aktivitas baru dan waktu ke array
                    array_unshift($currentActivities, $activity); // Tambahkan aktivitas baru di awal
                    array_unshift($currentTimes, $currentTime); // Tambahkan waktu aktivitas baru di awal

                    // Gabungkan kembali aktivitas dan waktu
                    $updatedActivities = implode(',', $currentActivities);
                    $updatedTimes = implode(',', $currentTimes);

                    // Update aktivitas dan waktu pengguna di tabel user
                    $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
                    mysqli_query($conn, $updateQuery);
                } else {
                    // Jika gagal, tampilkan pesan error
                    echo "<script>alert('Gagal menghapus data Pasien'); window.location.href='periksa.php';</script>";
                }
                // Tutup statement
                $stmt->close();
            } else {
                echo "<script>alert('Gagal menyiapkan statement'); window.location.href='periksa.php';</script>";
            }
        } else {
            echo "<script>alert('Pemeriksaan klinis tidak ditemukan'); window.location.href='periksa.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal menyiapkan query'); window.location.href='periksa.php';</script>";
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='periksa.php';</script>";
}
?>
