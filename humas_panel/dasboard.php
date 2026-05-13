<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Ambil Total Berita dari Database
try {
    $sql = "SELECT COUNT(*) as total FROM data_berita";
    $stmt = $pdo->query($sql);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_berita = $data['total'];
} catch (PDOException $e) {
    $total_berita = 0; // Fallback jika error
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Logo Web -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <!-- Logo web -->
  <title>Dashboard Humas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <div class="flex min-h-screen">

  <!-- SIDEBAR -->
<!-- Tambahan: sticky top-0 h-screen agar tetap di tempat saat di-scroll -->
<aside class="w-64 bg-white shadow-lg p-6 sticky top-0 h-screen flex flex-col">
  <h1 class="text-xl font-bold text-gray-800">Dashboard Humas</h1>
  <p class="text-sm text-gray-500 mb-8">Manajemen Berita</p>

  <nav class="space-y-4 flex-1">
    <!-- Dashboard (Active) -->
    <a href="./dasboard.php" class="flex items-center gap-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 3h7v4h-7v-4z" />
      </svg>
      <span>Dashboard</span>
    </a>

    <!-- Input Berita -->
    <a href="./tambah_berita.php" class="flex items-center gap-3 text-gray-600 hover:text-blue-500 px-4 py-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      <span>Input Berita</span>
    </a>

    <!-- Tabel Berita -->
    <a href="./tabel_berita.php" class="flex items-center gap-3 text-gray-600 hover:text-blue-500 px-4 py-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18M3 7v10M9 7v10M15 7v10M21 7v10" />
      </svg>
      <span>Tabel Berita</span>
    </a>
  </nav>

  <!-- Logout diletakkan di bawah (opsional) -->
  <div class="mt-auto">
    <a href="../login_user/proses_logout_user.php" class="flex items-center gap-3 text-red-500 hover:text-red-600 px-4 py-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V4" />
      </svg>
      <span>Log out</span>
    </a>
  </div>
</aside>

    <!-- MAIN CONTENT -->
<!-- MAIN CONTENT -->
<main class="flex-1 p-8 bg-gray-50 min-h-screen">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Selamat Datang, Tim Humas Polije</h2>
            <p class="text-gray-500 mt-1">Sistem Informasi Publikasi & Manajemen Berita Politeknik Negeri Jember</p>
        </div>

        <!-- Profile Badge -->
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
            <div class="flex flex-col items-end">
                <span class="text-gray-700 font-bold text-sm leading-none">Humas Polije</span>
                <span class="text-green-500 text-[10px] font-medium uppercase tracking-tighter">Online</span>
            </div>
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 text-white flex items-center justify-center rounded-full text-sm font-bold shadow-md border-2 border-white">
                PJ
            </div>
        </div>
    </div>

    <!-- Statistik Utama (Card Biasa) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- Card Total Berita -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h4m-4 4h8m-8 4h8" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Berita</p>
                <h3 class="text-3xl font-bold text-gray-800 tracking-tight">
                    <?= number_format($total_berita, 0, ',', '.'); ?>
                </h3>
            </div>
        </div>

        <!-- Card Verifikasi -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Status Sistem</p>
                <h3 class="text-xl font-bold text-gray-800">Terverifikasi</h3>
                <p class="text-green-500 text-xs font-semibold">100% Aman</p>
            </div>
        </div>

        <!-- Card Shortcut Tambah -->
        <a href="./tambah_berita.php" class="bg-blue-600 p-6 rounded-3xl shadow-lg shadow-blue-200 flex items-center justify-between group hover:bg-blue-700 transition-all">
            <div class="text-white">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Quick Action</p>
                <h3 class="text-lg font-bold">Input Berita</h3>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white group-hover:rotate-90 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </a>

    </div>

    <!-- Call to Action Section -->
    <div class="mt-8 flex flex-col lg:flex-row items-center justify-between p-8 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm gap-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-yellow-50 rounded-3xl flex items-center justify-center text-yellow-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-2xl text-gray-800">Publikasikan Prestasi Polije</h4>
                <p class="text-gray-500 max-w-md">Ayo unggah rilis berita terbaru untuk menyebarkan informasi positif dan menginspirasi seluruh civitas akademika Politeknik Negeri Jember.</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <a href="./tabel_berita.php" class="px-8 py-4 rounded-2xl font-bold text-center border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Lihat Semua Data
            </a>

        </div>
    </div>

</main>

  </div>

</body>
</html>