<?php
include "../function.php"; // Koneksi ke database

// Query untuk menghitung jumlah 'Pernah' dan 'Tidak Pernah'
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
    FROM pengendalianvektor
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($data);
?>
