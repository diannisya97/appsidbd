
<?php
session_start();
require "../function.php";
require "../cek.php";
require "header.php";
require "jentiktambah.php";
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
                        // Redirect ke halaman vektorlihat.php dengan ID yang diambil
                        window.location.href = `jentiklihat.php?id=${userId}`;
                    } else {
                        alert('ID Pemeriksaan Jentik tidak valid.');
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
                    <li class="breadcrumb-item active">Data Pemeriksaan Jentik</li>
                </ol>
                          <div class="card mb-4">
                           <div class="card-header">
                           <div class="header-title">Data Jentik Nyamuk</div>
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-bs-toggle="tooltip" title="Tambah Data"><i class="fas fa-plus"></i></button></div>
                            <div class="card-body" class="center-block">
                            <table id="datatablesSimple" data-bs-toggle="tooltip" title="Klik untuk edit data">
                                    <thead>
                                        <tr>
                                            <th>No.</th>                                            
                                            <th>Nama Koordinator</th>
                                            <th>Nama Pasien</th>
                                            <th>RT</th>
                                            <th>RW</th>
                                            <th>Desakelurahan</th> <!-- Ganti 'Kelurahan' menjadi 'Desakelurahan' -->
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; // Inisialisasi di luar loop
                                        $idpuskesmas = $_SESSION['idpuskesmas']; // Mendapatkan idpuskesmas dari session pengguna yang login

                                        // Query untuk mendapatkan data jentikperiksa dengan namapasien yang memiliki asalfasyankes yang sama dengan pengguna yang login
                                        $ambildataperiksa = mysqli_query($conn, "
                                            SELECT jp.*, k.namakoor AS nama_koordinator, p.namapasien AS nama_pemilik, p.rt, p.rw, p.desakelurahan 
                                            FROM jentikperiksa jp 
                                            JOIN koordinator k ON jp.namakoor = k.id 
                                            JOIN pasien p ON jp.namapemilik = p.id
                                            WHERE p.asalfasyankes = '$idpuskesmas'
                                        ");
                                        
                                        while ($row = mysqli_fetch_array($ambildataperiksa)) {
                                            $namapemilik = $row['nama_pemilik']; // Nama pemilik dari tabel pasien
                                            $namakoor = $row['nama_koordinator'];
                                            $RT = $row['rt']; // RT dari tabel pasien
                                            $RW = $row['rw']; // RW dari tabel pasien
                                            $desakelurahan = $row['desakelurahan']; // Desakelurahan dari tabel pasien
                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td> 
                                                <td><?php echo $namakoor; ?></td>
                                                <td><?php echo $namapemilik; ?></td> <!-- Menampilkan nama pasien -->
                                                <td><?php echo $RT; ?></td>
                                                <td><?php echo $RW; ?></td>
                                                <td><?php echo $desakelurahan; ?></td> <!-- Tampilkan desakelurahan -->
                                                <td>
                                                    <div>
                                                        <a href="jentikhapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-bs-toggle="tooltip" title="Hapus Data"><i class="fas fa-trash"></i></a>
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
    <style>
.dropdown-container {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    background-color: #fff;
    z-index: 1000;
}

.dropdown-menu div {
    padding: 8px;
    cursor: pointer;
}

.dropdown-menu div:hover {
    background-color: #f1f1f1;
}
</style>
<!-- The Modal -->
<div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tambah Periksa Jentik Nyamuk</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="post">
        <div class="modal-body">
          <div id="step1">
            <div class="mb-3">
              <label for="searchKoor">Nama Koordinator</label>
              <div class="dropdown-container">
                  <input type="text" id="searchKoor" name="namakoor" class="form-control" placeholder="Cari Koordinator..." autocomplete="off">
                  <div id="dropdownKoorMenu" class="dropdown-menu">
                      <!-- Daftar koordinator akan diisi oleh JavaScript -->
                  </div>
              </div>
              <input type="hidden" id="koordinatorId" name="idkoordinator">
          </div>
          <div class="mb-3">
              <label for="searchInput">Nama Pasien</label>
              <div class="dropdown-container">
                  <input type="text" id="searchInput" name="namapasien" class="form-control" placeholder="Cari Pasien..." autocomplete="off">
                  <div id="dropdownMenu" class="dropdown-menu">
                      <!-- Daftar pasien akan diisi oleh JavaScript -->
                  </div>
              </div>
              <input type="hidden" id="patientId" name="id">
          </div>
            <!-- Tambahkan ini di dalam modal-body -->
            <div class="mb-3">
                <label for="rt">RT</label>
                <input type="text" name="RT" id="rt" class="form-control" placeholder="RT" readonly>
            </div>
            <div class="mb-3">
                <label for="rw">RW</label>
                <input type="text" name="RW" id="rw" class="form-control" placeholder="RW" readonly>
            </div>
            <div class="mb-3">
                <label for="desakelurahan">Desa/Kelurahan</label>
                <input type="text" name="desakelurahan" id="desakelurahan" class="form-control" placeholder="Desa/Kelurahan" readonly>
            </div>
            <div class="mb-3">
              <label for="tanggalrekap" class="form-label">Tanggal Pemeriksaan</label>
              <input name="tanggalrekap" type="date" class="form-control" placeholder="Tanggal Pemeriksaan" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="nextStep" data-bs-toggle="tooltip" title="Ke Tahap Selanjutnya">Selanjutnya</button>
            </div>
          </div>

          <div id="step2" style="display: none;">
            <table class="table no-border-table">
              <tbody>
                <tr>
                  <td>Bak Mandi</td>
                  <td>
                    <input name="bakmandi" type="radio" value="ada" required> Ada
                    <input name="bakmandi" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Dispenser</td>
                  <td>
                    <input name="dispenser" type="radio" value="ada" required> Ada
                    <input name="dispenser" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Penampungan Hujan</td>
                  <td>
                    <input name="penampung" type="radio" value="ada" required> Ada
                    <input name="penampung" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Pot Bunga</td>
                  <td>
                    <input name="potbunga" type="radio" value="ada" required> Ada
                    <input name="potbunga" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Tempat Minum Hewan</td>
                  <td>
                    <input name="tempatminumhewan" type="radio" value="ada" required> Ada
                    <input name="tempatminumhewan" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Ban Bekas</td>
                  <td>
                    <input name="banbekas" type="radio" value="ada" required> Ada
                    <input name="banbekas" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Sampah</td>
                  <td>
                    <input name="sampah" type="radio" value="ada" required> Ada
                    <input name="sampah" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Pohon</td>
                  <td>
                    <input name="pohon" type="radio" value="ada" required> Ada
                    <input name="pohon" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
                <tr>
                  <td>Lainnya</td>
                  <td>
                    <input name="lainnya" type="radio" value="ada" required> Ada
                    <input name="lainnya" type="radio" value="tidak_ada" required> Tidak Ada
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="prevStep" data-bs-toggle="tooltip" title="Ke Tahap Sebelumnya">Sebelumnya</button>
              <button type="submit" class="btn btn-primary" name="tambahjentik" data-bs-toggle="tooltip" title="Simpan Data">Simpan</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Fungsi untuk menampilkan notifikasi sederhana
  function showNotification(message) {
    alert(message);  // Bisa diganti dengan modal atau elemen notifikasi
  }

  // Fungsi validasi untuk step 1
  function validateStep1() {
    // Cek apakah input Nama Koordinator dan Nama Pasien terisi
    const namaKoor = document.getElementById('searchKoor').value.trim();
    const namaPasien = document.getElementById('searchInput').value.trim();
    const tanggal = document.querySelector('input[name="tanggalrekap"]').value;

    if (!namaKoor || !namaPasien || !tanggal) {
      return false;
    }
    return true;
  }

  // Event Listener untuk tombol 'Selanjutnya'
  document.getElementById('nextStep').addEventListener('click', function () {
    if (!validateStep1()) {
      showNotification("Harap lengkapi semua data.");
      return;
    }
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
  });

  // Event Listener untuk tombol 'Sebelumnya'
  document.getElementById('prevStep').addEventListener('click', function () {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
  });
</script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Pilih opsi',
        allowClear: true
    });
});
</script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Pilih opsi',
        allowClear: true
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Koordinator
    var searchKoor = document.getElementById('searchKoor');
    var dropdownKoorMenu = document.getElementById('dropdownKoorMenu');
    var koordinatorIdInput = document.getElementById('koordinatorId');

    function loadKoordinators() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_koordinators.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                var koordinators = JSON.parse(this.responseText);
                var html = '';
                koordinators.forEach(function(koordinator) {
                    html += '<div class="dropdown-item" data-id="' + koordinator.id + '">' + koordinator.namakoor + '</div>';
                });
                dropdownKoorMenu.innerHTML = html;
            }
        };
        xhr.send();
    }

    loadKoordinators();

    searchKoor.addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var items = dropdownKoorMenu.querySelectorAll('div[data-id]');
        var visibleItems = false;

        items.forEach(function(item) {
            var text = item.textContent || item.innerText;
            if (text.toUpperCase().indexOf(filter) > -1) {
                item.style.display = '';
                visibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        dropdownKoorMenu.style.display = visibleItems ? 'block' : 'none';
    });

    dropdownKoorMenu.addEventListener('click', function(event) {
        var target = event.target;
        if (target.dataset.id) {
            searchKoor.value = target.textContent;
            koordinatorIdInput.value = target.dataset.id; // Set hidden ID value
            dropdownKoorMenu.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdownKoorMenu.contains(event.target) && event.target !== searchKoor) {
            dropdownKoorMenu.style.display = 'none';
        }
    });

// Event listeners for koordinator dropdown (as before)

    // Pasien
    var searchInput = document.getElementById('searchInput');
    var dropdownMenu = document.getElementById('dropdownMenu');
    var patientIdInput = document.getElementById('patientId');
    var rtInput = document.getElementById('rt'); // Ambil input RT
    var rwInput = document.getElementById('rw'); // Ambil input RW
    var desakelurahanInput = document.getElementById('desakelurahan'); // Ambil input Desa/Kelurahan

    function loadPatients() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_patient.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                var patients = JSON.parse(this.responseText);
                var html = '';
                patients.forEach(function(patient) {
                    html += '<div class="dropdown-item" data-id="' + patient.id + '">' + patient.namapasien + '</div>';
                });
                dropdownMenu.innerHTML = html;
            }
        };
        xhr.send();
    }

    loadPatients();

    searchInput.addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var items = dropdownMenu.querySelectorAll('div[data-id]');
        var visibleItems = false;

        items.forEach(function(item) {
            var text = item.textContent || item.innerText;
            if (text.toUpperCase().indexOf(filter) > -1) {
                item.style.display = '';
                visibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        dropdownMenu.style.display = visibleItems ? 'block' : 'none';
    });

    dropdownMenu.addEventListener('click', function(event) {
        var target = event.target;
        if (target.dataset.id) {
            var patientId = target.dataset.id;
            searchInput.value = target.textContent;
            patientIdInput.value = patientId; // Set hidden ID value

            // Fetch RT, RW, and DesaKelurahan values for the selected patient
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_patient_details.php?id=' + patientId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var patientDetails = JSON.parse(this.responseText);
                    rtInput.value = patientDetails.rt || ''; // Isi RT
                    rwInput.value = patientDetails.rw || ''; // Isi RW
                    desakelurahanInput.value = patientDetails.desakelurahan || ''; // Isi Desa/Kelurahan
                }
            };
            xhr.send();

            dropdownMenu.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdownMenu.contains(event.target) && event.target !== searchInput) {
            dropdownMenu.style.display = 'none';
        }
    });
});

</script>

</body>
</html>