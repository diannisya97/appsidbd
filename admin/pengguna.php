<?php
session_start();
require "../function.php";
require "penggunatambah.php";
require "../cek.php";
require "header.php";
?>
 <style>
        /* Card Header Styling */
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

        /* Modal Footer Styling */
        .modal-footer {
            display: flex;
            justify-content: center;
        }
        .modal-footer button {
            width: 350px;
            margin: 10px;
            border-radius: 8px;
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#datatablesSimple');
    table.addEventListener('click', function (event) {
        const row = event.target.closest('tr');
        if (row) {
            const link = row.querySelector('a');
            if (link) {
                const userId = new URL(link.href).searchParams.get('id');
                if (userId) {
                    window.location.href = `penggunalihat.php?id=${userId}`;
                }
            }
        }
    });
});

</script>

<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <br>
                <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item" data-bs-toggle="tooltip" title="Dashboard"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pengguna</li>
                    </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="header-title">Data Pengguna</div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-bs-toggle="tooltip" title="Tambah Data"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" data-bs-toggle="tooltip" title="Klik untuk melihat detail data">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No.</th>
                                    <th style="width: 20%;">Nama Pengguna</th>
                                    <th style="width: 20%;">Username</th>
                                    <th style="width: 15%;">Email</th>
                                    <th style="width: 15%;">Asal Fasyankes</th>
                                    <th style="width: 15%;">Level</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $ambildatauser = mysqli_query($conn, "SELECT u.*, p.Nama_Puskesmas FROM user u JOIN puskesmas p ON p.idpuskesmas = u.idpuskesmas");
                                while ($row = mysqli_fetch_array($ambildatauser)) {
                                    $nama = $row['nama'];
                                    $username = $row['username'];
                                    $contact = $row['contact'];
                                    $email = $row['email'];
                                    $namaPuskesmas = $row['Nama_Puskesmas'];
                                    $level = $row['level'];
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $nama; ?></td>
                                    <td><?php echo $username; ?></td>
                                    <td><?php echo $email; ?></td>
                                    <td><?php echo $namaPuskesmas; ?></td>
                                    <td><?php echo $level; ?></td>
                                    <td>
                                        <div>
                                            <a href="penggunahapus.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-bs-toggle="tooltip" title="Hapus Data"><i class="fas fa-trash"></i></a>
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
    <script>
        function showStep(step) {
            document.getElementById('step1').style.display = step === 1 ? 'block' : 'none';
            document.getElementById('step2').style.display = step === 2 ? 'block' : 'none';
        }

        function nextStep() {
            showStep(2);
        }

        function previousStep() {
            showStep(1);
        }

        document.addEventListener("DOMContentLoaded", function() {
            showStep(1);
        });
    </script>
</body>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">
                    <!-- Step 1 -->
                    <div id="step1">
                        Nama Pengguna <input name="nama" type="text" class="form-control" placeholder="Nama Lengkap" required> <br>
                        Nomor Kontak <input name="contact" type="text" class="form-control" placeholder="Nomor Kontak" required> <br>
                        Email <input name="email" type="email" class="form-control" placeholder="Email" required> <br>
                        Asal Fasyankes <select name="asalpkm" class="form-control">
                        <option value="">Pilih Puskesmas</option>
                            <?php
                            $ambilasalfasyankes = mysqli_query($conn, "SELECT idpuskesmas, Nama_Puskesmas FROM puskesmas");
                            while ($fetcharray = mysqli_fetch_array($ambilasalfasyankes)) {
                                $namafasyankes = $fetcharray['Nama_Puskesmas'];
                                $idpuskesmas = $fetcharray['idpuskesmas'];
                            ?>
                            <option value="<?php echo $idpuskesmas; ?>"><?php echo $namafasyankes; ?></option>
                            <?php
                            }
                            ?>
                        </select> <br>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn btn-primary" onclick="nextStep()" data-bs-toggle="tooltip" title="Ke Tahap Selanjutnya">Selanjutnya</button>
                            </div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step2" style="display: none;">
                        Username <input name="username" type="text" class="form-control" placeholder="Username" required> <br>
                        Kata Sandi <input name="password" type="password" class="form-control" placeholder="Kata Sandi" required> <br>
                        Level <select name="level" class="form-control">
                            <option value="">Pilih Level Penggguna</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="koordinator">Koordinator</option>
                        </select> <br>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="previousStep()" data-bs-toggle="tooltip" title="Ke Tahap Sebelumnya">Sebelumnya</button>
                        <button type="submit" class="btn btn-primary" name="tambahpengguna" data-bs-toggle="tooltip" title="Simpan Data">Simpan</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function nextStep() {
    // Get the values of each required field in Step 1
    const nama = document.getElementsByName("nama")[0].value;
    const contact = document.getElementsByName("contact")[0].value;
    const email = document.getElementsByName("email")[0].value;
    const asalpkm = document.getElementsByName("asalpkm")[0].value;
    
    // Check if any required field is empty
    if (!nama || !contact || !email || !asalpkm) {
        alert("Mohon isi semua data dengan lengkap. ");
        return; // Stop the function if any field is empty
    }

    // Hide Step 1 and show Step 2 if all fields are filled
    document.getElementById("step1").style.display = "none";
    document.getElementById("step2").style.display = "block";
}

function previousStep() {
    // Show Step 1 and hide Step 2 when going back
    document.getElementById("step1").style.display = "block";
    document.getElementById("step2").style.display = "none";
}
</script>
</html>
