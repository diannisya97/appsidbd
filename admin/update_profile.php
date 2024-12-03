<?php
require "../function.php";
require "../cek.php";

// Tambahkan ini untuk logging error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari POST
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $contact = isset($_POST['contact']) ? $_POST['contact'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($id) && !empty($nama) && !empty($username) && !empty($contact) && !empty($email)) {
        // Siapkan pernyataan SQL untuk update data
        if (!empty($password)) {
            // Tidak melakukan hashing, simpan password langsung
            $query = "UPDATE user SET nama = ?, username = ?, contact = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $nama, $username, $contact, $email, $password, $id);
        } else {
            $query = "UPDATE user SET nama = ?, username = ?, contact = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $nama, $username, $contact, $email, $id);
        }

        if ($stmt->execute()) {
            header("Location: profil.php?status=success");
        } else {
            header("Location: profil.php?status=error");
        }
    } else {
        header("Location: profil.php?status=error");
    }
} else {
    header("Location: profil.php?status=error");
}
exit;
?>
