<?php


// Koneksi ke dalam database
$conn = mysqli_connect("localhost", "root", "", "app-sidbd");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


function getPuskesmasById($id) {
    global $conn; // Sesuaikan dengan koneksi database Anda

    $stmt = $conn->prepare("SELECT Nama_Puskesmas, Alamat, Nomor_Kontak FROM puskesmas WHERE idpuskesmas = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

?>
