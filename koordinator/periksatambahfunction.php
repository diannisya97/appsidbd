<?php
// Include the database connection
require "../function.php";

if (isset($_POST["tambahperiksa"])) {
    // Mengambil data dari form
    $namapasien = $_POST["namapasien"];
    $tanggalperiksa = $_POST["tanggalperiksa"];
    $tanggalgejala = $_POST["tanggalgejala"];
    $trombositturun = $_POST["trombositturun"];
    $mimisan = $_POST["mimisan"];
    $nyeriperut = $_POST["nyeriperut"];
    $perasaansembuh = $_POST["perasaansembuh"];
    $suhutubuh = $_POST["suhutubuh"];
    $sakitkepala = $_POST["sakitkepala"];
    $muntah = $_POST["muntah"];
    $nyerisendi = $_POST["nyerisendi"];
    $mual = $_POST["mual"];
    $ptkie = $_POST["ptkie"];
    $infeksitenggorok = $_POST["infeksitenggorok"];
    $sakitbolamata = $_POST["sakitbolamata"];
    $lainnya = $_POST["lainnya"];
    $iggm = $_POST["iggm"];
    $igm = $_POST["igm"];
    $ns1 = $_POST["ns1"];
    $tombosit = $_POST["tombosit"];
    $hematokrit = $_POST["hematokrit"];
    $hb = $_POST["hb"];
    $leukosit = $_POST["leukosit"];
    $eritrosit = $_POST["eritrosit"];
    $pernahranap = $_POST["pernahranap"];
    $namars = $_POST["namars"];
    $tanggalmasuk = $_POST["tanggalmasuk"];
    $ruangrawat = $_POST["ruangrawat"];
    $namarssebelum = $_POST["namarssebelum"];
    $statuspasienakhir = $_POST["statuspasienakhir"];
    $periksajentik = $_POST["periksajentik"];
    $pjpemeriksa = $_POST["pjpemeriksa"];

    // Menambahkan variabel baru
    $diagnosislab = $_POST["diagnosislab"];
    $diagnosisklinis = $_POST["diagnosisklinis"];
    $tgl_keluar_perawatan = $_POST["tgl_keluar_perawatan"];

    // Variabel untuk file upload KDRS
    $upload_dir = "../KDRS/";

    // Cek apakah file upload ada
    if (isset($_FILES["upload_file_kdrs"])) {
        $file_name = $_FILES["upload_file_kdrs"]["name"];
        $file_tmp = $_FILES["upload_file_kdrs"]["tmp_name"];
        $file_size = $_FILES["upload_file_kdrs"]["size"];
        $file_error = $_FILES["upload_file_kdrs"]["error"];
        
        // Check if file upload is successful
        if ($file_error === 0) {
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_new_name = uniqid('KDRS_', true) . '.' . $file_ext;
            $file_destination = $upload_dir . $file_new_name;

            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Jika file berhasil diupload, simpan nama file ke database
                $upload_file_kdrs = $file_new_name;

                // Menambahkan data ke database
                $tambahperiksa = mysqli_query($conn, "INSERT INTO periksapasien (
                    namapasien, tanggalperiksa, tanggalgejala, trombositturun, mimisan, nyeriperut, perasaansembuh,
                    suhutubuh, sakitkepala, muntah, nyerisendi, mual, ptkie, infeksitenggorok, sakitbolamata, lainnya,
                    iggm, igm, ns1, tombosit, hematokrit, hb, leukosit, eritrosit, pernahranap, namars, tanggalmasuk,
                    ruangrawat, namarssebelum, statuspasienakhir, periksajentik, pjpemeriksa,
                    diagnosislab, diagnosisklinis, tgl_keluar_perawatan, upload_file_kdrs
                ) VALUES (
                    '$namapasien', '$tanggalperiksa', '$tanggalgejala', '$trombositturun', '$mimisan', '$nyeriperut',
                    '$perasaansembuh', '$suhutubuh', '$sakitkepala', '$muntah', '$nyerisendi', '$mual', '$ptkie',
                    '$infeksitenggorok', '$sakitbolamata', '$lainnya', '$iggm', '$igm', '$ns1', '$tombosit', '$hematokrit',
                    '$hb', '$leukosit', '$eritrosit', '$pernahranap', '$namars', '$tanggalmasuk', '$ruangrawat',
                    '$namarssebelum', '$statuspasienakhir', '$periksajentik', '$pjpemeriksa',
                    '$diagnosislab', '$diagnosisklinis', '$tgl_keluar_perawatan', '$upload_file_kdrs'
                )");

                if ($tambahperiksa) {
                    // Set timezone Indonesia
                    date_default_timezone_set('Asia/Jakarta');

                    // Catat aktivitas menambah data pemeriksaan klinis
                    session_start();
                    $userId = $_SESSION['id']; // Pastikan session sudah ter-set
                    $activity = "Melakukan pemeriksaan klinis kepada pasien";
                    $currentTime = date('Y-m-d H:i:s'); // Waktu sekarang di zona waktu Indonesia

                    // Query untuk mengambil aktivitas dan waktu sebelumnya
                    $activityQuery = "SELECT activity, created_at FROM user WHERE id = $userId";
                    $result = mysqli_query($conn, $activityQuery);
                    $data = mysqli_fetch_assoc($result);

                    // Ambil aktivitas dan waktu lama jika ada
                    $currentActivities = !empty($data['activity']) ? explode(',', $data['activity']) : [];
                    $currentTimes = !empty($data['created_at']) ? explode(',', $data['created_at']) : [];

                    // Tambahkan aktivitas baru dan waktu ke array
                    array_unshift($currentActivities, $activity); // Tambahkan aktivitas baru di awal
                    array_unshift($currentTimes, $currentTime); // Tambahkan waktu aktivitas baru di awal

                    // Gabungkan kembali aktivitas dan waktu
                    $updatedActivities = implode(',', $currentActivities);
                    $updatedTimes = implode(',', $currentTimes);

                    // Update data aktivitas dan waktu pengguna di tabel user
                    $updateQuery = "UPDATE user SET activity = '$updatedActivities', created_at = '$updatedTimes' WHERE id = $userId";
                    mysqli_query($conn, $updateQuery);

                    // Jika berhasil, tampilkan pesan sukses
                    echo "<script>
                            alert('Data berhasil ditambahkan. Pasien selesai diperiksa.');
                            window.location.href = 'periksa.php';
                          </script>";
                    exit; // Jangan lupa untuk keluar setelah header redirection
                } else {
                    // Jika gagal, tampilkan pesan error
                    echo "Gagal menambahkan data: " . mysqli_error($conn);
                }
            } else {
                echo "Gagal mengupload file KDRS.";
            }
        } else {
            echo "Terjadi kesalahan dalam mengupload file KDRS.";
        }
    } else {
        echo "File KDRS tidak ditemukan.";
    }
}
?>
