<?php
// PINDAHKAN KE BARIS 1 (SANGAT PENTING)
session_start();
require_once './koneksi/koneksi.php';

try {
    // Ambil 4 berita terbaru untuk ditampilkan di Landing Page
    $sql_news = "SELECT * FROM data_berita ORDER BY tanggal_publikasi DESC LIMIT 4";
    $stmt_news = $pdo->prepare($sql_news);
    $stmt_news->execute();
    $list_berita = $stmt_news->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Gagal memuat berita: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Logo Broser -->
   <link rel="icon" type="image/png" href="./image/LogoPolije.png">
  <!-- Logo Broser -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERANDA PKM POLIJE</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
      body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media (min-width: 1024px) {
            .group:hover .group-hover\:block { display: block; }
        }
    </style>
</head>
<body class="font-sans antialiased overflow-x-hidden bg-gray-50">
<!-- Navbar -->
<header class="fixed w-full z-50 bg-white shadow-md">
    <div class="container mx-auto px-5 py-0.5 flex items-center justify-between">
        
        <div class="flex items-center shrink-0 space-x-4">
            <img src="./image/LogoPolije.png" alt="Logo Kampus" class="h-8 md:h-10 w-auto object-contain">
            
            <div class="h-8 w-[1px] bg-gray-300"></div>
            
            <img src="./image/logoPKM.png" alt="Logo PKM" class="h-12 md:h-16 w-auto object-contain">
        </div>

        <button id="mobile-menu-button" class="lg:hidden text-gray-700 text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>

        <nav id="nav-menu" class="hidden lg:flex flex-col lg:flex-row absolute lg:relative top-full left-0 w-full lg:w-auto bg-white lg:bg-transparent shadow-lg lg:shadow-none items-center space-y-4 lg:space-y-0 lg:space-x-8 p-6 lg:p-0 text-[13px] font-bold text-gray-700 uppercase tracking-wide transition-all">
            <a href="index.php" class="hover:text-blue-900 transition-colors py-1 w-full text-center lg:w-auto">Beranda</a>
            
            <div class="relative group w-full text-center lg:w-auto">
                <button class="hover:text-blue-900 flex items-center justify-center lg:justify-start w-full lg:w-auto focus:outline-none py-2 lg:py-3 text-[13px] font-bold uppercase tracking-wide">
                    PKM <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                </button>
                <div class="lg:absolute hidden group-hover:block bg-white lg:shadow-xl border-t-2 border-blue-900 w-full lg:w-48 py-2 lg:-left-4 text-left">
                    <a href="./page/informasi_pkm.php" class="block px-4 py-2 hover:bg-gray-100 hover:text-blue-900 normal-case font-semibold">Informasi PKM</a>
                    <a href="./page/jadwal_pkm.php" class="block px-4 py-2 hover:bg-gray-100 hover:text-blue-900 normal-case font-semibold">Jadwal PKM</a>
                </div>
            </div>

            <a href="./page/informasi_dosen.php" class="hover:text-blue-900 transition-colors py-1 w-full text-center lg:w-auto">Informasi Dosen</a>
            <a href="./page/kontak_center.php" class="hover:text-blue-900 transition-colors py-1 w-full text-center lg:w-auto">Kontak Center</a>
            
            
        <!-- Pemngumuman  -->
             <?php
                // Pastikan session sudah dimulai di bagian paling atas file
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // 1. Ambil total pesan dari database
                $stmt = $pdo->query("SELECT COUNT(*) FROM pesan WHERE tujuan_pesan IN ('Semua', 'Mahasiswa')");
                $total_db = $stmt->fetchColumn();

                // 2. Ambil jumlah pesan yang terakhir dilihat dari session
                $dilihat = isset($_SESSION['last_viewed_count']) ? $_SESSION['last_viewed_count'] : 0;

                // 3. Hitung selisihnya
                $baru = $total_db - $dilihat;
                ?>

                <!-- Navigasi Pengumuman -->
                <a href="./page/pengumuman.php" class="relative hover:text-blue-900 transition-colors py-1 inline-block group">
                    Pengumuman
                    
                    <?php if ($baru > 0) : ?>
                        <span class="absolute -top-1.5 -right-3.5 bg-red-600 text-white text-[9px] min-w-[16px] h-[16px] px-1 flex items-center justify-center rounded-full border-2 border-white font-bold leading-none animate-bounce">
                            <?= $baru > 99 ? '99+' : $baru ?>
                        </span>
                    <?php endif; ?>
                </a>
            <!-- Pemngumuman  -->



            <div class="border-t lg:border-t-0 lg:border-l w-full lg:w-auto pt-4 lg:pt-0 lg:ml-4 lg:pl-6 border-gray-300 flex justify-center">
                <a href="./auth/login.php" class="flex items-center space-x-2 px-4 py-1.5 border-2 border-blue-900 text-blue-900 rounded-full hover:bg-blue-900 hover:text-white transition-all duration-300 shadow-sm active:scale-95 text-[11px]">
                    <i class="fas fa-user-circle text-base"></i>
                    <span>Log In</span>
                </a>
            </div>
        </nav>
    </div>
</header>
<!-- Navbar -->

    <!-- Konten 1 -->
  <section class="w-full pt-20 pb-10">
    <div class="relative w-full overflow-hidden shadow-2xl group">
        <img src="./image/bacround.jpg" class="w-full h-[500px] md:h-[600px] object-cover group-hover:scale-105 transition-transform duration-700" alt="Pusat Informasi PKM Polije">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-6 md:p-16 w-full lg:w-3/4 text-left">
            <h1 class="text-white text-3xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-lg group-hover:text-blue-200 transition-colors">
                Pusat Informasi PKM 2026 Persiapan Menuju PIMNAS ke-39
            </h1>

            <p class="text-gray-300 text-sm md:text-xl mb-6 line-clamp-2 md:line-clamp-none max-w-3xl">
                Humas Politeknik Negeri Jember menghadirkan panduan resmi dan berita terkini mengenai Program Kreativitas Mahasiswa. Siapkan inovasi terbaikmu dan pahami skema terbaru untuk membawa nama Polije ke puncak prestasi nasional.
            </p>

            <div class="flex items-center space-x-4">
                <span class="text-white text-[10px] md:text-xs font-black uppercase tracking-widest bg-blue-600 px-4 py-1.5 rounded-md">
                    Official Information
                </span>
                <span class="text-gray-300 text-[10px] md:text-xs font-medium uppercase tracking-wider">
                    Information - Humas Polije
                </span>
            </div>
        </div>
    </div>
</section>
<!-- Konten 1 end -->

<!-- Konten 2 (Latest News) -->
<section class="container mx-auto px-8 py-16">
  <div class="flex justify-between items-center mb-11">
    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Latest News</h2>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
    
    <?php if ($list_berita): ?>
      <?php foreach ($list_berita as $berita): ?>
        <!-- Card Berita -->
        <div class="group cursor-pointer" onclick="window.open('<?= htmlspecialchars($berita['link_website']) ?>', '_blank')">
          <div class="overflow-hidden rounded-3xl mb-5 shadow-lg bg-gray-200">
            <!-- Menampilkan Gambar dari folder uploads -->
            <img src="./berita/<?= htmlspecialchars($berita['gambar_utama']) ?>" 
                 alt="<?= htmlspecialchars($berita['judul_berita']) ?>"
                 class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500"
                 onerror="this.src='https://images.unsplash.com/photo-1504711432869-53c0311d792e?q=80&w=500&auto=format&fit=crop'">
          </div>
          
          <div class="flex items-center text-xs text-gray-500 mb-3">
            <span class="font-black text-blue-900 uppercase mr-2 tracking-tighter">Berita</span> 
            • 
            <span><?= date('d M Y', strtotime($berita['tanggal_publikasi'])) ?></span>
          </div>

          <h3 class="font-bold text-xl text-gray-900 leading-snug group-hover:text-blue-700 transition-colors line-clamp-2">
            <?= htmlspecialchars($berita['judul_berita']) ?>
          </h3>
          
          <p class="text-gray-600 text-sm mt-3 line-clamp-3 italic">
            <?= htmlspecialchars($berita['ringkasan']) ?>
          </p>
          
          <!-- Link mengarah ke link_website yang disimpan di database -->
          <a href="<?= htmlspecialchars($berita['link_website']) ?>" target="_blank" 
             class="mt-5 inline-flex items-center text-[11px] font-black text-blue-900 uppercase tracking-widest group-hover:underline">
            Informasi Selengkapnya
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <!-- Jika belum ada data -->
      <div class="col-span-full text-center py-10">
        <p class="text-gray-400">Belum ada berita terbaru saat ini.</p>
      </div>
    <?php endif; ?>

  </div>
</section>
<!-- Konten 2 end -->







<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<footer class="mt-16 bg-blue-900 pt-12 pb-8 px-6 border-t-4 border-blue-950">
  <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 items-start">
    
    <div class="flex flex-col gap-4">
      <div class="flex items-center gap-3 bg-white p-2 rounded-lg w-fit shadow-md">
        <img src="./image/logoPKM.png" alt="Logo PKM" class="h-10 w-auto object-contain">
        <div class="h-8 w-[1px] bg-gray-300"></div>
        <img src="./image/logoPolije.png" alt="Logo Kampus" class="h-10 w-auto object-contain">
      </div>
      <div>
        <p class="text-white font-semibold text-sm">Program Kreativitas Mahasiswa</p>
      </div>
      
      <div class="mt-2">
        <div class="flex gap-2 mb-2">
          <a href="https://facebook.com/username" target="_blank" class="w-8 h-8 rounded-full border border-blue-400/50 text-blue-100 flex items-center justify-center transition hover:bg-white hover:text-blue-900">
            <i class="fab fa-facebook-f text-sm"></i>
          </a>
          <a href="https://instagram.com/username" target="_blank" class="w-8 h-8 rounded-full border border-blue-400/50 text-blue-100 flex items-center justify-center transition hover:bg-white hover:text-blue-900">
            <i class="fab fa-instagram text-sm"></i>
          </a>
          <a href="https://yourwebsite.com" target="_blank" class="w-8 h-8 rounded-full border border-blue-400/50 text-blue-100 flex items-center justify-center transition hover:bg-white hover:text-blue-900">
            <i class="fas fa-globe text-sm"></i>
          </a>
        </div>
        <p class="font-bold text-white text-sm">Ikuti Kami</p>
      </div>
    </div>

    <div class="md:px-4">
      <nav class="flex flex-wrap gap-x-4 gap-y-1 mb-6">
        <a href="index.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Beranda</a>
        <a href="./page/informasi_dosen.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Informasi Dosen</a>
        <a href="./page/kontak_center.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Kontak Center</a>
        <a href="./page/pengumuman.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Pengumuman</a>
      </nav>

      <div>
        <h3 class="text-lg font-bold text-white mb-1">Tentang kami</h3>
        <p class="text-blue-100 text-[11px] leading-relaxed max-w-xs text-justify">
          PKM POLIJE adalah platform digital untuk memfasilitasi kolaborasi tim, informasi terpadu, dan koordinasi terpusat bagi seluruh kegiatan Program Kreativitas Mahasiswa di Politeknik Negeri Jember.
        </p>
      </div>
    </div>

    <div class="border-l-0 md:border-l border-blue-800 md:pl-10 flex flex-col gap-4">
      <div>
        <p class="text-xs font-bold text-blue-300 uppercase tracking-wide">Kontak :</p>
        <p class="text-white text-sm font-medium hover:text-blue-200 transition cursor-pointer">+62 812-3456-7890</p>
      </div>
      
      <div>
        <p class="text-xs font-bold text-blue-300 uppercase tracking-wide">Email :</p>
        <p class="text-white text-sm font-medium hover:text-blue-200 transition cursor-pointer">pkmpolije@polije.ac.id</p>
      </div>
    </div>

  </div>

  <div class="max-w-6xl mx-auto mt-10 pt-6 border-t border-blue-800 text-center">
    <p class="text-blue-300/60 text-[10px]">&copy; 2026 PKM POLIJE. All rights reserved.</p>
  </div>
</footer>

<!-- Footer end -->

    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('nav-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });
    </script>
</body>
</html>