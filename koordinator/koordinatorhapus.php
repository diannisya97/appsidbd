<?php
session_start();
require "../function.php";
require "../cek.php";
// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');
// Pastikan parameter id ada dan valid
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data dari database dengan prepared statement
    $query = "DELETE FROM koordinator WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Pastikan statement berhasil dipersiapkan
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id); // Bind parameter id

        if (mysqli_stmt_execute($stmt)) {
            // Catat aktivitas penghapusan jika berhasil
            $userId = $_SESSION['id']; // ID user yang sedang login
            $activity = "Menghapus data koordinator";
            $currentTime = date('Y-m-d H:i:s'); // Waktu sekarang

            // Query untuk mengambil aktivitas pengguna
            $activityQuery = "SELECT activity, created_at FROM user WHERE id = ?";
            $activityStmt = mysqli_prepare($conn, $activityQuery);
            mysqli_stmt_bind_param($activityStmt, 'i', $userId);

            if (mysqli_stmt_execute($activityStmt)) {
                $resultActivity = mysqli_stmt_get_result($activityStmt);
                $dataActivity = mysqli_fetch_assoc($resultActivity);

                $currentActivities = !empty($dataActivity['activity']) ? explode(',', $dataActivity['activity']) : [];
                $currentTimes = !empty($dataActivity['created_at']) ? explode(',', $dataActivity['created_at']) : [];

                // Tambahkan aktivitas baru dan waktu ke array
                array_unshift($currentActivities, $activity);
                array_unshift($currentTimes, $currentTime);

                // Gabungkan kembali aktivitas dan waktu
                $updatedActivities = implode(',', $currentActivities);
                $updatedTimes = implode(',', $currentTimes);

                // Update aktivitas dan waktu pengguna di tabel user
                $updateQuery = "UPDATE user SET activity = ?, created_at = ? WHERE id = ?";
                $updateStmt = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($updateStmt, 'ssi', $updatedActivities, $updatedTimes, $userId);
                mysqli_stmt_execute($updateStmt);
            }

            // Jika penghapusan berhasil, tampilkan alert dan redirect
            echo "<script>alert('Data Koordinator berhasil dihapus'); window.location.href = 'koordinator.php';</script>";
            exit();
        } else {
            // Jika gagal, tampilkan alert dan redirect
            echo "<script>alert('Gagal menghapus data Koordinator'); window.location.href = 'koordinator.php';</script>";
            exit();
        }
    } else {
        // Jika query tidak berhasil dipersiapkan, tampilkan alert dan redirect
        echo "<script>alert('Gagal mempersiapkan query penghapusan'); window.location.href = 'koordinator.php';</script>";
        exit();
    }
} else {
    // Jika id tidak ada atau kosong, tampilkan alert dan redirect
    echo "<script>alert('ID Koordinator tidak ditemukan'); window.location.href = 'koordinator.php';</script>";
    exit();
}
?>
