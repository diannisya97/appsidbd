<?php

require "../function.php";
require "../cek.php";

// Pastikan session login telah diatur
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href='../login.php';</script>";
    exit();
}

if (isset($_POST["tambahkoordinator"])) {
    $namakoor = $_POST["namakoor"];
    $alamat = $_POST["alamat"];
    $nokontak = $_POST["nokontak"];
    $asalfasyankes = $_POST["asalfasyankes"];

    // Query untuk menambah koordinator
    $tambahkoordinator = mysqli_query($conn, "INSERT INTO koordinator VALUES(NULL,'$namakoor','$alamat','$nokontak','$asalfasyankes')");

    // Menjalankan statement
    if ($tambahkoordinator) {
        // Catat aktivitas
        date_default_timezone_set('Asia/Jakarta');
        $userId = $_SESSION['id']; // ID user yang sedang login
        $activity = "Menambah data koordinator";
        $currentTime = date('Y-m-d H:i:s');

        // Ambil aktivitas dan waktu sebelumnya
        $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
        $result = mysqli_query($conn, $activityQuery);
        $data = mysqli_fetch_assoc($result);

        $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
        $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

        // Tambahkan aktivitas baru
        array_unshift($currentActivities, $activity);
        array_unshift($currentTimes, $currentTime);

        // Update aktivitas ke tabel user
        $updatedActivities = implode(',', $currentActivities);
        $updatedTimes = implode(',', $currentTimes);

        $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);

        echo "<script>alert('Berhasil Tambah Koordinator'); window.location.href='koordinator.php';</script>";
    } else {
        echo "<script>alert('Gagal Tambah Koordinator'); window.location.href='koordinator.php';</script>";
    }
}
?>
