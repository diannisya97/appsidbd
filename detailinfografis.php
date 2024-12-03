<?php
// Include file koneksi
include 'function.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data detail infografis
$stmt = $conn->prepare("SELECT * FROM infografis WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_assoc();

// Tutup koneksi
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infografis</title>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body */
        body {
            font-family: 'Arial', sans-serif;
            background: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        /* Container */
        .container {
            width: 90%;
            max-width: 1200px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #4e73df, #1d72b8);
            color: #fff;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #ffffff;
            color: #4e73df;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-button:hover {
            background-color: #4e73df;
            color: #fff;
        }

        /* Card */
        .card {
            padding: 20px;
            position: relative;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card-img:hover {
            transform: scale(1.1);
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .card-description {
            font-size: 1.2em;
            color: #555;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button class="back-button" onclick="window.history.back()">Kembali</button>
            <h1><?php echo htmlspecialchars($detail['judul']); ?></h1>
        </div>
        <div class="card">
            <img src="uploads/<?php echo htmlspecialchars($detail['foto1']); ?>" alt="Gambar Infografis" class="card-img">
            <div class="card-content">
                <p class="card-description"><?php echo htmlspecialchars($detail['deskripsi']); ?></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card');
            card.style.opacity = 0;
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = 1;
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
