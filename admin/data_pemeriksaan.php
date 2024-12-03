<?php
include "../function.php"; // Koneksi ke database

// Query untuk menghitung jumlah pasien per tanggal selama 5 hari terakhir
$query = "
    SELECT tanggalperiksa, COUNT(*) AS jumlah_pasien
    FROM periksapasien
    WHERE tanggalperiksa >= CURDATE() - INTERVAL 5 DAY
    GROUP BY tanggalperiksa
    ORDER BY tanggalperiksa
";

$result = mysqli_query($conn, $query);

$dates = [];
$counts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['tanggalperiksa'];
    $counts[] = $row['jumlah_pasien'];
}

header('Content-Type: application/json');
echo json_encode([
    'dates' => $dates,
    'counts' => $counts
]);
?>
