<?php
session_start();
require "../function.php";
require "../cek.php";
require "pasientambah.php";
require "header.php";
?>

<style>
    #step1, #step2 {
        margin-bottom: 15px;
    }
</style>

<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Pasien Kasus Demam Berdarah</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Pasien</button>
                    <br>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Pasien</th>
                                        <th>Alamat</th>
                                        <th>Desa / Kelurahan</th>
                                        <th>Nomor Kontak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $idpuskesmas = $_SESSION['idpuskesmas'];
                                    $ambildatakoor = mysqli_query($conn, "SELECT * FROM pasien WHERE asalfasyankes = '$idpuskesmas'");
                                    while ($row = mysqli_fetch_array($ambildatakoor)) {
                                        $namapasien = $row['namapasien'];
                                        $alamat = $row['alamat'];
                                        $desakelurahan = $row['desakelurahan'];
                                        $nokontak = $row['nokontak'];
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $namapasien; ?></td>
                                            <td><?php echo $alamat; ?></td>
                                            <td><?php echo $desakelurahan; ?></td>
                                            <td><?php echo $nokontak; ?></td>
                                            <td>
                                                <div>
                                                    <a href="pasienlihat.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                                    <a href="pasienedit.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                                                    <a href="pasienhapus.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus Pasien ini?');"><i class="fas fa-trash"></i></a>
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
                  <!-- The Modal -->
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Tambah Pasien</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                <form method="POST" action="pasientambah.php">
                                    <div class="modal-body">
                                        <!-- Langkah 1: Informasi Pasien dan Asal Fasyankes -->
                                        <div id="step1">
                                            <div class="mb-3">
                                                Nama Pasien <input name="namapasien" type="text" class="form-control" placeholder="Nama Pasien" required> 
                                            </div>
                                            <div class="mb-3">
                                                NIK <input name="nik" type="text" class="form-control" placeholder="NIK" required> 
                                            </div>
                                            <div class="mb-3">
                                                Tanggal Lahir <input type="date" name="tanggallahir" class="form-control" placeholder="Tanggal Lahir Pasien" required>
                                            </div>
                                            <div class="mb-3">
                                                Jenis Kelamin 
                                                <select name="jeniskelamin" class="form-control" aria-label="Default select example">
                                                    <option value="Laki Laki">Laki Laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                Nomor Kontak <input name="nokontak" type="text" class="form-control" placeholder="Nomor Kontak" required>
                                            </div>
                                            <div class="mb-3">
                                                Asal Fasyankes 
                                                <select name="asalfasyankes" class="form-control">
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
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                                        </div>

                                        <!-- Langkah 2: Informasi Alamat -->
                                        <div id="step2" style="display:none;">
                                            <div class="mb-3">
                                                Alamat <input name="alamat" type="text" class="form-control" placeholder="Alamat" required>
                                            </div>
                                            <div class="mb-3">
                                                RT/RW <input name="rtrw" type="text" class="form-control" placeholder="RT/RW" required>
                                            </div>
                                            <div class="mb-3">
                                                Desa/Kelurahan <input name="desakelurahan" type="text" class="form-control" placeholder="Desa/Kelurahan" required>
                                            </div>
                                            <div class="mb-3">
                                                Kecamatan <input name="kecamatan" type="text" class="form-control" placeholder="Kecamatan" required>
                                            </div>
                                            <div class="mb-3">
                                                Kab/Kota 
                                                <select name="kabkota" class="form-control" aria-label="Default select example">
                                                    <option value="Kota Tasikmalaya">Kota Tasikmalaya</option>
                                                    <option value="Kabupaten Tasikmalaya">Kabupaten Tasikmalaya</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                Longitude <input name="longitude" id="longitude" type="text" class="form-control" placeholder="Longitude">
                                            </div>
                                            <div class="mb-3">
                                                Latitude <input name="latitude" id="latitude" type="text" class="form-control" placeholder="Latitude">
                                            </div>
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mapModal">Pilih Titik Lokasi Melalui Peta</button>
                                            </div>
                                            <button type="button" class="btn btn-secondary" id="prevStep">Previous</button>
                                            <button type="submit" class="btn btn-primary" name="tambahpasien">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Peta Modal -->
                    <div class="modal fade" id="mapModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Pilih Lokasi pada Peta</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <div id="map" style="height: 400px;"></div>
                                </div>
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="selectLocation">Pilih Lokasi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                      <!-- Peta Modal -->
                    <div class="modal fade" id="mapModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Pilih Lokasi pada Peta</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <div id="map" style="height: 400px;"></div>
                                </div>
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="selectLocation">Pilih Lokasi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextStepButton = document.getElementById('nextStep');
            const prevStepButton = document.getElementById('prevStep');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');

            nextStepButton.addEventListener('click', function() {
                step1.style.display = 'none';
                step2.style.display = 'block';
            });

            prevStepButton.addEventListener('click', function() {
                step1.style.display = 'block';
                step2.style.display = 'none';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextStepButton = document.getElementById('nextStep');
            const prevStepButton = document.getElementById('prevStep');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');

            nextStepButton.addEventListener('click', function() {
                step1.style.display = 'none';
                step2.style.display = 'block';
            });

            prevStepButton.addEventListener('click', function() {
                step1.style.display = 'block';
                step2.style.display = 'none';
            });

            // Initialize map
            const map = L.map('map').setView([-7.35, 108.22], 13); // Center map to Tasikmalaya with zoom level 13

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker;

            map.on('click', function(e) {
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
                document.getElementById('longitude').value = e.latlng.lng;
                document.getElementById('latitude').value = e.latlng.lat;
            });

            document.getElementById('selectLocation').addEventListener('click', function() {
                const lng = document.getElementById('longitude').value;
                const lat = document.getElementById('latitude').value;
                if (lng && lat) {
                    // Close the map modal
                    document.querySelector('#mapModal .btn-close').click();
                    // Re-open the pasien modal
                    const pasienModal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
                    pasienModal.show();
                } else {
                    alert('Silakan pilih lokasi pada peta.');
                }
            });
        });
    </script>
</body>
</html>
