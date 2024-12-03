<?php
// Mulai sesi
session_start();
require '../vendor/autoload.php';
require "../function.php";
require "../cek.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Pastikan koneksi database ada
$conn = mysqli_connect("localhost", "root", "", "app-sidbd");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Ambil idpuskesmas dari sesi user yang login
$idpuskesmas = $_SESSION['idpuskesmas'];
// Ambil parameter pencarian dari query string
$name = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';
$address = isset($_GET['address']) ? mysqli_real_escape_string($conn, $_GET['address']) : '';
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// Buat query dasar dengan join tambahan untuk tabel pengendalianvektor
$query = "SELECT per.id, p.namapasien, p.nik, p.tanggallahir, p.tempatlahir, p.jeniskelamin, p.alamat, p.domisili, p.desakelurahan,
                 per.tanggalperiksa, per.tanggalgejala, per.trombositturun, per.mimisan, per.nyeriperut, per.perasaansembuh,
                 per.suhutubuh, per.sakitkepala, per.muntah, per.nyerisendi, per.mual, per.ptkie, per.infeksitenggorok, 
                 per.sakitbolamata, per.lainnya, per.iggm, per.igm, per.ns1, per.tombosit, per.hematokrit, per.hb, 
                 per.leukosit, per.eritrosit, per.pernahranap, per.namars, per.tanggalmasuk, per.ruangrawat, per.namarssebelum,
                 per.statuspasienakhir, per.periksajentik, per.pjpemeriksa, per.diagnosislab, per.diagnosisklinis,
                 IFNULL(pv.penyuluhan, 'Tidak ada') AS penyuluhan,
                 IFNULL(pv.psn3m, 'Tidak ada') AS psn3m,
                 IFNULL(pv.larvasidasi, 'Tidak ada') AS larvasidasi,
                 IFNULL(pv.fogging, 'Tidak ada') AS fogging,
                 IF((SELECT COUNT(*) FROM periksapasien p2 WHERE p2.namapasien = p.id) > 0, 'Ada', 'Data Tidak ada') AS ada_periksapasien,
                 IF((SELECT COUNT(*) FROM jentikperiksa j WHERE j.namapemilik = p.id) > 0, 'Ada', 'Data Tidak ada') AS ada_jentikperiksa
          FROM periksapasien per
          JOIN pasien p ON p.id = per.namapasien
          LEFT JOIN pengendalianvektor pv ON pv.id = p.id
          WHERE p.asalfasyankes = '$idpuskesmas'";

// Tambahkan filter berdasarkan parameter pencarian
if (!empty($name)) {
    $query .= " AND (p.namapasien LIKE '%$name%' OR p.nik LIKE '%$name%')";
}

if (!empty($address)) {
    $query .= " AND (p.alamat LIKE '%$address%' OR p.desakelurahan LIKE '%$address%')";
}

if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND per.tanggalperiksa BETWEEN '$start_date' AND '$end_date'";
} elseif (!empty($start_date)) {
    $query .= " AND per.tanggalperiksa >= '$start_date'";
} elseif (!empty($end_date)) {
    $query .= " AND per.tanggalperiksa <= '$end_date'";
}

// Jalankan query
$ambildatapasien = mysqli_query($conn, $query);

// Cek jika ada error
if (!$ambildatapasien) {
    die('Query Error: ' . mysqli_error($conn));
}

// Buat objek Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom pada baris 6
$sheet->setCellValue('A6', 'No.')
      ->setCellValue('B6', 'Tanggal Periksa')
      ->setCellValue('C6', 'Nama Pasien')
      ->setCellValue('D6', 'NIK')
      ->setCellValue('E6', 'Alamat') // Kolom E sebelumnya untuk Nama Ibu, diubah menjadi Alamat
      ->setCellValue('F6', 'Domisili')
      ->setCellValue('G6', 'Desa/Kelurahan')
      ->setCellValue('H6', 'Tempat Lahir')
      ->setCellValue('I6', 'Tanggal Lahir')
      ->setCellValue('J6', 'Jenis Kelamin')
      ->setCellValue('K7', 'Diagnosis Lab')
      ->setCellValue('L7', 'Diagnosis Klinis')
      ->setCellValue('M7', 'Status Akhir')
      ->setCellValue('N7', 'PE')
      ->setCellValue('O7', 'Hasil PE')
      ->setCellValue('P8', 'Penyuluhan')
      ->setCellValue('Q8', 'PSN 3M Plus')
      ->setCellValue('R8', 'Larvasidasi')
      ->setCellValue('S8', 'Fogging');

