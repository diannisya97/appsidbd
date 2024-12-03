<?php
session_start();
require "../function.php";
require "../cek.php";

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Validasi ID yang diterima melalui GET
if (isset($_GET['id'])) {
    $idpengendalian = $_GET['id'];

    // Query untuk menghapus data pengendalian vektor
    $queryDelete = "DELETE FROM pengendalianvektor WHERE idpengendalian = '$idpengendalian'";
    $resultDelete = mysqli_query($conn, $queryDelete);

    if ($resultDelete) {
        // Ambil id user yang sedang login dari session
        $userId = $_SESSION['id']; // Pastikan session sudah ter-set

        // Catat aktivitas menghapus data pengendalian vektor
        $activity = "Menghapus data pengendalian vektor";
        $currentTime = date('Y-m-d H:i:s'); // Waktu sekarang

        // Query untuk mengambil aktivitas pengguna
        $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
        $resultActivity = mysqli_query($conn, $activityQuery);
        $dataActivity = mysqli_fetch_assoc($resultActivity);

        // Ambil aktivitas dan waktu lama jika ada
        $currentActivities = !empty($dataActivity['activity']) ? explode(',', $dataActivity['activity']) : [];
        $currentTimes = !empty($dataActivity['created_at']) ? explode(',', $dataActivity['created_at']) : [];

        // Tambahkan aktivitas baru dan waktu ke array
        array_unshift($currentActivities, $activity); // Tambahkan aktivitas baru di awal
        array_unshift($currentTimes, $currentTime); // Tambahkan waktu aktivitas baru di awal

        // Gabungkan kembali aktivitas dan waktu
        $updatedActivities = implode(',', $currentActivities);
        $updatedTimes = implode(',', $currentTimes);

        // Update aktivitas dan waktu pengguna di tabel user
        $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);

        echo "<script>alert('Data Pengendalian Vektor berhasil dihapus'); window.location.href='vektor.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data Pengendalian Vektor'); window.location.href='vektor.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='vektor.php';</script>";
}
?>
