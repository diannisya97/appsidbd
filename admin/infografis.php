<?php
session_start();
require "../function.php";
require "infografistambah.php";
require "../cek.php";
require "header.php";
?>
<style>
    .radio-options {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-left: 100px;
    }

    .radio-options input[type="radio"] {
        margin-right: -10px;
    }

    .radio-options label {
        margin-right: 30px;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: bold;
        font-size: 1.5em;
    }

    .card-header .header-title {
        text-align: center;
        flex: 1;
    }

    .modal-footer {
        display: flex;
        justify-content: center;
    }

    .modal-footer button {
        width: 350px;
        margin: 10px;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff; /* White background */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Enhanced shadow for depth */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
            font-size: 0.95em; /* Slightly smaller font for a refined look */
        }
        table thead {
            background-color: #343a40; /* Dark grey background for header */
            color: #ffffff; /* White text */
            text-align: left;
            font-size: 1.1em; /* Larger font size for headers */
        }
        table th, table td {
            padding: 15px 20px; /* Increased padding for better spacing */
            border-bottom: 1px solid #dee2e6;
        }
        table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease; /* Smooth transition */
        }
        table tbody tr:nth-child(even) {
            background-color: #f8f9fc; /* Light blue-grey for alternate rows */
        }
        table tbody tr:hover {
            background-color: #e2e6ea; /* Slightly darker hover effect */
            transform: scale(1.02); /* Subtle scaling effect */
            cursor: pointer;
        }

        /* Plus Icon Button Styling */
        .card-header .btn-primary {
            background-color: #4e73df; /* Warna biru gelap */
            border-color: #4e73df; /* Warna biru gelap */
        }

        .card-header .btn-primary:hover {
            background-color: #2e59d9; /* Warna biru lebih gelap saat hover */
            border-color: #2653a0;
        }

</style>
<style>
    table#datatablesSimple {
        width: 100%;
        border-collapse: collapse;
    }
    table#datatablesSimple th, table#datatablesSimple td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    /* Mengatur lebar spesifik untuk setiap kolom */
    table#datatablesSimple th:nth-child(1), 
    table#datatablesSimple td:nth-child(1) {
        width: 50px;
    }
    table#datatablesSimple th:nth-child(2), 
    table#datatablesSimple td:nth-child(2) {
        width: 200px;
        text-align: left; /* Rata kiri untuk kolom Judul */
    }
    table#datatablesSimple th:nth-child(3), 
    table#datatablesSimple td:nth-child(3) {
        width: 300px;
        text-align: left; /* Rata kiri untuk kolom Deskripsi */
    }
    table#datatablesSimple th:nth-child(4), 
    table#datatablesSimple td:nth-child(4),
    table#datatablesSimple th:nth-child(5), 
    table#datatablesSimple td:nth-child(5),
    table#datatablesSimple th:nth-child(6), 
    table#datatablesSimple td:nth-child(6) {
        width: 100px;
    }
    table#datatablesSimple th:nth-child(7), 
    table#datatablesSimple td:nth-child(7) {
        width: 150px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('#datatablesSimple');

        table.addEventListener('click', function (event) {
            const row = event.target.closest('tr');
            
            if (row) {
                const link = row.querySelector('a');
                
                if (link) {
                    // Ambil parameter ID dari URL tautan
                    const userId = new URL(link.href).searchParams.get('id');
                    
                    if (userId) {
                        // Redirect ke halaman vektorlihat.php dengan ID yang diambil
                        window.location.href = `infografisedit.php?id=${userId}`;
                    } else {
                        alert('ID infografis tidak valid.');
                    }
                }
            }
        });
    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <br>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Infografis</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="header-title">Data Infografis</div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th style="width: 50px !important;">No.</th>
                                    <th style="width: 200px !important;">Judul</th>
                                    <th style="width: 300px !important;">Deskripsi</th>
                                    <th style="width: 100px !important;">Foto</th>
                                    <th style="width: 150px !important;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $ambildatainfografis = mysqli_query($conn, "SELECT * FROM infografis");
                                while ($row = mysqli_fetch_array($ambildatainfografis)) {
                                    $judul = $row['judul'];
                                    $deskripsi = $row['deskripsi'];
                                    $foto1 = $row['foto1'];
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $judul; ?></td>
                                    <td><?php echo $deskripsi; ?></td>
                                    <td><img src="../uploads/<?php echo $foto1; ?>" style="width: 90px; max-width: 100%; height: auto;"></td>
                                    <td>
                                        <div>
                                            <a href="infografishapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Infografis</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    Judul Infografis
                    <input type="text" name="judul" class="form-control" required><p>
                    Deskripsi
                    <textarea name="deskripsi" class="form-control" required></textarea><p>
                    Foto 1
                    <input type="file" name="foto1" class="form-control" required><p>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="tambahinfografis">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</html>
