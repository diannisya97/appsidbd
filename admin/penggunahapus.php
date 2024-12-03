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

// Get the username of the user to be deleted
$queryGetUsername = "SELECT username FROM user WHERE id = ?";
if ($stmt = $conn->prepare($queryGetUsername)) {
    $stmt->bind_param("i", $idpengguna);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
    exit();
}

// Delete data for the given id
$deleteQuery = "DELETE FROM user WHERE id = ?";
if ($stmt = $conn->prepare($deleteQuery)) {
    $stmt->bind_param("i", $idpengguna);
    if ($stmt->execute()) {
        // Log the activity
        date_default_timezone_set('Asia/Jakarta');
        $userId = $_SESSION['id']; // ID user yang sedang login
        $activity = "Menghapus pengguna: $username";
        $currentTime = date('Y-m-d H:i:s');

        // Ambil aktivitas dan waktu sebelumnya
        $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
        $result = mysqli_query($conn, $activityQuery);
        $data = mysqli_fetch_assoc($result);

        $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
        $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

        // Tambahkan aktivitas baru
        array_unshift($currentActivities, $activity);
        array_unshift($currentTimes, $currentTime);

        // Update aktivitas ke tabel user
        $updatedActivities = implode(',', $currentActivities);
        $updatedTimes = implode(',', $currentTimes);

        $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);

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
