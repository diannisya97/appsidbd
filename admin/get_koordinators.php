<?php
include '../function.php'; // Pastikan koneksi ke database sudah benar

header('Content-Type: application/json'); // Set header JSON

// Query untuk mengambil data koordinator
$result = mysqli_query($conn, "SELECT id, namakoor FROM koordinator");

if (!$result) {
    echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
    exit();
}

// Menyimpan data koordinator dalam array
$koordinators = [];
while ($row = mysqli_fetch_assoc($result)) {
    $koordinators[] = $row;
}

// Mengirim data koordinator dalam format JSON
echo json_encode($koordinators);
?>
