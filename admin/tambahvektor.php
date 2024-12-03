<?php
include '../function.php'; 

if (isset($_POST["tambahvektor"])) {
    // Ambil data dari form
    $id = $_POST["id"];
    $penyuluhan = $_POST["penyuluhan"];
    $psn3m = $_POST["psn3m"];
    $larvasidasi = $_POST["larvasidasi"];
    $fogging = $_POST["fogging"];

    // Pastikan semua data tidak kosong
    if (!empty($id) && !empty($penyuluhan) && !empty($psn3m) && !empty($larvasidasi) && !empty($fogging)) {
        // Cek apakah ID pasien ada di tabel pasien
        $stmt_check = $conn->prepare("SELECT id FROM pasien WHERE id = ?");
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Siapkan query menggunakan prepared statement
            $stmt = $conn->prepare("INSERT INTO pengendalianvektor (id, penyuluhan, psn3m, larvasidasi, fogging) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $id, $penyuluhan, $psn3m, $larvasidasi, $fogging);

            // Eksekusi statement dan cek apakah berhasil
            if ($stmt->execute()) {
                echo "<script>alert('Berhasil Tambah Pengendalian Vektor'); window.location.href='vektor.php';</script>";
            } else {
                echo "<script>alert('Gagal Tambah Pengendalian Vektor'); window.location.href='vektor.php';</script>";
            }

            // Tutup statement
            $stmt->close();
        } else {
            // ID pasien tidak ditemukan
            echo "<script>alert('ID pasien tidak ditemukan! Pastikan Anda memilih pasien yang valid.'); window.location.href='vektor.php';</script>";
        }

        // Tutup statement pengecekan
        $stmt_check->close();
    } else {
        echo "<script>alert('Semua field harus diisi!'); window.location.href='vektor.php';</script>";
    }
}
?>
