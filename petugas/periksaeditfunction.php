<?php
session_start();
require '../function.php';
require '../cek.php';

print_r($_POST); // Tambahkan ini untuk debug

$id = $_POST['id'];
$namapasien = $_POST['namapasien'];
$tanggalperiksa = $_POST['tanggalperiksa'];
$tanggalgejala = $_POST['tanggalgejala'];
$trombositturun = $_POST['trombositturun'];
$mimisan = $_POST['mimisan'];
$nyeriperut = $_POST['nyeriperut'];
$perasaansembuh = $_POST['perasaansembuh'];
$suhutubuh = $_POST['suhutubuh'];
$sakitkepala = $_POST['sakitkepala'];
$muntah = $_POST['muntah'];
$nyerisendi = $_POST['nyerisendi'];
$mual = $_POST['mual'];
$ptkie = $_POST['ptkie'];
$infeksitenggorok = $_POST['infeksitenggorok'];
$sakitbolamata = $_POST['sakitbolamata'];
$lainnya = $_POST['lainnya'];
$iggm = $_POST['iggm'];
$igm = $_POST['igm'];
$ns1 = $_POST['ns1'];
$tombosit = $_POST['tombosit'];
$hematokrit = $_POST['hematokrit'];
$hb = $_POST['hb'];
$leukosit = $_POST['leukosit'];
$eritrosit = $_POST['eritrosit'];
$pernahranap = $_POST['pernahranap'];
$namars = $_POST['namars'];
$tanggalmasuk = $_POST['tanggalmasuk'];
$ruangrawat = $_POST['ruangrawat'];
$namarssebelum = $_POST['namarssebelum'];
$statuspasienakhir = $_POST['statuspasienakhir'];
$periksajentik = $_POST['periksajentik'];
$pjpemeriksa = $_POST['pjpemeriksa'];

// Menambahkan variabel baru
$diagnosislab = $_POST['diagnosislab'];
$diagnosisklinis = $_POST['diagnosisklinis'];
$tgl_keluar_perawatan = $_POST['tgl_keluar_perawatan'];

// Menyiapkan variabel untuk file upload KDRS
$upload_dir = "../KDRS/";
$upload_file_kdrs = ""; // Inisialisasi variabel untuk menyimpan nama file

if (isset($_FILES["upload_file_kdrs"])) {
    $file_name = $_FILES["upload_file_kdrs"]["name"];
    $file_tmp = $_FILES["upload_file_kdrs"]["tmp_name"];
    $file_size = $_FILES["upload_file_kdrs"]["size"];
    $file_error = $_FILES["upload_file_kdrs"]["error"];

    if ($file_error === 0) {
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_new_name = uniqid('KDRS_', true) . '.' . $file_ext;
        $file_destination = $upload_dir . $file_new_name;

        if (move_uploaded_file($file_tmp, $file_destination)) {
            $upload_file_kdrs = $file_new_name; // Simpan nama file ke variabel
        } else {
            echo "Gagal mengupload file KDRS.";
        }
    } else {
        echo "Terjadi kesalahan dalam mengupload file KDRS.";
    }
}

// Menampilkan ID untuk debugging
echo "ID: $id";

// Menyiapkan query untuk memperbarui data
$query = "
    UPDATE periksapasien SET
        namapasien = '$namapasien',
        tanggalperiksa = '$tanggalperiksa',
        tanggalgejala = '$tanggalgejala',
        trombositturun = '$trombositturun',
        mimisan = '$mimisan',
        nyeriperut = '$nyeriperut',
        perasaansembuh = '$perasaansembuh',
        suhutubuh = '$suhutubuh',
        sakitkepala = '$sakitkepala',
        muntah = '$muntah',
        nyerisendi = '$nyerisendi',
        mual = '$mual',
        ptkie = '$ptkie',
        infeksitenggorok = '$infeksitenggorok',
        sakitbolamata = '$sakitbolamata',
        lainnya = '$lainnya',
        iggm = '$iggm',
        igm = '$igm',
        ns1 = '$ns1',
        tombosit = '$tombosit',
        hematokrit = '$hematokrit',
        hb = '$hb',
        leukosit = '$leukosit',
        eritrosit = '$eritrosit',
        pernahranap = '$pernahranap',
        namars = '$namars',
        tanggalmasuk = '$tanggalmasuk',
        ruangrawat = '$ruangrawat',
        namarssebelum = '$namarssebelum',
        statuspasienakhir = '$statuspasienakhir',
        periksajentik = '$periksajentik',
        pjpemeriksa = '$pjpemeriksa',
        diagnosislab = '$diagnosislab',
        diagnosisklinis = '$diagnosisklinis',
        tgl_keluar_perawatan = '$tgl_keluar_perawatan'
";

// Tambahkan file KDRS ke query jika ada
if ($upload_file_kdrs) {
    $query .= ", upload_file_kdrs = '$upload_file_kdrs'";
}

$query .= " WHERE id = '$id'";

if (mysqli_query($conn, $query)) {
    // Jika berhasil, tampilkan alert dan redirect
    echo "<script>
            alert('Data berhasil diubah.');
            window.location.href = 'periksalihat.php?id=$id';
          </script>";
    exit(); // Jangan lupa untuk keluar setelah redirect
} else {
    // Jika terjadi error dalam query
    echo "<script>
            alert('Gagal mengubah data: " . mysqli_error($conn) . "');
            window.location.href = 'periksaedit.php?id=$id';
          </script>";
    exit(); // Jangan lupa untuk keluar setelah redirect
}

?>
