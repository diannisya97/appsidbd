<?php
session_start();
require "../function.php";
require "penggunatambah.php";
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
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <br>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="header-title">Data Pengguna</div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fas fa-plus"></i></button></div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Pengguna</th>
                                        <th>Nomor Kontak</th>
                                        <th>Email</th>
                                        <th>Asal Fasyankes</th>
                                        <th>Level</th> <!-- Kolom Level -->
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1; // Inisialisasi di luar loop
                                    $idpuskesmasLogin = $_SESSION['idpuskesmas']; // Ambil ID puskesmas dari sesi pengguna yang login
                                    
                                    // Query untuk mengambil data user sesuai dengan ID puskesmas login
                                    $query = "
                                        SELECT u.*, p.Nama_Puskesmas 
                                        FROM user u 
                                        JOIN puskesmas p ON p.idpuskesmas = u.idpuskesmas 
                                        WHERE u.idpuskesmas = '$idpuskesmasLogin'
                                    ";
                                    $ambildatauser = mysqli_query($conn, $query);
                                    
                                    while ($row = mysqli_fetch_array($ambildatauser)) {
                                        $nama = $row['nama'];
                                        $username = $row['username'];
                                        $contact = $row['contact'];
                                        $email = $row['email'];
                                        $namaPuskesmas = $row['Nama_Puskesmas']; // Nama puskesmas dari tabel puskesmas
                                        $level = $row['level']; // Level pengguna
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($nama); ?></td>
                                        <td><?php echo htmlspecialchars($contact); ?></td>
                                        <td><?php echo htmlspecialchars($email); ?></td>
                                        <td><?php echo htmlspecialchars($namaPuskesmas); ?></td> <!-- Menampilkan nama puskesmas -->
                                        <td><?php echo htmlspecialchars($level); ?></td> <!-- Menampilkan level pengguna -->
                                        <td>
                                            <div>
                                                <a href="penggunalihat.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                                <a href="penggunaedit.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                                                <a href="penggunahapus.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="fas fa-trash"></i></a>
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
                        </select> <br>
                            <div class="modal-footer"><button type="button" class="btn btn-primary" onclick="nextStep()">Selanjutnya</button></div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step2" style="display: none;">
                        Username <input name="username" type="text" class="form-control" placeholder="Username" required> <br>
                        Kata Sandi <input name="password" type="password" class="form-control" placeholder="Kata Sandi" required> <br>
                        Level <select name="level" class="form-control">
                            <option value="">Pilih Level Penggguna</option>
                            <option value="petugas">Petugas</option>
                        </select> <br>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="previousStep()">Sebelumnya</button>
                        <button type="submit" class="btn btn-primary" name="tambahpengguna">Simpan</button></div>
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
