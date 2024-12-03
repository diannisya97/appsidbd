<?php
session_start();
require "function.php";

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

$error_message = ""; // Variabel untuk menyimpan pesan error

// ceklogin untuk masuk ke dalam sistem 
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Username dan Password tidak boleh kosong!";
    } else {
        // cek ke dalam database
        $cekdatabase = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");
        $hitungdata = mysqli_num_rows($cekdatabase);

        if ($hitungdata > 0) {
            $data = mysqli_fetch_assoc($cekdatabase);
            $_SESSION['log'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['id'] = $data['id'];
            $_SESSION['idpuskesmas'] = $data['idpuskesmas'];
            $_SESSION['level'] = $data['level'];

            // Catat aktivitas login
            $userId = $data['id'];
            $activity = "Login ke sistem";
            $currentTime = date('Y-m-d H:i:s'); // Format waktu Indonesia

            // Ambil data aktivitas dan waktu saat ini dari database
            $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
            $currentTimestamps = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

            // Filter aktivitas yang masih dalam rentang waktu 24 jam
            $filteredActivities = [];
            $filteredTimestamps = [];
            foreach ($currentTimestamps as $index => $timestamp) {
                if ((time() - strtotime($timestamp)) <= 86400) { // 86400 detik = 24 jam
                    $filteredActivities[] = $currentActivities[$index];
                    $filteredTimestamps[] = $timestamp;
                }
            }

            // Tambahkan aktivitas baru
            array_unshift($filteredActivities, $activity);
            array_unshift($filteredTimestamps, $currentTime);

            // Batasi hingga 30 entri
            $filteredActivities = array_slice($filteredActivities, 0, 50);
            $filteredTimestamps = array_slice($filteredTimestamps, 0, 50);

            // Gabungkan kembali menjadi string
            $updatedActivities = implode(',', $filteredActivities);
            $updatedTimestamps = implode(',', $filteredTimestamps);

            // Perbarui tabel user
            $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimestamps' WHERE id = $userId";
            mysqli_query($conn, $updateQuery);

            // Redirect berdasarkan level user
            if ($data['level'] == 'admin') {
                header('location:admin/dashboard.php');
            } elseif ($data['level'] == 'petugas') {
                header('location:petugas/dashboard.php');
            } elseif ($data['level'] == 'koordinator') {
                header('location:koordinator/dashboard.php');
            } else {
                header('location:login.php');
            }
        } else {
            $error_message = "Username atau Password salah!";
        }
    }
}

// Jika belum login, biarkan melihat halaman login
if (!isset($_SESSION['log'])) {
    // Tidak ada tindakan, biarkan pengguna melihat halaman login
} else {
    // Redirect berdasarkan session level
    if ($_SESSION['level'] == 'admin') {
        header('location:admin/dashboard.php');
    } elseif ($_SESSION['level'] == 'petugas') {
        header('location:petugas/dashboard.php');
    } elseif ($_SESSION['level'] == 'koordinator') {
        header('location:koordinator/dashboard.php');
    } else {
        header('location:login.php');
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>APP-SIDBD - Login</title>
    <link rel="icon" href="img/kiri.png">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background: linear-gradient(to right, #007bff, #6610f2);
    font-family: 'Poppins', sans-serif;
}

#layoutAuthentication {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    padding: 20px;
}

.card {
    width: 100%;
    max-width: 450px; /* Adjust for smaller screens */
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    overflow: hidden;
    background-color: #ffffff;
    border: none;
}

.card-header {
    text-align: center;
    padding: 1.5rem;
    background-color: #343a40;
    color: white;
    position: relative;
}

.card-header img {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;
}

.card-header h3 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 600;
    color: #f8f9fa;
}

.card-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 400;
    color: #adb5bd;
}

.card-body {
    padding: 1.5rem;
    background-color: #ffffff;
}

.form-floating input {
    padding: 0.75rem;
    height: auto;
    border-radius: 5px;
    background-color: #f8f9fa;
    color: #495057;
    border: 1px solid #ced4da;
}

.form-floating label {
    color: #6c757d;
}

.form-check-label {
    color: #495057;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.small a {
    color: #495057;
}

.small a:hover {
    color: #007bff;
}

/* Media queries for mobile view */
@media (max-width: 576px) {
    .card {
        max-width: 100%; /* Make the card full width */
        margin: 0 10px;
    }

    .card-header img {
        width: 50px;
        height: 50px;
    }

    .card-header h3 {
        font-size: 1.5rem; /* Adjust heading size */
    }

    .card-header h4 {
        font-size: 1rem;
    }

    .form-floating input {
        padding: 0.5rem; /* Reduce padding for inputs */
    }

    .card-body {
        padding: 1rem; /* Adjust padding */
    }

    .btn-primary {
        padding: 8px;
        font-size: 0.9rem; /* Reduce button size */
    }
}

    </style>
</head>
<body>
<?php if (!empty($error_message)) : ?>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Login Gagal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= htmlspecialchars($error_message); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div id="layoutAuthentication">
    <div class="card">
        <div class="card-header">
            <a href="index.php" class="btn btn-light" style="position: absolute; left: 15px; top: 15px;">
                <i class="fas fa-home"></i> Home
            </a>
            <img src="img/kiri.png" alt="Logo">
            <h3 class="text-center">Aplikasi Sistem Informasi Demam Berdarah</h3>
            <h4 class="text-center">Silahkan masuk</h4>
        </div>

        <div class="card-body">
            <form method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" name="username" id="username" type="text" placeholder="username" />
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Password" />
                    <label for="password">Password</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                    <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                    <button class="btn btn-primary" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($error_message)) : ?>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php endif; ?>
    });
</script>

</body>
</html>
