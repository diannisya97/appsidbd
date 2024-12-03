<?php
session_start();
require "../function.php";
require "periksatambahfunction.php";
require "../cek.php";
require "header.php";
?>

<style>
    .step {
        display: none;
    }

    .step.active {
        display: block;
    }

    .form-control {
        padding: 5px;
        margin-bottom: 5px;
    }

    .btn {
        margin-top: 5px;
    }

    .progress-bar {
        text-align: center;
        line-height: 5px;
    }
</style>
<style>
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
.table-container {
    display: flex;
    justify-content: center;
}

.table {
    width: 100%;
    max-width: 1000px;
    margin: auto;
    border-collapse: collapse; /* Menghilangkan border di luar tabel */
}

.table td {
    padding: 10px;
    vertical-align: middle;
    border: none; /* Menghilangkan border pada sel */
}

.custom-table {
    width: 100%;
    max-width: 1000px;
    margin: auto;
    border-collapse: collapse; /* Menghilangkan border di luar tabel */
}

.custom-table td {
    padding: 10px;
    vertical-align: middle;
    border: none; /* Menghilangkan border pada sel */
}

.custom-table label {
    display: block;
    margin-bottom: 5px;
}


.custom-table-step3 {
    width: 100%;
    max-width: 1200px;
    border-collapse: collapse; /* Menghilangkan border di luar tabel */
    margin: auto;
}

.custom-table-step3 td {
    padding: 10px;
    vertical-align: middle;
    border: none; /* Menghilangkan border pada sel */
}

.custom-table-step3 label {
    display: block;
    margin-bottom: 5px;
}

