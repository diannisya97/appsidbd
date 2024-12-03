<?php
session_start();
require "../function.php";

// Ambil id puskesmas dari session
$idpuskesmas = $_SESSION['idpuskesmas'];

// Ambil data pemeriksaan pasien per tanggal
$query = "
    SELECT 
        DATE(pr.tanggalperiksa) AS date,
        COUNT(*) AS count
    FROM periksapasien pr
    JOIN pasien p ON pr.namapasien = p.id
    WHERE p.asalfasyankes = ?
    GROUP BY DATE(pr.tanggalperiksa)
    ORDER BY DATE(pr.tanggalperiksa)
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idpuskesmas);
$stmt->execute();
$result = $stmt->get_result();

$data_pemeriksaan = [];
$dates = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}

$data_pemeriksaan['dates'] = $dates;
$data_pemeriksaan['counts'] = $counts;

header('Content-Type: application/json');
echo json_encode($data_pemeriksaan);
?>
