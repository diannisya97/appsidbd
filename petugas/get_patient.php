<?php
include '../function.php'; // Pastikan koneksi ke database sudah benar

header('Content-Type: application/json'); // Set header JSON

session_start(); // Mulai session untuk mendapatkan idpuskesmas dari session
$idpuskesmas = $_SESSION['idpuskesmas']; // Dapatkan idpuskesmas dari session

// Query untuk mengambil data pasien yang memiliki asalfasyankes yang sama dengan pengguna yang login
$result = mysqli_query($conn, "SELECT id, namapasien FROM pasien WHERE asalfasyankes = '$idpuskesmas'");

if (!$result) {
    echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
    exit();
}

$patients = [];

while ($row = mysqli_fetch_assoc($result)) {
    $patients[] = $row;
}

echo json_encode($patients);
?>
