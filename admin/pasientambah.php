<?php
// Koneksi ke dalam database
$conn = mysqli_connect("localhost", "root", "", "app-sidbd");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $namapasien = mysqli_real_escape_string($conn, $_POST["namapasien"]);
    $nik = mysqli_real_escape_string($conn, $_POST["nik"]);
    $tanggallahir = mysqli_real_escape_string($conn, $_POST["tanggallahir"]);
    $tempatlahir = mysqli_real_escape_string($conn, $_POST["tempatlahir"]);
    $jeniskelamin = mysqli_real_escape_string($conn, $_POST["jeniskelamin"]);
    $alamat = mysqli_real_escape_string($conn, $_POST["alamat"]);
    $rt = mysqli_real_escape_string($conn, $_POST["rt"]);
    $rw = mysqli_real_escape_string($conn, $_POST["rw"]);
    $desakelurahan = mysqli_real_escape_string($conn, $_POST["desakelurahan"]);
    $kecamatan = mysqli_real_escape_string($conn, $_POST["kecamatan"]);
    $kabkota = mysqli_real_escape_string($conn, $_POST["kabkota"]);
    $kodepos = mysqli_real_escape_string($conn, $_POST["kodepos"]);
    $nokontak = mysqli_real_escape_string($conn, $_POST["nokontak"]);
    $longitude = mysqli_real_escape_string($conn, $_POST["longitude"]);
    $latitude = mysqli_real_escape_string($conn, $_POST["latitude"]);
    $asalfasyankes = mysqli_real_escape_string($conn, $_POST["asalfasyankes"]);
    $domisili = mysqli_real_escape_string($conn, $_POST["domisili"]);

    // Query untuk menambah data pasien dengan kolom baru
    $query = "INSERT INTO pasien (namapasien, nik, tanggallahir, tempatlahir, jeniskelamin, alamat, rt, rw, desakelurahan, kecamatan, kabkota, kodepos, nokontak, longitude, latitude, asalfasyankes, domisili) 
    VALUES ('$namapasien', '$nik', '$tanggallahir', '$tempatlahir', '$jeniskelamin', '$alamat', '$rt', '$rw', '$desakelurahan', '$kecamatan', '$kabkota', '$kodepos', '$nokontak', '$longitude', '$latitude', '$asalfasyankes', '$domisili')";

    $tambahpasien = mysqli_query($conn, $query);

    // Set timezone Indonesia
    date_default_timezone_set('Asia/Jakarta');

    // Menjalankan statement
    if ($tambahpasien) {
        // Ambil id user yang sedang login dari session
        session_start();
        $userId = $_SESSION['id']; // Pastikan session sudah ter-set

        // Catat aktivitas menambah data pasien
        $activity = "Menambah data pasien: $namapasien";
        $currentTime = date('Y-m-d H:i:s'); // Waktu sekarang di zona waktu Indonesia

        // Query untuk mengambil aktivitas dan waktu sebelumnya
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

        // Update data aktivitas dan waktu pengguna di tabel user
        $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);

        // Redirect setelah berhasil
        echo "<script>alert('Berhasil Tambah Pasien'); window.location.href='pasien.php';</script>";
    } else {
        echo "<script>alert('Gagal Tambah Pasien'); window.location.href='pasien.php';</script>";
    }

    // Tutup koneksi database
    mysqli_close($conn);
}
?>
