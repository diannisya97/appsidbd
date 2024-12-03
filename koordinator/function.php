<?php
session_start();
include '../function.php';

echo "<style>
    /* Modal Table Custom Styles */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 16px;
        font-family: 'Arial', sans-serif;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .custom-table thead {
        background-color: #4e73df;
        color: #fff;
        text-align: left;
        font-weight: bold;
    }

    .custom-table th, .custom-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .custom-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .custom-table tbody tr td:last-child {
        text-align: center;
        font-weight: bold;
        color: #4e73df;
    }

    .no-history {
        font-style: italic;
        color: #6c757d;
        text-align: center;
        margin: 20px 0;
    }
</style>";

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $query = "SELECT activity, created_at FROM user WHERE id = $userId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if (!empty($row['activity'])) {
            echo "<table class='custom-table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Keterangan/Aktivitas</th>";
            echo "<th>Tanggal & Jam</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $activities = explode(',', $row['activity']);
            $createdTimes = explode(',', $row['created_at']);
            $count = min(count($activities), count($createdTimes));

            for ($i = 0; $i < $count; $i++) {
                $activity = htmlspecialchars($activities[$i]);
                $createdTime = $createdTimes[$i];
                $formattedDate = date('d-m-Y H:i', strtotime($createdTime));

                echo "<tr>";
                echo "<td>{$activity}</td>";
                echo "<td>{$formattedDate}</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='no-history'>Tidak ada histori untuk ditampilkan.</p>";
        }
    } else {
        echo "<p class='text-danger'>Terjadi kesalahan saat mengambil data.</p>";
    }
} else {
    echo "<p class='text-danger'>Anda belum login.</p>";
}
?>
