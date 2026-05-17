-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 17, 2026 at 11:10 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkm_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_berita`
--

CREATE TABLE `data_berita` (
  `id_berita` int NOT NULL,
  `judul_berita` varchar(100) NOT NULL,
  `tanggal_publikasi` date NOT NULL,
  `link_website` varchar(255) NOT NULL,
  `ringkasan` text,
  `gambar_utama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_berita`
--

INSERT INTO `data_berita` (`id_berita`, `judul_berita`, `tanggal_publikasi`, `link_website`, `ringkasan`, `gambar_utama`, `created_at`) VALUES
(5, 'Mahasiswa Polije Raih Juara Nasional Lomba Robotik', '2026-05-03', 'https://polije.ac.id/', 'Tim robotik Polije berhasil meraih juara pertama dalam kompetisi robotik tingkat nasional setelah mengalahkan puluhan tim dari berbagai perguruan tinggi.', '69fab8a047a51.jpg', '2026-05-03 14:59:58'),
(6, 'Mahasiswa Polije Raih Juara Nasional Kompetisi Teknologi 2026', '2026-05-06', 'https://www.polije.ac.id', 'Mahasiswa Politeknik Negeri Jember (Polije) berhasil meraih juara pertama dalam ajang kompetisi teknologi tingkat nasional yang diselenggarakan di Jakarta. Tim yang terdiri dari mahasiswa jurusan Teknologi Informasi ini mengembangkan aplikasi berbasis kecerdasan buatan untuk membantu sektor pertanian. Prestasi ini menjadi bukti kualitas pendidikan dan inovasi mahasiswa Polije di tingkat nasional.', '69fab9be0d3e1.jpg', '2026-05-06 03:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `data_dosen`
--

CREATE TABLE `data_dosen` (
  `id_dosen` int NOT NULL,
  `foto_dosen` varchar(255) DEFAULT 'default.jpg',
  `nama_lengkap` varchar(150) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `linkedin_name` varchar(100) DEFAULT NULL,
  `instagram_username` varchar(100) DEFAULT NULL,
  `facebook_name` varchar(100) DEFAULT NULL,
  `riwayat_bimbingan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_dosen`
--

INSERT INTO `data_dosen` (`id_dosen`, `foto_dosen`, `nama_lengkap`, `nip`, `jurusan`, `no_whatsapp`, `email`, `linkedin_name`, `instagram_username`, `facebook_name`, `riwayat_bimbingan`, `created_at`, `updated_at`) VALUES
(9, 'dosen_197104082001121003_1778373444.png', 'Ir. Wahyu Kurnia Dewanto, S.Kom, MT', '197104082001121003', 'Teknologi Informasi', '-', 'wahyu.k.dewanto@gmail.com', '-', '-', '-', 0, '2026-05-10 00:37:24', '2026-05-10 00:37:24'),
(10, 'dosen_198302032006041003_1778373558.jpg', 'Ir. Hendra Yufit Riskiawan, S.Kom, M.Cs', '198302032006041003', 'Teknologi Informasi', '-', 'yufit@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:39:18', '2026-05-10 00:39:18'),
(11, 'dosen_197808162005011002_1778373708.jpg', 'Beni Widiawan, S.ST, MT', '197808162005011002', 'Teknologi Informasi', '-', 'beni@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:41:48', '2026-05-10 00:41:48'),
(12, 'dosen_198511282008121002_1778373817.jpg', 'Aji Seto Arifianto, S.ST., M.T.', '198511282008121002', 'Teknologi Informasi', '-', 'ajiseto@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:43:37', '2026-05-10 00:43:37'),
(13, 'dosen_199305102024062001_1778374007.jpg', 'Lukie Perdanasari, S.Kom., M.T.', '199305102024062001', 'Teknologi Informasi', '-', 'lukieperdanasari@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:46:47', '2026-05-10 00:46:47'),
(14, 'dosen_198012122005011001_1778374136.jpeg', 'Prawidya Destarianto, S.Kom, M.T', '198012122005011001', 'Teknologi Informasi', '-', 'prawidya@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:48:56', '2026-05-10 00:48:56'),
(15, 'dosen_199002272018032001_1778374309.png', 'Trismayanti Dwi P, S.Kom, M.Cs', '199002272018032001', 'Teknologi Informasi', '-', 'trismayanti@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:51:49', '2026-05-10 00:51:49'),
(16, 'dosen_199305082022032013_1778374396.png', 'Dia Bitari Mei Yuana, S.ST., M.Tr.Kom.', '199305082022032013', 'Teknologi Informasi', '-', 'dia.bitari@polije.ac.id', '-', '-', '-', 0, '2026-05-10 00:53:16', '2026-05-10 00:53:16'),
(17, 'dosen_197806142024211000_1778635651.png', 'Aditya Wahyu Pratama, S.T., M.T.', '197806142024211000', 'Teknik', '-', 'aditya_wa@polije', '-', '-', '-', 0, '2026-05-13 01:27:31', '2026-05-13 01:27:31'),
(18, 'dosen_198009272008011008_1778638694.png', 'Arif Wahyudiono, S.T., M.T.', '198009272008011008', 'Teknik', '-', 'arif_wahyudiono@polije.ac.id', '-', '-', '-', 0, '2026-05-13 02:18:14', '2026-05-13 02:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `data_kontak_center`
--

CREATE TABLE `data_kontak_center` (
  `id_kontak` int NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `foto_admin` varchar(255) DEFAULT 'default.jpg',
  `tgl_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_kontak_center`
--

INSERT INTO `data_kontak_center` (`id_kontak`, `nama_admin`, `jurusan`, `whatsapp`, `lokasi`, `foto_admin`, `tgl_dibuat`) VALUES
(5, 'Lukie Perdanasari, S.Kom., M.T.', 'Teknik Informatika', '087757636646', 'Gedung JTI, Lantai 1', '1777855454_TI.jpg', '2026-05-04 00:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `data_pkm`
--

CREATE TABLE `data_pkm` (
  `id_pkm` int NOT NULL,
  `foto_pkm` varchar(255) NOT NULL,
  `nama_pkm` varchar(255) NOT NULL,
  `singkatan` varchar(20) NOT NULL,
  `deskripsi_singkat` text,
  `panduan_umum` longtext,
  `panduan_penulisan` longtext,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_pkm`
