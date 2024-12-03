<?php
session_start();
require "../function.php";
require "../cek.php";

// Get ID
$idpuskesmas = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idpuskesmas) || !is_numeric($idpuskesmas)) {
    echo "<script>alert('ID Puskesmas tidak valid!'); window.location.href='puskesmas.php';</script>";
    exit();
}

$idpuskesmas = intval($idpuskesmas); // Ensure the id is an integer

// Log activity before deleting data
if ($stmt = $conn->prepare("SELECT Nama_Puskesmas FROM puskesmas WHERE idpuskesmas = ?")) {
    $stmt->bind_param("i", $idpuskesmas);
    $stmt->execute();
    $result = $stmt->get_result();
    $puskesmasData = $result->fetch_assoc();
    $stmt->close();

    // Check if the Puskesmas exists
    if ($puskesmasData) {
        // Log activity for deletion
        $userId = $_SESSION['id']; // Get the logged-in user's ID
        $activity = "Menghapus data: " . $puskesmasData['Nama_Puskesmas']; // Activity description
        $currentTime = date('Y-m-d H:i:s'); // Current time of the activity

        // Retrieve the existing activity and time from the user table
        $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
        $result = mysqli_query($conn, $activityQuery);
        $data = mysqli_fetch_assoc($result);

        $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
        $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

        // Add the delete activity and timestamp to the beginning of the arrays
        array_unshift($currentActivities, $activity);
        array_unshift($currentTimes, $currentTime);

        // Update the activity log in the user table
        $updatedActivities = implode(',', $currentActivities);
        $updatedTimes = implode(',', $currentTimes);

        $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);
    }
}

// Perform the deletion
if ($stmt = $conn->prepare("DELETE FROM puskesmas WHERE idpuskesmas = ?")) {
    $stmt->bind_param("i", $idpuskesmas);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='puskesmas.php';</script>";
        } else {
            echo "<script>alert('Data tidak ditemukan atau tidak dapat dihapus!'); window.location.href='puskesmas.php';</script>";
        }
    } else {
        echo "DATA GAGAL DIHAPUS! Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ERROR: Could not prepare query: " . $conn->error;
}

$conn->close();
?>
