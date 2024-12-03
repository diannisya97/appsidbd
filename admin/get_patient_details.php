<?php
require '../function.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "SELECT rt, rw, desakelurahan FROM pasien WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
        $stmt->close();
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
