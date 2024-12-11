<?php


// Koneksi ke dalam database
$conn = mysqli_connect("localhost", "data", "kaskus2727", "appsidbd");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


?>
