<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";

// Pastikan pengguna telah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['id'];

// Query untuk mengambil data pengguna berdasarkan ID
$query = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Jika pengguna tidak ditemukan, arahkan ke halaman error atau login
if (!$user) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan nama puskesmas
$query_puskesmas = "SELECT Nama_Puskesmas FROM puskesmas WHERE idpuskesmas = ?";
$stmt_puskesmas = $conn->prepare($query_puskesmas);
$stmt_puskesmas->bind_param("i", $user['idpuskesmas']);
$stmt_puskesmas->execute();
$result_puskesmas = $stmt_puskesmas->get_result();
$puskesmas = $result_puskesmas->fetch_assoc();
$nama_puskesmas = $puskesmas ? $puskesmas['Nama_Puskesmas'] : 'Puskesmas tidak ditemukan';

// Tampilkan pesan jika ada (setelah pengiriman form)
$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $message = '<div class="alert alert-success">Profil berhasil diperbarui!</div>';
} elseif (isset($_GET['status']) && $_GET['status'] == 'error') {
    $message = '<div class="alert alert-danger">Terjadi kesalahan saat memperbarui profil.</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Ganti dengan path ke stylesheet Anda -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Tambahkan CSS tambahan sesuai kebutuhan di sini */
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .container-fluid {
            max-width: 800px;
            margin: auto;
        }
        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
            padding: 20px;
            text-align: center;
        }
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-bottom: 15px;
            border: 2px solid #007bff;
        }
        .profile-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        table.form-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px;
        }
        table.form-table td {
            vertical-align: middle;
        }
        table.form-table label {
            font-weight: normal;
            color: #555;
            margin-bottom: 5px;
            font-size: 14px;
            display: block;
            text-align: left;
        }
        table.form-table input {
            padding: 8px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            transition: border-color 0.3s ease;
        }
        table.form-table input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            table.form-table {
                border-spacing: 5px;
            }
        }
        .input-group {
            display: flex;
            align-items: center;
        }
        .input-group .form-control {
            flex: 1;
            border-radius: 5px 0 0 5px;
        }
        .input-group .btn-outline-secondary {
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <br><br>
                <div class="card mb-4">
                    <div class="profile-header">
                        <div class="profile-photo">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($user['nama']); ?></h3>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        <form action="update_profile.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <table class="form-table">
                                <tr>
                                    <td><label for="nama">Nama:</label></td>
                                    <td><input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required></td>
                                </tr>
                                <tr>
                                    <td><label for="username">Username:</label></td>
                                    <td><input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required></td>
                                </tr>
                                <tr>
                                    <td><label for="contact">Kontak:</label></td>
                                    <td><input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" required></td>
                                </tr>
                                <tr>
                                    <td><label for="email">Email:</label></td>
                                    <td><input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></td>
                                </tr>
                                <tr>
                                    <td><label for="password">Password:</label></td>
                                    <td>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="idpuskesmas">Puskesmas:</label></td>
                                    <td><input type="text" class="form-control" id="idpuskesmas" value="<?php echo htmlspecialchars($nama_puskesmas); ?>" readonly></td>
                                </tr>
                                <tr>
                                    <td><label for="level">Level:</label></td>
                                    <td><input type="text" class="form-control" id="level" value="<?php echo htmlspecialchars($user['level']); ?>" readonly></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>
