<?php
session_start();
require "../function.php";
require "../cek.php";
// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama pasien yang akan dihapus
    $queryGetPatient = "SELECT namapasien FROM pasien WHERE id = '$id'";
    $resultGetPatient = mysqli_query($conn, $queryGetPatient);

    if (mysqli_num_rows($resultGetPatient) > 0) {
        $data = mysqli_fetch_assoc($resultGetPatient);
        $namapasien = $data['namapasien'];

        // Query untuk menghapus data pasien
        $query = "DELETE FROM pasien WHERE id = '$id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Ambil id user yang sedang login dari session
            $userId = $_SESSION['id']; // Pastikan session sudah ter-set

            // Catat aktivitas menghapus data pasien
            $activity = "Menghapus data pasien: $namapasien";
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

            echo "<script>alert('Data Pasien berhasil dihapus'); window.location.href='pasien.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data Pasien'); window.location.href='pasien.php';</script>";
        }
    } else {
        echo "<script>alert('Pasien tidak ditemukan'); window.location.href='pasien.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='pasien.php';</script>";
}

?>