</style>
<body class="sb-nav-fixed">
    <?php include 'nav.php'; ?>
        <?php include 'menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-5">
                    <h2>Tambah Data Pemeriksaan Pasien</h2>
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: 33%;" id="progressBar">
                            Langkah 1 dari 3
                        </div>
                    </div>
                    <form method="post" id="multiStepForm" enctype="multipart/form-data">
                        <!-- Step 1 -->
                        <div class="step step-1 active mb-4">
                            <h3>Informasi Pasien</h3>
                            <div class="table-container">
                                <table class="custom-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="searchInput">Nama Pasien</label>
                                                <div class="dropdown-container">
                                                    <input type="text" id="searchInput" name="namapasien" class="form-control" placeholder="Cari Pasien..." autocomplete="off" required>
                                                    <div id="dropdownMenu" class="dropdown-menu">
                                                        <!-- Daftar pasien akan diisi oleh JavaScript -->
                                                    </div>
                                                </div>
                                                <input type="hidden" id="patientId" name="namapasien" required>
                                            </td>
                                            <td>
                                                <label>Tanggal Pemeriksaan</label>
                                                <input name="tanggalperiksa" type="date" class="form-control" placeholder="Tanggal Pemeriksaan"  required>
                                            </td>
                                            <td>
                                                <label>Tanggal Gejala</label>
                                                <input name="tanggalgejala" type="date" class="form-control" placeholder="Tanggal Gejala Kasus Terjadi"  required>
                                            </td>
                                            <td>
                                                <label>Suhu Tubuh</label>
                                                <input name="suhutubuh" type="number" class="form-control" placeholder="Suhu Tubuh" required>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Mimisan</label>
                                                <select name="mimisan" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Nyeri Perut</label>
                                                <select name="nyeriperut" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Perasaan Sembuh</label>
                                                <select name="perasaansembuh" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                        <td>
                                                <label>Trombosit Turun</label>
                                                <select name="trombositturun" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Sakit Kepala</label>
                                                <select name="sakitkepala" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Muntah</label>
                                                <select name="muntah" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Nyeri Sendi</label>
                                                <select name="nyerisendi" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Mual</label>
                                                <select name="mual" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>PTKIE</label>
                                                <select name="ptkie" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Infeksi Tenggorokan</label>
                                                <select name="infeksitenggorok" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Sakit Bola Mata</label>
                                                <select name="sakitbolamata" class="form-control" required>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Lainnya</label>
                                                <input name="lainnya" type="text" class="form-control" placeholder="Keluhan Lainnya" value="Tidak Ada Keluhan" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="nextButton" class="btn btn-primary" onclick="nextStep()" data-bs-toggle="tooltip" title="Ke Tahap Selanjutnya">Selanjutnya</button>
                            </div>
                        </div>


                        <!-- Step 2 -->
                        <div class="step step-2 mb-4">
                            <h3>Pemeriksaan Lab Darah</h3>
                            <div class="table-container">
                            <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><label>Nilai IGGM</label></td>
                                            <td>
                                                <select name="iggm" class="form-control" required>
                                                    <option value="Positif">Positif</option>
                                                    <option value="Negatif">Negatif</option>
                                                </select>
                                            </td>
                                            <td><label>Nilai IGM</label></td>
                                            <td>
                                                <select name="igm" class="form-control" required>
                                                    <option value="Positif">Positif</option>
                                                    <option value="Negatif">Negatif</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label>Nilai NS-1</label></td>
                                            <td>
                                                <select name="ns1" class="form-control" required>
                                                    <option value="Positif">Positif</option>
                                                    <option value="Negatif">Negatif</option>
                                                </select>
                                            </td>
                                            <td><label>Nilai Trombosit</label></td>
                                            <td><input name="tombosit" type="number" class="form-control" placeholder="Nilai Trombosit" required></td>
                                        </tr>
                                        <tr>
                                            <td><label>Nilai Hematokrit</label></td>
                                            <td><input name="hematokrit" type="number" class="form-control" placeholder="Nilai Hematokrit" required></td>
                                            <td><label>Nilai Hemaglobin</label></td>
                                            <td><input name="hb" type="number" class="form-control" placeholder="Nilai Hemaglobin" required></td>
                                        </tr>
                                        <tr>
                                            <td><label>Nilai Leukosit</label></td>
                                            <td><input name="leukosit" type="number" class="form-control" placeholder="Nilai Leukosit" required></td>
                                            <td><label>Nilai Eritrosit</label></td>
                                            <td><input name="eritrosit" type="number" class="form-control" placeholder="Nilai Eritrosit" required></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="prevStep()" data-bs-toggle="tooltip" title="Ke Tahap Sebelumnya">Sebelumnya</button>
                                <button type="button" id="nextButton2" class="btn btn-primary" onclick="nextStep(2)" disabled data-bs-toggle="tooltip" title="Ke Tahap Selanjutnya">Selanjutnya</button>
                            </div>
                        </div>
                        <!-- Step 3 -->
                        <div class="step step-3">
                            <h3>Hasil Pemeriksaan Perawatan Sebelumnya</h3>
                            <div class="table-container">
                                <table class="custom-table-step3">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label>Pernah Rawat Inap</label>
                                                <select name="pernahranap" class="form-control">
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Nama Rumah Sakit</label>
                                                <input type="text" name="namars" class="form-control"  required>
                                            </td>
                                            <td>
                                                <label>Tanggal Rawat Inap</label>
                                                <input type="date" name="tanggalmasuk" class="form-control" required>
                                            </td>
                                            <td>
                                                <label>Ruang Rawat Inap</label>
                                                <input type="text" name="ruangrawat" class="form-control" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Nama RS Sebelumnya (Jika Pasien Rujukan)</label>
                                                <input type="text" id="namarssebelum" name="namarssebelum" class="form-control" required>
                                            </td>
                                            <td>
                                                <label>Status Pasien Keluar</label>
                                                <select name="statuspasienakhir" class="form-control" required>
                                                    <option value="Keluar Sehat">Keluar Sehat</option>
                                                    <option value="Keluar Mati">Keluar Mati</option>
                                                    <option value="Rujuk Ke RS Lain">Rujuk Ke RS Lain</option>
                                                    <option value="Pulang Dengan Permintaan Sendiri">Pulang Dengan Permintaan Sendiri</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label>Pemeriksaan Jentik Nyamuk</label>
                                                <select name="periksajentik" class="form-control" required>
                                                    <option value="Pernah">Pernah</option>
                                                    <option value="Tidak Pernah">Tidak Pernah</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for="searchInputKoor">Penanggung Jawab Pemeriksaan</label>
                                                <div class="dropdown-container">
                                                    <input type="text" id="searchInputKoor" name="pjpemeriksa" class="form-control" placeholder="Cari Koordinator..." autocomplete="off" required>
                                                    <div id="dropdownMenuKoor" class="dropdown-menu">
                                                        <!-- Daftar koordinator akan diisi oleh JavaScript -->
                                                    </div>
                                                </div>
                                                <input type="hidden" id="coordinatorId" name="pjpemeriksa"required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Diagnosis Laboratorium</label>
                                                <input type="text" name="diagnosislab" class="form-control" required>
                                            </td>
                                            <td>
                                                <label>Diagnosis Klinis</label>
                                                <input type="text" name="diagnosisklinis" class="form-control" required>
                                            </td>
                                            <td>
                                                <label for="tgl_keluar_perawatan">Tanggal Keluar Perawatan</label>
                                                <input type="date" name="tgl_keluar_perawatan" class="form-control" required>
                                            </td>
                                            <td>
                                                <label for="upload_file_kdrs">Upload File KDRS</label>
                                                <input type="file" name="upload_file_kdrs" class="form-control" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.jpg,.jpeg,.png,.gif" required>
                                            <td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="prevStep()" data-bs-toggle="tooltip" title="Ke Tahap Sebelumnya">Sebelumnya</button>
                                <button type="button" class="btn btn-info" onclick="cekDataLengkap()" data-bs-toggle="tooltip" title="Validasi Ketepatan Data Input">Validasi Data</button>
                                <button name="tambahperiksa" type="submit" class="btn btn-success" data-bs-toggle="tooltip" title="Simpan Data">Simpan Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
