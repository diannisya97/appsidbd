<?php
require "../function.php";
require "../cek.php"; // Ensure user has permissions

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambahinfografis'])) {
    // Sanitize and validate form inputs
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Handle file upload for foto1
    $uploadDir = '../uploads/';
    $foto1 = $_FILES['foto1']['name'];
    $foto1Temp = $_FILES['foto1']['tmp_name'];
    $foto1Path = $uploadDir . basename($foto1);

    // Check if the file is uploaded correctly
    if (move_uploaded_file($foto1Temp, $foto1Path)) {
        // Prepare and execute SQL query
        $stmt = $conn->prepare("INSERT INTO infografis (judul, deskripsi, foto1) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $judul, $deskripsi, $foto1);

        if ($stmt->execute()) {
            // Log the activity
            date_default_timezone_set('Asia/Jakarta');
            $userId = $_SESSION['id']; // ID user yang sedang login
            $activity = "Menambah infografis: $judul";
            $currentTime = date('Y-m-d H:i:s');

            // Retrieve previous activities and times
            $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
            $result = mysqli_query($conn, $activityQuery);
            $data = mysqli_fetch_assoc($result);

            $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
            $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

            // Add the new activity and time
            array_unshift($currentActivities, $activity);
            array_unshift($currentTimes, $currentTime);

            // Update user activity log
            $updatedActivities = implode(',', $currentActivities);
            $updatedTimes = implode(',', $currentTimes);

            $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
            mysqli_query($conn, $updateQuery);

            // Success: Show alert and redirect
            echo "<script>
                    alert('Infografis berhasil ditambahkan!');
                    window.location.href = 'infografis.php';
                  </script>";
            exit();
        } else {
            // Failure: Show alert and stay on the same page
            echo "<script>
                    alert('Gagal menambahkan infografis: " . $stmt->error . "');
                    window.location.href = 'infografis.php';
                  </script>";
        }
        $stmt->close();
    } else {
        // Failure: Show alert for file upload failure
        echo "<script>
                alert('Gagal mengunggah gambar.');
                window.location.href = 'infografis.php';
              </script>";
    }
}
?>
