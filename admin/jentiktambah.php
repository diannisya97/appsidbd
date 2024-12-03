<?php
require "../function.php";

// Pastikan session login telah diatur
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href='../login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambahjentik'])) {
    // Ambil data dari form
    $namapemilik = mysqli_real_escape_string($conn, $_POST['id']); // Menggunakan id pasien
    $namakoor = mysqli_real_escape_string($conn, $_POST['idkoordinator']); // Menggunakan id koordinator
    $tanggalrekap = mysqli_real_escape_string($conn, $_POST['tanggalrekap']);
    $bakmandi = mysqli_real_escape_string($conn, $_POST['bakmandi']);
    $dispenser = mysqli_real_escape_string($conn, $_POST['dispenser']);
    $penampung = mysqli_real_escape_string($conn, $_POST['penampung']);
    $potbunga = mysqli_real_escape_string($conn, $_POST['potbunga']);
    $tempatminumhewan = mysqli_real_escape_string($conn, $_POST['tempatminumhewan']);
    $banbekas = mysqli_real_escape_string($conn, $_POST['banbekas']);
    $sampah = mysqli_real_escape_string($conn, $_POST['sampah']);
    $pohon = mysqli_real_escape_string($conn, $_POST['pohon']);
    $lainnya = mysqli_real_escape_string($conn, $_POST['lainnya']);

    // Pastikan semua input tidak kosong
    if (
        empty($namapemilik) || empty($namakoor) || empty($tanggalrekap) ||
        empty($bakmandi) || empty($dispenser) || empty($penampung) ||
        empty($potbunga) || empty($tempatminumhewan) || empty($banbekas) ||
        empty($sampah) || empty($pohon) || empty($lainnya)
    ) {
        echo "<script>alert('Semua field harus diisi.'); window.location.href='jentik.php';</script>";
        exit();
    }

    // Query untuk menyimpan data ke dalam tabel `jentikperiksa`
    $query = "INSERT INTO jentikperiksa (namapemilik, namakoor, tanggalrekap, bakmandi, dispenser, penampung, potbunga, tempatminumhewan, banbekas, sampah, pohon, lainnya) 
              VALUES ('$namapemilik', '$namakoor', '$tanggalrekap', '$bakmandi', '$dispenser', '$penampung', '$potbunga', '$tempatminumhewan', '$banbekas', '$sampah', '$pohon', '$lainnya')";

    if (mysqli_query($conn, $query)) {
        // Catat aktivitas
        date_default_timezone_set('Asia/Jakarta');
        $userId = $_SESSION['id'];
        $activity = "Menambah data pemeriksaan jentik";
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

        echo "<script>alert('Data jentik berhasil ditambahkan.'); window.location.href='jentik.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($conn) . "'); window.location.href='jentik.php';</script>";
    }

    // Tutup koneksi
    mysqli_close($conn);
}
?>