<script>
    // Function to check if all required fields are filled
    function validateStep2() {
        // Get all input fields and select elements in the form
        const requiredFields = document.querySelectorAll('.step-2 [required]');
        
        // Check if any required field is empty
        let allFilled = true;
        requiredFields.forEach(function(field) {
            if (field.value.trim() === '') {
                allFilled = false;
            }
        });

        // Enable or disable the 'Selanjutnya' button based on validation
        const nextButton = document.getElementById('nextButton2');
        nextButton.disabled = !allFilled;  // Disable if not all fields are filled
    }

    // Attach event listeners to all required fields to validate when changed
    document.querySelectorAll('.step-2 [required]').forEach(function(field) {
        field.addEventListener('input', validateStep2);
    });

    // Initial validation on page load
    validateStep2();
</script>
<script>
    // Function to validate if all required fields are filled in Step 1
function validateStep1() {
    let isValid = true;

    // Get all required input fields in step 1
    const requiredFields = document.querySelectorAll('.step-1 [required]');

    // Check each required field
    requiredFields.forEach(field => {
        if (!field.value) {
            isValid = false;
        }
    });

    return isValid;
}

// Function to handle the "Selanjutnya" button click
function nextStep() {
    // Check if Step 1 is valid
    if (validateStep1()) {
        // If valid, proceed to the next step
        alert("Step 1 is valid, proceeding to the next step.");
        // Add code here to move to the next step (e.g., hide step 1 and show step 2)
    } else {
        // If not valid, show an alert and prevent moving to the next step
        alert("Please fill in all required fields before proceeding.");
    }
}

// Initially disable the "Selanjutnya" button
document.getElementById('nextButton').disabled = !validateStep1();

