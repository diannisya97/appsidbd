<?php
session_start();
require "../function.php";
require "../cek.php"; // Ensure user has permissions

// Check if the ID parameter is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Convert to integer to prevent SQL injection

    // Retrieve the infographic record to get the file name
    $stmt = $conn->prepare("SELECT foto1 FROM infografis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $foto1 = $row['foto1'];

        // Delete the file from the server
        $uploadDir = '../uploads/';
        if ($foto1 && file_exists($uploadDir . $foto1)) {
            unlink($uploadDir . $foto1);
        }

        // Delete record from database
        $stmt = $conn->prepare("DELETE FROM infografis WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Log the activity
            date_default_timezone_set('Asia/Jakarta');
            $userId = $_SESSION['id']; // ID user yang sedang login
            $activity = "Menghapus data infografis";
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

            echo "<script>alert('Infografis berhasil dihapus!'); window.location.href='infografis.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus infografis: " . $stmt->error . "'); window.location.href='infografis.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Infografis tidak ditemukan.'); window.location.href='infografis.php';</script>";
    }
    $conn->close();
} else {
    echo "<script>alert('ID infografis tidak valid.'); window.location.href='infografis.php';</script>";
}
?>