// Merge cells for the header row across 6th, 7th, and 8th rows
$sheet->mergeCells('A6:A8')
      ->mergeCells('B6:B8')
      ->mergeCells('C6:C8')
      ->mergeCells('D6:D8')
      ->mergeCells('E6:E8') // Kolom Nama Ibu dihapus, jadi alamat dipindahkan ke sini
      ->mergeCells('F6:F8')
      ->mergeCells('G6:G8')
      ->mergeCells('H6:H8')
      ->mergeCells('I6:I8')
      ->mergeCells('J6:J8')
      ->mergeCells('K7:K8')
      ->mergeCells('L7:L8')
      ->mergeCells('M7:M8')
      ->mergeCells('N7:N8')
      ->mergeCells('O7:O8')
      ->mergeCells('K6:S6') // Merge K6 to S6
      ->mergeCells('P7:S7'); // Merge P7 to S7

// Atur tinggi baris 6, 7, dan 8 menjadi 50 piksel
$sheet->getRowDimension('6')->setRowHeight(20);
$sheet->getRowDimension('7')->setRowHeight(20);
$sheet->getRowDimension('8')->setRowHeight(20);

// Styling untuk header bagian No sampai Jenis Kelamin
$headerStyle1 = [
    'font' => [
        'bold' => true,
        'size' => 11, // Ukuran teks 11
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'C6E0B4', // Warna hijau muda untuk bagian No sampai Jenis Kelamin
        ],
    ],
];

// Styling untuk header bagian Diagnosis Lab sampai Fogging
$headerStyle2 = [
    'font' => [
        'bold' => true,
        'size' => 11, // Ukuran teks 11
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFE699', // Warna kuning untuk bagian Diagnosis Lab sampai Fogging
        ],
    ],
];

// Styling tambahan untuk merge K6:S6 dan P7:S7
$headerStyle3 = [
    'font' => [
        'bold' => true,
        'size' => 11,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

// Terapkan styling ke kolom No sampai Jenis Kelamin
$sheet->getStyle('A6:J8')->applyFromArray($headerStyle1);

// Terapkan styling ke kolom Diagnosis Lab sampai Fogging
$sheet->getStyle('K6:S8')->applyFromArray($headerStyle2);

// Terapkan styling tambahan ke K6:S6 dan P7:S7
$sheet->getStyle('K6:S6')->applyFromArray($headerStyle3);
$sheet->getStyle('P7:S7')->applyFromArray($headerStyle3);

// Tambahkan teks ke cell yang telah di-merge
$sheet->setCellValue('K6', 'Dengue');
$sheet->setCellValue('P7', 'Pengendalian Vektor');

// Set lebar kolom sesuai kebutuhan
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(18);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(30); // Di sini diubah untuk Alamat
$sheet->getColumnDimension('F')->setWidth(18);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(12);
$sheet->getColumnDimension('K')->setWidth(25);
$sheet->getColumnDimension('L')->setWidth(25);
$sheet->getColumnDimension('M')->setWidth(18);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(15);
$sheet->getColumnDimension('P')->setWidth(15);
$sheet->getColumnDimension('Q')->setWidth(15);
$sheet->getColumnDimension('R')->setWidth(15);
$sheet->getColumnDimension('S')->setWidth(15);

// Set format sel untuk kolom D menjadi format kustom "0"
$sheet->getStyle('D')->getNumberFormat()->setFormatCode('0');

// Isi data ke dalam file Excel mulai dari baris ke-9
$rowNumber = 9; // Mulai dari baris kesembilan karena header ada di baris 6-8
$i = 1;
while ($row = mysqli_fetch_assoc($ambildatapasien)) {
    $sheet->setCellValue('A' . $rowNumber, $i++)
          ->setCellValue('B' . $rowNumber, $row['tanggalperiksa'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('C' . $rowNumber, $row['namapasien'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('D' . $rowNumber, $row['nik'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('E' . $rowNumber, $row['alamat'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('F' . $rowNumber, $row['domisili'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('G' . $rowNumber, $row['desakelurahan'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('H' . $rowNumber, $row['tempatlahir'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('I' . $rowNumber, $row['tanggallahir'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('J' . $rowNumber, $row['jeniskelamin'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('K' . $rowNumber, $row['diagnosislab'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('L' . $rowNumber, $row['diagnosisklinis'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('M' . $rowNumber, $row['statuspasienakhir'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('N' . $rowNumber, $row['ada_periksapasien'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('O' . $rowNumber, $row['ada_jentikperiksa'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('P' . $rowNumber, $row['penyuluhan'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('Q' . $rowNumber, $row['psn3m'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('R' . $rowNumber, $row['larvasidasi'] ?: 'PEMERIKSAAN BELUM DILAKUKAN')
          ->setCellValue('S' . $rowNumber, $row['fogging'] ?: 'PEMERIKSAAN BELUM DILAKUKAN');

    // Tambahkan border pada setiap sel data
    $sheet->getStyle('A' . $rowNumber . ':S' . $rowNumber)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $rowNumber++;
}



// Buat file Excel dan kirim ke browser
$writer = new Xlsx($spreadsheet);
$filename = 'Data_Pemeriksaan_Pasien.xlsx';

// Hapus output buffer
ob_clean();

// Set headers
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer->save('php://output');
exit;
?>
