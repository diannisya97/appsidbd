<?php
include 'function.php'; // Pastikan koneksi database

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $pdo->prepare("SELECT id, namapasien, rt, rw, desakelurahan FROM pasien WHERE namapasien LIKE :query");
    $stmt->execute(['query' => "%$query%"]);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($patients);
}
?>
