<?php


// Koneksi ke dalam database
$conn = mysqli_connect("localhost", "root", "", "app-sidbd");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


?>
