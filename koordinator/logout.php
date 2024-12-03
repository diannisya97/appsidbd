<?php
session_start();
ob_start(); // Start output buffering at the very beginning

require "../function.php"; // Include your database connection file

// Log out activity
if (isset($_POST['confirm'])) {
    // If logout is confirmed, log activity before destroying session
    date_default_timezone_set('Asia/Jakarta');
    $userId = $_SESSION['id']; // Get the logged-in user's ID
    $activity = "Logout"; // Activity description
    $currentTime = date('Y-m-d H:i:s'); // Current time of the activity

    // Retrieve the existing activity and time from the user table
    $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
    $result = mysqli_query($conn, $activityQuery);
    $data = mysqli_fetch_assoc($result);

    $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
    $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

    // Add the logout activity and timestamp to the beginning of the arrays
    array_unshift($currentActivities, $activity);
    array_unshift($currentTimes, $currentTime);

    // Update the activity log in the user table
    $updatedActivities = implode(',', $currentActivities);
    $updatedTimes = implode(',', $currentTimes);

    $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
    mysqli_query($conn, $updateQuery);

    // Destroy session and redirect to the homepage
    session_destroy();
    header('Location: ../index.php');
    exit();
}

if (isset($_POST['cancel'])) {
    // If logout is canceled, redirect to dashboard
    header('Location: dashboard.php'); // Replace with the correct page
    exit();
}

ob_end_flush(); // End output buffering
?>

<style>
    /* Reset default margin dan padding */
    body, html {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    /* Style untuk notifikasi */
    .notification {
        background: linear-gradient(135deg, #004aad 0%, #0072ff 100%); /* Warna biru pilihan */
        border-radius: 15px; /* Sudut melengkung */
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Bayangan lebih dalam */
        color: #fff; /* Warna teks putih */
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.4s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        max-width: 450px;
        margin: 200px auto;
        text-align: center;
    }

    .notification:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .notification h2 {
        margin: 0 0 15px 0;
        font-size: 24px;
        color: #fff;
    }

    .notification p {
        margin: 0 0 20px 0;
        font-size: 16px;
        color: rgba(255, 255, 255, 0.8);
    }

    .notification button {
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        margin: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        color: white;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .notification button[name="confirm"] {
        background-color: #d9534f; /* Merah */
    }

    .notification button[name="confirm"]:hover {
        background-color: #c9302c; /* Merah lebih gelap saat hover */
        transform: scale(1.05); /* Efek zoom saat hover */
    }

    .notification button[name="cancel"] {
        background-color: #5bc0de; /* Biru */
    }

    .notification button[name="cancel"]:hover {
        background-color: #31b0d5; /* Biru lebih gelap saat hover */
        transform: scale(1.05); /* Efek zoom saat hover */
    }
</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <?php include 'header.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="notification">
                <h2>Konfirmasi Logout</h2>
                <p>Apakah Anda yakin ingin logout?</p>
                <form method="post" action="">
                    <button type="submit" name="confirm">Ya, Logout</button>
                    <button type="submit" name="cancel">Batal</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