--

INSERT INTO `data_pkm` (`id_pkm`, `foto_pkm`, `nama_pkm`, `singkatan`, `deskripsi_singkat`, `panduan_umum`, `panduan_penulisan`, `created_at`, `updated_at`) VALUES
(4, 'banner_pkm-re_1778034794.png', 'PKM Riset Eksakta', 'PKM-RE', 'Penelitian untuk memonitor kualitas air menggunakan sensor IoT secara real-time.', 'Program ini berfokus pada kegiatan penelitian ilmiah di bidang eksakta dengan pendekatan berbasis teknologi. Mahasiswa diharapkan mampu mengidentifikasi permasalahan nyata, merancang solusi berbasis riset, serta melakukan eksperimen secara sistematis. Kegiatan mencakup studi literatur, perancangan alat, pengambilan data lapangan, analisis data, hingga penyusunan laporan ilmiah. Program ini juga mendorong mahasiswa untuk menghasilkan inovasi yang dapat diterapkan di masyarakat serta dipublikasikan dalam jurnal ilmiah.', 'Proposal harus disusun secara sistematis dengan struktur yang jelas meliputi pendahuluan, tinjauan pustaka, metode penelitian, jadwal kegiatan, dan anggaran biaya. Pendahuluan harus memuat latar belakang yang didukung data dan referensi ilmiah. Tinjauan pustaka wajib menggunakan sumber terpercaya seperti jurnal dan buku akademik. Metode penelitian harus dijelaskan secara rinci agar dapat direplikasi. Penulisan menggunakan bahasa Indonesia baku, mengikuti format PKM, serta memperhatikan konsistensi sitasi dan daftar pustaka.', '2026-05-06 02:33:14', '2026-05-06 02:33:14'),
(5, 'banner_pkm-k_1778035331.png', 'PKM Kewirausahaan', 'PKM-K', 'am Kreativitas Mahasiswa Kewirausahaan (PKM-K) merupakan program kreativitas mahasiswa dalam menciptakan aktivitas usaha. Dalam PKM-K, tim mahasiswa berlatih membuat kreativitas produk usaha yang dibutuhkan masyarakat (pasar)', 'Melalui program\r\nPKM-K, mahasiswa memiliki kesempatan yang luas untuk meningkatkan kompetensinya\r\ndalam berkreasi dan berinovasi menciptakan produk baru, juga meningkatkan wawasan dan\r\npengalamannya dalam berwirausaha. PKM-K ini dilaksanakan di dalam dan di luar kampus\r\ndengan rentang waktu 3-4 bulan efektif atau dalam satu semester. Program PKM-K memiliki\r\nbobot yang cukup untuk dikonversikan ke dalam mata kuliah sesuai kurikulum yang berlaku,\r\ndan menjadi bagian dari kebijakan Merdeka Belajar - Kampus Merdeka (MBKM).\r\nPelaksanaan PKM-K mendukung target pencapaian Indikator Kinerja Utama (IKU)\r\nperguruan tinggi.\r\n', 'Judul PKM tidak boleh menggunakan akronim atau singkatan yang tidak baku dan hanya\r\ndiperbolehkan maksimal 20 kata.\r\nIsian kelengkapan\r\nyang dientrikan secara langsung interaktif pada SIMBelmawa, dan proses\r\npengesahan dilakukan dengan validasi oleh dosen pendamping dan pimpinan\r\nperguruan tinggi bidang kemahasiswaan. Isian kelengkapan sampul meliputi Judul\r\nPKM, bidang PKM, nama dan nomor induk tim mahasiswa, asal perguruan tinggi\r\npengusul, dan tahun usulan.\r\nIsi utama proposal\r\nyang dikemas dalam bentuk file pdf. Isi utama proposal terdiri dari: daftar isi,\r\nhalaman inti, dan lampiran. Halaman daftar isi diberi nomor halaman dengan huruf:\r\ni, ii, iii, …, yang diletakkan pada sudut kanan bawah. Penomoran halaman i dimulai\r\ndari Daftar Isi. Halaman inti adalah halaman proposal yang memuat Bab\r\nPendahuluan sampai dengan Daftar Pustaka. Halaman inti memuat maksimum 10\r\n(sepuluh) halaman. Halaman inti dan lampiran diberi nomor halaman dengan angka\r\narab: 1, 2, 3, …, yang diletakkan pada sudut kanan atas. Penomoran halaman 1 (satu)\r\ndimulai dari Bab Pendahuluan. File isi utama proposal diunggah ke SIMBelmawa\r\ndengan penamaan file: namaketua_namapt_PKM-K.pdf untuk divalidasi oleh dosen\r\npendamping dan disahkan oleh pimpinan perguruan tinggi bidang kemahasiswaan.\r\nIsi utama proposal ditulis dengan:\r\n1. Tipe huruf menggunakan Times New Roman ukuran 12;\r\n2. Teks paragraf menggunakan jarak baris 1,15 spasi dan perataan teks menggunakan rata\r\nkiri dan kanan;\r\n3. Layout menggunakan ukuran kertas A-4, satu kolom, margin kiri 4 cm, margin kanan,\r\natas, dan bawah masing-masing 3 cm.\r\n\r\n', '2026-05-06 02:42:11', '2026-05-13 07:59:53'),
(6, 'banner_pkm-pm_1778035382.png', 'PKM Pengabdian Masyarakat', 'PKM-PM', 'PKM-PM adalah program penerapan ilmu pengetahuan, teknologi dan seni yang berorientasi non-profit dalam upaya untuk membantu meningkatkan kualitas hidup, mengakhiri kemiskinan, mengurangi kesenjangan, dan melindungi lingkungan. Mitra dalam PKM-PM adalah masyarakat nonprofit, seperti lembaga pendidikan (formal maupun non-formal), instansi pemerintah, karang taruna, kelompok PKK (Pembinaan Kesejahteraan Keluarga), panti asuhan, atau lembaga sosial kemasyarakatan yang lain.', 'Sebelum menyusun proposal, mahasiswa menggali informasi secara langsung dengan terjun ke\r\nmasyarakat mitra atau mencari informasi dari sumber lain berkaitan dengan kondisi masyarakat\r\nmitra, atau berkomunikasi dengan masyarakat mitra untuk mendiskusikan kebutuhan atau\r\npersoalan prioritas yang harus diselesaikan. Setelah melalui interaksi dengan mitra, mahasiswa\r\nmembantu masyarakat mitra dalam memetakan masalah yang dihadapi, menentukan skala\r\nprioritas yang harus diselesaikan dan membantu menyelesaikan masalah tersebut.', 'Judul PKM tidak boleh menggunakan akronim atau singkatan yang tidak baku dan hanya\r\ndiperbolehkan maksimal 20 kata.\r\nIsian kelengkapan\r\nDientrikan secara langsung interaktif pada SIMBelmawa, dan proses pengesahan\r\ndilakukan dengan validasi oleh dosen pendamping dan pimpinan perguruan tinggi\r\nbidang kemahasiswaan. Isian kelengkapan sampul meliputi Judul PKM, bidang\r\nPKM, nama dan nomor induk tim mahasiswa, asal perguruan tinggi pengusul, dan\r\ntahun usulan.\r\nIsi utama proposal\r\nDikemas dalam bentuk file pdf. Isi utama proposal terdiri dari: daftar isi, halaman\r\ninti, dan lampiran. Halaman daftar isi diberi nomor halaman dengan huruf: i, ii, iii,\r\n…, yang diletakkan pada sudut kanan bawah. Penomoran halaman i dimulai dari\r\nDaftar Isi. Halaman inti adalah halaman proposal yang memuat Bab Pendahuluan\r\nsampai dengan Daftar Pustaka. Halaman inti memuat maksimum 10 (sepuluh)\r\nhalaman. Halaman inti dan lampiran diberi nomor halaman dengan angka arab: 1, 2,\r\n3, …, yang diletakkan pada sudut kanan atas. Penomoran halaman 1 (satu) dimulai\r\ndari Bab Pendahuluan. File isi utama proposal diunggah ke SIMBelmawa dengan\r\npenamaan file: namaketua_namapt_PKM-PM.pdf untuk divalidasi oleh dosen\r\npendamping dan disahkan oleh pimpinan perguruan tinggi bidang kemahasiswaan.\r\nIsi utama proposal ditulis dengan:\r\n1. Tipe huruf menggunakan Times New Roman ukuran 12;\r\n2. Teks paragraf menggunakan jarak baris 1,15 spasi dan perataan teks menggunakan rata\r\nkiri dan kanan;\r\n3. Layout menggunakan ukuran kertas A-4, satu kolom, margin kiri 4 cm, margin kanan,\r\natas, dan bawah masing-masing 3 cm.', '2026-05-06 02:43:02', '2026-05-13 07:57:04'),
(7, 'banner_pkm-kc_1778035427.png', 'PKM Karsa Cipta', 'PKM-KC', 'Program Kreativitas Mahasiswa Karsa Cipta (PKM-KC) merupakan wahana mahasiswa untuk mewujudkan ide konstruktif berbasis karsa dan nalar walaupun masih belum sampai pada tahap memberikan nilai fungsional yang sempurna dan atau kemanfaatan langsung bagi pihak lain. ', 'PKM-KC\r\nmenekankan keaslian ide atau minimal modifikasi produk yang sudah ada dan bukan\r\nmenggunakan atau menerapkan karya yang sudah ada.\r\nSebagai contoh pada Gambar 1. ditampilkan Pratima yang ditemukan di pulau Bali sebagai\r\nsalah satu sumber inspirasi PKM-KC. Pratima yang merupakan karya nyata umat Hindu\r\nmenjadi salah satu bagian penting Pura di Bali. Pratima rentan dicuri orang karena dinilai\r\nsakral, nilai budaya yang tinggi, kedudukannya yang tidak terpancang serta tidak ada\r\npenjagaan khusus. Sampai saat ini belum ada upaya melindunginya dari pencuri barang-barang\r\nantik dan berharga ini. ', 'Judul PKM tidak boleh menggunakan akronim atau singkatan yang tidak baku dan hanya\r\ndiperbolehkan maksimal 20 kata.\r\nIsian kelengkapan\r\nDientrikan secara langsung interaktif pada SIMBelmawa, dan proses pengesahan\r\ndilakukan dengan validasi oleh dosen pendamping dan pimpinan perguruan tinggi\r\nbidang kemahasiswaan. Isian kelengkapan sampul meliputi Judul PKM, bidang\r\nPKM, nama dan nomor induk tim mahasiswa, asal perguruan tinggi pengusul, dan\r\ntahun usulan.\r\nIsi utama proposal\r\nDikemas dalam bentuk file pdf. Isi utama proposal terdiri dari: daftar isi, halaman\r\ninti, dan lampiran. Halaman daftar isi diberi nomor halaman dengan huruf: i, ii, iii, \r\n…, yang diletakkan pada sudut kanan bawah. Penomoran halaman i dimulai dari\r\nDaftar Isi. Halaman inti adalah halaman proposal yang memuat Bab Pendahuluan\r\nsampai dengan Daftar Pustaka. Halaman inti memuat maksimum 10 (sepuluh)\r\nhalaman. Halaman inti dan lampiran diberi nomor halaman dengan angka arab: 1, 2,\r\n3, …, yang diletakkan pada sudut kanan atas. Penomoran halaman 1 (satu) dimulai\r\ndari Bab Pendahuluan. File isi utama proposal diunggah ke SIMBelmawa dengan\r\npenamaan file: namaketua_namapt_PKM-KC.pdf untuk divalidasi oleh dosen\r\npendamping dan disahkan oleh pimpinan perguruan tinggi bidang kemahasiswaan.\r\nIsi utama proposal ditulis dengan:\r\n1. Tipe huruf menggunakan Times New Roman ukuran 12;\r\n2. Teks paragraf menggunakan jarak baris 1,15 spasi dan perataan teks menggunakan rata\r\nkiri dan kanan;\r\n3. Layout menggunakan ukuran kertas A-4, satu kolom, margin kiri 4 cm, margin kanan,\r\natas, dan bawah masing-masing 3 cm.', '2026-05-06 02:43:47', '2026-05-13 07:24:38'),
(8, 'banner_pkm-td_1778035477.png', 'PKM Teknologi Digital', 'PKM-TD', 'Pengembangan aplikasi pemesanan tempat wisata secara online.', 'Program ini berfokus pada pengembangan solusi digital untuk mempermudah aktivitas masyarakat. Mahasiswa akan merancang dan mengembangkan aplikasi berbasis web atau mobile dengan fitur yang sesuai kebutuhan pengguna. Kegiatan meliputi analisis kebutuhan, desain sistem, implementasi, serta pengujian aplikasi. Program ini diharapkan menghasilkan produk digital yang siap digunakan', 'Proposal harus menjelaskan kebutuhan pengguna serta solusi yang ditawarkan secara jelas. Metode pengembangan sistem harus dijelaskan secara rinci, termasuk teknologi yang digunakan. Selain itu, perlu disertakan jadwal kegiatan dan anggaran biaya yang realistis. Penulisan harus mengikuti pedoman PKM, menggunakan bahasa formal, serta disusun secara sistematis dan mudah dipahami.', '2026-05-06 02:44:37', '2026-05-06 02:44:37'),
(9, 'banner_pkm-gf_1778036266.png', 'PKM Gagasan Futuristik', 'PKM-GF', 'PKM Gagasan Futuristik Tertulis (PKM-GFT) merupakan gagasan kreatif yang futuristik sebagai respons intelektual atas persoalan aktual yang dihadapi bangsa. Gagasan tersebut tidak terikat bidang ilmu, bersifat unik dan bermanfaat, sehingga kampus yang diidealisasikan sebagai pusat solusi dapat menjadi kenyataan. Sebagai intelektual muda, mahasiswa umumnya mempunyai potensi untuk mengungkapkan fakta-fakta yang terjadi di masyarakat, dan melalui PKM-GFT, mahasiswa dengan kemampuan nalarnya diberi kesempatan untuk mengungkap fakta-fakta tersebut sekaligus menawarkan solusi yang realistik dan implementatif.', 'PKM-GFT tidak mengenal batasan keilmuan (borderless) artinya mahasiswa dengan bidang\r\nilmu eksakta diperkenankan untuk menyusun PKM-GFT persoalan sosial budaya, demikian\r\npula sebaliknya. Namun disarankan agar tim PKM-GFT terdiri dari berbagai bidang ilmu.\r\nSebagai salah satu bidang PKM yang ditampilkan dalam PIMNAS, maka tata tertib dan segala\r\nsesuatu yang terkait pada persyaratan presentasi mengacu pada tata cara pelaksanaan PIMNAS.\r\n', 'Judul Artikel PKM-GFT tidak diperkenankan menggunakan akronim atau singkatan yang tidak\r\nbaku dan hanya diperbolehkan menggunakan maksimal 20 (dua puluh) kata.\r\nIsian kelengkapan\r\nDientrikan secara langsung interaktif pada SIMBelmawa, dan proses pengesahan\r\ndilakukan dengan validasi oleh dosen pendamping dan pimpinan perguruan tinggi\r\nbidang kemahasiswaan. Isian kelengkapan sampul meliputi Judul PKM, bidang\r\nPKM, nama dan nomor induk tim mahasiswa, asal perguruan tinggi pengusul, dan\r\ntahun usulan.\r\nIsi utama artikel PKM-GFT\r\nDikemas dalam bentuk file pdf. Isi utama artikel PKM-GFT terdiri dari: daftar isi,\r\nhalaman inti, dan lampiran. Halaman daftar isi diberi nomor halaman dengan huruf:\r\ni, ii, iii, …, yang diletakkan pada sudut kanan bawah. Penomoran halaman i dimulai\r\ndari Daftar Isi. Halaman inti adalah halaman yang memuat isi keseluruhan artikel\r\nPKM-GFT dari halaman pendahuluan sampai dengan halaman akhir daftar pustaka\r\nyang jumlahnya minimal 8 (delapan) dan maksimal 15 (lima belas) halaman. \r\nHalaman inti dan lampiran diberi nomor halaman dengan angka arab: 1, 2, 3, …,\r\nyang diletakkan pada sudut kanan atas. Penomoran halaman 1 (satu) dimulai dari\r\nhalaman Bab Pendahuluan. File isi utama artikel PKM-GFT diunggah ke\r\nSIMBelmawa dengan penamaan file: namaketua_namapt_PKM-GFT.pdf untuk\r\ndivalidasi oleh dosen pendamping dan disahkan oleh pimpinan perguruan tinggi\r\nbidang kemahasiswaan.\r\nIsi utama artikel PKM-GFT ditulis dengan:\r\n1. Tipe huruf menggunakan Times New Roman ukuran 12;\r\n2. Teks paragraf menggunakan jarak baris 1,15 spasi dan perataan teks menggunakan rata\r\nkiri dan kanan;\r\n3. Layout menggunakan ukuran kertas A-4, satu kolom, margin kiri 4 cm, margin kanan,\r\natas, dan bawah masing-masing 3 cm;\r\n4. Jumlah halaman inti dari “Pendahuluan” hingga “Daftar Pustaka” adalah 8-15 halaman.', '2026-05-06 02:57:46', '2026-05-13 07:18:26'),
(10, 'banner_pkm-ai_1778036324.png', 'PKM Artikel Ilmiah', 'PKM-AI', 'Program Kreativitas Mahasiswa Artikel Ilmiah (PKM-AI) adalah salah satu bidang PKM yang mempunyai tujuan utama membantu dan menyediakan media bagi mahasiswa Indonesia untuk membuat artikel ilmiah dari hasil kegiatan akademik berkelompok yang telah dilakukan.', 'Program Kreativitas Mahasiswa Artikel Ilmiah (PKM-AI) adalah salah satu bidang PKM yang\r\nmempunyai tujuan utama membantu dan menyediakan media bagi mahasiswa Indonesia untuk\r\nmembuat artikel ilmiah dari hasil kegiatan akademik berkelompok yang telah dilakukan.\r\nBerbeda dengan bidang PKM lainnya yang pelaksanaannya berupa kegiatan fisik di\r\nlaboratorium atau lapangan, PKM-AI tidak mengenal adanya kegiatan semacam itu. Jika dalam\r\nbidang PKM lainnya kelompok mahasiswa mengajukan proposal kegiatan ke Direktorat\r\nBelmawa, maka untuk PKM-AI kelompok mahasiswa cukup menyampaikan karya tulis dalam\r\nbentuk artikel ilmiah. Karya tersebut ditulis mengacu pada kegiatan yang telah selesai\r\ndilakukan kelompok mahasiswa dan belum pernah dipublikasikan pada media ilmiah maupun\r\ndiikutkan dalam kompetisi. Hasil kegiatan berkelompok yang dapat ditulis menjadi artikel\r\nilmiah untuk PKM-AI diantaranya adalah hasil Praktek Kerja Lapangan (PKL) atau Praktek\r\nPengalaman Lapangan (PPL), Kuliah Kerja Nyata (KKN) atau kegiatan akademik berkelompok\r\nlainnya, tetapi bukan tugas-tugas atau praktikum individu, perkuliahan, skripsi atau tugas akhir.\r\nHasil pelaksanaan PKM pendanaan yang tidak diundang ke PIMNAS tahun sebelumnya juga\r\ndapat diajukan untuk PKM-AI. ', 'Judul Artikel Ilmiah dibuat ringkas maksimal 20 kata dengan menonjolkan kata kunci kegiatan\r\nilmiah dan hasil utamanya, huruf kapital, serta hindari adanya singkatan. Naskah artikel ilmiah\r\nditulis dalam Bahasa Indonesia.\r\nArtikel Ilmiah yang disusun terdiri dari:\r\nIsian kelengkapan\r\nDientrikan secara langsung (interaktif) pada SIMBelmawa, dan proses pengesahan\r\ndilakukan dengan validasi oleh dosen pendamping dan pimpinan perguruan tinggi\r\nbidang kemahasiswaan. Isian kelengkapan sampul meliputi Judul PKM, bidang\r\nPKM, nama dan nomor induk tim mahasiswa, asal perguruan tinggi pengusul, dan\r\ntahun usulan.\r\nIsi utama artikel ilmiah\r\nDikemas dalam bentuk berkas (file) pdf. Isi utama artikel ilmiah terdiri dari: halaman\r\ninti dan lampiran. Halaman inti adalah halaman yang memuat isi keseluruhan artikel\r\nilmiah dari halaman judul sampai dengan halaman akhir daftar pustaka yang\r\njumlahnya minimal 8 (delapan) dan maksimal 15 (lima belas) halaman. Halaman inti\r\ndan lampiran diberi nomor halaman dengan angka arab: 1, 2, 3, …, yang diletakkan\r\npada sudut kanan atas. Penomoran halaman 1 (satu) dimulai dari halaman judul\r\nartikel ilmiah. Berkas (file) isi utama artikel ilmiah diunggah ke SIMBelmawa\r\ndengan penamaan file: namaketua_namapt_PKM-AI.pdf untuk divalidasi oleh dosen\r\npendamping dan disahkan oleh pimpinan perguruan tinggi bidang kemahasiswaan.\r\nIsi utama artikel ilmiah ditulis dengan:\r\n1. Tipe huruf menggunakan Times New Roman ukuran 12;\r\n2. Teks paragraf menggunakan jarak baris 1,15 spasi dan perataan teks menggunakan rata\r\nkiri dan kanan;\r\n3. Layout menggunakan ukuran kertas A-4, satu kolom, margin kiri 4 cm, margin kanan,\r\natas, dan bawah masing-masing 3 cm;\r\n4. Jumlah halaman inti dari “Judul” hingga “Daftar Pustaka” adalah 8-15 halaman;\r\n5. Isi utama artikel ilmiah terdiri dari halaman inti dan lampiran, tanpa ada halaman daftar\r\nisi.\r\nLAMPIRAN\r\nLampiran 1. Biodata Ketua dan Anggota, serta Dosen Pendamping;\r\nLampiran 2. Kontribusi ketua, anggota, dan dosen pendamping;\r\nLampiran 3. Surat Pernyataan Ketua Tim Penyusun;\r\nLampiran 4. Surat Pernyataan Sumber Tulisan.', '2026-05-06 02:58:44', '2026-05-13 07:12:25'),
(11, 'banner_pkm-vgk_1778036374.png', 'PKM Video Gagasan Konstruktif', 'PKM-VGK', 'PKM Video Gagasan Konstruktif (PKM-VGK) merupakan transformasi dari PKM Gagasan Futuristik Konstruktif (PKM-GFK). PKM-VGK diupayakan dalam rangka mengakomodasi kesenangan generasi saat ini dalam mengunggah konten di media sosial dan mewadahi dalam koridor kreativitas, keilmiahan, dan kemanfaatan. PKM-VGK menekankan pada gagasan bersifat pemecahan masalah secara konstruktif yang dikomunikasikan dalam bentuk konten media sosial.', 'Program PKM-VGK dilaksanakan secara luring, sehingga diharapkan terjadi pertemuan dan\r\ninteraksi langsung dalam pelaksanaan program, dengan tetap memperhatikan protokol\r\nkesehatan secara ketat.\r\nPelaksanaan PKM-VGK dilakukan melalui tahapan pengusulan proposal, pendanaan dan\r\nimplementasi, yang nantinya akan bermuara ke Pekan Ilmiah Mahasiswa Nasional (PIMNAS).\r\nTim mahasiswa mengusulkan gagasan konten komunikasi melalui proposal. Apabila proposal\r\ntersebut dinilai layak, maka akan diberikan pendanaan untuk pelaksanaannya. Tim yang\r\nmemiliki kreativitas dan pelaksanaan yang dinilai baik, selanjutnya akan diundang untuk\r\npresentasi dalam PIMNAS.\r\nPada tahap pengusulan proposal, tim mahasiswa mengusulkan konten komunikasi dari\r\npermasalahan keprihatinan bangsa dan/atau SDGs yang ingin diselesaikan serta metode\r\npelaksanaan kegiatannya. Konten komunikasi yang diusulkan yaitu konten media sosial di\r\nYouTube. Konten komunikasi tersebut berisi permasalahan yang akan diselesaikan secara\r\nkonstruktif, dasar keilmiahan, dan kreativitas penyelesaian masalahnya. Jangka waktu\r\npenyelesaian masalah dapat bersifat jangka pendek atau jangka panjang, tergantung dari\r\nkebutuhan penyelesaian masalahnya. ', 'Judul PKM-VGK tidak diperkenankan menggunakan akronim atau singkatan yang tidak baku\r\ndan hanya diperbolehkan menggunakan maksimal 20 (dua puluh) kata. Gagasan konten yang\r\ndiusulkan diberi judul yang berkaitan dengan 6 (enam) keprihatinan bangsa Indonesia dan/atau\r\n17 (tujuh belas) tujuan pembangunan berkelanjutan (SDGs).\r\nIsian kelengkapan\r\nDientrikan secara langsung interaktif pada SIMBelmawa, dan proses pengesahan\r\ndilakukan dengan validasi oleh dosen pendamping dan pimpinan perguruan tinggi\r\nbidang kemahasiswaan. Isian kelengkapan sampul meliputi Judul PKM, bidang\r\nPKM, nama dan nomor induk tim mahasiswa, asal perguruan tinggi pengusul, dan\r\ntahun usulan.\r\nIsi utama proposal\r\nDikemas dalam bentuk file pdf. Isi utama proposal terdiri dari: daftar isi, halaman\r\ninti, dan lampiran. Halaman daftar isi diberi nomor halaman dengan huruf: i, ii, iii,\r\n…, yang diletakkan pada sudut kanan bawah. Penomoran halaman i dimulai dari\r\nDaftar Isi. Halaman inti adalah halaman proposal yang memuat Bab Pendahuluan\r\nsampai dengan Daftar Pustaka. Halaman inti memuat maksimum 10 (sepuluh)\r\nhalaman. Halaman inti dan lampiran diberi nomor halaman dengan angka arab: 1, 2,\r\n3, …, yang diletakkan pada sudut kanan atas. Penomoran halaman 1 (satu) dimulai\r\ndari Bab Pendahuluan. File isi utama proposal diunggah ke SIMBelmawa dengan\r\npenamaan file: namaketua_namapt_PKM-VGK.pdf untuk divalidasi oleh dosen\r\npendamping dan disahkan oleh pimpinan perguruan tinggi bidang kemahasiswaan.\r\nIsi utama proposal ditulis dengan:\r\n1. Tipe huruf menggunakan Times New Roman ukuran 12;\r\n2. Teks menggunakan jarak baris 1,15 spasi dan perataan teks menggunakan rata kiri dan\r\nkanan;\r\n3. Layout menggunakan ukuran kertas A-4, satu kolom, margin kiri 4 cm, margin kanan,\r\natas, dan bawah masing-masing 3 cm.', '2026-05-06 02:59:34', '2026-05-13 07:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `data_tim_pkm`
--

CREATE TABLE `data_tim_pkm` (
  `id_tim` int NOT NULL,
  `id_ketua` int NOT NULL,
  `nama_tim` varchar(100) NOT NULL,
  `kategori_pkm` varchar(50) NOT NULL,
  `deskripsi_projek` text NOT NULL,
  `status_tim` enum('Mencari Anggota','Penuh','Selesai') DEFAULT 'Mencari Anggota',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_tim_pkm`
--

INSERT INTO `data_tim_pkm` (`id_tim`, `id_ketua`, `nama_tim`, `kategori_pkm`, `deskripsi_projek`, `status_tim`, `created_at`) VALUES
(18, 17, 'Spontan Juara', 'PKM-KC', 'Membuat aplikasi monitoring bawang bermasih IoT', 'Mencari Anggota', '2026-05-05 17:53:23'),
(20, 24, 'Spontan Full', 'PKM-KC', 'Membuat aplikasi etol berbasis boking', 'Mencari Anggota', '2026-05-06 03:14:11'),
(22, 23, 'Prime Spontan', 'PKM-KC', 'Membuat aplikasi mendeteksi satelit nasa', 'Mencari Anggota', '2026-05-06 03:29:56'),
(24, 20, 'Laba Laba 12', 'PKM-KC', 'Membuat satelit', 'Mencari Anggota', '2026-05-06 08:36:45'),
(25, 32, 'Sukowono maju', 'PKM-KC', 'pembuatan panel surya', 'Mencari Anggota', '2026-05-09 07:07:01'),
(28, 40, 'Amin Projek V1', 'PKM-KC', 'Membuat IoT Roket', 'Mencari Anggota', '2026-05-13 07:36:23');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pkm`
--

CREATE TABLE `jadwal_pkm` (
  `id_jadwal` int NOT NULL,
  `judul_jadwal` varchar(255) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_berakhir` date NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_pkm`
--

INSERT INTO `jadwal_pkm` (`id_jadwal`, `judul_jadwal`, `tgl_mulai`, `tgl_berakhir`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 'Pembuatan Rancangan', '2026-05-29', '2026-05-30', 'Wajib', '2026-05-01 15:02:27', '2026-05-01 15:02:27'),
(3, 'Pendaftaran Tim', '2026-05-02', '2026-05-14', 'Wajib', '2026-05-01 15:09:05', '2026-05-01 17:51:53'),
(4, 'Pembuatan Proposal', '2026-06-03', '2026-06-17', 'wajib', '2026-05-01 17:53:31', '2026-05-01 17:53:31'),
(5, 'Pemgumpulan Proposal Akhir', '2026-05-13', '2026-05-23', 'Jangan telat', '2026-05-13 07:50:57', '2026-05-13 07:50:57');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_tim`
--

CREATE TABLE `pendaftaran_tim` (
  `id_pendaftaran` int NOT NULL,
  `id_tim` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `pesan_tambahan` text,
  `status_pendaftaran` enum('Pending','Diterima','Ditolak') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pendaftaran_tim`
--

INSERT INTO `pendaftaran_tim` (`id_pendaftaran`, `id_tim`, `id_mahasiswa`, `pesan_tambahan`, `status_pendaftaran`, `created_at`, `updated_at`) VALUES
(49, 18, 28, NULL, 'Diterima', '2026-05-06 03:01:41', '2026-05-06 03:07:59'),
(51, 18, 26, NULL, 'Diterima', '2026-05-06 03:10:03', '2026-05-06 03:10:08'),
(52, 18, 25, NULL, 'Diterima', '2026-05-06 03:10:49', '2026-05-06 03:10:54'),
(53, 22, 22, NULL, 'Pending', '2026-05-06 08:25:20', '2026-05-06 08:25:20'),
(55, 25, 33, NULL, 'Diterima', '2026-05-09 07:37:50', '2026-05-09 08:34:07'),
(56, 22, 17, NULL, 'Pending', '2026-05-09 08:32:09', '2026-05-09 08:32:09'),
(60, 18, 27, NULL, 'Diterima', '2026-05-13 07:43:51', '2026-05-13 07:44:11'),
(61, 28, 34, NULL, 'Pending', '2026-05-13 09:19:11', '2026-05-13 09:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` int NOT NULL,
  `judul_pesan` varchar(255) NOT NULL,
  `tujuan_pesan` enum('Semua','Mahasiswa','Dosen') NOT NULL,
  `isi_pesan` longtext NOT NULL,
  `tgl_kirim` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesan`
--

INSERT INTO `pesan` (`id_pesan`, `judul_pesan`, `tujuan_pesan`, `isi_pesan`, `tgl_kirim`) VALUES
(2, 'Pengingat Batas Pengumpulan Proposal PKM', 'Semua', 'Halo mahasiswa!\r\n\r\nBerikut beberapa tips agar proposal PKM kamu lolos:\r\n1. Pastikan ide kamu inovatif dan belum pernah ada\r\n2. Gunakan bahasa yang jelas dan sistematis\r\n3. Sertakan data pendukung yang valid\r\n4. Konsultasikan dengan dosen pembimbing\r\n\r\nSemangat dan jangan menyerah!', '2026-05-01 16:02:22'),
(3, 'Rapat Dosen PKM', 'Mahasiswa', 'Wajib Hadir', '2026-05-13 07:49:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_mahasiswa`
--

CREATE TABLE `user_mahasiswa` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `email_polije` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `jurusan` enum('PP','TP','PETERNAKAN','MNA','TI','BKP','KESEHATAN','TEKNIK','BISNIS') NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `angkatan` year NOT NULL,
  `password` varchar(255) NOT NULL,
  `khs_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_mahasiswa`
--

INSERT INTO `user_mahasiswa` (`id`, `nama_lengkap`, `nim`, `email_polije`, `no_whatsapp`, `gender`, `jurusan`, `program_studi`, `angkatan`, `password`, `khs_image`, `created_at`) VALUES
(17, 'Trivaldo Aminullah', 'E41250470', 'e41250470@student.polije.ac.id', '089601991505', 'L', 'TI', 'Teknik Informatika', 2025, '$2y$10$ZB.UL4NxFOn4q8wOu7Jvbe5XNJhCNwJsMpsq/JM2LBdMy.Y8NTaHS', 'E41250470_1778003519.png', '2026-05-05 17:51:59'),
(18, 'Zalifa Naila Salsabila', 'E41250482', 'E41250482@student.polije.ac.id', '085258629618', 'P', 'TI', 'Teknik Informatika', 2025, '$2y$10$OG1a1fLofUGn1/ITg0TCQeZI4JeLn7RQvKp1cdQcsEFnSITRV9fLy', 'E41250482_1778004752.png', '2026-05-05 18:12:32'),
(19, 'Andi Pratama', 'E4121001', 'e4121001@student.polije.ac.id', '081234567801', 'L', 'TI', 'Teknik Informatika', 2025, '$2y$10$y.M9Cghowz6Co7YQcbXTDObCiaZjOxbG1VGHdE.Fp5pOWYKXNNINK', 'E4121001_1778030029.png', '2026-05-06 01:13:49'),
(20, 'Budi Santoso', 'E4121002', 'e4121002@student.polije.ac.id', '081234567802', 'L', 'TI', 'Manajemen Informatika', 2023, '$2y$10$QeiCaZDwte/zzOjJYayGGO2oZUeTrCIinohruVyicEyWdnw5U5ygq', 'E4121002_1778030100.png', '2026-05-06 01:15:00'),
(21, 'Citra Dewi', 'E4121003', 'e4121003@student.polije.ac.id', '081234567803', 'P', 'BKP', 'Bahasa Inggris', 2023, '$2y$10$02V3B/Q4CbMi0afHLsJ4X.s4Nxj85wF6ebS0pYLHbhec8xk/7WsN.', 'E4121003_1778030163.png', '2026-05-06 01:16:03'),
(22, 'Dewi Lestari', 'E4121004', 'e4121004@student.polije.ac.id', '081234567804', 'P', 'BKP', 'Bahasa Inggris', 2024, '$2y$10$pT13Sz0ubK4LeTm8x9SOg.dxJS7Jfq2sOBzmGhEEVefh11gIbSN/i', 'E4121004_1778030239.png', '2026-05-06 01:17:19'),
(23, 'Eko Saputra', 'E4121005', 'e4121005@student.polije.ac.id', '081234567805', 'L', 'TEKNIK', 'Mesin', 2024, '$2y$10$IWRWKP52LAqcOHDid.h5JOcW3xJIXEgwumy4AHphtHgKw58CvH3v6', 'E4121005_1778030312.png', '2026-05-06 01:18:32'),
(24, 'Fajar Hidayat', 'E4121006', 'e4121006@student.polije.ac.id', '081234567806', 'L', 'BISNIS', 'Bisnis Industri', 2023, '$2y$10$b9Z2YQBu.8tAKj6v/1duHeaAA.l8p/Zyi31gc7sCdjntIa4kvmK5G', 'E4121006_1778030458.png', '2026-05-06 01:20:58'),
(25, 'Gita Permata', 'E4121007', 'e4121007@student.polije.ac.id', '081234567807', 'P', 'TI', 'Teknik Informatika', 2025, '$2y$10$ZEiQycUrD5wcilCJI.6Yt.E7877Ai.O0hNSMK4HH/R24U07xQA2wC', 'E4121007_1778030529.png', '2026-05-06 01:22:09'),
(26, 'Hendra Wijaya', 'E4121008', 'e4121008@student.polije.ac.id', '081234567808', 'L', 'TI', 'Teknik Informatika', 2023, '$2y$10$G0KpPiuVEXTprVaVwWr7YuFI7rS054eUOGixaVaz6um6MRHbXvZ52', 'E4121008_1778031962.png', '2026-05-06 01:46:02'),
(27, 'Intan Sari', 'E4121009', 'e4121009@student.polije.ac.id', '081234567809', 'P', 'BKP', 'Pariwisata', 2023, '$2y$10$0M68VljNqJRGtXl88jmOx.Y//dsEU7FidDN0rYn4HCZxWEgtFaWJC', 'KHS_E4121009_1778657970.png', '2026-05-06 01:48:22'),
(28, 'Joko Susilo', 'E4121010', 'e4121010@student.polije.ac.id', '081234567810', 'L', 'TI', 'Teknik Informatika', 2022, '$2y$10$tyVgUGVCMrPIj1zimtktrOin8tpka3bqfaQPALqJtlk2IaJKzcj12', 'KHS_E4121010_1778036631.png', '2026-05-06 01:49:52'),
(29, 'Maulana Izzulhaq Imron', 'E41250579', 'maulanaizzulhaq.dax21@gmail.com', '083851537009', 'L', 'TI', 'Teknik Informatika', 2025, '$2y$10$gQD0s6enQPo0ItZbCEaJIe3uk1vZJfagbXHNhW2Q/5E2Mrxw2mcu2', 'E41250579_1778042602.jpg', '2026-05-06 04:43:22'),
(30, 'Agnes Riskiya ismi', 'E41250685', 'e41250685@student.polije.ac.id', '089685438', 'P', 'TI', 'Teknik Informatika', 2025, '$2y$10$fbfUeTRfHFOiJ2w.93lKQObpeEb1BemKEm9NtbE4L1Of8jPMYv6O.', 'E41250685_1778308173.png', '2026-05-09 06:29:33'),
(32, 'Sugi Hartanto ', 'E4125023', 'e4125023@polije.ac.id', '081155337788', 'L', 'TEKNIK', 'Teknik Elektro', 2025, '$2y$10$PGWmYj0Lzq.EJdwnIRsmbuvi.SbKUgo8saP3cUaONPEYLmmb9DkvO', 'E4125023_1778310289.jpg', '2026-05-09 07:04:49'),
(33, 'Rena Hartono', 'e4125654', 'e4125654@polije.ac.id', '081299995555', 'L', 'TI', 'Teknik Informatika', 2025, '$2y$10$B2UuXjWl6xeF2IT4QDnDXe3KYRoaFo5wPQhdIvVia/YMNBzsBQsUS', 'e4125654_1778311280.jpg', '2026-05-09 07:21:20'),
(34, 'Niken Putri Lestari', 'E41250812', 'e41250812@student.polije.ac.id', '0812352922111', 'P', 'TI', 'Teknik Informatika', 2025, '$2y$10$NsXFDhtELFqkncEjjDLnlOheLn5OiUPSvW.ZykflZtEOBMXdEeVCW', 'E41250812_1778590352.jpg', '2026-05-12 12:52:33'),
(37, 'Fahmi Rozik', 'E41234567', 'e41234567@student.polije.ac.id', '089601991303', 'L', 'TI', 'Teknik Informatika', 2025, '$2y$10$gL4VmjwB7JyDWrQO/NoCXe12xFwc./twOZTlbQI0Mqu9a6ZrNYTCi', 'E41234567_1778657434.png', '2026-05-13 07:30:34'),
(40, 'Bambang Madrid', 'E412345678', 'e412345678@student.polije.ac.id', '0897648382', 'L', 'TI', 'Manajemen Informatika', 2023, '$2y$10$B/kCaIGqZFUL7f1n6BkDkuy/PCrxuAFY6FbfBvw05BjrULRGHcBEW', 'E412345678_1778657699.png', '2026-05-13 07:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_pengelola`
--

CREATE TABLE `user_pengelola` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','humas') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_pengelola`
--

INSERT INTO `user_pengelola` (`id`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin@polije.ac.id', '0192023a7bbd73250516f069df18b500', 'admin', '2026-04-14 04:05:51'),
(2, 'humas@polije.ac.id', '441ebb68b40aaaf53d0085e04b32ba40', 'humas', '2026-04-14 04:05:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_berita`
--
ALTER TABLE `data_berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `data_dosen`
--
ALTER TABLE `data_dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `data_kontak_center`
--
ALTER TABLE `data_kontak_center`
  ADD PRIMARY KEY (`id_kontak`);

--
-- Indexes for table `data_pkm`
--
ALTER TABLE `data_pkm`
  ADD PRIMARY KEY (`id_pkm`);

--
-- Indexes for table `data_tim_pkm`
--
ALTER TABLE `data_tim_pkm`
  ADD PRIMARY KEY (`id_tim`),
  ADD KEY `id_ketua` (`id_ketua`);

--
-- Indexes for table `jadwal_pkm`
--
ALTER TABLE `jadwal_pkm`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `pendaftaran_tim`
--
ALTER TABLE `pendaftaran_tim`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `id_tim` (`id_tim`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`);

--
-- Indexes for table `user_mahasiswa`
--
ALTER TABLE `user_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email_polije` (`email_polije`);

--
-- Indexes for table `user_pengelola`
--
ALTER TABLE `user_pengelola`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_berita`
--
ALTER TABLE `data_berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `data_dosen`
--
ALTER TABLE `data_dosen`
  MODIFY `id_dosen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `data_kontak_center`
--
ALTER TABLE `data_kontak_center`
  MODIFY `id_kontak` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `data_pkm`
--
ALTER TABLE `data_pkm`
  MODIFY `id_pkm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `data_tim_pkm`
--
ALTER TABLE `data_tim_pkm`
  MODIFY `id_tim` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `jadwal_pkm`
--
ALTER TABLE `jadwal_pkm`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pendaftaran_tim`
--
ALTER TABLE `pendaftaran_tim`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_mahasiswa`
--
ALTER TABLE `user_mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user_pengelola`
--
ALTER TABLE `user_pengelola`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_tim_pkm`
--
ALTER TABLE `data_tim_pkm`
  ADD CONSTRAINT `data_tim_pkm_ibfk_1` FOREIGN KEY (`id_ketua`) REFERENCES `user_mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran_tim`
--
ALTER TABLE `pendaftaran_tim`
  ADD CONSTRAINT `pendaftaran_tim_ibfk_1` FOREIGN KEY (`id_tim`) REFERENCES `data_tim_pkm` (`id_tim`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_tim_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `user_mahasiswa` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