// Add event listener to enable/disable the button when fields change
const fields = document.querySelectorAll('.step-1 [required]');
fields.forEach(field => {
    field.addEventListener('input', function() {
        document.getElementById('nextButton').disabled = !validateStep1();
    });
});

</script>
<script>
    function cekDataLengkap() {
    // Mendapatkan semua input dan select dalam form
    var inputs = document.querySelectorAll('#multiStepForm input, #multiStepForm select');
    var semuaDataLengkap = true;

    inputs.forEach(function(input) {
        // Cek jika elemen memiliki atribut 'required' dan tidak kosong
        if (input.hasAttribute('required') && (input.value === "" || input.value === null)) {
            semuaDataLengkap = false;
            // Menambahkan peringatan jika input belum terisi
            input.style.borderColor = "red";  // Menandai input yang tidak terisi
        } else {
            input.style.borderColor = "";  // Reset border jika input sudah terisi
        }
    });

    // Jika semua data lengkap, aktifkan tombol submit dan beri peringatan jika ada yang kosong
    if (semuaDataLengkap) {
        alert("Semua data lengkap. Data bisa disimpan.");
        document.querySelector("button[type='submit']").disabled = false;  // Mengaktifkan tombol submit
    } else {
        alert("Ada data yang belum lengkap. Silakan periksa kembali.");
        document.querySelector("button[type='submit']").disabled = true;  // Menonaktifkan tombol submit
    }
}

