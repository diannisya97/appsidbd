<?php
session_start();

require "header.php";
if (isset($_POST['confirm'])) {
    // Jika konfirmasi logout diberikan
    session_destroy();
    header('Location: login.php');
    exit();
}

if (isset($_POST['cancel'])) {
    // Jika pembatalan logout diberikan
    header('Location: login.php'); // Ganti dengan halaman yang sesuai
    exit();
}
?>
<style>
    .notification {
    text-align: center;
    padding: 50px;
    border: 1px solid #ddd;
    background-color: #f9f9f9;
    margin: 20px auto;
    max-width: 400px;
    border-radius: 8px;
}

.notification h2 {
    margin-bottom: 20px;
}

.notification p {
    margin-bottom: 20px;
}

.notification button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    margin: 5px;
    cursor: pointer;
    font-size: 16px;
}

.notification button[name="confirm"] {
    background-color: #d9534f;
    color: white;
}

.notification button[name="cancel"] {
    background-color: #5bc0de;
    color: white;
}

</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
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
</body>
</html>
