<?php
// Koneksi ke dalam database
$conn = mysqli_connect("localhost", "data", "kaskus27", "appsidbd");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Pastikan session dimulai
    if (!isset($_SESSION['id'])) {
        echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href='../login.php';</script>";
        exit();
    }

    $nama_puskesmas = mysqli_real_escape_string($conn, $_POST['Nama_Puskesmas']);
    $nomor_kontak = mysqli_real_escape_string($conn, $_POST['Nomor_Kontak']);
    $kepala_puskesmas = mysqli_real_escape_string($conn, $_POST['kepala_puskesmas']);
    $jam_operasional = mysqli_real_escape_string($conn, $_POST['jam_operasional']);
    $alamat = mysqli_real_escape_string($conn, $_POST['Alamat']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);

    // Insert the new Puskesmas data into the database
    $query = "INSERT INTO puskesmas (Nama_Puskesmas, Nomor_Kontak, kepala_puskesmas, jam_operasional, Alamat, longitude, latitude)
              VALUES ('$nama_puskesmas', '$nomor_kontak', '$kepala_puskesmas', '$jam_operasional', '$alamat', '$longitude', '$latitude')";
    
    // Execute the query
    $tambahpuskesmas = mysqli_query($conn, $query);

    if ($tambahpuskesmas) {
        // Catat aktivitas
        date_default_timezone_set('Asia/Jakarta');
        $userId = $_SESSION['id'];
        $activity = "Menambah data $nama_puskesmas";
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

        echo "<script>alert('Berhasil Tambah Puskesmas'); window.location.href='puskesmas.php';</script>";
    } else {
        echo "<script>alert('Gagal Tambah Puskesmas'); window.location.href='puskesmas.php';</script>";
    }
}
?>
