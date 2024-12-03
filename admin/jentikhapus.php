<?php
session_start();
require "../function.php";
require "../cek.php";
// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Get id from URL
$idjentik = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idjentik) || !is_numeric($idjentik)) {
    echo "<script>alert('ID jentik tidak valid!'); window.location.href='jentik.php';</script>";
    exit();
}

$idjentik = intval($idjentik); // Ensure the id is an integer

// Ambil informasi data jentik yang akan dihapus
$queryGetJentik = "SELECT id FROM jentikperiksa WHERE id = ?";
if ($stmt = $conn->prepare($queryGetJentik)) {
    $stmt->bind_param("i", $idjentik);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Proses penghapusan data
        $queryDelete = "DELETE FROM jentikperiksa WHERE id = ?";
        if ($stmtDelete = $conn->prepare($queryDelete)) {
            $stmtDelete->bind_param("i", $idjentik);
            if ($stmtDelete->execute()) {
                // Jika penghapusan berhasil
                // Ambil id user yang sedang login dari session
                $userId = $_SESSION['id']; // Pastikan session sudah ter-set

                // Catat aktivitas menghapus data jentik
                $activity = "Menghapus data pemeriksaan jentik";
                $currentTime = date('Y-m-d H:i:s'); // Waktu sekarang

                // Query untuk mengambil aktivitas pengguna
                $activityQuery = "SELECT activity, created_at FROM user WHERE id = ?";
                if ($stmtActivity = $conn->prepare($activityQuery)) {
                    $stmtActivity->bind_param("i", $userId);
                    $stmtActivity->execute();
                    $resultActivity = $stmtActivity->get_result();
                    $dataActivity = $resultActivity->fetch_assoc();

                    // Ambil aktivitas dan waktu lama jika ada
                    $currentActivities = !empty($dataActivity['activity']) ? explode(',', $dataActivity['activity']) : [];
                    $currentTimes = !empty($dataActivity['created_at']) ? explode(',', $dataActivity['created_at']) : [];

                    // Tambahkan aktivitas baru dan waktu ke array
                    array_unshift($currentActivities, $activity); // Tambahkan aktivitas baru di awal
                    array_unshift($currentTimes, $currentTime); // Tambahkan waktu aktivitas baru di awal

                    // Gabungkan kembali aktivitas dan waktu
                    $updatedActivities = implode(',', $currentActivities);
                    $updatedTimes = implode(',', $currentTimes);

                    // Update aktivitas dan waktu pengguna di tabel user
                    $updateQuery = "UPDATE user SET activity = ?, created_at = ? WHERE id = ?";
                    if ($stmtUpdate = $conn->prepare($updateQuery)) {
                        $stmtUpdate->bind_param("ssi", $updatedActivities, $updatedTimes, $userId);
                        $stmtUpdate->execute();
                    }
                }

                echo "<script>alert('Data jentik berhasil dihapus!'); window.location.href='jentik.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menghapus data jentik.'); window.location.href='jentik.php';</script>";
            }
            $stmtDelete->close();
        }
    } else {
        echo "<script>alert('Data jentik tidak ditemukan!'); window.location.href='jentik.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Terjadi kesalahan saat menyiapkan query.'); window.location.href='jentik.php';</script>";
}
?>
