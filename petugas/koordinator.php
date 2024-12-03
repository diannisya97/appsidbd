<?php
session_start();
require "../function.php";
require "koordinatortambah.php";
require "../cek.php";
require "header.php";
?>

<style>
    .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Aligns items to the left and right */
            font-weight: bold;
            font-size: 1.5em;
        }
    .card-header .header-title {
            text-align: center;
            flex: 1; /* Takes up remaining space */
        }
    .modal-footer {
        display: flex;
        justify-content: center;
    }
    .modal-footer button {
        width: 350px; /* Atur ukuran lebar tombol sesuai keinginan Anda */
        margin: 10px;
        border-radius: 5px; /* Membuat sudut tombol menjadi bundar */
        font-size: 16px; /* Atur ukuran font tombol */
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #007bff;
        color: white;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #4e73df;
        border-color: #004085;
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
        table tbody tr {
    cursor: pointer;
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
                    // Redirect ke halaman penggunalihat.php dengan ID yang diambil
                    window.location.href = `koordinatoredit.php?id=${userId}`;
                } else {
                    alert('ID Koordinator tidak valid.');
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
                        <li class="breadcrumb-item"><a href="dashboard.php" data-bs-toggle="tooltip" title="Dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Koordinator</li>
                    </ol>
                    <div class="card mb-4">
                    <div class="card-header">
                        <div class="header-title">Data Koordinator</div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-bs-toggle="tooltip" title="Tambah Data"><i class="fas fa-plus"></i></button></div>
                        <div class="card-body">
                            <table id="datatablesSimple" data-bs-toggle="tooltip" title="Klik edit data">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Koordinator</th>
                                        <th>Alamat</th>
                                        <th>Nomor Kontak</th>
                                        <th>Asal Fasyankes</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 1; // Inisialisasi di luar loop
                                    $idpuskesmasLogin = $_SESSION['idpuskesmas']; // Ambil ID puskesmas dari sesi pengguna yang login
                                    
                                    // Query untuk mengambil data koordinator sesuai dengan ID puskesmas login
                                    $query = "
                                        SELECT u.*, p.Nama_Puskesmas 
                                        FROM koordinator u 
                                        JOIN puskesmas p ON p.idpuskesmas = u.asalfasyankes 
                                        WHERE u.asalfasyankes = '$idpuskesmasLogin'
                                    ";
                                    $ambildatakoor = mysqli_query($conn, $query);
                                    
                                    while ($row = mysqli_fetch_array($ambildatakoor)) {
                                        $namakoor = $row['namakoor'];
                                        $alamat = $row['alamat'];
                                        $nokontak = $row['nokontak'];
                                        $namaPuskesmas = $row['Nama_Puskesmas']; // Nama puskesmas dari tabel puskesmas
                                        $id = $row['id']; // Pastikan 'id' adalah nama kolom yang benar dari tabel koordinator
                                    ?>
                                    <tr data-id="<?php echo $id; ?>">
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $namakoor; ?></td>
                                        <td><?php echo $alamat; ?></td>
                                        <td><?php echo $nokontak; ?></td>
                                        <td><?php echo $namaPuskesmas; ?></td>
                                        <td>
                                            <div>
                                                <a href="koordinatorhapus.php?id=<?php echo $id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-bs-toggle="tooltip" title="Hapus Data"><i class="fas fa-trash"></i></a>
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
    </div>
</body>
<!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Koordinator</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post"  onsubmit="return validateKoordinatorForm()">
                <div class="modal-body">
                    <!-- Notifikasi jika data tidak lengkap -->
                    <div id="notification" style="display:none; color: white; background-color: red; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                        Harap lengkapi semua data.
                    </div>
                    <label for="namakoor">Nama Koordinator</label>
                    <input type="text" id="namakoor" name="namakoor" class="form-control" placeholder="Nama Koordinator"><br>

                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" placeholder="Alamat"><br>

                    <label for="nokontak">Nomor Kontak</label>
                    <input type="text" id="nokontak" name="nokontak" class="form-control" placeholder="Nomor Kontak"><br>

                    <label for="asalfasyankes">Asal Fasyankes</label>
                    <select id="asalfasyankes" name="asalfasyankes" class="form-control">
                                                    <option value="">Pilih Puskesmas</option>
                                                    <?php
                                                    $idpuskesmas = $_SESSION['idpuskesmas'];
                                                    $ambilasalfasyankes = mysqli_query($conn, "SELECT * FROM puskesmas WHERE idpuskesmas = '$idpuskesmas'");
                                                    while ($fetcharray = mysqli_fetch_array($ambilasalfasyankes)) {
                                                        $namafasyankes = $fetcharray['Nama_Puskesmas'];
                                                        $idpuskesmas = $fetcharray['idpuskesmas'];
                                                        ?>
                                                        <option value="<?php echo $idpuskesmas; ?>"><?php echo $namafasyankes; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select><br>
                                                </div>
                <!-- Footer Modal di luar modal-body -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="tambahkoordinator" data-bs-toggle="tooltip" title="Simpan Data">Simpan</button>
                </div>
                </form>
                <!-- Modal footer -->
                
            </div>
        </div>
    </div>
    
<script>
// Fungsi untuk menampilkan atau menyembunyikan notifikasi
function showNotification(message) {
    const notification = document.getElementById("notification");
    notification.innerHTML = message; // Set pesan notifikasi
    notification.style.display = "block"; // Tampilkan notifikasi

    // Sembunyikan notifikasi setelah 3 detik
    setTimeout(() => {
        notification.style.display = "none";
    }, 3000);
}

// Fungsi validasi form Koordinator sebelum submit
function validateKoordinatorForm() {
    const namakoor = document.getElementById("namakoor").value.trim();
    const alamat = document.getElementById("alamat").value.trim();
    const nokontak = document.getElementById("nokontak").value.trim();
    const asalfasyankes = document.getElementById("asalfasyankes").value;

    // Cek apakah semua field telah diisi
    if (!namakoor || !alamat || !nokontak || asalfasyankes === "") {
        showNotification("Harap lengkapi semua data.");
        return false; // Batalkan submit jika tidak lengkap
    }

    document.getElementById("notification").style.display = "none"; // Sembunyikan notifikasi jika lengkap
    return true; // Lanjutkan submit
}
</script>
</html>
