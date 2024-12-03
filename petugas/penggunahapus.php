<?php
session_start();
require "../function.php";
require "../cek.php";

// Get id
$idpengguna = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpengguna) || !is_numeric($idpengguna)) {
    echo "<script>alert('ID Pengguna tidak valid!'); window.location.href='pengguna.php';</script>";
    exit();
}

$idpengguna = intval($idpengguna); // Ensure the id is an integer

// Delete data for the given id
$deleteQuery = "DELETE FROM user WHERE id = ?";
if ($stmt = $conn->prepare($deleteQuery)) {
    $stmt->bind_param("i", $idpengguna);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='pengguna.php';</script>";
    } else {
        echo "DATA GAGAL DIHAPUS! Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
}
$conn->close();
?>