</script>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
    var namarssebelumInput = document.getElementById('namarssebelum');
    
    // Jika input kosong, set value menjadi '-'
    if (namarssebelumInput.value.trim() === '') {
        namarssebelumInput.value = '-';
    }
});
</script>
<script>
    let currentStep = 1;

    function showStep(step) {
        document.querySelector(`.step-${currentStep}`).classList.remove('active');
        document.querySelector(`.step-${step}`).classList.add('active');
        document.getElementById('progressBar').style.width = `${(step - 1) * 50}%`;
        document.getElementById('progressBar').textContent = `Langkah ${step} dari 3`;
        currentStep = step;
    }

    function nextStep() {
        if (currentStep < 3) showStep(currentStep + 1);
    }

    function prevStep() {
        if (currentStep > 1) showStep(currentStep - 1);
    }

    document.addEventListener('DOMContentLoaded', () => {
        showStep(1);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInput');
    var dropdownMenu = document.getElementById('dropdownMenu');
    var patientIdInput = document.getElementById('patientId');

    // Fungsi untuk memuat daftar pasien dari server
    function loadPatients() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_patients.php', true); // Ganti dengan URL endpoint yang benar
        xhr.onload = function() {
            if (this.status === 200) {
                var patients = JSON.parse(this.responseText);
                var html = '';
                patients.forEach(function(patient) {
                    // Tampilkan nama pasien dan simpan ID-nya di atribut data-id
                    html += '<div data-id="' + patient.id + '">' + patient.namapasien + '</div>';
                });
                dropdownMenu.innerHTML = html; // Isi dropdown dengan data pasien
            } else {
                console.error('Gagal memuat pasien:', this.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request error...');
        };
        xhr.send();
    }

    // Panggil fungsi untuk memuat pasien
    loadPatients();

    // Event listener untuk pencarian pasien
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

        // Tampilkan dropdown jika ada item yang cocok
        dropdownMenu.style.display = visibleItems ? 'block' : 'none';
    });

    // Event listener untuk pemilihan pasien dari dropdown
    dropdownMenu.addEventListener('click', function(event) {
        var target = event.target;
        if (target.dataset.id) {
            searchInput.value = target.textContent; // Tampilkan nama pasien di input pencarian
            patientIdInput.value = target.dataset.id; // Set ID pasien ke input tersembunyi
            console.log("ID Pasien Terpilih:", patientIdInput.value); // Debugging: Cek ID pasien
            dropdownMenu.style.display = 'none'; // Sembunyikan dropdown
        }
    });

    // Event listener untuk menutup dropdown jika klik di luar dropdown atau search input
    document.addEventListener('click', function(event) {
        if (!dropdownMenu.contains(event.target) && event.target !== searchInput) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Event listener untuk menangani tombol Enter pada input pencarian
    searchInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            var selectedItem = dropdownMenu.querySelector('div:not([style*="display: none"])');
            if (selectedItem) {
                searchInput.value = selectedItem.textContent; // Tampilkan nama pasien di input pencarian
                patientIdInput.value = selectedItem.dataset.id; // Set ID pasien ke input tersembunyi
                console.log("ID Pasien Terpilih via Enter:", patientIdInput.value); // Debugging: Cek ID pasien
                dropdownMenu.style.display = 'none'; // Sembunyikan dropdown
            }
            event.preventDefault(); // Mencegah pengiriman form saat menekan Enter
        }
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInputKoor = document.getElementById('searchInputKoor');
    var dropdownMenuKoor = document.getElementById('dropdownMenuKoor');
    var coordinatorIdInput = document.getElementById('coordinatorId');

    // Fungsi untuk memuat daftar koordinator dari server
    function loadCoordinators() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_koordinators.php', true); // Ganti dengan URL endpoint yang benar
        xhr.onload = function() {
            if (this.status === 200) {
                var coordinators = JSON.parse(this.responseText);
                var html = '';
                coordinators.forEach(function(coordinator) {
                    // Tampilkan nama koordinator dan simpan ID-nya di atribut data-id
                    html += '<div data-id="' + coordinator.id + '">' + coordinator.namakoor + '</div>';
                });
                dropdownMenuKoor.innerHTML = html; // Isi dropdown dengan data koordinator
            } else {
                console.error('Gagal memuat koordinator:', this.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request error...');
        };
        xhr.send();
    }

    // Panggil fungsi untuk memuat koordinator
    loadCoordinators();

    // Event listener untuk pencarian koordinator
    searchInputKoor.addEventListener('input', function() {
        var filter = this.value.toUpperCase();
        var items = dropdownMenuKoor.querySelectorAll('div[data-id]');
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

        // Tampilkan dropdown jika ada item yang cocok
        dropdownMenuKoor.style.display = visibleItems ? 'block' : 'none';
    });

    // Event listener untuk pemilihan koordinator dari dropdown
    dropdownMenuKoor.addEventListener('click', function(event) {
        var target = event.target;
        if (target.dataset.id) {
            searchInputKoor.value = target.textContent; // Tampilkan nama koordinator di input pencarian
            coordinatorIdInput.value = target.dataset.id; // Set ID koordinator ke input tersembunyi
            console.log("ID Koordinator Terpilih:", coordinatorIdInput.value); // Debugging: Cek ID koordinator
            dropdownMenuKoor.style.display = 'none'; // Sembunyikan dropdown
        }
    });

    // Event listener untuk menutup dropdown jika klik di luar dropdown atau search input
    document.addEventListener('click', function(event) {
        if (!dropdownMenuKoor.contains(event.target) && event.target !== searchInputKoor) {
            dropdownMenuKoor.style.display = 'none';
        }
    });

    // Event listener untuk menangani tombol Enter pada input pencarian
    searchInputKoor.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            var selectedItem = dropdownMenuKoor.querySelector('div:not([style*="display: none"])');
            if (selectedItem) {
                searchInputKoor.value = selectedItem.textContent; // Tampilkan nama koordinator di input pencarian
                coordinatorIdInput.value = selectedItem.dataset.id; // Set ID koordinator ke input tersembunyi
                console.log("ID Koordinator Terpilih via Enter:", coordinatorIdInput.value); // Debugging: Cek ID koordinator
                dropdownMenuKoor.style.display = 'none'; // Sembunyikan dropdown
            }
            event.preventDefault(); // Mencegah pengiriman form saat menekan Enter
        }
    });
});
</script>