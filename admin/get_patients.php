<?php
include '../function.php'; // Pastikan koneksi ke database sudah benar

header('Content-Type: application/json'); // Set header JSON

$result = mysqli_query($conn, "SELECT id, namapasien FROM pasien");

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
