<?php
include "../function.php"; // Koneksi ke database
session_start();

if (!isset($_SESSION['idpuskesmas'])) {
    die('User belum login atau session tidak valid.');
}

$idpuskesmas = $_SESSION['idpuskesmas'];

// Query untuk menghitung data 'Pernah' dan 'Tidak Pernah' dari pengendalianvektor berdasarkan id pasien yang terkait dengan idpuskesmas
$query = "
    SELECT 
        SUM(CASE WHEN fogging = 'Pernah' THEN 1 ELSE 0 END) AS fogging_pernah,
        SUM(CASE WHEN fogging = 'Tidak Pernah' THEN 1 ELSE 0 END) AS fogging_tidak_pernah,
        SUM(CASE WHEN larvasidasi = 'Pernah' THEN 1 ELSE 0 END) AS larvasidasi_pernah,
        SUM(CASE WHEN larvasidasi = 'Tidak Pernah' THEN 1 ELSE 0 END) AS larvasidasi_tidak_pernah,
        SUM(CASE WHEN psn3m = 'Pernah' THEN 1 ELSE 0 END) AS psn3m_pernah,
        SUM(CASE WHEN psn3m = 'Tidak Pernah' THEN 1 ELSE 0 END) AS psn3m_tidak_pernah,
        SUM(CASE WHEN penyuluhan = 'Pernah' THEN 1 ELSE 0 END) AS penyuluhan_pernah,
        SUM(CASE WHEN penyuluhan = 'Tidak Pernah' THEN 1 ELSE 0 END) AS penyuluhan_tidak_pernah
    FROM pengendalianvektor pv
    JOIN pasien p ON pv.id = p.id
    WHERE p.asalfasyankes = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idpuskesmas); // Menggunakan idpuskesmas dari sesi login untuk memfilter pasien
$stmt->execute();
$result = $stmt->get_result();
$data = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($data);
?>
