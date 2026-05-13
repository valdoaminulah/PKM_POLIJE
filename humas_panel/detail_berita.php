<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Logo Broser -->
     <link rel="icon" type="image/png" href="../img/logoPolije.png">
    <!-- Logo Broser -->
  <title>Detail Berita</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

  <div class="max-w-5xl mx-auto">

    <!-- CARD CONTAINER -->
    <div class="bg-white rounded-2xl shadow-md border p-8">

      <!-- HEADER -->
      <div class="flex justify-between items-start mb-6">

        <div class="flex items-start gap-4">
          <!-- Back Button -->
          <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 19l-7-7 7-7"/>
            </svg>
          </button>

          <div>
            <h2 class="text-xl font-semibold text-gray-800">
              Detail Berita
            </h2>
            <p class="text-sm text-gray-500">
              Lihat detail lengkap artikel berita
            </p>
          </div>
        </div>

        <!-- Action Buttons -->
<div class="flex gap-3">

  <!-- EDIT BUTTON -->
  <button class="flex items-center gap-2 px-4 py-2 text-sm rounded-lg border bg-gray-100 hover:bg-gray-200 transition">
    <svg xmlns="http://www.w3.org/2000/svg" 
         class="w-4 h-4 text-gray-600" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke="currentColor" 
         stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.125 1.125-4.5L16.862 3.487z"/>
    </svg>
    Edit
  </button>

  <!-- DELETE BUTTON -->
  <button class="flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
    <svg xmlns="http://www.w3.org/2000/svg" 
         class="w-4 h-4 text-white" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke="currentColor" 
         stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6M5 7h14l-1 13H6L5 7z"/>
    </svg>
    Hapus
  </button>

</div>

      </div>
      <!-- END HEADER -->

      <!-- IMAGE -->
      <!-- IMAGE -->
<div class="mb-6">
  <img src="./img/pkmfoto.jpeg"
       class="w-full h-auto rounded-xl"
       alt="Gambar Berita">
</div>

      <!-- TITLE -->
      <h1 class="text-2xl font-bold text-gray-800 mb-3">
        Pendaftaran PKM 2026
      </h1>

      <!-- META INFO -->
      <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
        <span>📅 20 Februari 2026</span>
        <span>🌐 https://polije.ac.id</span>
      </div>

      <hr class="mb-6">

      <!-- RINGKASAN -->
      <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-10">
        <h3 class="text-base font-semibold text-blue-700 mb-3">
          Ringkasan
        </h3>
        <p class="text-blue-800 leading-relaxed">
          Pendaftaran Tim PKM resmi ditutup hari ini. Pastikan seluruh berkas dan proposal sudah diunggah sebelum batas waktu berakhir agar tim kamu dapat mengikuti proses seleksi.
        </p>
      </div>

      <!-- CONTENT -->
      <div class="space-y-6 text-gray-700 leading-relaxed text-[16px]">

        <h2 class="text-xl font-semibold text-gray-800">
          📢 Pengumuman Penting untuk Seluruh Mahasiswa!
        </h2>

        <p>
          Kami mengingatkan kembali bahwa tenggat pendaftaran Tim Program Kreativitas Mahasiswa (PKM) adalah hari ini. Seluruh tim yang ingin berpartisipasi dalam PKM tahun ini wajib menyelesaikan proses pendaftaran dan mengunggah proposal sebelum batas waktu yang telah ditentukan.
        </p>

        <p>
          PKM merupakan ajang bergengsi yang mendorong mahasiswa untuk berinovasi, berkreasi, serta mengembangkan ide-ide solutif dalam berbagai bidang. Melalui program ini, mahasiswa tidak hanya berkesempatan untuk mengembangkan kemampuan akademik dan soft skills, tetapi juga membuka peluang untuk melaju ke tingkat nasional.
        </p>

        <div>
          <h3 class="text-lg font-semibold text-gray-800 mb-3">
            🔎 Pastikan sebelum mengirim:
          </h3>

          <ul class="list-disc list-inside space-y-2">
            <li>Proposal sudah sesuai dengan pedoman PKM terbaru</li>
            <li>Format dan sistematika penulisan telah diperiksa kembali</li>
            <li>Seluruh anggota tim telah terdaftar dengan benar</li>
            <li>Berkas sudah berhasil terunggah tanpa kendala</li>
          </ul>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <p class="font-medium text-yellow-800">
            ⏰ Jangan menunda hingga menit terakhir untuk menghindari kendala teknis saat proses pengunggahan.
          </p>
        </div>

        <p>
          Kami berharap seluruh tim dapat memanfaatkan kesempatan ini dengan maksimal. Semoga proposal yang diajukan dapat memberikan kontribusi nyata serta membawa prestasi bagi kampus tercinta.
        </p>

        <p class="font-medium text-gray-800">
          Semangat berinovasi dan semoga sukses! 🚀✨
        </p>

      </div>

    </div>

  </div>

</body>
</html>