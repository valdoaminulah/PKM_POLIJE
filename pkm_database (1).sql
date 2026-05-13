-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 06, 2026 at 07:15 AM
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
(2, 'dosen_1234567800_1777618662.jpg', 'Tintang Tirta.MT.PSHT.PN', '1234567800', 'Teknologi Informasi', '098765432112', 'valdoaminullah903@gmail.com', 'Lintang', '@lintang11', 'lintang', 101, '2026-05-01 06:57:42', '2026-05-01 17:30:48');

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
(5, 'Lukie Perdanasari, S.Kom., M.T.', 'Teknologi Informasi', '087757636646', 'Gedung J, Lantai 1', '1777855454_TI.jpg', '2026-05-04 00:44:14');

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
(5, 'banner_pkm-k_1778035331.png', 'PKM Kewirausahaan', 'PKM-K', 'Pengembangan bisnis minuman herbal sehat dengan inovasi rasa modern', 'Program ini bertujuan mengembangkan jiwa kewirausahaan mahasiswa melalui penciptaan usaha yang inovatif dan berkelanjutan. Mahasiswa akan merancang produk, melakukan produksi, serta memasarkan produk secara langsung kepada konsumen. Kegiatan juga mencakup analisis pasar, branding, strategi promosi, serta pengelolaan keuangan usaha. Program ini diharapkan mampu menghasilkan usaha yang memiliki nilai jual tinggi dan dapat terus berkembang.', 'Proposal harus memuat analisis peluang usaha yang jelas, termasuk target pasar dan keunggulan produk. Bagian rencana usaha harus menjelaskan proses produksi, strategi pemasaran, dan model bisnis secara detail. Selain itu, wajib disertakan analisis SWOT dan perencanaan keuangan yang realistis. Penulisan harus sistematis, menggunakan bahasa formal, serta mengikuti pedoman PKM. Semua data yang digunakan harus relevan dan dapat dipertanggungjawabkan.', '2026-05-06 02:42:11', '2026-05-06 02:42:11'),
(6, 'banner_pkm-pm_1778035382.png', 'PKM Pengabdian Masyarakat', 'PKM-PM', 'Pendampingan UMKM desa dalam memanfaatkan teknologi digital', 'Program ini berfokus pada pemberdayaan masyarakat melalui penerapan ilmu pengetahuan dan teknologi. Mahasiswa akan bekerja sama dengan mitra untuk mengidentifikasi masalah, merancang solusi, dan melaksanakan kegiatan yang berdampak langsung. Kegiatan meliputi pelatihan, pendampingan, serta evaluasi hasil program. Program ini diharapkan mampu meningkatkan kesejahteraan masyarakat serta memberikan solusi berkelanjutan.', 'Proposal harus menjelaskan kondisi mitra secara rinci serta permasalahan yang dihadapi. Metode pelaksanaan harus dijelaskan secara bertahap dan realistis. Mahasiswa juga perlu mencantumkan indikator keberhasilan serta dampak program. Penulisan harus jelas, sistematis, dan sesuai dengan struktur PKM. Data yang digunakan harus valid dan relevan dengan kondisi lapangan.', '2026-05-06 02:43:02', '2026-05-06 02:43:02'),
(7, 'banner_pkm-kc_1778035427.png', 'PKM Karsa Cipta', 'PKM-KC', 'Tempat sampah pintar yang dapat terbuka otomatis dan memilah sampah.', 'Program ini berfokus pada penciptaan produk inovatif berbasis teknologi. Mahasiswa diharapkan mampu merancang dan mengembangkan alat yang memiliki nilai guna tinggi. Kegiatan meliputi desain produk, pemilihan komponen, perakitan, serta pengujian alat. Program ini mendorong kreativitas dan inovasi dalam menciptakan solusi teknologi yang aplikatif.', 'Proposal harus menjelaskan latar belakang kebutuhan produk serta solusi yang ditawarkan. Bagian metode harus menjelaskan proses perancangan dan pembuatan produk secara detail. Selain itu, perlu dijelaskan keunggulan produk dibandingkan produk yang sudah ada. Penulisan harus mengikuti format PKM dan menggunakan bahasa yang baku serta sistematis.', '2026-05-06 02:43:47', '2026-05-06 02:43:47'),
(8, 'banner_pkm-td_1778035477.png', 'PKM Teknologi Digital', 'PKM-TD', 'Pengembangan aplikasi pemesanan tempat wisata secara online.', 'Program ini berfokus pada pengembangan solusi digital untuk mempermudah aktivitas masyarakat. Mahasiswa akan merancang dan mengembangkan aplikasi berbasis web atau mobile dengan fitur yang sesuai kebutuhan pengguna. Kegiatan meliputi analisis kebutuhan, desain sistem, implementasi, serta pengujian aplikasi. Program ini diharapkan menghasilkan produk digital yang siap digunakan', 'Proposal harus menjelaskan kebutuhan pengguna serta solusi yang ditawarkan secara jelas. Metode pengembangan sistem harus dijelaskan secara rinci, termasuk teknologi yang digunakan. Selain itu, perlu disertakan jadwal kegiatan dan anggaran biaya yang realistis. Penulisan harus mengikuti pedoman PKM, menggunakan bahasa formal, serta disusun secara sistematis dan mudah dipahami.', '2026-05-06 02:44:37', '2026-05-06 02:44:37'),
(9, 'banner_pkm-gf_1778036266.png', 'PKM Gagasan Futuristik', 'PKM-GF', 'Pengembangan ide kota pintar berbasis kecerdasan buatan untuk masa depan.', 'Program ini berfokus pada pengembangan gagasan inovatif yang bersifat futuristik dan visioner dalam menjawab tantangan masa depan. Mahasiswa diharapkan mampu mengidentifikasi permasalahan global seperti urbanisasi, lingkungan, dan teknologi, kemudian merumuskan solusi kreatif berbasis kecerdasan buatan. Kegiatan meliputi eksplorasi ide, analisis tren teknologi, serta penyusunan konsep solusi yang aplikatif. Program ini tidak menekankan pada implementasi langsung, melainkan pada kekuatan ide dan potensi pengembangannya di masa depan.', 'Proposal harus menekankan kekuatan ide dan kejelasan konsep yang ditawarkan. Bagian pendahuluan harus menjelaskan latar belakang masalah secara global dan relevan dengan perkembangan zaman. Mahasiswa harus menyusun argumentasi yang logis serta didukung referensi yang kuat. Struktur proposal harus sistematis dan mencakup penjelasan konsep, manfaat, serta potensi implementasi di masa depan. Penulisan harus menggunakan bahasa formal dan mengikuti pedoman PKM.', '2026-05-06 02:57:46', '2026-05-06 02:57:46'),
(10, 'banner_pkm-ai_1778036324.png', 'PKM Artikel Ilmiah', 'PKM-AI', 'Penulisan artikel ilmiah berdasarkan kajian literatur dan data sekunder.', 'Program ini berfokus pada penyusunan karya ilmiah berupa artikel yang berasal dari hasil kajian literatur atau penelitian yang telah dilakukan sebelumnya. Mahasiswa diharapkan mampu menganalisis data, mengolah informasi, serta menyusun argumen ilmiah secara logis dan sistematis. Kegiatan meliputi pengumpulan referensi, analisis data, serta penulisan artikel sesuai standar jurnal ilmiah. Program ini bertujuan meningkatkan kemampuan akademik mahasiswa dalam bidang penulisan ilmiah.', 'Proposal harus disusun dengan format artikel ilmiah yang jelas dan sistematis. Bagian pendahuluan harus memuat latar belakang dan tujuan penulisan. Tinjauan pustaka harus menggunakan referensi yang relevan dan terbaru. Metode penulisan harus dijelaskan secara rinci, termasuk teknik analisis data. Penulisan harus mengikuti kaidah ilmiah, menggunakan bahasa baku, serta memperhatikan sitasi dan daftar pustaka sesuai standar.', '2026-05-06 02:58:44', '2026-05-06 02:58:44'),
(11, 'banner_pkm-vgk_1778036374.png', 'PKM Video Gagasan Konstruktif', 'PKM-VGK', 'Penyampaian ide kreatif melalui video edukatif berbasis isu lingkungan.', 'Program ini bertujuan menyampaikan gagasan konstruktif melalui media video yang kreatif dan informatif. Mahasiswa diharapkan mampu mengemas ide menjadi konten visual yang menarik dan mudah dipahami. Kegiatan meliputi penulisan naskah, produksi video, editing, serta publikasi. Program ini mendorong mahasiswa untuk berkontribusi dalam penyebaran informasi positif melalui media digital.', 'Proposal harus memuat konsep video secara jelas, termasuk tema, alur cerita, serta pesan yang ingin disampaikan. Mahasiswa harus menjelaskan proses produksi video mulai dari pra-produksi, produksi, hingga pasca-produksi. Penulisan harus sistematis dan menggunakan bahasa yang jelas. Selain itu, proposal harus mencantumkan target audiens serta media distribusi video.', '2026-05-06 02:59:34', '2026-05-06 02:59:34');

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
(18, 17, 'Spontan V1', 'PKM-KC', 'Membuat aplikasi monitoring bawang bermasih IoT', 'Mencari Anggota', '2026-05-05 17:53:23'),
(20, 24, 'Spontan Full', 'PKM-KC', 'Membuat aplikasi etol berbasis boking', 'Mencari Anggota', '2026-05-06 03:14:11'),
(22, 23, 'Prime Spontan', 'PKM-KC', 'Membuat aplikasi mendeteksi satelit nasa', 'Mencari Anggota', '2026-05-06 03:29:56');

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
(4, 'Pembuatan Proposal', '2026-06-03', '2026-06-17', 'wajib', '2026-05-01 17:53:31', '2026-05-01 17:53:31');

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
(50, 18, 27, NULL, 'Diterima', '2026-05-06 03:09:23', '2026-05-06 03:09:30'),
(51, 18, 26, NULL, 'Diterima', '2026-05-06 03:10:03', '2026-05-06 03:10:08'),
(52, 18, 25, NULL, 'Diterima', '2026-05-06 03:10:49', '2026-05-06 03:10:54');

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
(2, 'Pengingat Batas Pengumpulan Proposal PKM', 'Semua', 'Halo mahasiswa!\r\n\r\nBerikut beberapa tips agar proposal PKM kamu lolos:\r\n1. Pastikan ide kamu inovatif dan belum pernah ada\r\n2. Gunakan bahasa yang jelas dan sistematis\r\n3. Sertakan data pendukung yang valid\r\n4. Konsultasikan dengan dosen pembimbing\r\n\r\nSemangat dan jangan menyerah!', '2026-05-01 16:02:22');

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
(27, 'Intan Sari', 'E4121009', 'e4121009@student.polije.ac.id', '081234567809', 'P', 'BKP', 'Pariwisata', 2023, '$2y$10$0M68VljNqJRGtXl88jmOx.Y//dsEU7FidDN0rYn4HCZxWEgtFaWJC', 'E4121009_1778032102.png', '2026-05-06 01:48:22'),
(28, 'Joko Susilo', 'E4121010', 'e4121010@student.polije.ac.id', '081234567810', 'L', 'TI', 'Teknik Informatika', 2022, '$2y$10$tyVgUGVCMrPIj1zimtktrOin8tpka3bqfaQPALqJtlk2IaJKzcj12', 'KHS_E4121010_1778036631.png', '2026-05-06 01:49:52'),
(29, 'Maulana Izzulhaq Imron', 'E41250579', 'maulanaizzulhaq.dax21@gmail.com', '083851537009', 'L', 'TI', 'Teknik Informatika', 2025, '$2y$10$gQD0s6enQPo0ItZbCEaJIe3uk1vZJfagbXHNhW2Q/5E2Mrxw2mcu2', 'E41250579_1778042602.jpg', '2026-05-06 04:43:22');

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
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `data_dosen`
--
ALTER TABLE `data_dosen`
  MODIFY `id_dosen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id_tim` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `jadwal_pkm`
--
ALTER TABLE `jadwal_pkm`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pendaftaran_tim`
--
ALTER TABLE `pendaftaran_tim`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_mahasiswa`
--
ALTER TABLE `user_mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
