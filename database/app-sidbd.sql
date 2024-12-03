-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Des 2024 pada 05.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app-sidbd`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `infografis`
--

CREATE TABLE `infografis` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto1` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `infografis`
--

INSERT INTO `infografis` (`id`, `judul`, `deskripsi`, `foto1`, `created_at`) VALUES
(3, 'Sistem Informasi Demam Berdarah', 'Sistm ini sangat memudahkan pengguna dalam melakukan pekerjaannya sehingga....', 'Planet9_Wallpaper_5000x2813.jpg', '2024-08-29 06:46:56'),
(4, 'Rekam Medis dan Informasi Kesehatan', 'Rekam Medis dan Informasi Kesehatan adalah dua konsep penting dalam dunia kesehatan yang saling terkait, tetapi memiliki fokus dan fungsi yang berbeda:\\r\\n\\r\\n1. Rekam Medis (Medical Record)\\r\\nRekam medis adalah kumpulan catatan atau dokumentasi yang berisi informasi medis seorang pasien, yang dibuat oleh tenaga kesehatan selama proses pelayanan kesehatan. Rekam medis mencakup semua aspek kesehatan pasien, mulai dari diagnosis, riwayat penyakit, hasil pemeriksaan, pengobatan, dan prosedur medis lainnya. Rekam medis bersifat rahasia dan hanya dapat diakses oleh pihak-pihak yang berwenang.', 'Acer_Wallpaper_02_5000x2813.jpg', '2024-08-29 06:52:04'),
(5, 'Poltekke Kemenkes Tasikmalaya', 'Poltekke Kemenkes Tasikmalaya adalah sebuah perguruan tinggi kedinasan di bawah naungan Kementerian Kesehatan Republik Indonesia yang terletak di Tasikmalaya, Jawa Barat. Poltekke Kemenkes Tasikmalaya dikenal dengan nama resmi Politeknik Kesehatan Kemenkes Tasikmalaya, dan memiliki tujuan utama untuk menyediakan pendidikan tinggi di bidang kesehatan serta menghasilkan tenaga kesehatan yang berkualitas. Program studi yang ditawarkan meliputi berbagai disiplin ilmu kesehatan seperti keperawatan, kesehatan masyarakat, dan teknologi laboratorium medis, yang dirancang untuk memenuhi kebutuhan tenaga medis di berbagai fasilitas kesehatan di Indonesia.\\r\\n\\r\\nSebagai institusi pendidikan yang terintegrasi dengan Kementerian Kesehatan, Poltekke Kemenkes Tasikmalaya juga berperan dalam pengembangan penelitian dan pengabdian masyarakat di bidang kesehatan. Dengan fasilitas modern dan kurikulum yang sesuai dengan standar nasional, Poltekke Kemenkes Tasikmalaya berkomitmen untuk mencetak lulusan yang siap pakai dan mampu berkontribusi secara signifikan dalam upaya meningkatkan kualitas kesehatan masyarakat. Selain itu, institusi ini juga menjalin kerjasama dengan berbagai lembaga kesehatan dan pendidikan lainnya untuk mendukung pengembangan ilmu pengetahuan dan praktik kesehatan yang berbasis bukti.', 'Screenshot (406).png', '2024-08-29 06:53:07'),
(6, 'DBD Tasikmalaya', '**Demam Berdarah** atau Demam Berdarah Dengue (DBD) adalah penyakit infeksi yang disebabkan oleh virus dengue, yang ditularkan melalui gigitan nyamuk Aedes aegypti atau Aedes albopictus. Virus ini memiliki empat serotipe berbeda, dan infeksi dapat terjadi lebih dari satu kali jika terpapar oleh serotipe yang berbeda. Gejala utama DBD meliputi demam tinggi yang mendadak, nyeri otot dan sendi, ruam kulit, serta pendarahan dari gusi, hidung, atau tempat lain. Gejala ini biasanya muncul antara 4 hingga 10 hari setelah gigitan nyamuk yang terinfeksi.\\r\\n\\r\\nPenyakit ini dapat berkembang menjadi bentuk yang lebih parah yang dikenal sebagai demam berdarah dengue berat atau Dengue Hemorrhagic Fever (DHF), yang ditandai dengan penurunan kadar trombosit dalam darah, kebocoran plasma, dan pendarahan berat. Kondisi ini bisa mengancam nyawa dan memerlukan perawatan medis segera. Pencegahan utama DBD adalah mengurangi tempat perindukan nyamuk dengan menjaga kebersihan lingkungan dan menggunakan obat nyamuk. Selain itu, vaksinasi juga merupakan langkah pencegahan yang disarankan di beberapa daerah endemis.', 'download.jpeg', '2024-08-29 06:55:00'),
(7, 'Halo', 'wadsasidifgwiasd qwas dwasdgw asdx gwasd qwa sdgxiqwagsdixwgasdiu gwas aw sx aws aws gawgswgas s qwgas asxg awsxg asgx ', 'DSC07631.JPG', '2024-08-30 05:30:50'),
(8, 'Halo', 'asdweaf wef wef wefhewfdhweihegef gegwegd gwqdgwwdg8wq', 'DSC07616.JPG', '2024-08-30 14:59:06'),
(16, 'aus', 'assdeasfe re rgregfregregvg  grre ggrgrr g grg rrgrg', 'E73XTrmUUAIPFmQ.jpg', '2024-11-30 03:58:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jentikperiksa`
--

CREATE TABLE `jentikperiksa` (
  `id` int(11) NOT NULL,
  `namakoor` int(11) DEFAULT NULL,
  `namapemilik` int(11) DEFAULT NULL,
  `tanggalrekap` date DEFAULT NULL,
  `bakmandi` enum('ada','tidak_ada') DEFAULT NULL,
  `dispenser` enum('ada','tidak_ada') DEFAULT NULL,
  `penampung` enum('ada','tidak_ada') DEFAULT NULL,
  `potbunga` enum('ada','tidak_ada') DEFAULT NULL,
  `tempatminumhewan` enum('ada','tidak_ada') DEFAULT NULL,
  `banbekas` enum('ada','tidak_ada') DEFAULT NULL,
  `sampah` enum('ada','tidak_ada') DEFAULT NULL,
  `pohon` enum('ada','tidak_ada') DEFAULT NULL,
  `lainnya` enum('ada','tidak_ada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jentikperiksa`
--

INSERT INTO `jentikperiksa` (`id`, `namakoor`, `namapemilik`, `tanggalrekap`, `bakmandi`, `dispenser`, `penampung`, `potbunga`, `tempatminumhewan`, `banbekas`, `sampah`, `pohon`, `lainnya`) VALUES
(17, 43, 65, '2024-10-02', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada'),
(21, 43, 57, '2024-10-03', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada'),
(24, 43, 62, '2024-12-07', 'tidak_ada', 'tidak_ada', 'tidak_ada', 'tidak_ada', 'tidak_ada', 'tidak_ada', 'ada', 'ada', 'ada'),
(26, 42, 63, '2024-12-19', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada', 'ada');

-- --------------------------------------------------------

--
-- Struktur dari tabel `koordinator`
--

CREATE TABLE `koordinator` (
  `id` int(11) NOT NULL,
  `namakoor` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nokontak` varchar(255) NOT NULL,
  `asalfasyankes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `koordinator`
--

INSERT INTO `koordinator` (`id`, `namakoor`, `alamat`, `nokontak`, `asalfasyankes`) VALUES
(42, 'Ghifari', 'Perum Puri Ciawi KencanajVHXhjasVX', '0258741369', 23),
(43, 'Rafian', 'Perum Puri Ciawi KencanajVHXhjasVX', '08214253687', 23),
(57, 'c', 'dc', 'dcs', 23);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `namapasien` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `tanggallahir` date NOT NULL,
  `tempatlahir` varchar(100) DEFAULT NULL,
  `jeniskelamin` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `rt` varchar(11) NOT NULL,
  `rw` varchar(11) NOT NULL,
  `desakelurahan` varchar(11) NOT NULL,
  `kecamatan` varchar(255) NOT NULL,
  `kabkota` varchar(255) NOT NULL,
  `kodepos` varchar(11) DEFAULT NULL,
  `nokontak` varchar(255) NOT NULL,
  `asalfasyankes` int(11) DEFAULT NULL,
  `domisili` varchar(100) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `namapasien`, `nik`, `tanggallahir`, `tempatlahir`, `jeniskelamin`, `alamat`, `rt`, `rw`, `desakelurahan`, `kecamatan`, `kabkota`, `kodepos`, `nokontak`, `asalfasyankes`, `domisili`, `longitude`, `latitude`) VALUES
(57, 'Adhi Nugaha', '3206381805030003', '2024-07-03', 'Tasikmalaya', 'Laki-laki', 'Jalan Gambang, Tasikmalaya, Jawa Barat, Indonesia', '02', '08', 'Pagersari', 'mangkubumi', 'Kabupaten Tasikmalaya', '014788', '-', 23, 'Kota Tasikmalaya', '108.21144668062236', '-7.356503956429371'),
(61, 'Galang', '3206381805030003', '2024-07-04', 'Tasikmalaya', 'Laki-laki', 'Jalan Fernwood, Tasikmalaya, Jawa Barat, Indonesia', '05', '02', 'Pagersari', 'kawalu', 'tasikmalaya', '012365', '-', 21, 'Kota Tasikmalaya', '108.21977758567864', '-7.343569147887031'),
(62, 'Reza', '3206381805030003', '2024-08-15', 'Tasikmalaya', 'Laki-laki', 'Jalan Buninagara I, Nagarasari, Kecamatan Cipedes, Kabupaten Tasikmalaya, Jawa Barat, Indonesia, 46132', '08', '08', 'Nagarasari', 'Kecamatan Cipedes', 'Kabupaten Tasikmalaya', '46132', '-', 21, 'Kota Tasikmalaya', '108.21794986724854', '-7.314530549224419'),
(63, 'Hamda Nilan', '3207012611020002', '2024-08-09', 'Tasikmalaya', 'Laki-laki', 'Jalan Noenoeng Tisnasaputra, Kahuripan, Kecamatan Tawang, Kabupaten Tasikmalaya, Jawa Barat, Indonesia, 46115', '08', '05', 'Kahuripan', 'Kecamatan Tawang', 'Kabupaten Tasikmalaya', '46115', '-', 23, 'bjt', '108.22842314396414', '-7.359862092931879'),
(64, 'Safaat', 'cmyf18@gmail.com', '2024-08-09', 'Tasikmalaya', 'Laki-laki', 'Jalan Rumah Sakit, Tasikmalaya, Jawa Barat, Indonesia', '02', '02', 'Pagersari', 'mangkubumi', 'tasikmalaya', '1', '-', 21, 'bnj', '108.22344817814972', '-7.3338954613044285'),
(65, 'Farhan', '3207012611020002', '2024-08-12', 'Tasikmalaya', 'Perempuan', 'Jalan Petir, Tasikmalaya, Jawa Barat, Indonesia', '08', '08', 'Pagersari', 'kawalu', 'Kabupaten Tasikmalaya', '-', '082145686592', 23, 'bnj', '108.22590363749933', '-7.333809268527362'),
(69, 'Faris', '3206381805030003', '2015-02-03', 'Tasikmalaya', 'Laki Laki', 'Jalan Winaya III, Kahuripan, Tasikmalaya, Jawa Barat, Jawa, 46182, Indonesia', '02', '02', 'Kahuripan', 'Kahuripan', ' Kahuripan', '025887', '-', 23, 'Kota Tasikmalaya', '108.222749', '-7.355757'),
(72, 'cecep', '3206382409900003', '2024-11-08', 'Tasikmalaya', 'Laki Laki', 'Kahuripan, Tasikmalaya, Jawa Barat, Jawa, 46182, Indonesia', '05', '008', 'Kahuripan', 'kawalu', ' Tasikmalaya', ' 46182', '-', 19, 'Kota Tasikmalaya', '108.212828', '-7.349596'),
(81, 'Pacar siapa', '3206381802870003', '2024-11-02', 'tasikmalaya', 'Perempuan', '18, Jalan Taman Pahlawan Kusuma Bangsa, Cikalang, Kecamatan Tawang, Kabupaten Tasikmalaya, Jawa Barat, Indonesia, 46114', '02', '02', 'Cikalang', 'Kecamatan Tawang', 'Kabupaten Tasikmalaya', '46114', '0258741369', 40, 'Kota Tasikmalaya', '108.228115', '-7.331287'),
(82, 'Mitaaaa', '32070126110200021', '2024-11-30', 'tasikmalaya', 'Laki-laki', '88, Jalan Sutisna Senjaya, Cikalang, Kecamatan Tawang, Kabupaten Tasikmalaya, Jawa Barat, Indonesia, 46114', '02', '02', 'Cikalang', 'Kecamatan Tawang', 'Kabupaten Tasikmalaya', '46114', '0258741369', 40, 'Kota Tasikmalaya', '108.230106', '-7.328555');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengendalianvektor`
--

CREATE TABLE `pengendalianvektor` (
  `idpengendalian` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `penyuluhan` enum('Pernah','Tidak Pernah') NOT NULL,
  `psn3m` enum('Pernah','Tidak Pernah') NOT NULL,
  `larvasidasi` enum('Pernah','Tidak Pernah') NOT NULL,
  `fogging` enum('Pernah','Tidak Pernah') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengendalianvektor`
--

INSERT INTO `pengendalianvektor` (`idpengendalian`, `id`, `penyuluhan`, `psn3m`, `larvasidasi`, `fogging`) VALUES
(4, 57, 'Pernah', 'Tidak Pernah', 'Pernah', 'Pernah'),
(8, 62, 'Pernah', 'Pernah', 'Pernah', 'Pernah'),
(18, 63, 'Tidak Pernah', 'Tidak Pernah', 'Tidak Pernah', 'Tidak Pernah'),
(25, 81, 'Pernah', 'Pernah', 'Pernah', 'Pernah'),
(28, 65, 'Pernah', 'Pernah', 'Pernah', 'Pernah'),
(29, 82, 'Pernah', 'Pernah', 'Tidak Pernah', 'Tidak Pernah'),
(31, 62, 'Tidak Pernah', 'Tidak Pernah', 'Pernah', 'Pernah'),
(33, 69, 'Pernah', 'Pernah', 'Pernah', 'Pernah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periksapasien`
--

CREATE TABLE `periksapasien` (
  `id` int(11) NOT NULL,
  `namapasien` int(10) NOT NULL,
  `tanggalperiksa` date NOT NULL,
  `tanggalgejala` date NOT NULL,
  `trombositturun` varchar(255) NOT NULL,
  `mimisan` varchar(255) NOT NULL,
  `nyeriperut` varchar(255) NOT NULL,
  `perasaansembuh` varchar(255) NOT NULL,
  `suhutubuh` varchar(255) NOT NULL,
  `sakitkepala` varchar(255) NOT NULL,
  `muntah` varchar(255) NOT NULL,
  `nyerisendi` varchar(255) NOT NULL,
  `mual` varchar(255) NOT NULL,
  `ptkie` varchar(255) NOT NULL,
  `infeksitenggorok` varchar(255) NOT NULL,
  `sakitbolamata` varchar(255) NOT NULL,
  `lainnya` varchar(255) NOT NULL,
  `iggm` enum('Positif','Negatif') NOT NULL,
  `igm` enum('Positif','Negatif') NOT NULL,
  `ns1` enum('Positif','Negatif') NOT NULL,
  `tombosit` int(11) NOT NULL,
  `hematokrit` int(11) NOT NULL,
  `hb` int(11) NOT NULL,
  `leukosit` int(11) NOT NULL,
  `eritrosit` int(11) NOT NULL,
  `pernahranap` varchar(255) NOT NULL,
  `namars` varchar(255) NOT NULL,
  `tanggalmasuk` date NOT NULL,
  `ruangrawat` varchar(255) NOT NULL,
  `namarssebelum` varchar(255) NOT NULL,
  `statuspasienakhir` varchar(244) NOT NULL,
  `periksajentik` varchar(255) NOT NULL,
  `pjpemeriksa` int(11) DEFAULT NULL,
  `diagnosislab` varchar(255) DEFAULT NULL,
  `diagnosisklinis` varchar(255) DEFAULT NULL,
  `tgl_keluar_perawatan` date DEFAULT NULL,
  `upload_file_kdrs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `periksapasien`
--

INSERT INTO `periksapasien` (`id`, `namapasien`, `tanggalperiksa`, `tanggalgejala`, `trombositturun`, `mimisan`, `nyeriperut`, `perasaansembuh`, `suhutubuh`, `sakitkepala`, `muntah`, `nyerisendi`, `mual`, `ptkie`, `infeksitenggorok`, `sakitbolamata`, `lainnya`, `iggm`, `igm`, `ns1`, `tombosit`, `hematokrit`, `hb`, `leukosit`, `eritrosit`, `pernahranap`, `namars`, `tanggalmasuk`, `ruangrawat`, `namarssebelum`, `statuspasienakhir`, `periksajentik`, `pjpemeriksa`, `diagnosislab`, `diagnosisklinis`, `tgl_keluar_perawatan`, `upload_file_kdrs`) VALUES
(93, 65, '2024-10-07', '2024-10-07', 'Ya', 'Ya', 'Ya', 'Ya', '36', 'Ya', 'Ya', 'Ya', 'Ya', 'Ya', 'Ya', 'Ya', 'Tidak Ada Keluhan', 'Positif', 'Positif', 'Positif', 1, 1, 1, 1, 1, 'Pernah', 'TMC', '2024-10-07', '-', '-', 'Keluar Sehat', 'Pernah', 43, 'dfcdf', 'qqq', '2024-10-04', 'KDRS_67040e48762b97.05936164.pdf'),
(95, 64, '2024-10-08', '2024-10-08', 'Ya', 'Ya', 'Ya', 'Ya', '36', 'Ya', 'Ya', 'Ya', 'Ya', 'Ya', 'Ya', 'Ya', 'Tidak Ada Keluhan', 'Positif', 'Positif', 'Positif', 0, 8, 8, 88, 8, 'Pernah', 'TMC', '2024-10-08', '-', '-', 'Keluar Sehat', 'Pernah', 43, 'cccc', '-', '2024-10-03', 'KDRS_670499fdd1ac51.12798530.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `puskesmas`
--

CREATE TABLE `puskesmas` (
  `idpuskesmas` int(11) NOT NULL,
  `Nama_Puskesmas` varchar(111) NOT NULL,
  `Alamat` varchar(111) NOT NULL,
  `Nomor_Kontak` varchar(111) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `jam_operasional` varchar(100) DEFAULT NULL,
  `kepala_puskesmas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `puskesmas`
--

INSERT INTO `puskesmas` (`idpuskesmas`, `Nama_Puskesmas`, `Alamat`, `Nomor_Kontak`, `created_at`, `longitude`, `latitude`, `jam_operasional`, `kepala_puskesmas`) VALUES
(19, 'RS TMC', '-', '-', '2024-07-16 18:56:10', '108.2087515', '-7.3437693', NULL, NULL),
(21, 'Puskesmas Tawang', 'Tawangsari, Tasikmalaya, Jawa Barat, Jawa, 46121, Indonesia', '-', '2024-07-16 19:01:25', '108.22437569819166', '-7.333514510918099', '07.00-14.00', 'Tes'),
(22, 'Puskesmas Cibeureum', 'Kampung Babakan Kertasari, RT. 03 RW. 03, Kalimanggis, Kec. Manonjaya, Kabupaten Tasikmalaya, Jawa Barat 46197,', '0258741369', '2024-07-16 19:06:43', '108.301731', '-7.366246', '07.00-14.00', 'Cecep Maulana YF'),
(23, 'Puskesmas Kahuripan', 'J6R7+R9J, Jl. Sambong Jaya, Sambongjaya, Kec. Mangkubumi, Kab. Tasikmalaya, Jawa Barat 46181, Indonesia', '0258963147', '2024-07-16 19:07:37', '108.21339943722916', '-7.358087263337924', '07.00-14.00', '-'),
(24, 'RSUD Dr Sukardjo', 'Tawangsari, Tasikmalaya, Jawa Barat, Jawa, 46121, Indonesia', '-', '2024-07-16 19:09:06', '108.22096445751244', '-7.328484440869979', '07.00-14.00', '-'),
(37, 'Puskesmas Indihiang', 'Indihiang, Tasikmalaya, Jawa Barat, Jawa, 46151, Indonesia', '-', '2024-09-08 18:42:00', '108.184311', '-7.295200', '07.00-14.00', '-'),
(39, 'ayoo', 'Tawangsari, Tasikmalaya, Jawa Barat, Jawa, 46121, Indonesia', '-', '2024-11-11 08:59:01', '108.220635', '-7.327293', '07.00-14.00', '-'),
(40, 'cecep', 'M6CG+HG6, Jl. Kehutanan, Empangsari, Kec. Tawang, Kab. Tasikmalaya, Jawa Barat 46113, Indonesia', '0258741369', '2024-11-17 21:57:34', '108.226312', '-7.328581', '07.00-14.00', '-'),
(42, 'aaa', 'Jl. Mayor S.L. Tobing No.37, Tugujaya, Kec. Cihideung, Kab. Tasikmalaya, Jawa Barat 46126, Indonesia', '0258963147', '2024-11-30 10:14:57', '108.210149', '-7.347814', '07.00-14.00', '-'),
(44, 'ub', 'J6V6+4XG, Sambongjaya, Kec. Mangkubumi, Kab. Tasikmalaya, Jawa Barat 46181, Indonesia', '0258741369', '2024-11-30 10:19:34', '108.212381', '-7.357177', '07.00-14.00', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(244) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `idpuskesmas` int(11) DEFAULT NULL,
  `level` enum('admin','petugas','koordinator') NOT NULL DEFAULT 'petugas',
  `activity` text DEFAULT NULL,
  `created_at` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `contact`, `email`, `idpuskesmas`, `level`, `activity`, `created_at`) VALUES
(17, 'kahuripan', 'petugas', 'petugas', '0258147369', 'kahuripan@gmail.com', 23, 'petugas', 'Logout,Menghapus data pengendalian vektor,Login ke sistem,Login ke sistem', '2024-12-01 11:18:11,2024-12-01 11:17:09,2024-12-01 11:16:42,2024-12-01 10:52:41'),
(26, 'Koordinator', 'koordinator', 'koordinator', '0258147369', 'koor@gmail.com', 23, 'koordinator', 'Logout,Menambah data pemeriksaan jentik,Menambah data koordinator,Login ke sistem', '2024-12-01 11:20:05,2024-12-01 11:19:27,2024-12-01 11:18:42,2024-12-01 11:18:21'),
(27, 'Admin (APP-SIDBD)', 'admin', 'admin', '08214556398', 'admin@gmail.com', 22, 'admin', 'Menghapus data pengendalian vektor,Menghapus data pengendalian vektor,Login ke sistem,Logout,Login ke sistem,Logout,Menghapus pemeriksaan klinis pasien,Login ke sistem,Logout,Login ke sistem', '2024-12-01 11:28:16,2024-12-01 11:28:10,2024-12-01 11:20:10,2024-12-01 11:16:32,2024-12-01 10:55:03,2024-12-01 10:52:34,2024-12-01 10:40:02,2024-12-01 10:38:12,2024-12-01 10:38:04,2024-12-01 10:18:30');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `infografis`
--
ALTER TABLE `infografis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jentikperiksa`
--
ALTER TABLE `jentikperiksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `namakoor` (`namakoor`),
  ADD KEY `jentikperiksa_ibfk_2` (`namapemilik`);

--
-- Indeks untuk tabel `koordinator`
--
ALTER TABLE `koordinator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_koordinator` (`id`),
  ADD KEY `fk_koordinator_puskesmas` (`asalfasyankes`),
  ADD KEY `idx_koordinator_id` (`id`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pasien_puskesmas` (`asalfasyankes`),
  ADD KEY `idx_namapasien` (`namapasien`);

--
-- Indeks untuk tabel `pengendalianvektor`
--
ALTER TABLE `pengendalianvektor`
  ADD PRIMARY KEY (`idpengendalian`),
  ADD KEY `id` (`id`);

--
-- Indeks untuk tabel `periksapasien`
--
ALTER TABLE `periksapasien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_namapasien_periksa` (`namapasien`),
  ADD KEY `fk_pjpemeriksa` (`pjpemeriksa`);

--
-- Indeks untuk tabel `puskesmas`
--
ALTER TABLE `puskesmas`
  ADD PRIMARY KEY (`idpuskesmas`),
  ADD UNIQUE KEY `Nama_Puskesmas` (`Nama_Puskesmas`),
  ADD KEY `idx_idpuskesmas` (`idpuskesmas`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_puskesmas` (`idpuskesmas`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `infografis`
--
ALTER TABLE `infografis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `jentikperiksa`
--
ALTER TABLE `jentikperiksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `koordinator`
--
ALTER TABLE `koordinator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT untuk tabel `pengendalianvektor`
--
ALTER TABLE `pengendalianvektor`
  MODIFY `idpengendalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `periksapasien`
--
ALTER TABLE `periksapasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT untuk tabel `puskesmas`
--
ALTER TABLE `puskesmas`
  MODIFY `idpuskesmas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jentikperiksa`
--
ALTER TABLE `jentikperiksa`
  ADD CONSTRAINT `jentikperiksa_ibfk_1` FOREIGN KEY (`namakoor`) REFERENCES `koordinator` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jentikperiksa_ibfk_2` FOREIGN KEY (`namapemilik`) REFERENCES `pasien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `koordinator`
--
ALTER TABLE `koordinator`
  ADD CONSTRAINT `fk_koordinator_puskesmas` FOREIGN KEY (`asalfasyankes`) REFERENCES `puskesmas` (`idpuskesmas`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD CONSTRAINT `fk_pasien_puskesmas` FOREIGN KEY (`asalfasyankes`) REFERENCES `puskesmas` (`idpuskesmas`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengendalianvektor`
--
ALTER TABLE `pengendalianvektor`
  ADD CONSTRAINT `pengendalianvektor_ibfk_1` FOREIGN KEY (`id`) REFERENCES `pasien` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `periksapasien`
--
ALTER TABLE `periksapasien`
  ADD CONSTRAINT `fk_pasien` FOREIGN KEY (`namapasien`) REFERENCES `pasien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pjpemeriksa` FOREIGN KEY (`pjpemeriksa`) REFERENCES `koordinator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_puskesmas` FOREIGN KEY (`idpuskesmas`) REFERENCES `puskesmas` (`idpuskesmas`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
