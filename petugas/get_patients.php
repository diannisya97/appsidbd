<?php
include '../function.php'; // Pastikan koneksi ke database sudah benar

header('Content-Type: application/json'); // Set header JSON

// Pastikan idpuskesmas diterima dengan benar dari request
$idpuskesmas = isset($_GET['idpuskesmas']) ? $_GET['idpuskesmas'] : null;

if ($idpuskesmas) {
    // Query hanya pasien yang asalfasyankes-nya sama dengan idpuskesmas user
    $query = "SELECT DISTINCT p.id, p.namapasien 
              FROM pasien p 
              JOIN koordinator k ON p.asalfasyankes = k.asalfasyankes 
              WHERE k.asalfasyankes = '$idpuskesmas'";
    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
        exit();
    }

    $patients = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row;
    }

    echo json_encode($patients);
} else {
    echo json_encode(["error" => "idpuskesmas not provided"]);
}
?>
