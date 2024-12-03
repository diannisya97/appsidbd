<?php
require "../function.php";
require "../cek.php";

// Pastikan session login telah diatur
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href='../login.php';</script>";
    exit();
}

if (isset($_POST['tambahpengguna'])) {
    $nama = $_POST['nama'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $asalpkm = $_POST['asalpkm'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    // Query untuk menambahkan pengguna baru
    $query = "INSERT INTO user (nama, username, password, contact, email, idpuskesmas, level) 
              VALUES ('$nama', '$username', '$password', '$contact', '$email', '$asalpkm', '$level')";
    $tambahpengguna = mysqli_query($conn, $query);

    if ($tambahpengguna) {
        // Catat aktivitas
        date_default_timezone_set('Asia/Jakarta');
        $userId = $_SESSION['id']; // ID user yang sedang login
        $activity = "Menambah pengguna baru: $username";
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

        echo "<script>alert('Berhasil Tambah Pengguna'); window.location.href='pengguna.php';</script>";
    } else {
        echo "<script>alert('Gagal Tambah Pengguna'); window.location.href='pengguna.php';</script>";
    }
}
?>
