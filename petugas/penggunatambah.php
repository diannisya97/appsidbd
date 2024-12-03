<?php
require "../function.php";
require "../cek.php";

if (isset($_POST['tambahpengguna'])) {
    $nama = $_POST['nama'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $asalpkm = $_POST['asalpkm'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    $query = "INSERT INTO user (nama, username, password, contact, email, idpuskesmas, level) VALUES ('$nama', '$username', '$password', '$contact', '$email', '$asalpkm', '$level')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: pengguna.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
